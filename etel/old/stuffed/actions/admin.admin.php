<?
/////////////////////////////////////////////
///////// permission check here

if ($nsUser->MERCHANT&&!$nsUser->SUPER_USER) $nsProduct->Redir("default", "", "admin");

/////////////////////////////////////////////
///////// require libraries here
$nsLang->TplInc("inc/user_welcome");
$ProgPath[0]['Name']=$Lang['Administr'];
$ProgPath[0]['Url']=getURL("admin", "", "admin");

/////////////////////////////////////////////
///////// prepare any variables

$MenuSection="admin";


/////////////////////////////////////////////
///////// call any process functions


/////////////////////////////////////////////
///////// display section here

include $nsTemplate->Inc();

/////////////////////////////////////////////
///////// process functions here


/////////////////////////////////////////////
///////// library section

?>