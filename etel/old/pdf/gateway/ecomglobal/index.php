<?php
	session_start();
	$localdir = "../../";
	chdir($localdir);
	require_once( 'includes/function.php');


	$database["server"]	="localhost";
	$database["user"]		="etel_root";
	$database["password"]	="WSD%780="	;
//	$database["database"]	="dbs_companysetup";	
	$database["database"]	="etel_gwEcomGlobal";	
	
	
	$cnn_cs = mysql_connect($database["server"],$database["user"],$database["password"]) 
       or die("Could not find server"); 
	mysql_select_db($database["database"],$cnn_cs) or die ("Unable to connect database"); 

	
	if($_POST['Login'] == "Login") general_login($_POST['username'],$_POST['password'],$_POST['type'],2);
?>

<form name="form1" method="post" action="">
 
<input type="hidden" name="login_redir" value="<?=$_REQUEST['login_redir']?>">
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
  </table>
   <p><a href="https://safe.ecommerceglobal.com/forgotpassword.php">Forgot Password?</a></p>
  </div>
</form>
<?php 
// make gateway login

?>