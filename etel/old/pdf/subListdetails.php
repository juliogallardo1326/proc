<?php
$str_UserId = isset($HTTP_SESSION_VARS["sessionlogin"])?trim($HTTP_SESSION_VARS["sessionlogin"]):"";
if($str_UserId !="") {
	$gateway_id = func_get_value_of_field($cnn_cs,"cs_companydetails","gateway_id","userid",$str_UserId);
} else {
	$gateway_id =-1;
}
?>
<link href="styles/comp_set.css" rel="stylesheet" type="text/css">
<table border="0" cellpadding="0" cellspacing="0" height="60%">
<tr>
 <td width="100%" valign="top" align="center"><br>
<table border="0" cellpadding="0" cellspacing="0" align="right">
  <tr>
    <td width="524" valign="top" align="center" height="25">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <?php if($starthere) { ?><tr>
          <td width="100%" height="20"><img border="0" src="images/starthere.jpg" width="213" height="49"></td>
        </tr><?php } ?>
        <tr>
          <td width="100%"></td>
        </tr>
        <tr>
          <td width="100%" valign="middle" align="center">
<table border="0" cellspacing="0" cellpadding="0">
  <tr>
                      <td  height="30" class="sidemenu"><a href="application_cci.php" class="link1">1. Please fill out the online merchant application in its ENTIRETY.</a></td>
  </tr>
  <tr>
                      <td  height="30" class="sidemenu"><a href="merchantContract.php" class="link1">2. 
                        Request Rates and Fees </a></td>
  </tr>
  <tr>
                      <td  height="30" class="sidemenu"><a href="uploadDocuments.php" class="link1">3. 
                        Gather/Upload required documents.</a></td>
  </tr>
  <tr>
                      <td  height="30" class="sidemenu"><a href="merchantContract.php" class="link1">4. 
                        Sign Contract </a></td>
  </tr>

  <tr>
                      <td  height="30" class="sidemenu"><a href="integrate.php?type=testMode" class="link1">5. 
                        Integration.</a></td>
  </tr>
  <tr>
                      <td  height="30" class="sidemenu"><a href="integrate.php?type=testMode" class="link1">6. 
                        Put in Request to go Live.</a></td>
  </tr>
</table>
</td>
        </tr>
      </table>
      <div align="center"></div></td>
  </tr>
</table>
    </td>
  	</tr>
	</table>
