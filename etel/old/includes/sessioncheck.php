<?php
	session_start();
	if($_SESSION["userType"]!="Merchant")
	{
		$index = $_SESSION["gw_index"];
		if($_GET['nr']) header("location:".$index);
		else header("location:".$index."?login_redir=".base64_encode($_SERVER['REQUEST_URI']));
		die();
	}
	?>