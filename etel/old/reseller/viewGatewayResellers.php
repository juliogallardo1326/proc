<?php 
$headerInclude = 'subgatewayusers';
include("includes/header.php");
include("../includes/resellerSubView.php");

$Transtype = isset($HTTP_GET_VARS['trans_type'])?quote_smart($HTTP_GET_VARS['trans_type']):"";
$companytype = isset($HTTP_GET_VARS['companymode'])?$HTTP_GET_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_GET_VARS['companytrans_type'])?quote_smart($HTTP_GET_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$level = 'minimal';

$qrt_select_company =" from cs_resellerdetails where rd_subgateway_id = '".$resellerInfo['reseller_id']."' order by reseller_companyname";
if($resellerInfo['isMasterMerchant'])
	genResellerViewTable($qrt_select_company,'editResellerInfo.php','editResellerInfo.php',$level);

include("includes/footer.php");
?>