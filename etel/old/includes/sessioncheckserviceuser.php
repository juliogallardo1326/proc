<?php
	session_start();
	//print($_SESSION["sessionService"]);
	if(!isset($_SESSION["sessionServiceUser"]) && !isset($_SESSION["sessionService"]))
	{
		$index = $_SESSION["gw_index"];
		header("location:".$index."?login_redir=".base64_encode($_SERVER['REQUEST_URI']));
	}?>