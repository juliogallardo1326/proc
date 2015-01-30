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
# $Id: lang.class.php,v 1.30 2005/10/21 09:56:22 kuindji Exp $
# ============================================================================

class nsLang{
	var $CurrentLang;// current language for this page.
	var $_LangList;// the variable for list of languages
	var $htmlTagArguments;
	//-------------------------------------------------------------------------
	//-------------------------------------------------------------------------
	function nsLang(){
		global $DefLangFile;
		$this->htmlTagArguments = 'dir="ltr" '; 
		$this->CurrentLang($DefLangFile);	}
	//-------------------------------------------------------------------------
	function GetList(){
		global $nsProduct;
	 if(!isset($this->_LangList)){
	 // unpacking the query get vars:
	 //	 $querystr = 'http://'.HOST.'/'.$_REQUEST['RequestPath'].'?';
	 $querystr=$nsProduct->SelfAction();
		foreach ($_GET as $GName=>$GValue){
			if(
				$GName != 'RequestPath' && 
				$GName != 'lsave'&& 
				$GName != 'lang'&& 
				$GName != 'action'&& 
				$GName != 'sc'&& 
				$GName != 'prd'
				)
					$querystr .=	$GName.'='.$GValue.'&';
		}		 	 	

		$langd = dir($this->Path());
		for($i=0; false !== ($entry = $langd->read()); $i) {
		 if(strpos($entry, '.lang.php') && !is_dir($this->Path().$entry)){
			$CLangs[$i]['name'] = str_replace('.lang.php', '', $entry);		
			$CLangs[$i]['path'] = $this->Path(). '/'. $CLangs[$i]['name'].'/';
			$req = $CLangs[$i]['path'].'_system/'.$CLangs[$i]['name'].'.config.php';			
			require($req);
			$CLangs[$i]['caption'] = $LangConfig['name'];
			$CLangs[$i]['charset'] = $LangConfig['charset'];
			$CLangs[$i]['url'] = $querystr.'lang='.$CLangs[$i]['name'];
			$i++;
			unset($LangConfig);
		 }
		}

		$langd->close();		
		if(!$i){ // no languages found - but we have to return something:
			$CLangs[$i]['name'] = 
			$CLangs[$i]['path'] = 
			$CLangs[$i]['caption'] = 
			$CLangs[$i]['charset'] = 
			$CLangs[$i]['url'] = '';

		}
		return $this->_LangList=$CLangs;
	 }
	 return $this->_LangList;

	}
	//-------------------------------------------------------------------------
	/**
	* @return void
	* @desc includes file that contains
	* additional cells for the array "$Lang". Exactly for the current
	* product. If nsProduct was not constructed yet - includes
	* the file for the inner system
	*/
	function Inc($def=false){		
		global $nsProduct, $DefLangFile, $Lang;
		if($def){
			$tmpcur = $this->CurrentLang; 
			$this->CurrentLang = $DefLangFile;
		}
		$retsys = SYS.'/system/lang/'.$this->CurrentLang.'.lang.php';
		// I am not shure that chosen language exists inside the
		// system - but only in this product 			
		$retsys = file_exists($retsys) ? $retsys : SYS.'/system/lang/'.$DefLangFile.'.lang.php';
				
		if(isset($nsProduct->Action)){
			$retprod = SELF.'/lang/'.$this->CurrentLang.'/'.$nsProduct->Section.'.'.$nsProduct->Action.'.php';
			$retdefprod = SELF.'/lang/'.$DefLangFile.'/'.$nsProduct->Section.'.'.$nsProduct->Action.'.php';
			$retval = file_exists($retprod) ? $retprod : 
								(file_exists($retdefprod)? $retdefprod : $retsys);
		}else if(isset($nsProduct->ID)){
			$retprod = SELF.'/lang/'.$this->CurrentLang.'.lang.php';
			$retdefprod = SELF.'/lang/'.$DefLangFile.'.lang.php';
			$retval = file_exists($retprod) ? $retprod : 
								(file_exists($retdefprod)? $retdefprod : $retsys);
		}else { 
			$retval = $retsys;
		}		
		if(!$def){ 
			$this->Inc(true);
		}
		require($retval);
		if($def){ 
			 $this->CurrentLang = $tmpcur; 
		}
	}
	//-------------------------------------------------------------------------
	function IncConfig(){
		global $LangConfig;
		require(SELF.'/lang/'.$this->CurrentLang.'/_system/'.$this->CurrentLang.'.config.php');
	}

	function ReturnConfig($Lang)
	{
		$LangConfig=array();
		require(SELF."/lang/$Lang/_system/$Lang.config.php");
		return $LangConfig;
	}
	//-------------------------------------------------------------------------
	/**
	* @return void
	* @param string $file
	* @desc Include for use within naTemplate's "Inc()"
	*/
	function TplInc($file, $def=false){
		global $DefLangFile, $Lang;
		if($def){
			$tmpcur = $this->CurrentLang; 
			$this->CurrentLang = $DefLangFile;
		}
		global $nsProduct, $DefLangFile, $Lang;	
		$incf = SELF.'/lang/'.$this->CurrentLang.'/'.$file.'.php';
		if(!$def){ 
			$this->TplInc($file, true);
		}
		if(file_exists($incf)){
			require($incf);
		}	
		if($def){ 
			 $this->CurrentLang = $tmpcur; 
		}
		
	}

	function TplReturn($file, $def=false)
	{
		global $DefLangFile;
		$Lang=array();
		if($def){
			$tmpcur = $this->CurrentLang; 
			$this->CurrentLang = $DefLangFile;
		}
		global $nsProduct, $DefLangFile;	
		$incf = SELF.'/lang/'.$this->CurrentLang.'/'.$file.'.php';
		if(file_exists($incf)){
			require($incf);
			return $Lang;
		}	
	}
	//-------------------------------------------------------------------------
	function InitLang($L = ''){
		if(!empty($L))	$this->CurrentLang($L);
	}
	//-------------------------------------------------------------------------
	function CurrentLang($L){
		global $nsSession;
		$this->CurrentLang = $L;		
		if(isset($nsSession))
			$nsSession->set('ns_lang_current', $L);		
	}
	//-------------------------------------------------------------------------
	function Path(){

			return SELF.'/lang';
	}
	//-------------------------------------------------------------------------
	/**
	* @return void
	* @param filename $file
	* @desc Includes the specific file from the current language directory
	*/
	function IncFile($file){
		global $Lang;
		require($this->Path().'/'.$this->CurrentLang.'/'.$file);
	}
};
?>