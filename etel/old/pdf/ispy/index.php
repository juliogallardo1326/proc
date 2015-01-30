<?php

	require_once('includes/sp_db.php');
	require_once('includes/ISPY.php');

	//error_reporting(0);
	

	$act = preg_replace('/[^a-z]/i','',$_REQUEST['act']);
	$menu_array=array(	'configuration'=>array('label'=>'Configuration','obj'=>'link'),
						'websites'=>array('label'=>'Website Management','obj'=>'link'),
						'reporting'=>array('label'=>'Reporting','obj'=>'link'),
						//'logout'=>array('label'=>'Log Out','obj'=>'link')
	);
	$login_array=array(	'username'=>array('obj'=>'input','type'=>'text','label'=>'Username'),
						'password'=>array('obj'=>'input','type'=>'password','label'=>'Password'),
						'login'=>array('obj'=>'input','value'=>'Log In','type'=>'submit')
	);
	//$menu_array=array(''=>"Username: <input name='username' type='text' size='12' >Password: <input name='password' type='password' size='12'>");

?>

<?php
	include(SP_HEADER);
?>

 <link rel="stylesheet" href="styles.css" type="text/css">
<table width="640">
<tr><td colspan="2">
<img src="img/header_1.png">
</td></tr>
<tr >
<td align="left"  class="spy-header">
<form action="?act=login" method="post" name="loginform" id="loginform">
<?php foreach($menu_array as $key=>$data) 
	switch($data['obj'])
	{
		case 'link':
			echo "<a href='?act=$key' ".($act==$key?"style='font-weight:bold;'":'').">".$data['label']."</a>";
			break;
		case 'input':
			echo "<a>".$data['label']." <input name='$key' type='".$data['type']."' value='".$data['value']."'></a>";
			break;
		default:
			echo "<a>".$data['label']."</a>";
			break;
	}
?>
</form>
</td>
</tr>
<tr>
<td colspan="2">
<?php
	$st = ISPY::sp_time();
	if($act) include("action/$act.php");
	$dur = ISPY::sp_dur($st);
?>
</td>
</tr>
<tr><td align="center"><strong>Generated in <?=$dur?></strong></td></tr>
</table>
<?php
	include(SP_FOOTER);
?>