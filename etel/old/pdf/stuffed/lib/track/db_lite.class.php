<?
class nsDatabaseLite
{
	var $ID;

	function nsDatabaseLite($DbUser=false, $DbPass=false, $DbHost=false, $DbPort=false, $DbName=false)
	{
		$this->AutoFree=true;
		$this->LastAffected=false;
		$this->LastInsertId=false;
		$ID = @mysql_connect($DbHost, $DbUser, $DbPass);
		if (!$ID) return false;
		@mysql_select_db($DbName, $ID) or $Logs->DbErr(false, $Lang["DbSetErr"], "DIE");
		$this->ID = $ID;
	}

	function Close()
	{
		@mysql_close($this->ID);
	}

	function PrepareQuery($Query, $Args, $PreNum) 
	{
		$result='';
		$sql_stains = explode('?', $Query);
		for($i=$PreNum;$i<count($Args);$i++) {
			$result .= array_shift($sql_stains). 
						( (is_null($Args[$i]) || $Args[$i]===false) ? 'NULL' 
						: '\''. $this->escape_string($Args[$i]).'\'');
		}
		$result .= array_shift($sql_stains);
		return $result;		
	}

	function escape_string($string) {
		if (is_null($string) || $string===false) return null;		 
		if(version_compare(phpversion(),"4.3.0")=="-1") return mysql_escape_string($string);
		else return mysql_real_escape_string($string);
	}

	function Select($Query = false, $Type = "OBJ")
	{    
		if (!$Query) return false;
		if (!$Type) $Type="OBJ";
		if ($Query == "void") return true;
		if (func_num_args()>2) {
			$Args=func_get_args();
			$Query=$this->PrepareQuery($Query, $Args, 2);
		}
		$this->LastQuery = $Query;
		$this->LastError="";
		$ResultLink = @mysql_query($Query, $this->ID);
		if (@mysql_error()) return false;
		if ($Type == "OBJ") $Result = @mysql_fetch_object($ResultLink);
		if ($Type == "ARR") $Result = @mysql_fetch_array($ResultLink, MYSQL_ASSOC);
		if ($Type == "ROW") $Result = @mysql_fetch_row($ResultLink);
		if (@mysql_error()) return false;
		@mysql_free_result($ResultLink);
		$this->LastResult = $Result;
		return $Result;
	}

	function Query($Query = false)
	{
		if (!$Query) return false;
		if ($Query == "void") return true;
		$this->LastAffected=false;
		$this->LastInsertId=false;
		if (func_num_args()>1) {
			$Args=func_get_args();
			$Query=$this->PrepareQuery($Query, $Args,1);
		}
		$this->LastError="";
		$ResultLink = @mysql_query($Query, $this->ID);
		if (@mysql_error()) return false;
		$this->LastAffected = @mysql_affected_rows($this->ID);
		$this->LastInsertId = @mysql_insert_id($this->ID);
		@mysql_free_result($ResultLink);
		$this->LastQuery = $Query;
		return true;
	}


	function SetMysql40Mode() 
	{
		$Version=$this->ReturnValue("SELECT VERSION()");
		$this->Version=$Version;
		if ($this->CompareVersions($Version, "4.1") == 1) {
			$this->Query(" SET SESSION sql_mode='MYSQL40' ");
		}
	}


	function ReturnValue($Query = false)
	{
		if (!$Query) return false;
		$Res = $this->Select($Query, "ROW");
		return $Res[0];
	}

	function CompareVersions($Str1="", $Str2="", $Position=false)
	{
		if (!$Str1&&!$Str2) return 0;
		if (!$Position&&function_exists("version_compare")) {
			return version_compare($Str1, $Str2);
		}
		if (!$Str2) return 1;
		if (!$Str1) return -1;
		if ($Str1==$Str2) return 0;

		$Arr1=explode(".", $Str1);
		$Arr2=explode(".", $Str2);

		if (count($Arr1)!=count($Arr2)) {
			if (count($Arr1)>count($Arr2)) {
				for ($i=count($Arr2);$i<count($Arr1);$i++) $Arr2[$i]=0;
			}
			else {
				for ($i=count($Arr1);$i<count($Arr2);$i++) $Arr1[$i]=0;
			}
		}
		$iStop=($Position)?$Position:count($Arr1);
		for ($i=0;$i<$iStop;$i++) {
			$V1=intval($Arr1[$i]);
			$V2=intval($Arr2[$i]);
			if ($V1==$V2) continue;
			if ($V1<$V2) return -1;
			if ($V1>$V2) return 1;
		}

		return 0;
	}

}



class nsTrackQuery
{

	function nsTrackQuery($Query = false, $Type = "OBJ")
	{
		if (!$Type)$Type="OBJ";
		global $_NS_TRACK_VARS;
		$Db=&$_NS_TRACK_VARS['Db'];
		$this->DbId = $Db->ID;
		if (!$this->DbId) return false;
		if (!$Query) return false;
		if ($Query == "void") return true;
		$this->QID=false;
		if (func_num_args()>2) {
			$Args=func_get_args();
			$Query=$Db->PrepareQuery($Query, $Args, 2);
		}
		$this->Query = $Query;
		$this->Position = -1;
		$this->Type = $Type;
		$this->AutoFree = $Db->AutoFree;
		$this->Count = false;
		$this->LastRow=false;	
		$QID = @mysql_query($Query, $this->DbId);
		if (@mysql_error()) return false;
		$this->QID = $QID;
	}

	function Count()
	{
		if (!$this->Count) $this->Count = @mysql_num_rows($this->QID);
		return $this->Count;	
	}

	function Row()
	{
		if (!$this->QID) return false;
		$Row=false;
		if ($this->Type == "OBJ") $Row = @mysql_fetch_object($this->QID);
		if ($this->Type == "ARR") $Row = @mysql_fetch_array($this->QID, MYSQL_ASSOC);
		if ($this->Type == "ROW") $Row = @mysql_fetch_row($this->QID);
		if (@mysql_error()) return false;
		if (!$Row) $this->Free();
		$this->Position++; 
		$this->LastRow = $Row;
		return $Row;
	}

	function Free()
	{
		@mysql_free_result($this->QID);
	}





}
?>