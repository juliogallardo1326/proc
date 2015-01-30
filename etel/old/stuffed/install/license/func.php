<?
$SaveVars=array("IAgree");

$AdditionalOnload="BtnForw(GetObj('IAgree'));";

$IAgree=(ValidVar($_REQUEST['IAgree']))?$_REQUEST['IAgree']:false;
$DisableNext=(!ValidVar($_REQUEST['IAgree']))?true:false;

?>