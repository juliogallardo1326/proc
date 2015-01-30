<?php
$viewall=1;
include("../includes/companySubView.php");

$headerInclude = "companies";
include("includes/header.php");

$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$level = 'minimal';
if($adminInfo['li_level']=='full') $level = 'full';
if($adminInfo['username']=='etel1') {$_GET['showall']=1;$level = 'medium';}


beginTable();
echo genCompanyViewTable('editCompanyProfileAccess.php','viewCompanyNext.php',$level);
endTable("Company Payment");

include("includes/footer.php");
?>