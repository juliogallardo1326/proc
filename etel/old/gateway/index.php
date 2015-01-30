<?php
	$etel_debug_mode=0;
	if($_REQUEST['gw_id']) $gateway_db_select = $_REQUEST['gw_id'];

	session_start();
	//print_r($_SERVER);
	if(!$gateway_db_select) $gateway_db_select=3;
	require_once( '../includes/function.php');
	require_once( '../includes/dbconnection.php');

	if($_POST['Login'] == "Login") general_login($_POST['username'],$_POST['password'],$_POST['type'],$gateway_db_select);
?>
<style type="text/css">
<!--
.style1 {font-size: 12px}
-->
</style>


<form name="form1" method="post" action="">
 
  <div align="center">
   <table width="200" border="1">
    <tr bgcolor="#CCCCCC">
      <th scope="col">&nbsp;</th>
      <th scope="col">Gateway Login </th>
    </tr>
    <tr>
      <th bgcolor="#CCCCCC" scope="row">UserName</th>
      <td><input name="username" type="text" id="username"></td>
    </tr>
    <tr>
      <th bgcolor="#CCCCCC" scope="row">Password</th>
      <td><input name="password" type="password" id="password"></td>
    </tr>
    <tr>
      <th bgcolor="#CCCCCC" scope="row">User Type </th>
      <td><select name="type" >
        <option value="merchant">Merchant</option>
        <option value="reseller">Reseller</option>
        <option value="customerservice">Customer Service</option>
        <option value="admin">Admin</option>
      </select></td>
    </tr>
    <tr>
      <th bgcolor="#CCCCCC" scope="row">&nbsp;</th>
      <td><input name="Login" type="submit" id="Login" value="Login"></td>
    </tr>
  </table></div>
</form>