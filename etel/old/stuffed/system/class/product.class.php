<?
# ============================================================================
#
#                        ___
#                    ,yQ$SSS$Q·,      ,yQQQL
#           i_L   I$;            `$½`       `$$,
#                                 `          I$$
#           .yQ$$$$,            ;        _,d$$'
#        ,d$$P"^```?$b,       _,'  ;  ,d$$P"`
#     ,d$P"`        `"?$$Q#QP½`    $d$$P"`
#   ,$$"         ;       ``       ;$?'
#   $$;        ,dI                I$;
#   `$$,    ,d$$$`               j$I
#     ?$S#S$P'j$'                $$;         Copyright (c) Stuffed Guys
#       `"`  j$'  __....,,,.__  j$I              www.stuffedguys.com
#           j$$½"``           ',$$
#           I$;               ,$$'
#           `$$,         _.u$$½`
#             "?$$Q##Q$$SP½"^`
#                `````
#
# ============================================================================
# $Id: product.class.php,v 1.85 2005/11/22 10:06:22 kuindji Exp $
# ============================================================================

class nsProduct extends nsBase
{
	var $SKIN;
	var $FOLDER;
	var $HL; // http location

	// ACTIONS
	var $Action; // action for include
	var $ActExists; // bool
	var $TplExists; // bool
	var $InitExists; // bool
	var $Section; // admin/pub/user defined
	
	function nsProduct()
	{
		global $Db, $Logs, $ProductCall, $nsLang;
		$this->SKIN="default";

		// prd, action, sc
		$this->ParseRequest();

		if ($Db->ID) {
			$Query = "SELECT * FROM ".PFX."_system_product WHERE FOLDER = '$ProductCall'";
			$Product=$Db->Select($Query);
			if (!$Product->ID) $Logs->Error("NO_PROD"); else $this->UnpackVars($Product);
			if ($this->DEFAULT_SKIN) $this->SKIN=$this->DEFAULT_SKIN;
			if ($this->DEFAULT_LANG) $nsLang->InitLang($this->DEFAULT_LANG);	
		}


		$this->HL='http'.((strtolower(ValidVar($_SERVER["HTTPS"]))=="on") ? 's' : '').'://'.HOST.PATH;
	}

	function ParseRequest()
	{	
		if (isset($GLOBALS['_REQUEST']['RequestPath'])) {
			$this->Request=$GLOBALS['_REQUEST']['RequestPath'];
			//$this->Request=preg_replace("/\/+/", "/", $this->Request);
			$Arr=explode("/", $this->Request);
			$Pos=0;
			for($i=0;$i<count($Arr);$i++) {
				$Arr[$i]=strtolower($Arr[$i]);
				if (strpos($Arr[$i], ".html")) {
					$Action=str_replace(".html", "", $Arr[$i]);
					break;
				}
				if (!$Arr[$i]) continue;
				if ($Pos==0) $Sc=$Arr[$i];
				if ($Pos>1) break;
				$Pos++;
			}
		}
		if (isset($Sc)) $GLOBALS['_REQUEST']['sc']=$Sc;
		if (isset($Action)) $GLOBALS['_REQUEST']['action']=$Action;
	}


	////////////
	function Redir($Action=false, $Get=false, $Section=false) 
	{
		if (!$Action&&isset($this->Action)) $Action=$this->Action;
		if (!$Action) $Action="default";
		if (!$Section) $Section=$this->Section;
		if (MOD_R) if (strlen($Section)) $Section.="/";
		if (MOD_R) if ($Get) $Get="?".$Get;
		if (!MOD_R) Redir($this->SelfUrl()."sc=$Section&action=$Action&$Get");
		Redir($this->SelfUrl()."/$Section$Action.html$Get"); 
	}     
	
	function GetUrl($Action=false, $Get=false, $Section=false)
	{
		if (!$Action) $Action="default";
		if (!$Section) $Section=$this->Section;
		if (MOD_R) if (strlen($Section)) $Section.="/";
		if (MOD_R) if ($Get) $Get="?".$Get;
		if (!MOD_R) return $this->SelfUrl()."sc=$Section&action=$Action&$Get";
		return $this->SelfUrl()."/$Section$Action.html$Get";
	}

	function SelfAction($Get=false)
	{
		$Section=$this->Section;
		$Action=$this->Action;
		if (MOD_R) if ($Get) $Get="?".$Get;
		if (MOD_R) if (strlen($Section)) $Section.="/";
		if (!MOD_R) return $this->SelfAction=$this->SelfUrl()."sc=$Section&action=$Action&$Get";
		return $this->SelfUrl()."/".$Section.$this->Action.".html$Get";
	}

	function SelfUrl()
	{
		if (!MOD_R) return $this->HL."/index.php?";
		return $this->HL;
	}


	function CreateForm($Method="get", $Action=false, $Get=false, $Section=false, $Attr=false)
	{
		if ($Method!="get"&&$Method!="post") $Method="get";
		if (!$Section) $Section=$this->Section;
		if (!$Action) $Action=$this->Action;
		if (!MOD_R) {
			echo "<form action=\"".$this->HL."/index.php\" method=\"$Method\" $Attr>\n";
			echo "<input type=hidden name=\"sc\" value=\"$Section\">\n";
			echo "<input type=hidden name=\"action\" value=\"$Action\">\n";
		}
		if (MOD_R)  {
			echo "<form action=\"".$this->getURL($Action, false, $Section)."\" method=\"$Method\" $Attr>\n";
		}
		if ($Get) {
			$GetArr=explode("&", $Get);
			if (is_array($GetArr)) {
				for($i=0;$i<count($GetArr);$i++) {
					$SubArr=explode("=", $GetArr[$i]);
					if (!is_array($SubArr)||count($SubArr)!=2) continue;
					echo "<input type=hidden name=\"".$SubArr[0]."\" value=\"".$SubArr[1]."\">\n";
				}
			}
		}
	}

	////
	//////////////

	function PrepareSection()
	{
		global $nsSession;
		$this->Section=false;
		if (ValidVar($GLOBALS['_REQUEST']['sc'])) {
			$GLOBALS['_REQUEST']['sc']=ereg_replace("[^[:alnum:]_]", "", $GLOBALS['_REQUEST']['sc']);
			$this->Section=$GLOBALS['_REQUEST']['sc'];
		}
		if (!$this->Section) $this->Section="pub";
	}

	function PrepareAction($Action=false)
	{
		$this->PrepareSection();
		global $Logs, $nsTemplate, $nsSession, $nsProduct;
		if (!$Action&&isset($GLOBALS['_REQUEST']['action']))$Action=$GLOBALS['_REQUEST']['action'];
		if (!$Action) $Action="default";
		$Section=$this->Section;
		$this->ActExists=@file_exists("actions/$Section.$Action.php");
		$this->TplExists=@file_exists("skins/".$nsProduct->SKIN."/$Section.$Action.php");
		$this->InitExists=@file_exists("init.$Section.php");
		if (!$this->ActExists&&!$this->TplExists&&$Action!="default") {
			$this->PrepareAction("default");
			return;
		}
		if (!$this->ActExists&&!$this->TplExists&&$Action=="default") $Logs->Error("NO_ACTION");
		if ($this->ActExists||$this->TplExists) $this->Action=$Action;
	}

	function CurrentInit()
	{
		$Section=$this->Section;
		if ($this->InitExists==1) return "init.$Section.php";
	}
	
	function CurrentInclude()
	{
		global $nsTemplate, $nsProduct;
		$Section=$this->Section;
		$Action=$this->Action;
		if ($this->ActExists==1) return "actions/$Section.$Action.php";
		if ($this->TplExists==1) return "skins/".$nsProduct->SKIN."/$Section.$Action.php";
	}


}

?>