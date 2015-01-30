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
# $Id: db.class.php,v 1.63 2006/01/11 15:56:16 kuindji Exp $
# ============================================================================

class nsDatabase
{
	var $ID;
	var $AutoFree;
	var $LastInsertId;
	var $LastError;
	var $LastQuery;

	function nsDatabase()
	{
		global $Lang, $Logs, $DbUser, $DbPass, $DbHost, $DbPort, $DbName;
		if (!$DbHost || !$DbName) return false;
		if ($DbPort) $DbHost = $DbHost.":".$DbPort;

		$this->AutoFree = true;
		$this->Debug=false;
		$this->DebugArr = array();
		$this->LastError = "";
		$this->LastAffected=false;
		$this->LastInsertId=false;

		$this->GetTime=false;
		$this->ResTime=0;
		$this->LoggerFunc="";

		$ID = @mysql_connect($DbHost, $DbUser, $DbPass);
		//$Logs->DbErr(false, $Lang["DbConnectErr"], "DIE");
		if (!$ID) return false;
		@mysql_select_db($DbName, $ID) or $Logs->DbErr(false, $Lang["DbSetErr"], "DIE");
		$this->ID = $ID;

		$GLOBALS['DBCON']=&$this->ID;
		$GLOBALS['DBCLASS']=&$this;

		$this->Version=false;

		unset($GLOBALS['DbName'], $GLOBALS['DbHost'], $GLOBALS['DbPost'], $GLOBALS['DbUser'], $GLOBALS['DbPass']);
		//$this->Host = $Host;
		//$this->Port = $Port;
		//$this->DbName = $DbName;
		//$this->Usr = $Usr;


	}

	function Close()
	{
		global $Lang, $Logs;
		@mysql_close($this->ID) or $Logs->DbErr(false, $Lang['DbCloseErr']);
	}


	function PrepareQuery($Query, $Args, $PreNum) 
	{
		$result='';
		$sql_stains = explode('?', $Query);
		for($i=$PreNum;$i<count($Args);$i++) {
			$result .= array_shift($sql_stains). 
						( (is_null($Args[$i]) || $Args[$i]===false) ? 'NULL' 
						: '\''. escape_string($Args[$i]).'\'');
		}
		$result .= array_shift($sql_stains);
//		echo "<code>MySQLQuery: <b>$result</b></code><br>";
		return $result;		
	}

	
	// 
	function Select($Query = false, $Type = "OBJ")
	{    
		global $Lang, $Logs;
		if (!$Query) return false;
		if (!$Type) $Type="OBJ";
		if ($Query == "void") return true;

		if (func_num_args()>2) {
			$Args=func_get_args();
			$Query=$this->PrepareQuery($Query, $Args, 2);
		}
		$this->LastQuery = $Query;
		if ($this->Debug) $this->DebugArr[] = $Query;
		$this->LastError="";
		if ($this->GetTime) $StartTime=$this->GetMicroTime();
		$ResultLink = @mysql_query($Query, $this->ID);
		if (@mysql_error()) {$this->LastError = @mysql_error(); $Logs->DbErr($Query, $Lang['DbQueryErr']); return;}
		if ($Type == "OBJ") $Result = @mysql_fetch_object($ResultLink);
		if ($Type == "ARR") $Result = @mysql_fetch_array($ResultLink, MYSQL_ASSOC);
		if ($Type == "ROW") $Result = @mysql_fetch_row($ResultLink);
		if (@mysql_error()) {$this->LastError = @mysql_error(); $Logs->DbErr($Query, $Lang['DbFetchErr']); return;}
		if ($this->GetTime) $this->ResTime=$this->GetMicroTime()-$StartTime;
		if ($this->LoggerFunc) {
			$Func=$this->LoggerFunc;
			$Func($Query, $this->ResTime);
		}
		if ($this->AutoFree) @mysql_free_result($ResultLink);
		$this->LastResult = $Result;
		
		
		return $Result;
	}

	function Query($Query = false)
	{
		global $Lang, $Logs;
		if (!$Query) return false;
		if ($Query == "void") return true;

		$this->LastAffected=false;
		$this->LastInsertId=false;

		if (func_num_args()>1) {
			$Args=func_get_args();
			$Query=$this->PrepareQuery($Query, $Args,1);
		}
		if ($this->Debug) $this->DebugArr[] = $Query;
		$this->LastError="";
		if ($this->GetTime) $StartTime=$this->GetMicroTime();
		$ResultLink = @mysql_query($Query, $this->ID);
		if ($this->GetTime) $this->ResTime=$this->GetMicroTime()-$StartTime;
		if ($this->LoggerFunc) {
			$Func=$this->LoggerFunc;
			$Func($Query, $this->ResTime);
		}
		if (@mysql_error()) {$this->LastError = @mysql_error(); $Logs->DbErr($Query, $Lang['DbQueryErr']); return false;}
		$this->LastAffected = @mysql_affected_rows($this->ID);
		$this->LastInsertId = @mysql_insert_id($this->ID);
		if ($this->AutoFree) @mysql_free_result($ResultLink);
		$this->LastQuery = $Query;
		
		return true;
	}

	function ReturnValue($Query = false)
	{
		if (!$Query) return false;
		
		if (func_num_args() > 1) {
			$Args = func_get_args();
			$Query = $this->PrepareQuery($Query, $Args, 1);
		}
		
		$Res = $this->Select($Query, "ROW");
		return $Res[0];
	}


	function SetMysql40Mode() 
	{
		$Version=$this->ReturnValue("SELECT VERSION()");
		$this->Version=$Version;
		if (CompareVersions($Version, "4.1") == 1) {
			$this->Query(" SET SESSION sql_mode='MYSQL40' ");
		}
	}

	function GetTables()
	{
		$Key = "Tables_in_".DB_NAME;
		$Query = "show tables";
		$Sql = new Query($Query);
		while ($Row = $Sql->Row())
		{
			$Arr[] = $Row->$Key;
		}
		$this->TablesArr = $Arr;
		return $Arr;
	}

	function TableExists($Table = false)
	{
		if (!$Table) return false;
		if (!is_array($this->TablesArr)) $this->GetTables();
		if (!in_array($Table, $this->TablesArr)) return false;
		else return true;
	}

	function CNT($Table = false, $Where = false)
	{
		if (!$Table) return false;
		if ($Where) $Where = "WHERE ".$Where;
		$Query = "SELECT COUNT(*) AS CNT FROM $Table $Where";
		$Count = $this->Select($Query);
		return $Count->CNT;
	}

	function IsExists($Table = false, $Field = false, $Value = false) 
	{
		if (!$Table || !$Field || !$Value) return false;
		$Query = "SELECT $Field FROM $Table WHERE $Field = $Value";
		$Check = $this->Select($Query);
		if ($Check->$Field == $Value) return true;
		else return false;
	}

	function GenId($Table=false)
	{
		if (!$Table) return false;
		$Query = "SELECT MAX(ID) FROM $Table";
		$Max=$this->ReturnValue($Query);
		$Max++;
		return $Max;
	}


	function DumpDebug()
	{
		for ($i = 0; $i < count($this->DebugArr); $i++)
		{
			echo $this->DebugArr[$i];
			echo "<br>---------<br>";
		}
	}

	function GetMicrotime() 
	{ 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float)$usec + (float)$sec); 
	} 

}

//////////////////////////////////////////
// 
class Query
{

	function Query($Query = false, $Type = "OBJ")
	{
		global $Lang, $Logs;
		if (!$Type)$Type="OBJ";
		$Db=&$GLOBALS['DBCLASS'];
		$this->DbId = $Db->ID;
		if (!$this->DbId) {$Logs->Err($Lang['DbNoHandler']); return false;}
		if (!$Query) return false;
		if ($Query == "void") return true;
		$this->QID=false;
	
		if (func_num_args()>2) {
			$Args=func_get_args();
			$Query=$Db->PrepareQuery($Query, $Args, 2);
		}
		if ($Db->Debug) $Db->DebugArr[] = $Query;

		$this->GetTime=false;
		$this->ResTime=0;
		$this->PrevTime=0;
		$this->LoggerFunc="";

		
		$this->Query = $Query;

		$this->Position = -1;

		$this->LastRec = false;


		$this->Type = $Type;
		$this->AutoFree = $Db->AutoFree;
		$this->Count = false;

		$this->ColPos = 0;
		$this->_STYLE=false;
		$this->_COLOR=false;
		$this->LastRow=false;	
		
		
		
		$this->PrevTime=$this->GetMicroTime();
		$QID = @mysql_query($Query, $this->DbId);
		$this->ResTime+=$this->GetMicroTime()-$this->PrevTime;
		
		if (@mysql_error()) {
				$Logs->DbErr($Query, $Lang['DbQueryErr']); 
				$this->QID=false;
				return false;
		}
		$this->QID = $QID;


	}

	function Count()
	{
		if (!$this->Count) $this->Count = @mysql_num_rows($this->QID);
		return $this->Count;	
	}


	function ReadSkinConfig()
	{
		global $nsTemplate, $nsProduct;
		if (@file_exists($nsTemplate->LinkToFile("list/config.php"))) {
			include $nsTemplate->LinkToFile("list/config.php");
		}
		$this->ListRowStyle=(isset($ListRowStyle))?$ListRowStyle:false;
		$this->ListRowColor=(isset($ListRowColor))?$ListRowColor:false;
	}
	

	function Row()
	{
		if (!$this->QID) return false;
		global $Lang, $Logs;
		$Row=false;

		if (isset($this->ListRowStyle)&&is_array($this->ListRowStyle)) {
			if ($this->ColPos == count($this->ListRowStyle)) $this->ColPos = 0;
			$this->_STYLE=$this->ListRowStyle[$this->ColPos];
		}
		elseif (isset($this->ListRowColor)&&is_array($this->ListRowColor)) {
			if ($this->ColPos == count($this->ListRowColor)) $this->ColPos = 0;
			$this->_COLOR=$this->ListRowColor[$this->ColPos];
		}

		if ($this->LastRec && $this->Position >= $this->LastRec) return false;
	
		if ($this->GetTime&&$this->Position==-1) $this->PrevTime=$this->GetMicroTime();
		if ($this->GetTime) {
			$Prev=$this->PrevTime;
			$this->PrevTime=$this->GetMicroTime();
			$Diff=$this->PrevTime-$Prev;
		}
		if ($this->GetTime&&$this->Position>-1) $this->ResTime+=$Diff;

		if ($this->Type == "OBJ") $Row = @mysql_fetch_object($this->QID);
		if ($this->Type == "ARR") $Row = @mysql_fetch_array($this->QID, MYSQL_ASSOC);
		if ($this->Type == "ROW") $Row = @mysql_fetch_row($this->QID);
		if (@mysql_error()) {$Logs->DbErr($this->Query, $Lang['DbFetchErr']); return;}
		if ($this->AutoFree && !$Row) $this->Free();

		if (!$Row&&$this->GetTime) {
			if ($this->ResTime==0) $this->ResTime=$this->GetMicroTime()-$this->PrevTime;
			if ($this->LoggerFunc) {
				$Func=$this->LoggerFunc;
				$Func($this->Query, $this->ResTime);
			}
		}

		$this->Position++; 
		$this->ColPos++;
		$this->LastRow = $Row;
		return $Row;
	}

	function Seek($Pos = false)
	{
		if (!$this->QID) return false;
		if (!$Pos || $Pos <= 0) return false;
		if ($this->LastRec > -1 && $Pos > $this->LastRec) return false;
		if ($Pos < $this->Position) return false;
		global $Logs;
		@mysql_data_seek($this->QID, $Pos);
		if (@mysql_error()) {$Logs->DbErr($this->Query, $Lang['DbSeekErr']); return;}
		$this->Position = $Pos;
	}

	function Free()
	{
		@mysql_free_result($this->QID);
	}

	function GetMicrotime() 
	{ 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float)$usec + (float)$sec); 
	} 

}
//----------------------------------------------------
function QueryAllArray($Query, $type='OBJ'){
  global $DbLastQuery;
		$Db=&$GLOBALS['DBCLASS'];
		if (func_num_args()>2) {
			$Args=func_get_args();
			$Query=$Db->PrepareQuery($Query, $Args, 2);
		}
	$DbLastQuery = ($Query);                           
	$Sql = new Query($Query, $type);
	while ($Row = $Sql->Row()) {
		$Return[] = $Row;
	}
	return ValidVar($Return, null); 
}
?>