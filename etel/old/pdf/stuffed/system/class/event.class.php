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
# $Id: event.class.php,v 1.18 2005/10/25 13:48:25 kuindji Exp $
# ============================================================================

// 
class nsEvent extends nsBase{
	var $LastEvent;// 
	var $Path; // path to plugins
	//---------------------------------------------------------------------------------------
	function nsEvent(){
		global $nsProduct;
		$this->LastEvent = '';
		$this->Path = SELF."/plugins";
		
	}
	//---------------------------------------------------------------------------------------
	// nsEvent->On('event') - will call an event "event" and will plug here all the plugins
	// associated with this event name. 
	function On($EventName){
		global $Db, $nsProduct;
		// first, let's check what plugins are plugged into	the current event:
		$Query = "SELECT * FROM ".PFX."_system_plugin WHERE EVENT_NAME = '$EventName' AND PRODUCT_ID=".$nsProduct->ID." ORDER BY ORD";
		$Sql = new Query($Query);
		// OK. plugged as is.
		while ($Row = $Sql->Row()) {	
				// creating the filename with it's path. 
			 $fn = $this->Path($Row->DIRNAME, $EventName);
			 // pluggin in the plugin )
			 // CAUTION: THE FUNCTION "file_exists" WILL SLOW DOWN THE CPU!!!
			 if(file_exists($fn)) include_once($fn);
		}
		$this->LastEvent = $EventName;
	}
	
	function ReturnOn($EventName) {
		global $Db, $nsProduct;
		$Query = "SELECT * FROM ".PFX."_system_plugin WHERE EVENT_NAME = '$EventName' AND PRODUCT_ID=".$nsProduct->ID." ORDER BY ORD";
		$Sql = new Query($Query);
		while ($Row = $Sql->Row()) {	
			 $fn = $this->Path($Row->DIRNAME, $EventName);
			 if(!file_exists($fn)) return false;
		}
		$this->LastEvent = $EventName;
		return $fn;
	}

	//---------------------------------------------------------------------------------------
	/**
	* @return string
	* @param unknown $PluginName
	* @param unknown $PluginFile
	* @desc Returns the path of the plugins for the current directory...
	*/
	function Path($PluginName=false, $PluginFile='main'){ 
		if($PluginName)
			return $this->Path."/".$PluginName.'.plugin/'.$PluginFile.'.plugin.php';
		return $this->Path; 
	}
	//---------------------------------------------------------------------------------------
	function AddPlugin($PluginName, $DirName, $Events, $ProductID, $Desc){
			global $Db;
			$this->RemovePlugin($DirName, $ProductID);
			// XXXXXX 
			// XXXXXX BUG HERE:
			// XXXXXX every event in this cycle gets the same 
			// XXXXXX values - this bug does not damage the system,
			// XXXXXX but takes more place in SQL data area(and also server CPU time)
			// XXXXXX  TO BE FIXED!!!
			// XXXXXX 
			for($i=0; $i < count($Events);++$i){
				$Event = $Events[$i];
				$Q = 'INSERT INTO '.PFX.'_system_plugin (PRODUCT_ID, ORD, NAME, DIRNAME, EVENT_NAME, DESCRIPTION) '
				.' values ('
				.''.$ProductID.', '
				.''.$i.', '
				.'\''.$PluginName.'\', '
				.'\''.$DirName.'\', '
				.'\''.$Event.'\', '
				.'\''.$Desc.'\')';
				$Db->Query($Q);		
			}	
			// XXXXXX 
			// XXXXXX BUG END
			// XXXXXX 
	}
	//---------------------------------------------------------------------------------------
	function RemovePlugin($PluginDirName, $ProductID){
			global $Db;
			$Q = 'DELETE FROM '.PFX
			."_system_plugin WHERE PRODUCT_ID=$ProductID AND DIRNAME='$PluginDirName'";
			$Db->Query($Q);
	}
	//---------------------------------------------------------------------------------------
};


?>