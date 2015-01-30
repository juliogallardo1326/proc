<?

class NS_TRACK_GENERAL {


function UpdateVisitorAction()
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$Actions=&$_NS_TRACK_VARS['Actions'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$UpdateVisPath=&$_NS_TRACK_VARS['UpdateVisPath'];

	if (!NS_TRACK_MISC::ValidArr($Actions)||count($Actions)==0) return false;
	foreach ($Actions as $ActionId=>$Arr) {
		$UpdateVisPath=true;
		$Item=NS_TRACK_MISC::ValidVar($Actions[$ActionId]['Item']);
		if (!$LogId) $LogId=NS_TRACK_GENERAL::GetLogRecord();
		$Query = "
			INSERT INTO ".NS_DB_PFX."_tracker_".$CompanyId."_stat_action 
				(LOG_ID, ACTION_ID, SITE_ID) VALUES
				($LogId, $ActionId, $StId)
		";
		$Db->Query($Query);
		$StatActionId=$Db->LastInsertId;
		if (NS_TRACK_MISC::ValidVar($Item)!=false) {
			$ItemId=NS_TRACK_ACTION::GetActionItemId($Item, $CompanyId);
			$Query = "
				INSERT INTO ".NS_DB_PFX."_tracker_action_set 
				(STAT_ACTION_ID, ACTION_ITEM_ID, COMPANY_ID) VALUES 
				($StatActionId, $ItemId, $CompanyId)
			";
			$Db->Query($Query);
		}
		$Item=false;
	}
}

function UpdateVisitorPath()
{


	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$PageId=&$_NS_TRACK_VARS['PageId'];
	$RefSet=&$_NS_TRACK_VARS['RefSet'];
	$QueryId=&$_NS_TRACK_VARS['QueryId'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$SiteHostId=&$_NS_TRACK_VARS['SiteHostId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$SSL=&$_NS_TRACK_VARS['SSL'];

	if (!$PageId) return false;
	if (!$LogId) $LogId=NS_TRACK_GENERAL::GetLogRecord();
	$Scheme=($SSL)?1:0;
	$Query = "
		UPDATE ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log SET  PAGE_ID = $PageId, REFERER_SET = $RefSet, QUERY_ID=$QueryId, SITE_HOST_ID=$SiteHostId , SCHEME='$Scheme' 
		WHERE ID=$LogId
	";
	$Db->Query($Query);
}

function UpdateVisitorSplit($SplitCamp)
{

	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$LogId=&$_NS_TRACK_VARS['LogId'];


	if (!$LogId) $LogId=NS_TRACK_GENERAL::GetLogRecord();
	$Query = "
		INSERT INTO ".NS_DB_PFX."_tracker_".$CompanyId."_stat_split
			(LOG_ID, SPLIT_ID)
			VALUES 
			($LogId, $SplitCamp)
	";
	$Db->Query($Query);
}

function UpdateVisitorClick()
{


	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$SourceId=&$_NS_TRACK_VARS['SourceId'];
	$KeyId=&$_NS_TRACK_VARS['KeyId'];
	$ClickSubId=&$_NS_TRACK_VARS['ClickSubId'];
	$UpdateVisPath=&$_NS_TRACK_VARS['UpdateVisPath'];
	$CookieLogSet=&$_NS_TRACK_VARS['CookieLogSet'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$Fraud=&$_NS_TRACK_VARS['Fraud'];

	if (!$LogId) $LogId=NS_TRACK_GENERAL::GetLogRecord();
	$UpdateVisPath=true;
	$CookieLogSet=true;
	if (!$Fraud) $Fraud="0";
	$Query = "
		INSERT INTO ".NS_DB_PFX."_tracker_".$CompanyId."_stat_click
			(LOG_ID, SOURCE_HOST_ID, KEYWORD_ID, CAMP_ID, FRAUD)
			VALUES 
			($LogId, $SourceId, $KeyId, $ClickSubId, '$Fraud')
	";
	$Db->Query($Query);
}

function UpdateVisitorSale()
{

	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$Cost=&$_NS_TRACK_VARS['Cost'];
	$Items=&$_NS_TRACK_VARS['Items'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$CustomId=&$_NS_TRACK_VARS['CustomId'];
	$AddInfo=&$_NS_TRACK_VARS['AddInfo'];


	if (!$LogId) $LogId=NS_TRACK_GENERAL::GetLogRecord();
	if ($CustomId&&NS_TRACK_GENERAL::CheckDblSale($StId, $CustomId)) return false;

	if ($AddInfo===false) $AddInfo="";

	if ($Cost==0&&NS_TRACK_MISC::ValidArr($Items)&&count($Items)>0) {
		for($i=0;$i<count($Items);$i++) $Cost+=$Items[$i][2]*$Items[$i][1];
	}

	$Query = "
		INSERT INTO ".NS_DB_PFX."_tracker_".$CompanyId."_stat_sale
			(LOG_ID, COST, SITE_ID, CUSTOM_ORDER_ID, ADDITIONAL) VALUES 
			($LogId, '$Cost', $StId, '$CustomId', ?)
	";
	$Db->Query($Query, $AddInfo);
	$SaleId=$Db->LastInsertId;
	if (NS_TRACK_MISC::ValidArr($Items)) {
		for($i=0;$i<count($Items);$i++) {
			$Count=$Items[$i][2];
			settype($Count, "integer");
			if (!$Count) continue;
			$Cost=$Items[$i][1];
			settype($Cost, "float");
			$ItemId=NS_TRACK_SALE::GetSaleItem($Items[$i][0], $CompanyId);
			$Query = "
				INSERT INTO ".NS_DB_PFX."_tracker_sale_set 
				(SALE_ID, ITEM_ID, QUANT, COST, COMPANY_ID) VALUES 
				($SaleId, $ItemId, $Count, '$Cost', $CompanyId)
			";
			$Db->Query($Query);
		}
	}
}


function UpdateStatUndef()
{

	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$Current=&$_NS_TRACK_VARS['Current'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	
	if (!$LogId) $LogId=NS_TRACK_GENERAL::GetLogRecord();
	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_".$CompanyId."_stat_undef (LOG_ID, ADDRESS) VALUES ($LogId, '$Current')";
	$Db->Query($Query);
}

///////////////////////////////////////////////////////
///


function GetLogRecord()
{
	global $_COOKIE;

	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$VisId=&$_NS_TRACK_VARS['VisId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$IpId=&$_NS_TRACK_VARS['IpId'];
	$AgentId=&$_NS_TRACK_VARS['AgentId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$FindLastNode=&$_NS_TRACK_VARS['FindLastNode'];

	$CookieLog=0;
	$CookieStr=(NS_TRACK_MISC::ValidVar($_COOKIE[NS_COOKIE_PFX.'log']))?$_COOKIE[NS_COOKIE_PFX.'log']:false;
	if ($CookieStr) {
		$CookieStr=@urldecode($CookieStr);
		$CookieStr=@stripslashes($CookieStr);
		$CookieArr=@unserialize($CookieStr);
		if (NS_TRACK_MISC::ValidArr($CookieArr)&&NS_TRACK_MISC::ValidVar($CookieArr[$StId])) $CookieLog=$CookieArr[$StId];
		if (NS_TRACK_MISC::ValidId($CookieLog) && $CookieLog > 0) {
			$FindLastNode=false;
			Header("x-Log-Exists: yes_$CookieLog");
		}
	}
	$Stamp=gmdate("Y-m-d H:i:s", time());
	$Query ="INSERT INTO ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log (VISITOR_ID, SITE_ID, STAMP, COOKIE_LOG, IP_ID, AGENT_ID) VALUES ($VisId, $StId, '$Stamp', $CookieLog, $IpId, $AgentId)";
	$Db->Query($Query);
	return $Db->LastInsertId;
}


//////////////////////////////////////////////////////
// MISC

function GetCurrentSite($StId) 
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$Site=false;
	$Query="SELECT * FROM ".NS_DB_PFX."_tracker_site WHERE ID=$StId";
	$Site=$Db->Select($Query);
	 $_NS_TRACK_VARS['COOKIE_DOMAIN']=$Site->COOKIE_DOMAIN;
	return $Site;
}

function GetSiteHosts($StId, $Site=false) 
{
	$Where="";
	global $_NS_TRACK_VARS;
	$QueryClass=&$_NS_TRACK_VARS['QueryClass'];

	if ($Site&&NS_TRACK_MISC::ValidId($Site->ID)&&$Site->USE_HOSTS) $Where=" AND ENABLED = '1'";
	$Hosts=array();
	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_site_host WHERE SITE_ID=$StId $Where";
	$Sql = new $QueryClass($Query);
	while ($Row=$Sql->Row()) $Hosts[$Row->HOST]=$Row;
	return $Hosts;
}

function CurrentSiteHost($HostsArr=false, $PathArr=false, $Site=false)
{
	$HostId=0;
	if (!$PathArr||!$HostsArr) return 0;
	if (isset($HostsArr[NS_TRACK_MISC::ToLower($PathArr['host'])])) $HostId=$HostsArr[NS_TRACK_MISC::ToLower($PathArr['host'])]->ID;
	if (!$HostId&&$Site&&!$Site->USE_HOSTS) return NS_TRACK_GENERAL::AddNewSiteHost($Site->ID, $PathArr['host']);
	return (NS_TRACK_MISC::ValidId($HostId))?$HostId:0;
}

function AddNewSiteHost($SiteId, $Host)
{
	global $_NS_TRACK_VARS;
	$HostsArr=&$_NS_TRACK_VARS['HostsArr'];
	$Db=&$_NS_TRACK_VARS['Db'];
	
	$Host=NS_TRACK_MISC::ToLower(trim($Host));
	$Query = "INSERT INTO ".NS_DB_PFX."_tracker_site_host (SITE_ID, HOST, ENABLED) VALUES ($SiteId, ?, '1')";
	$Db->Query($Query, $Host);
	$NewId=$Db->LastInsertId;
	$Query = "SELECT * FROM ".NS_DB_PFX."_tracker_site_host WHERE ID = $NewId";
	$OHost=$Db->Select($Query);
	$HostsArr[$Host]=$OHost;
	return $NewId;
}


function PreparePathAddr($Addr)
{
	$Arr=@parse_url(urldecode(urldecode($Addr)));
	if (!isset($Arr['path'])) $Arr['path']="";
	$Arr['path']=ereg_replace("/+", "/", $Arr['path']);
	if ($Arr['path']=="") $Arr['path']="/";
	if (!NS_TRACK_MISC::ValidVar($Arr['host'])) return false;
	$Arr['host']=NS_TRACK_MISC::ToLower($Arr['host']);
	if (!isset($Arr['query'])) $Arr['query']="";
	return $Arr;
}

function ValidHost($Host)
{
	$Arr1=array();
	$Arr2=array();
	$From="~!@#$%^&*()_+|`=\\{}[]:\";',/<>?¹";
	$To="                                 ";
	$Arr1=explode(".", $Host);
	for($i=0;$i<count($Arr1);$i++) {
		$Arr1[$i]=strtr($Arr1[$i], $From, $To);
		$Arr1[$i]=preg_replace("/\s+/", " ", $Arr1[$i]);
		if ($Arr1[$i]=="") continue;
		$Arr2[]=$Arr1[$i];
	}
	if (count($Arr2)<2) return false;
	$Host=implode(".",$Arr2);
	return $Host;
}


function SetCookieLog($StartNewNode=true, $SetLogId=false)
{
	global $nsUser, $_COOKIE;
	global $_NS_TRACK_VARS;

	$Db=&$_NS_TRACK_VARS['Db'];
	$LogId=&$_NS_TRACK_VARS['LogId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$FindLastNode=&$_NS_TRACK_VARS['FindLastNode'];
	$CookieStr=(NS_TRACK_MISC::ValidVar($_COOKIE[NS_COOKIE_PFX.'log']))?$_COOKIE[NS_COOKIE_PFX.'log']:false;
	if ($CookieStr) {
		$CookieStr=@urldecode($CookieStr);
		$CookieStr=@stripslashes($CookieStr);
		$CookieArr=@unserialize($CookieStr);
		if (!NS_TRACK_MISC::ValidArr($CookieArr)) $CookieArr=array();
	} 
	else $CookieArr=array();
	$CookieArr[$StId]=$SetLogId?$SetLogId:$LogId;
	$CookieStr=serialize($CookieArr);

	$Domain=false;
	if (defined("COOKIE_DOMAIN")) $Domain=COOKIE_DOMAIN;
	if (isset($_NS_TRACK_VARS['COOKIE_DOMAIN'])) $Domain=$_NS_TRACK_VARS['COOKIE_DOMAIN'];

	NS_TRACK_GENERAL::TrackingCookie(NS_COOKIE_PFX."log", $CookieStr, time()+60*60*24*10*365, "/", $Domain);
	$FindLastNode=false;
	Header("x-Set-Coookie-Log: yes");
	
	if (!$StartNewNode && $SetLogId) {
		$Query = "UPDATE ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log SET COOKIE_LOG=$SetLogId WHERE ID=$LogId";
		$Db->Query($Query);
		Header("x-LogUpdated: $Query");
	}

	if (!$StartNewNode) return;
	$Db->Query("UPDATE ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log SET COOKIE_LOG=$LogId WHERE ID=$LogId");
}


function FlushImg()
{
	if (defined("NO_TRACK_IMG")&&NO_TRACK_IMG) return;
	$Img="R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
	Header("Content-type: image/gif");
	echo base64_decode($Img);
	exit();
}

function FinishTracking()
{
	global $_NS_TRACK_VARS;
	if (!defined("NS_PHP_TRACKING")) {
		if (isset($_NS_TRACK_VARS['ExitFunc'])) {
			$ExitFunc=$_NS_TRACK_VARS['ExitFunc'];
			return $ExitFunc();
		}
		else return NS_TRACK_GENERAL::FlushImg();
	}
	return false;
}

function TrackingCookie($Name=false, $Value=false, $Expire=false, $Path=false, $Domain=false, $Secure=false)
{
	if (defined("NS_PHP_TRACKING")) {
		setcookie($Name, $Value, $Expire, $Path, $Domain, $Secure);
	}
	else {
		global $nsUser;
		$nsUser->SetCookie($Name, $Value, $Expire, $Path, $Domain, $Secure);
	}
	global $_COOKIE;
	$_COOKIE[$Name]=$Value;
}







function CheckPathDblClick()
{

	
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$PageId=&$_NS_TRACK_VARS['PageId'];
	$RefSet=&$_NS_TRACK_VARS['RefSet'];
	$QueryId=&$_NS_TRACK_VARS['QueryId'];
	$VisId=&$_NS_TRACK_VARS['VisId'];
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$TimeDblPageLoad=&$_NS_TRACK_VARS['TimeDblPageLoad'];
	
	$TimeLimit=(NS_TRACK_MISC::ValidVar($TimeDblPageLoad))?$TimeDblPageLoad:1;
	$Query="
		SELECT
		UNIX_TIMESTAMP(S_LOG.STAMP)
		FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log S_LOG
		WHERE S_LOG.VISITOR_ID=$VisId
		AND S_LOG.PAGE_ID=$PageId
		AND S_LOG.QUERY_ID=$QueryId
		AND S_LOG.REFERER_SET=$RefSet
		AND S_LOG.SITE_ID=$StId
		ORDER BY S_LOG.STAMP DESC
		LIMIT 1
	";
	$CheckStamp=$Db->ReturnValue($Query);
	if (time()-$CheckStamp<$TimeLimit) $Skip=true;
}

function CheckAdvDblClick()
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$VisId=&$_NS_TRACK_VARS['VisId'];
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$ClickSubId=&$_NS_TRACK_VARS['ClickSubId'];
	$KeyId=&$_NS_TRACK_VARS['KeyId'];
	$SourceId=&$_NS_TRACK_VARS['SourceId'];
	
	if (!$ClickSubId) return;
	$TimeDblAdvClick=&$_NS_TRACK_VARS['TimeDblAdvClick'];
	
	$TimeLimit=(NS_TRACK_MISC::ValidVar($TimeDblAdvClick))?$TimeDblAdvClick:5;
	$Query="
		SELECT
		UNIX_TIMESTAMP(S_LOG.STAMP)
		FROM  ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log S_LOG
			INNER JOIN ".NS_DB_PFX."_tracker_".$CompanyId."_stat_click S_CLICK
				ON S_CLICK.LOG_ID=S_LOG.ID
		WHERE S_LOG.VISITOR_ID=$VisId
		AND S_LOG.SITE_ID=$StId
		AND S_CLICK.KEYWORD_ID=$KeyId
		AND S_CLICK.CAMP_ID=$ClickSubId
		AND S_CLICK.SOURCE_HOST_ID=$SourceId
		ORDER BY S_LOG.STAMP DESC
		LIMIT 1
	";
	$CheckStamp=$Db->ReturnValue($Query);
	if (time()-$CheckStamp<$TimeLimit) $Skip=true;
}


function CheckDblSale($StId, $CustomId)
{
	if (!$CustomId) return false;

	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
		
	$CustomId=NS_TRACK_MISC::escape_string($CustomId);
	$Query = "SELECT ID FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_sale WHERE SITE_ID=$StId AND CUSTOM_ORDER_ID='$CustomId'";
	$CheckId=$Db->ReturnValue($Query);
	return ($CheckId)?true:false;
}

function CheckSaleDblClick()
{

	
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$VisId=&$_NS_TRACK_VARS['VisId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];	
	$TimeDblSale=&$_NS_TRACK_VARS['TimeDblSale'];
	
	$TimeLimit=(NS_TRACK_MISC::ValidVar($TimeDblSale))?$TimeDblSale:5;
	$Query = "
		SELECT 
		UNIX_TIMESTAMP(S_LOG.STAMP)
		FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log S_LOG
		INNER JOIN ".NS_DB_PFX."_tracker_".$CompanyId."_stat_sale S_SALE
			ON S_SALE.LOG_ID=S_LOG.ID
		WHERE S_LOG.VISITOR_ID=$VisId
		AND S_LOG.SITE_ID=$StId
		ORDER BY S_LOG.STAMP DESC
		LIMIT 1
	";
	$CheckStamp=$Db->ReturnValue($Query);
	if (time()-$CheckStamp<$TimeLimit) $Skip=true;
}


function CheckActionDblClick($ActionId)
{
	
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$VisId=&$_NS_TRACK_VARS['VisId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$Skip=&$_NS_TRACK_VARS['Skip'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];	
	$TimeDblEvent=&$_NS_TRACK_VARS['TimeDblEvent'];
		
	$TimeLimit=(NS_TRACK_MISC::ValidVar($TimeDblEvent))?$TimeDblEvent:5;
	$Query = "
		SELECT 
		UNIX_TIMESTAMP(S_LOG.STAMP)
		FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log S_LOG
		INNER JOIN ".NS_DB_PFX."_tracker_".$CompanyId."_stat_action S_ACTION
			ON S_ACTION.LOG_ID=S_LOG.ID
		WHERE S_LOG.VISITOR_ID=$VisId
		AND S_LOG.SITE_ID=$StId
		AND S_ACTION.ACTION_ID=$ActionId
		ORDER BY S_LOG.STAMP DESC
		LIMIT 1
	";
	$CheckStamp=$Db->ReturnValue($Query);
	if (time()-$CheckStamp<$TimeLimit) $Skip=true;
}

function CheckClickFraud(&$Settings, $VisId=false, $SubId=false)
{

	if (!$SubId||!$VisId) return 0;
	if (!$Settings['All']->FRAUD_ENABLE) return 0;
	if ($Settings['All']->FRAUD_COUNT<1) return 0;
	if ($Settings['All']->FRAUD_PERIOD <1) return 0;
	
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];	
	
	$Stamp=gmdate("Y-m-d H:i:s", time());
	$Query = "
		SELECT COUNT(S_LOG.ID) 
		FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log S_LOG
		INNER JOIN ".NS_DB_PFX."_tracker_".$CompanyId."_stat_click S_CLICK
			ON S_CLICK.LOG_ID=S_LOG.ID
		
		WHERE S_LOG.VISITOR_ID=$VisId
				AND S_CLICK.CAMP_ID=$SubId
				AND S_LOG.STAMP >= DATE_ADD('$Stamp', INTERVAL -".$Settings['All']->FRAUD_PERIOD." MINUTE)
	";
	$Count=$Db->ReturnValue($Query);
	if (!$Count) return 0;
	if ($Count>=$Settings['All']->FRAUD_COUNT) return 1;
}


function CheckTrackingMode()
{
	global $_NS_TRACK_VARS, $_COOKIE;
	$Db=&$_NS_TRACK_VARS['Db'];
	$Settings=&$_NS_TRACK_VARS['Settings'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$VisId=&$_NS_TRACK_VARS['VisId'];
	$ClickSubId=&$_NS_TRACK_VARS['ClickSubId'];
	$TM=$Settings['All']->TRACKING_MODE;
	if (!$TM) return false;
	$Mode="";
	$PaidPoint="";
	$NaturalPoint="";
	$NonePoint="";


	$PriorArr=explode("|", $TM);
	$Mode=NS_TRACK_MISC::ValidVar($PriorArr[0], "NONE");
	if (NS_TRACK_MISC::ValidVar($PriorArr[1])) {
		$EntryArr=explode(";", $PriorArr[1]);
		for($i=0;$i<count($EntryArr);$i++) {
			$TmpArr=explode(":", $EntryArr[$i]);
			if (NS_TRACK_MISC::ValidVar($TmpArr[0])=="NONE") $NonePoint=NS_TRACK_MISC::ValidVar($TmpArr[1], "LAST");
			if (NS_TRACK_MISC::ValidVar($TmpArr[0])=="PAID") $PaidPoint=NS_TRACK_MISC::ValidVar($TmpArr[1], "LAST");
			if (NS_TRACK_MISC::ValidVar($TmpArr[0])=="NATURAL") $NaturalPoint=NS_TRACK_MISC::ValidVar($TmpArr[1], "LAST");
		}
	}

	$RequestedMode=($ClickSubId)?"PAID":"NATURAL";

	if ($Mode=="NONE" && $NonePoint=="LAST") return false;

	if ($Mode=="PAID" && $PaidPoint=="LAST" && $ClickSubId) return false;
	if ($Mode=="NATURAL" && $NaturalPoint=="LAST" && !$ClickSubId) return false;

	if ($Mode=="NONE" && $NonePoint=="FIRST") {
		$NodeId=NS_TRACK_GENERAL::FindSomeNode("ANY", "FIRST");
		if ($NodeId) return NS_TRACK_GENERAL::UpdateCookieLog($NodeId);
		return false;
	}



	$NodeId=NS_TRACK_GENERAL::FindSomeNode($Mode, "FIRST");
	if ($NodeId) return NS_TRACK_GENERAL::UpdateCookieLog($NodeId);

	if ($Mode==$RequestedMode) return false;

	$NodeId=NS_TRACK_GENERAL::FindSomeNode( (($Mode=="PAID")?"NATURAL":"PAID") , (($Mode=="PAID")?$NaturalPoint:$PaidPoint) );
	if ($NodeId) return NS_TRACK_GENERAL::UpdateCookieLog($NodeId);
	return false;
}


function FindSomeNode($Mode="ANY", $Order="LAST")
{
	global $_NS_TRACK_VARS;
	$Db=&$_NS_TRACK_VARS['Db'];
	$CompanyId=&$_NS_TRACK_VARS['CompanyId'];
	$StId=&$_NS_TRACK_VARS['StId'];
	$VisId=&$_NS_TRACK_VARS['VisId'];

	$JoinStr="";
	$JoinArr=array();
	$WhereStr="";
	$WhereArr=array();

	if ($Mode=="PAID") {
		$JoinArr[]="INNER JOIN ".NS_DB_PFX."_tracker_".$CompanyId."_stat_click S_CLICK ON S_CLICK.LOG_ID=S_LOG.ID";
	}
	if ($Mode=="NATURAL") $WhereArr[]="S_LOG.REFERER_SET>0";
	if ($Mode=="ANY") {
		$JoinArr[]="LEFT JOIN ".NS_DB_PFX."_tracker_".$CompanyId."_stat_click S_CLICK ON S_CLICK.LOG_ID=S_LOG.ID";
		$WhereArr[]=" (S_LOG.REFERER_SET>0  OR S_CLICK.ID > 0) ";
	}

	if (count($JoinArr)) $JoinStr=implode("\n", $JoinArr);
	$WhereArr[]="S_LOG.ID=S_LOG.COOKIE_LOG";
	$WhereArr[]="S_LOG.VISITOR_ID=$VisId";
	$WhereArr[]="S_LOG.SITE_ID=$StId";
	
	if (count($WhereArr)) $WhereStr="WHERE ".implode(" AND ", $WhereArr);

	$Order=($Order=="LAST")?"DESC":"ASC";

	$Query = "
		SELECT S_LOG.ID
			FROM ".NS_DB_PFX."_tracker_".$CompanyId."_stat_log S_LOG
			$JoinStr
			$WhereStr
			ORDER BY S_LOG.STAMP $Order
			LIMIT 1
	";
	return $Db->ReturnValue($Query);
}

function UpdateCookieLog($LogId)
{
	NS_TRACK_GENERAL::SetCookieLog(false, $LogId);
	return $LogId;
}


}

?>