<?

class TrackerConsult
{
	var $UseConsult;
	var $CurrentHelp;
	var $Link;
	var $Display;
	var $Header;
	var $Footer;
	function TrackerConsult()
	{
		global $nsUser;
		if (!$nsUser->Logged()||!ValidVar($nsUser->HELP_MODE)||$nsUser->HELP_MODE==0) {
			$this->UseConsult=false;
			return false;
		}
	
		$this->CurrentHelp="default";
		$this->UseConsult=true;
		$this->Display="none";

		global $_GP, $Db;
		$DisableContext=(ValidVar($_GP['DisableContext']))?true:false;
		$DisableHelp=(ValidVar($_GP['DisableHelp']))?true:false;
		if ($DisableContext) {
			$Query = "UPDATE ".PFX."_tracker_user_settings SET HELP_MODE='0' WHERE USER_ID=".$nsUser->UserId();
			$Db->Query($Query);
			$this->UseConsult=false;
			$nsUser->HELP_MODE=0;
		}
		if ($DisableHelp) {
			$Query = "UPDATE ".PFX."_tracker_user_settings SET HELP_MODE='1' WHERE USER_ID=".$nsUser->UserId();
			$Db->Query($Query);
			$nsUser->HELP_MODE=1;
		}
	}

	function ShowHelpLink()
	{
		global $nsLang, $nsUser;
		if (!$this->ShowHelp()) {
			$this->UseConsult=false;
			return false;
		}
		if ($nsUser->HELP_MODE!=1) return false;
		if (!file_exists("consult/".$nsLang->CurrentLang."/context_block.php")) return false;
		if (!file_exists("consult/".$nsLang->CurrentLang."/context_header.php")) return false;
		if (!file_exists("consult/".$nsLang->CurrentLang."/context_footer.php")) return false;
		return true;
	}

	function ShowHelp()
	{
		if (!$this->UseConsult) return false;
		global $nsProduct, $nsLang, $nsUser;
		$Link = "consult/".$nsLang->CurrentLang."/";
		$Link.=$nsProduct->Section.".".$nsProduct->Action."/";
		$Link.=$this->CurrentHelp.".html";
		if (!@file_exists($Link)) return false;
		$this->Link=$Link;
		$this->Header="consult/".$nsLang->CurrentLang."/context_header.php";
		$this->Footer="consult/".$nsLang->CurrentLang."/context_footer.php";
		if ($nsUser->HELP_MODE==2) $this->Display="";
		return true;
	}
}

?>