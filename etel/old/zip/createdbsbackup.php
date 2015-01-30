<?php 
//******************************************************************//
//  This file is part of the Zerone Consulting development package. //
//  Copyright (C) Zerone Consulting 2003-2004, All Rights Reserved. //
//                                                                  //
//******************************************************************//
// Package:         Zerone Consulting
// Description:     Online Payment Gateway
// dbsbackupfile.php:	The page functions for creating the zipped backup of the database dbs_companysetup .

$dateTimeStamp  = date("j F Y g i a");
$dateTimeStamp = str_replace(" ","_",$dateTimeStamp);
$zipFileName   = "dbs_companysetup_".$dateTimeStamp.".gz"; 
$outputFile = system ("/usr/bin/mysqldump -u root -pWSD%780= dbs_companysetup | gzip > /var/www/html/database/$zipFileName",$ret);
?>
