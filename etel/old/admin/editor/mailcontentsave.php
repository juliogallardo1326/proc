<?php
include("../../includes/dbconnection.php");
$strContent = (isset($HTTP_POST_VARS["submitData"])?quote_smart($HTTP_POST_VARS["submitData"]):"");
$iTemplateId = (isset($HTTP_POST_VARS["hdId"])?quote_smart($HTTP_POST_VARS["hdId"]):"");
if($strContent <> "" and $iTemplateId <> ""){
	$qryUpdate = "update cs_mailtemplate set template_content = '".$strContent."' where template_id =".$iTemplateId;
	if(!(mysql_query($qryUpdate,$cnn_cs))){
		print("Can not execute query");
		exit();
	}else{
?>
<body onload="javascript:window.close();">
</body>
<script language="JavaScript">
window.close();
alert("File updated successfully.")
</script>

<?	
	}
}
?>



