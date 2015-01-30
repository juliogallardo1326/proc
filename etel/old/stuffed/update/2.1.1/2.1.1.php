<?

$RunSql=false;
$UpdateVersion=true;
$Query = "SELECT ID FROM ".PFX."_tracker_client";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$Query = "ALTER TABLE `".PFX."_tracker_".$Row->ID."_stat_sale` CHANGE `ADDITIONAL` `ADDITIONAL` text NOT NULL";
	$Db->Query($Query);
}	


?>