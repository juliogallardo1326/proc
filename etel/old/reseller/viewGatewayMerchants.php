<?php 
$headerInclude = 'subgatewayusers';
include("includes/header.php");
include("../includes/companySubView.php");

$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$level = 'minimal';

$bank_sql_limit =" and cd_subgateway_id = '".$resellerInfo['reseller_id']."'";
if($resellerInfo['isMasterMerchant'])
{
beginTable();
echo genCompanyViewTable('editCompanyInfo.php','editCompanyInfo.php',$level);
endTable("Company Payment");
}
include("includes/footer.php");
?>