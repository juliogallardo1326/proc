<?


define("NS_PHP_TRACKING", true);
$GLOBALS['_NS_TRACK_VARS']=array();

if (!isset($nsSTcost)) $nsSTcost="";
if (!isset($nsSToid)) $nsSToid="";
if (!isset($nsSToinfo)) $nsSToinfo="";
if (!isset($nsSTItems)) $nsSTItems=array();

class nsTrack
{
	function nsTrack($nsSTMode, $nsSTID, $nsSTPath)
	{
		$this->Enable=false;
		if (!$nsSTMode) return;
		if (!$nsSTID) return;
		if (!$nsSTPath) return;
		$this->Mode=$nsSTMode;
		$this->SiteId=$nsSTID;
		$this->nsSelf=$nsSTPath;

		if (@is_dir($nsSTPath."/system")) $this->nsSys=$nsSTPath;
		else $this->nsSys=$nsSTPath."/..";

		require $nsSTPath."/conf.vars.php";

		$this->CookiePfx=$CookiePfx;
		$this->CookieExp=$CookieExp;
		$this->DbPfx=$DbPfx;

		if (!defined("NS_DB_PFX")) define("NS_DB_PFX", $DbPfx);
		if (!defined("NS_COOKIE_PFX")) define("NS_COOKIE_PFX", $CookiePfx);

		$this->DbName=$DbName;
		$this->DbHost=$DbHost;
		$this->DbPass=$DbPass;
		$this->DbUser=$DbUser;
		$this->DbPort=$DbPort;
		$this->Enable=true;
		$this->PresetIP=false;
		$this->PresetID=false;
		$this->Params=array();
	}

	function Order($nsSTcost=false, $nsSToid=false, $nsSToinfo=false, $nsSTItems=false)
	{
		$this->OrderCost=$nsSTcost;
		$this->OrderId=$nsSToid;
		$this->OrderInfo=$nsSToinfo;
		$this->OrderItems=$nsSTItems;
	}

	function Event($nsSTEvent, $nsSTItem=false)
	{
		$this->EventId=$nsSTEvent;
		$this->EventItem=$nsSTItem;
	}

	function AddParam($Name=false, $Value=false)
	{
		if (!$Name||!$Value) return false;
		$this->Params[$Name]=$Value;
	}

	function PresetValues($PresetIP=false, $PresetID=false) 
	{
		$this->PresetIP=$PresetIP;
		$this->PresetID=$PresetID;
	}

	function DoTrack()
	{
		if (!$this->Enable) return false;
		global $_NS_TRACK_VARS, $_SERVER, $_COOKIE;
		if (!$_NS_TRACK_VARS) $_NS_TRACK_VARS=array();
		$_NS_TRACK_VARS['QueryClass']="nsTrackQuery";
		$RequestURI=$_SERVER['HTTP_HOST'].((isset($_SERVER['REQUEST_URI']))?$_SERVER['REQUEST_URI']:$_SERVER['URL']);
		$RequestURI=((isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS'])=="on")?"https":"http")."://".$RequestURI;
		$_GP=array();
		if ($this->PresetIP) $_GP['presetIP']=$this->PresetIP;
		if ($this->PresetID) $_GP['presetID']=$this->PresetID;
		if (count($this->Params)>0) {
			foreach ($this->Params as $Key=>$Val) $_GP[$Key]=$Val;
		}
		$_GP['cur']=$RequestURI;
		$_GP['ref']="";
		if (isset($_SERVER['HTTP_REFERER'])) $_GP['ref']=$_SERVER['HTTP_REFERER'];
		$_GP['st']=$this->SiteId;

		if ($this->Mode=="sale") {
			$_GP['cs']=$this->OrderCost;
			$_GP['oid']=$this->OrderId;
			$_GP['oinfo']=$this->OrderInfo;
			$ItemsStr=array();
			if (is_array($this->OrderItems)) {
				foreach ($this->OrderItems as $i=>$Row) {
					$ItemsStr[]="{{".$Row['Name']."}}{{".$Row['Value']."}}{{".$Row['Cnt']."}}";
				}
			}
			$_GP['itm']=$ItemsStr;
		}

		if ($this->Mode=="event") {
			$_GP['code']=1;
			$_GP['eid']=$this->EventId;
			$_GP['itm']=$this->EventItem;
		}

		$nsSelf=$this->nsSelf;
		$nsSys=$this->nsSys;
		require_once $nsSelf."/lib/track/db_lite.class.php";
		$Db = new nsDatabaseLite($this->DbUser, $this->DbPass, $this->DbHost, $this->DbPort, $this->DbName);
		$Db->SetMysql40Mode();
		if (!$Db) return;

		if ($this->Mode=="default") include $nsSelf."/actions/track.default.php";
		if ($this->Mode=="sale") include $nsSelf."/actions/track.sale.php";
		if ($this->Mode=="event") include $nsSelf."/actions/track.event.php";

		$this->PresetIP=false;
		$this->PresetID=false;
		$this->Params=array();

		$Db->Close();
	}
}



?>