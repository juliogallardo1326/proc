<?php
	require_once 'sp_func.php';
	require_once 'sp_config.php';
	require_once 'FTP.php';
	include('adodb/adodb.inc.php');
	define("TBL_LINKS","sp_links");
	
	$DB = NewADOConnection(DB_TYPE);
	$DB->Connect(DB_SERVER, DB_USER, DB_PASS, DB_DBNAME);
	$DB->Execute('use '.DB_DBNAME);
 	$DB->SetFetchMode(ADODB_FETCH_ASSOC);

?>