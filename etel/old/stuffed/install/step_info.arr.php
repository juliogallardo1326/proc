<?
$ProductVersion="2.2.1";
$NoForm=false;

$Charset['ru']="Windows-1251";
$Charset['en']="ISO-8859-1";

$StepLang['Name']=$Lang['StepLang'];
$StepLang['Folder']="lang";
$StepLang['ValidateFunc']="ValidateLang";

$StepLicense['Name']=$Lang['StepLicense'];
$StepLicense['Folder']="license";
$StepLicense['ValidateFunc']="NextStep";

$StepDb['Name']=$Lang['StepSettings'];
$StepDb['Folder']="settings";
$StepDb['ValidateFunc']="CheckSettings";

$StepClient['Name']=$Lang['StepKey'];
$StepClient['Folder']="lkey";
$StepClient['ValidateFunc']="CheckLicense";

$StepRegister['Name']=$Lang['StepReg'];
$StepRegister['Folder']="register";
$StepRegister['ValidateFunc']="CheckReg";

$StepCompany['Name']=$Lang['StepCompany'];
$StepCompany['Folder']="company";
$StepCompany['ValidateFunc']="CheckCompany";

$StepConfirm['Name']=$Lang['StepConfirm'];
$StepConfirm['Folder']="confirm";
$StepConfirm['ValidateFunc']="CheckPermission";

$StepProcess['Name']=$Lang['StepInstall'];
$StepProcess['Folder']="create";
$StepProcess['ValidateFunc']="NextStep";

$StepCode['Name']=$Lang['StepCode'];
$StepCode['Folder']="code";
$StepCode['ValidateFunc']="NextStep";

$StepImport['Name']=$Lang['StepImport1'];
$StepImport['Folder']="import1";
$StepImport['ValidateFunc']="NextStep";

$StepImport2['Name']=$Lang['StepImport2'];
$StepImport2['Folder']="import2";
$StepImport2['ValidateFunc']="NextStep";


$StepArr[]=$StepLang;
$StepArr[]=$StepLicense;
$StepArr[]=$StepClient;
$StepArr[]=$StepDb;
$StepArr[]=$StepRegister;
$StepArr[]=$StepCompany;
$StepArr[]=$StepConfirm;
$StepArr[]=$StepProcess;
$StepArr[]=$StepImport;
$StepArr[]=$StepImport2;
$StepArr[]=$StepCode;

?>