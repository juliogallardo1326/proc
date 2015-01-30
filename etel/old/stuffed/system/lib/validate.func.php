<?

function ValidMail($email)
{
	if (!$email) return false;
	if (preg_match("/^[^\@\s,;]+\@[^\.\s,;]+\.[^\s,;]+$/", $email)) return true;
	else return false;
}


function ToLower($str)
{
	if (!$str) return false;
	$U_alph = "éöóêåíãøùçõúôûâàïðîëäæýÿ÷ñìèòüáþ¸qwertyuiopasdfghjklzxcvbnm";
	$L_alph = "ÉÖÓÊÅÍÃØÙÇÕÚÔÛÂÀÏÐÎËÄÆÝß×ÑÌÈÒÜÁÞ¨QWERTYUIOPASDFGHJKLZXCVBNM";
	return strtr($str, $L_alph, $U_alph);
}

function ToUpper($str)
{
	if (!$str) return false;
	$U_alph = "ÉÖÓÊÅÍÃØÙÇÕÚÔÛÂÀÏÐÎËÄÆÝß×ÑÌÈÒÜÁÞ¨QWERTYUIOPASDFGHJKLZXCVBNM";
	$L_alph = "éöóêåíãøùçõúôûâàïðîëäæýÿ÷ñìèòüáþ¸qwertyuiopasdfghjklzxcvbnm";
	return strtr($str, $L_alph, $U_alph);
}


function ReplaceCyrSymb($Str)
{
	$Cyr = "éöóêåíãøùçõúôûâàïðîëäæýÿ÷ñìèòüáþ¸ÉÖÓÊÅÍÃØÙÇÕÚÔÛÂÀÏÐÎËÄÆÝß×ÑÌÈÒÜÁÞ¨";
	$Eng = "ycukenghhzx_fyvaproldjeacsmit_bueYCUKENGHHZX_FYVAPROLDJEACSMIT_BUE";
	return strtr($Str, $Cyr, $Eng);
}

function ReplaceSymb($Str, $File=false)
{
	if($File===1) {
		$Arr = explode(".", $Str);
		$Arr[0] = ereg_replace("[^[:alnum:]]", "", $Arr[0]);
		return $Arr[0].".".$Arr[count($Arr)-1];
	}
	else return ereg_replace("[^[:alnum:]]", "", $Str);
}

function CheckSymb($Str)
{
	return eregi("[^[:alnum:]]", $Str);
}


function CheckMiscSymb($Str, $Symb)
{
	return eregi("[^[:alnum:]$Symb]", $Str);
}

function RemoveMiscSymb($Str, $Symb)
{
	return eregi_replace("[^[:alnum:]$Symb]", "",  $Str);
}


function ReplaceSymb_($Str, $File=false)
{
	if($File===1) {
		$Arr = explode(".", $Str);
		$Arr[0] = ereg_replace("[^[:alnum:]_]", "", $Arr[0]);
		return $Arr[0].".".$Arr[count($Arr)-1];
	}
	else return ereg_replace("[^[:alnum:]]_", "", $Str);
}

function CheckSymb_($Str)
{
	return eregi("[^[:alnum:]_]", $Str);
}


function ValidDate(&$Date, $Templ="yyyy-mm-dd")
{
	if (!isset($Date)) return false;
	$Arr=explode("-", $Templ);
	$First="";
	$Second="";
	$RowTempl="";
	for($i=0;$i<count($Arr);$i++) {
		if ($Arr[$i]=="yyyy") $RowTempl="([0-9]{4})";
		else $RowTempl="([0-9]{2})";
		if ($i==0) $First=$RowTempl;
		else $Second=$RowTempl;
	}
	return preg_match("/^$First-$Second-$Second$/", $Date);
}


function FormError($Name=false, $Arr=false)
{
	if (!$Name) return false;
	if (!is_array($Arr)&&isset($GLOBALS['ErrArr'])) $Arr=&$GLOBALS['ErrArr'];
	if (!is_array($Arr)) return false;
	if (isset($Arr[$Name])) echo "<br><span class=FormError>".$Arr[$Name]."</span>";
}


?>