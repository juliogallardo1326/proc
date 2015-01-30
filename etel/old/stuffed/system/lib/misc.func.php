<?
# ============================================================================
#
#                        ___
#                    ,yQ$SSS$Q·,      ,yQQQL
#           i_L   I$;            `$½`       `$$,
#                                 `          I$$
#           .yQ$$$$,            ;        _,d$$'
#        ,d$$P"^```?$b,       _,'  ;  ,d$$P"`
#     ,d$P"`        `"?$$Q#QP½`    $d$$P"`
#   ,$$"         ;       ``       ;$?'
#   $$;        ,dI                I$;
#   `$$,    ,d$$$`               j$I
#     ?$S#S$P'j$'                $$;         Copyright (c) Stuffed Guys
#       `"`  j$'  __....,,,.__  j$I              www.stuffedguys.com
#           j$$½"``           ',$$
#           I$;               ,$$'
#           `$$,         _.u$$½`
#             "?$$Q##Q$$SP½"^`
#                `````
#
# ============================================================================
# $Id: misc.func.php,v 1.59 2005/11/15 14:57:04 kuindji Exp $
# ============================================================================


function TextDump(&$Var, $Level = 0)
{
	if(is_array($Var)) $Type = "Array[".count($Var)."]";
	else if (is_object($Var)) $Type = "Object";
	else $Type = "";

	if ($Type)
	{
		echo "$Type\n";
		for (Reset($Var), $Level++; list($k, $v) = each($Var);)
		{
			if (is_array($v) && $k === "GLOBALS") continue;
			for ($i = 0; $i < $Level*3; $i++) echo " ";
			echo "<b>- [".htmlspecialchars($k)."]</b> => ", TextDump($v, $Level);
		}
	}
	//else echo ' " ', ereg_replace("[[:space:]]+", " ", htmlspecialchars($Var)), ' " '."\n";
	else echo "'".htmlspecialchars($Var)."'\n";
}

function Dump(&$Var, $full=false)
{
	if($full){fullDump($Var);return;}
	if ((is_array($Var) || is_object($Var)) && count($Var)) echo "<pre>\n",TextDump($Var),"</pre><br>\n";
	else echo "<tt>",TextDump($Var),"</tt>\n";
}


function fullDump(&$variable, $parents='$var'){ 
	if(!isset($variable)){ echo "<font color='#777777'>undefined</font>;<br>";; return;}
	$var = $variable;
	if($parents=='$var') echo '<code><b>';
	if(is_object($var)){
			$type = 'obj'; settype($var, 'array');
	}
	else if(is_array($var)){
		$type = 'arr';
	}else $type = '';
	
	if(!empty($type)){
		$num = count($var);

		echo '<font color="#007700">'.($type=='obj'?'Object' : 'Array') ." ($num)</font>";
		echo '<br>';	
		
		foreach($var as $key => $val){
			$key = htmlspecialchars($key);
			$kout = $type=='obj'?  ('->'.$key) :"[<font color=#00bbbb>'$key'</font>]";
			
			echo '<font color="$0000ff">'.$parents.$kout.'</font> = ';
			echo fullDump($val, $parents.$kout);
		}
	}else{
		if(is_numeric($var))	
				echo "<font color='#005f5f'>".($var)."</font>;<br>";
		elseif(is_string($var))	
				echo "<font color='#777700'>\"".htmlspecialchars($var)."\"</font>;<br>";
		elseif(is_bool($var))		echo ($var?'true':'false').";<br>";
		elseif(is_resource($var))		
				echo "<font color='#aa0000'>::".$var."::</font>;<br>";
		else		  					echo "'".htmlspecialchars($var)."';<br>";
	}
	if($type = 'obj') settype($var, 'object');
	if($parents=='$var') echo '</b></code><hr>';
}

function ValidId(&$id)
{
    if ( !isset($id) )
        return false;

    if ( !is_integer($id) && !is_string($id) )
        return false;

    if ( is_string($id) && strval(intval($id)) != $id )
        return false;

    if ( !(intval($id) >= 0) )
        return false;

    return true;
}

function ValidVar(&$Var, $defval=false)
{
	if (empty($Var)) return $defval;
	return $Var;
}

function ValidReqVar($name, $defval=false)
{
	return ValidVar($_REQUEST[$name], $defval);	
}
function UrlValidReqVar($name, $defval=''){
	return (ValidReqVar($name))? ('&'.$name.'='.ValidReqVar($name)):'';
}
function ValidArr(&$Arr)
{
	if (!isset($Arr)) return false;
	if (!is_array($Arr)) return false;
	return true;
}


function FileSizeStr($Size)
{
	if ($Size < 1024) {
		return $Size." b";
	}
	if ($Size >= 1024 && $Size < 1048576) {
		$Str = round($Size/1024, 2);
		return $Str." Kb";
	}
	if ($Size >= 1048576) {
		$Str = round($Size/1048576, 2); 
		return $Str." Mb";
	}
}

function GetURL($Action=false, $Get=false, $Section=false)
{
	global $nsProduct;
	return $nsProduct->GetUrl($Action, $Get, $Section);
}

function Redir($Url) 
{
	//echo "Should be redirected to <a href='$Url'>$Url</a>";
  Header("Location: $Url"); 
  	 exit; 
}


function GetFORM($Action=false, $Get=false, $Section=false, $Attr=false)
{
	global $nsProduct;
	$nsProduct->CreateForm("get", $Action, $Get, $Section, $Attr);
}

function PostFORM($Action=false, $Get=false, $Section=false, $Attr=false)
{
	global $nsProduct;
	$nsProduct->CreateForm("post", $Action, $Get, $Section, $Attr);
}


function GetParam($Name, $Type, $Plg=false)
{
	global $Db, $nsProduct;
	if (!$Name) return false;
	if (!$Type) return false;
	if ($Type!="INTVAL"&&$Type!="STRVAL"&&$Type!="MEMO") return false;
	$Prd=($nsProduct->ID>0)?$nsProduct->ID:"0";
	if (!$Plg||!ValidId($Plg)) $Plg=0;
	$Name=ereg_replace("[^[:alnum:]_]", "", $Name);
	$Name=addslashes($Name);
	$Query = "SELECT $Type FROM ".PFX."_system_config WHERE PRODUCT_ID=$Prd AND PLUGIN_ID=$Plg AND CALLNAME='$Name' AND DATATYPE='$Type'";
	return stripslashes($Db->ReturnValue($Query));
}

function SetParam($Name, $Type, $Value, $Plg=false)
{
	global $Db, $nsProduct;
	if (!$Type) return false;
	if ($Type!="INTVAL"&&$Type!="STRVAL"&&$Type!="MEMO") return false;
	$Prd=($nsProduct->ID>0)?$nsProduct->ID:"0";
	if (!$Plg||!ValidId($Plg)) $Plg=0;	
	$Query = "SELECT ID FROM ".PFX."_system_config WHERE PRODUCT_ID=$Prd AND PLUGIN_ID=$Plg AND CALLNAME='$Name'";
	$Id=$Db->ReturnValue($Query);
	$Name=ereg_replace("[^[:alnum:]_]", "", $Name);
	$Name=addslashes($Name);
	$Value=addslashes($Value);
	if (ValidId($Id)) {
		$Query = "UPDATE ".PFX."_system_config SET $Type = '$Value', DATATYPE = '$Type' WHERE PRODUCT_ID=$Prd AND PLUGIN_ID=$Plg AND CALLNAME='$Name'";
	}
	else {
		$Query = "INSERT INTO ".PFX."_system_config (PRODUCT_ID, PLUGIN_ID, CALLNAME, DATATYPE, $Type) VALUES ($Prd, $Plg, '$Name', '$Type', '$Value')";
	}
	$Db->Query($Query);
}

function DropParam($Name)
{
	global $Db, $nsProduct;
	$Prd=($nsProduct->ID>0)?$nsProduct->ID:"0";
	$Name=ereg_replace("[^[:alnum:]_]", "", $Name);
	$Name=addslashes($Name);
	$Query = "DELETE FROM ".PFX."_system_config WHERE CALLNAME = '$Name' AND PRODUCT_ID = $Prd";
	$Db->Query($Query);
}



function ParseNumber($Num, $Word = 1)
{
	global $Words;
	$Num=abs($Num);
	$Rem = $Num % 10;
	if ($Num > 10 && $Num < 20) {
		return $Words[$Word][0];
	}
	else {
		switch ($Rem)
		{
			    case 0:
				case 5:
				case 6:
				case 7:
				case 8:
				case 9:
						return $Words[$Word][0];
				case 1:
					    return $Words[$Word][1];
				case 2:
				case 3:
				case 4:
					     return $Words[$Word][2];
		}
	}
}

function ParseNumberNow($Num, $WordN, $Word1, $Word2){
	global $Words;
	$Words['ParseNumberNow temp'][0] = $WordN;
	$Words['ParseNumberNow temp'][1] = $Word1;
	$Words['ParseNumberNow temp'][2] = $Word2;
	return ParseNumber($Num, 'ParseNumberNow temp');
}



function escape_string($string){
	if (is_null($string) || $string===false){
			 return null;
	}		 
   if(version_compare(phpversion(),"4.3.0")=="-1") {
     return mysql_escape_string($string);
   } else {
     return mysql_real_escape_string($string);
   }
}

function ns_my_url_enc(){ return urlencode(ns_my_url()); }
function ns_my_url(){
	return 'http'.((strtolower(ValidVar($_SERVER["HTTPS"]))=="on") ? 's' : '').'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
}

function strip_certain_req_vars($var, $url=''){
	if(empty($url)) $url = ns_my_url();
	if (!is_array($var)) $var = array($var);
	foreach($var as $v)	$url = strip_certain_req_var_one($v, $url);			
	return $url;
}
function strip_certain_req_var_one($var, $url){
	$ret = '';
	if(strpos($url, '?')!==false)
		list($host, $query) = explode('?', $url);
	else return $url;
	
	if(strpos($url, '&')!==false)
		$pairs = explode('&', $query);
	else 	$pairs[0] = $query;
	if (isset($pairs) && is_array($pairs)) {
		foreach ($pairs as $pair) {
			// here was a bug, $pair could contain nothing when in URL there was a
			// structure like this: &some_param=1&&another_param=2 -- note 2 &
			if (!$pair) continue;
			list($key, $val) = explode('=', $pair);			
			if ($key!=$var) $ret .= (!empty($ret) ? '&' : '') . $pair;
		}
	}
	return $host . '?' . $ret; 
}

if(!function_exists('is_executable')){
	function is_executable($a){ return false; }
}
function timeintval($a){ return ($a<10?('0'.intval($a)):intval($a)); }

if (version_compare(phpversion(), '5.0') < 0) { 
    eval('
    function clone($object) {
      return $object;
    }
	');
}


function CompareVersions($Str1="", $Str2="", $Position=false)
{
	if (!$Str1&&!$Str2) return 0;
	if (!$Position&&function_exists("version_compare")) {
		return version_compare($Str1, $Str2);
	}
	if (!$Str2) return 1;
	if (!$Str1) return -1;
	if ($Str1==$Str2) return 0;

	$Arr1=explode(".", $Str1);
	$Arr2=explode(".", $Str2);

	if (count($Arr1)!=count($Arr2)) {
		if (count($Arr1)>count($Arr2)) {
			for ($i=count($Arr2);$i<count($Arr1);$i++) $Arr2[$i]=0;
		}
		else {
			for ($i=count($Arr1);$i<count($Arr2);$i++) $Arr1[$i]=0;
		}
	}
	$iStop=($Position)?$Position:count($Arr1);
	for ($i=0;$i<$iStop;$i++) {
		$V1=intval($Arr1[$i]);
		$V2=intval($Arr2[$i]);
		if ($V1==$V2) continue;
		if ($V1<$V2) return -1;
		if ($V1>$V2) return 1;
	}

	return 0;
}


?>