<?
function GetPagesList($HostId, $Templ=false, $PageStart=false, $Limit=false)
{
	$PagesArr=array();
	$LimitStr="";
	if ($Limit&&!$PageStart) $LimitStr=" LIMIT $Limit";
	if ($Limit&&$PageStart) $LimitStr="LIMIT $PageStart, $Limit";
	$Like="";
	if ($Templ) $Like=" AND PATH LIKE '%$Templ%'";
	$Query = "SELECT * FROM ".PFX."_tracker_site_page WHERE SITE_ID=$HostId $Like ORDER BY PATH ASC $LimitStr";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row=$Sql->Row()) {
		$Row->Actions=array();
		$Query = "SELECT * FROM ".PFX."_tracker_visitor_action WHERE PAGE_ID = ".$Row->ID." AND SITE_ID = $HostId";
		$SubSql = new Query($Query);
		while ($SubRow=$SubSql->Row()) {
			$Row->Actions[]=$SubRow;
		}
		$Row->_STYLE=$Sql->_STYLE;
		$Row->PATH=htmlspecialchars($Row->PATH);
		$PagesArr[]=$Row;
	}
	if (count($PagesArr)>0) return $PagesArr;
	else return false;
}

function GetActions($PageId=false, $HostId=false)
{
	if (!ValidId($PageId)||!ValidId($HostId)) return false;
	$Actions=array();
	$Query = "SELECT * FROM ".PFX."_tracker_visitor_action WHERE PAGE_ID = $PageId AND SITE_ID=$HostId";
	$Sql = new Query($Query);
	while ($Row=$Sql->Row()) {
		$Actions[]=$Row;
	}
	if (count($Actions)>0) return $Actions;
	else return false;
}

function GetEvent($Id)
{
	global $Db;
	$Query = "SELECT * FROM ".PFX."_tracker_visitor_action WHERE ID = $Id";
	$Event=$Db->Select($Query);
	return $Event;
}


function GetPage($Id)
{
	global $Db;
	$Query = "SELECT * FROM ".PFX."_tracker_site_page WHERE ID = $Id";
	$Page=$Db->Select($Query);
	return $Page;
}


function GetCompany($Id)
{
	global $Db;
	$Query = "SELECT * FROM ".PFX."_tracker_client WHERE ID = $Id";
	$Company=$Db->Select($Query);
	return $Company;
}


function GetHost($Id, $NoHosts=false)
{
	global $Db;
	$Query = "SELECT * FROM ".PFX."_tracker_site WHERE ID = $Id";
	$EditHost=$Db->Select($Query);
	if (!$NoHosts) {
		$EditHost->Hosts=array();		
		$Query = "SELECT * FROM ".PFX."_tracker_site_host WHERE SITE_ID=".$EditHost->ID." ORDER BY HOST";
		$SubSql=new Query($Query);	
		while ($SubRow=$SubSql->Row()) $EditHost->Hosts[]=$SubRow;
	}
	return $EditHost;
}

function GetHostsList($Id)
{
	global $ShowExpand;
	$HostsArr=array();
	$Query= "SELECT * FROM ".PFX."_tracker_site WHERE COMPANY_ID = $Id ORDER BY HOST";
	$Sql = new Query($Query);
	$Sql->ReadSkinConfig();
	while ($Row = $Sql->Row()) {
		$Row->_STYLE=$Sql->_STYLE;
		$Row->Hosts=array();
		if ($Row->USE_HOSTS) $ShowExpand+=1;
		$Query = "SELECT * FROM ".PFX."_tracker_site_host WHERE SITE_ID=".$Row->ID." ORDER BY HOST";
		$SubSql=new Query($Query);
		while ($SubRow=$SubSql->Row()) $Row->Hosts[]=$SubRow;
		$HostsArr[]=$Row;
	}
	if (count($HostsArr)>0) return $HostsArr;
	else return false;
}


function CreateStatTables($Prefix=false)
{
	global $Db;
	if (!$Prefix) return false;
	$Query = "
		CREATE TABLE `".PFX."_tracker_".$Prefix."_stat_log` (
	  `ID` int(11) unsigned NOT NULL auto_increment,
	  `STAMP` datetime NOT NULL default '0000-00-00 00:00:00',
	  `VISITOR_ID` int(11) unsigned NOT NULL default '0',
	  `SITE_ID` int(11) unsigned NOT NULL default '0',
	  `SITE_HOST_ID` int(11) unsigned NOT NULL default '0',
	  `COOKIE_LOG` int(11) unsigned NOT NULL default '0',
	  `PAGE_ID` int(11) unsigned NOT NULL default '0',
	  `QUERY_ID` int(11) unsigned NOT NULL default '0',
	  `REFERER_SET` int(11) unsigned NOT NULL default '0',
	  `IP_ID` int(11) unsigned NOT NULL default '0',
	  `AGENT_ID` int(11) unsigned NOT NULL default '0',
	  `SCHEME` enum('0','1') NOT NULL default '0',
	  PRIMARY KEY  (`ID`),
	  KEY `VISITOR_ID` (`VISITOR_ID`),
	  KEY `COOKIE_LOG` (`COOKIE_LOG`),
	  KEY `DBL_1` (`SITE_ID`,`STAMP`),
	  KEY `PAGE_ID` (`PAGE_ID`),
	  KEY `QUERY_ID` (`QUERY_ID`),
	  KEY `SITE_ID` (`SITE_ID`),
	  KEY `REFERER_SET` (`REFERER_SET`),
	  KEY `SITE_HOST_ID` (`SITE_HOST_ID`),
	  KEY `IP_ID` (`IP_ID`),
	  KEY `AGENT_ID` (`AGENT_ID`)
	)
	";
	$Db->Query($Query);
	$Query = "
		CREATE TABLE `".PFX."_tracker_".$Prefix."_stat_action` (
	  `ID` int(11) unsigned NOT NULL auto_increment,
	  `LOG_ID` int(11) unsigned NOT NULL default '0',
	  `ACTION_ID` int(11) unsigned NOT NULL default '0',
	  `SITE_ID` int(11) unsigned NOT NULL default '0',
	  PRIMARY KEY  (`ID`),
	  KEY `LOG_ID` (`LOG_ID`),
	  KEY `ACTION_ID` (`ACTION_ID`),
	  KEY `SITE_ID` (`SITE_ID`)
	) 
	";
	$Db->Query($Query);
	$Query = "
	CREATE TABLE `".PFX."_tracker_".$Prefix."_stat_click` (
	  `ID` int(11) unsigned NOT NULL auto_increment,
	  `LOG_ID` int(11) unsigned NOT NULL default '0',
	  `KEYWORD_ID` int(11) unsigned NOT NULL default '0',
	  `CAMP_ID` int(11) unsigned NOT NULL default '0',
	  `SOURCE_HOST_ID` int(11) unsigned NOT NULL default '0',
	  `FRAUD` enum('0','1') NOT NULL DEFAULT '0',
	  PRIMARY KEY  (`ID`),
	  KEY `LOG_ID` (`LOG_ID`),
	  KEY `KEYWORD_ID` (`KEYWORD_ID`),
	  KEY `CAMP_ID` (`CAMP_ID`),
	  KEY `SOURCE_HOST_ID` (`SOURCE_HOST_ID`),
	  INDEX `FRAUD` (`FRAUD`)
	)
	";
	$Db->Query($Query);
	$Query = "
		CREATE TABLE `".PFX."_tracker_".$Prefix."_stat_sale` (
	  `ID` int(11) unsigned NOT NULL auto_increment,
	  `LOG_ID` int(11) unsigned NOT NULL default '0',
	  `COST` float(9,2) unsigned NOT NULL default '0.00',
	  `SITE_ID` int(11) unsigned NOT NULL default '0',
	  `CUSTOM_ORDER_ID` varchar(64) NOT NULL default '',
	  `ADDITIONAL` text NOT NULL,
	  PRIMARY KEY  (`ID`),
	  KEY `LOG_ID` (`LOG_ID`),
	  KEY `SITE_ID` (`SITE_ID`),
	  KEY `CUSTOM_ORDER_ID` (`CUSTOM_ORDER_ID`)
	)
	";
	$Db->Query($Query);
	$Query = "
		CREATE TABLE `".PFX."_tracker_".$Prefix."_stat_split` (
	  `ID` int(11) unsigned NOT NULL auto_increment,
	  `LOG_ID` int(11) unsigned NOT NULL default '0',
	  `SPLIT_ID` int(11) unsigned NOT NULL default '0',
	  PRIMARY KEY  (`ID`),
	  KEY `LOG_ID` (`LOG_ID`),
	  KEY `SPLIT_ID` (`SPLIT_ID`)
	) 
	";
	$Db->Query($Query);
	$Query = "
		CREATE TABLE `".PFX."_tracker_".$Prefix."_stat_undef` (
	  `ID` int(11) unsigned NOT NULL auto_increment,
	  `LOG_ID` int(11) unsigned NOT NULL default '0',
	  `ADDRESS` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`ID`),
	  KEY `ADDRESS` (`ADDRESS`),
	  KEY `LOG_ID` (`LOG_ID`)
	)
	";
	$Db->Query($Query);
}

function DropStatTables($Prefix=false)
{
	global $Db;
	if (!$Prefix) return false;
	$Query = "DROP TABLE ".PFX."_tracker_".$Prefix."_stat_log";
	$Db->Query($Query);
	$Query = "DROP TABLE ".PFX."_tracker_".$Prefix."_stat_action";
	$Db->Query($Query);
	$Query = "DROP TABLE ".PFX."_tracker_".$Prefix."_stat_click";
	$Db->Query($Query);
	$Query = "DROP TABLE ".PFX."_tracker_".$Prefix."_stat_sale";
	$Db->Query($Query);
	$Query = "DROP TABLE ".PFX."_tracker_".$Prefix."_stat_split";
	$Db->Query($Query);
	$Query = "DROP TABLE ".PFX."_tracker_".$Prefix."_stat_undef";
	$Db->Query($Query);
}

?>