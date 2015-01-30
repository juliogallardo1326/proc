<?php
	session_start();
	if($_SESSION["userType"]!='Merchant')
	{
		header("location:index.php");
		die();
	}?>