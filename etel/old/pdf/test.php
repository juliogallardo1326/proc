<?php
function func_check_isnumber($strnum)
{
	//returns 1 if valid number (only numeric string), 0 if not
//	if (ereg('^[[:digit:]]+$', $strnum)) {
	if(ereg('^0|[1-9][0-9]*|[1-9][0-9]*[.][0-9]{1,2}$',$strnum)) {
   		return 1;
 	} else {
   		return 0;
	}
}

function func_check_isnumberdot($var)
{
   for ($i=0;$i<strlen($var);$i++)
   {
       $ascii_code=ord($var[$i]);
       
       if ($ascii_code >=49 && $ascii_code <=57 || $ascii_code ==46 ) {
           continue;
       } else { 
           return 0;
	  }
   }
	 	return true;
}

$export_value=6576578.9789;
print "Check number : ".func_check_isnumber(552083);
if(!func_check_isnumberdot($export_value)) {
	$export_value = "*".$export_value;
	print "<br>Abish checking :".$export_value;
}
print "<br>Exp val : ".$export_value;
print "<br>Is number of : ".func_check_isnumberdot('6576578.9789');
print "<br> val". ord('.');

$ans = split(",",$export_value);
print $ans[0];
?>