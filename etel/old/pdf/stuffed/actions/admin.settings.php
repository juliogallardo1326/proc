<?
/////////////////////////////////////////////
///////// permission check here
if (!$nsUser->Logged()) $nsProduct->Redir("login", "", "admin");


/////////////////////////////////////////////
///////// require libraries here

$nsLang->TplInc("inc/menu");

/////////////////////////////////////////////
///////// prepare any variables
$CpId=(ValidId($_GP['CpId']))?$_GP['CpId']:false;

$PageTitle="";
if (!ValidId($CpId)) $nsProduct->Redir("default");
if (ValidId($CpId)) {
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $CpId";
	$Comp=$Db->Select($Query);
	$PageTitle=$Comp->NAME.": ";
}
$PageTitle.=$Lang['Title'];
$MenuSection="settings";
$ProgPath[0]['Name']=$Lang['MSettings'];
$ProgPath[0]['Url']=$nsProduct->SelfAction("CpId=$CpId");

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