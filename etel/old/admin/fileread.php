<?php
	
?>	
<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<body>
<?php
	$str_from = "sreeji_m@rediffmail.com";
	$str_to = "sreejith_m@hotmail.com";
	$str_subject = "Test mail from CS";
	$str_message = "Test content";
	func_send_mail($str_from,$str_to,$str_subject,$str_message)

?>
</body>
</html>
