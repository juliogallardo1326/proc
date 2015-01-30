#!/usr/bin/perl -w

#vvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvvv
#                                                        #
# Name:		Password Management Script                   #
# Version:	Version 1.3				                     #
#                                                        #
# Latest:                                                #
#  06-24-2006 - Added optional group management (htgroup)#
#^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

#########################################################################
#
#	- Authentication Code - 
#	This must be the same value as the value you entered in your Control Panel. 
#   This is how your server authenticates ours.
#
$authpwd = "{#secret#}";   # your authentication code
#
#
#	- Password File (Required) - 
#	This required field is your .htpasswd file. This file must be writable.
#
$pwdfile = "{#passdir#}";   #(ex. /public_html/cgi-bin/pass/.htpasswd)
#
#
#	- Group Management (Optional) - 
#	Enabling this option will allow this script to add users to groups based on price points they purchased. 
#	This allows you to provide specific access to certain files/folders based on a subscription type.
#	To disable this option, please leave it blank.  This file must be writable.
#
$groupfile = "{#groupdir#}";  #(ex. /public_html/cgi-bin/pass/.htgroup)
#
#
#########################################################################

##### DO NOT MODIFY ANYTHING BELOW THIS LINE #####

#get the environment variables
$method = $ENV{"REQUEST_METHOD"};
$type = $ENV{"CONTENT_TYPE"};

# read form data from standard input
%input_values = &get_form_tuples;

$authcode = &normalize_query($input_values{"authpwd"});

if($authcode ne $authpwd){
	&send_error("501"); # authentication failed
	exit;
}

$reqtype = &normalize_query($input_values{"reqtype"});
$groupaccess = &normalize_query($input_values{"groupaccess"});

if(!($reqtype eq "add" || $reqtype eq "delete" || $reqtype eq "chgpwd" || $reqtype eq "list")){
	&send_error("502"); # invalid request type
	exit;
}

$username = &normalize_query($input_values{"username"});
$password = &normalize_query($input_values{"password"});
if(length($groupfile)>0)
{
   $groupaccess = &normalize_query($input_values{"groupaccess"});
}

%users = &read_pwd_file();		# Read the user password file.
%groups = &read_group_file();	# Read the group file (optional).
	
# add a user
if ($reqtype eq "add"){

	if(!&valid_username($username)){
		send_error("507");  #invalid username
		exit;
	}
	if(!&valid_password($password)){
		send_error("508");  #invalid password
		exit;
	}
	if ($users{$username} ne ""){
		&send_error("505"); # specified user already exists
		exit;
	}
	$users{$username}=crypt($password,&get_key());
	&write_pwd_file();
	if(length($groupaccess) > 1)
	{
		$groups{$groupaccess}{$username} = $username;
		&write_group_file();
	}
	&send_success("201");
	exit;
}

#delete a user
if ($reqtype eq "delete"){
	if(!&valid_username($username)){
		send_error("507"); #invalid username
		exit;
	}
	if ($users{$username} eq ""){
		&send_error("506"); # specified user doesn't exist
		exit;
	}
	delete $users{$username};
	&write_pwd_file();
	if($groupfile ne "")
	{
		foreach $group (keys %groups){	#check all groups for username.
			delete $groups{$group}{$username};
		}
	
		&write_group_file();
	}
	&send_success("202");
	exit;
}

#change the password of a user
if ($reqtype eq "chgpwd"){
	if(!&valid_username($username)){
		send_error("507"); #invalid username
		exit;
	}
	if(!&valid_password($password)){
		send_error("508"); #invalid password
		exit;
	}
	if ($users{$username} eq ""){
		&send_error("506"); # specified user doesn't exist
		exit;
	}
	$users{$username}= crypt($password,&get_key());
	&write_pwd_file();
	&send_success("203");
	exit;
}

#list all users and groups for remote editing. 
if ($reqtype eq "list"){
	$list = "&version=1.3";
	foreach $user (keys %users){
		$list = $list  . "&user[]=" . $user;
	}
	if($groupfile ne "")
	{
		$list = $list . "&usinggroups=1";
		foreach $group (keys %groups){
	
			$i=0;
			$list = $list  . "&group[".$group."]=";
			for $u (keys %{ $groups{$group} } ) 
			{	
				if($i++>0) {$list = $list . ","}
				$list = $list . $u;
			}
		}
	}
	$list = $list . "&";
	&send_success($list); # Send User Lists
	exit;
}

# read CONTENT_LENGTH bytes from the standard input and decode
# the URL format input, breaking it into an associative array
# of HTML variable names and their values.
sub get_form_tuples 
{
	local ($i);
	read(STDIN,$input,$ENV{'CONTENT_LENGTH'});
	@form_names = split('&',$input);
	foreach $i(@form_names) {
		($html_name,$html_value) = split('=',$i);
		$input_values{$html_name} = $html_value;
	}
	$request = $ENV{'QUERY_STRING'};
	@form_names = split('&',$request);
	foreach $i(@form_names) {
		($html_name,$html_value) = split('=',$i);
		$input_values{$html_name} = $html_value;
	}
	return %input_values;
}

# URL syntax converts most non-alphanumeric characters into a
# percentage sign, followed by the character's value in hex.
sub normalize_query {
	local($value) = @_;
	$value =~ tr/+/ /;
	$value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C",hex($1))/eg;
	return $value;
}

# Send error messages
sub send_error
{
	print "Content-type: text/plain\n\n";
	print "@_\n"
}

# Send error messages
sub send_success
{
	print "Content-type: text/plain\n\n";
	print "@_\n"
}

$LOCK_EX=2;
$LOCK_UN=8;

# read the password file and create an associative array
sub read_pwd_file 
{
	if($pwdfile eq ""){
		&send_error("503"); #failed to locate the password file
		exit;
	}
	#open password file
	unless(open(PWD,$pwdfile)){
		&send_error("504"); #failed to open the password file
		exit;
	}
	flock(PWD,$LOCK_EX);
	seek(PWD,0,0);
	while(<PWD>){
		if (index($_,":") >= 0){
			($cuser,$cpwd) = split(':',$_);
			chop($cpwd);
			if($users{$cuser} eq ""){
				$users{$cuser}=$cpwd;
			}
		}
	}
	flock(PWD,$LOCK_UN);
	close(PWD);

	return %users;
}

# read the group file and create an associative array
sub read_group_file 
{
	if($groupfile eq ""){
		return 0;
	}
	#open group file
	unless(open(PWD,$groupfile)){
		&send_error("504"); #failed to open the password file
		exit;
	}
	flock(PWD,$LOCK_EX);
	seek(PWD,0,0);
	while(<PWD>){
		if (index($_,":") >= 0){
			chop($_);
			($cgroup,$ulist) = split(':',$_);
			@uarray = split(',',$ulist);
			if($groups{$cgroup} eq ""){
				#$groups{$cgroup} = @uarray; 
				foreach $u (@uarray)
				{
					$groups{$cgroup}{$u}=$u;
				}
				
			}
		}
	}
	flock(PWD,$LOCK_UN);
	close(PWD);

	return %groups;
}

# write the associative array to password file
sub write_pwd_file
{
	if($pwdfile eq ""){
		&send_error("503"); #failed to locate the password file
		exit;
	}
	#open password file
	unless(open(PWD,">" . $pwdfile)){
		&send_error("504"); #failed to open the password file
		exit;
	}
	flock(PWD,$LOCK_EX);
	seek(PWD,0,0);
	foreach $user (keys %users){
		$temp = $temp . $user . " :: " . $users{$user} . "\n";
		print PWD $user . ":" . $users{$user} . "\n";
	}
	flock(PWD,$LOCK_UN);
	close(PWD);
}


# write the associative array to group file
sub write_group_file
{
	if($groupfile eq ""){
		return 0;
	}
	#open group file
	unless(open(PWD,">" . $groupfile)){
		&send_error("504"); #failed to open the password file
		exit;
	}
	flock(PWD,$LOCK_EX);
	seek(PWD,0,0);

	
	foreach $group (keys %groups){
				
		$i=0;
		$out = "$group:";
		for $u (keys %{ $groups{$group} } ) 
		{	
			if($i++>0) {$out = $out . ","}
			$out = $out . $u;
		}
		if($i>0) 
		{
			$temp = $temp . $out . "\n";
			print PWD $out . "\n";
		}
	}
	flock(PWD,$LOCK_UN);
	close(PWD);
}

# generates a random salt for crypt function
sub get_key
{
	$chars[0] = chr(65+int(rand(26)));
	$chars[1] = chr(97+int(rand(26)));
	$chars[2] = chr(48+int(rand(9)));

	$key = $chars[int(rand(3))] . $chars[int(rand(3))];

	return $key;
}

# returns 0 if the 'username' is not a valid one. otherwise
# returns 1
sub valid_username()
{
	local($usr)=@_;
	local($res)=1;
	
	if(length($usr) < 3){
		$res=0;
	}
	if($usr =~ /\s/) {
		$res=0;	
	}
	return $res;
}

# returns 0 if the 'password' is not a valid one. otherwise
# returns 1
sub valid_password()
{
	local($pwd)=@_;
	local($res)=1;
	
	if(length($pwd) < 3){
		$res=0;
	}
	if($pwd =~ /\s/) {
		$res=0;	
	}
	return $res;
}
