<?

$RunSql=false;

RunSqlArr(PrepareQueries(ReadFileData(SELF."/update/2.1.0/2.1.0.sql")));
$Query = "SELECT ID FROM ".PFX."_tracker_client";
$Sql = new Query($Query);
while ($Row=$Sql->Row()) {
	$Query = "ALTER TABLE `".PFX."_tracker_".$Row->ID."_stat_click` ADD `FRAUD` enum('0','1') NOT NULL DEFAULT '0' AFTER `SOURCE_HOST_ID`";
	$Db->Query($Query);
	$Query = "ALTER TABLE `".PFX."_tracker_".$Row->ID."_stat_sale` ADD `ADDITIONAL` text NOT NULL AFTER `CUSTOM_ORDER_ID`";
	$Db->Query($Query);
	$Query = "ALTER TABLE `".PFX."_tracker_".$Row->ID."_stat_click` ADD INDEX `FRAUD` (`FRAUD`)";
	$Db->Query($Query);
}	

?>