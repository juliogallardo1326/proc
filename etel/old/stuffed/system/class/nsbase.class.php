<?
//================================================================
//================================================================
// the grand-parent for all ns-classes
//================================================================
class nsBase{

	var $HeadersSent;
	var $CookieSent;
	var $P3PSent;

	//-----------------------------------------------------------
	function nsBase(){
		// nothing yet
		$this->HeadersSent=false;
		$this->CookieSent=false;
		$this->P3PSent=false;
	}
	//-----------------------------------------------------------
	// 
	/**
	* @return void
	* @param object $Obj
	* @desc dynamicly unpacks all variables from the object inside the class
	*/
	function UnpackVars(&$Obj)
	{
		if(!is_array($Obj))
			settype($Obj, "array");
		foreach ($Obj as $Key=>$Value) $this->$Key=$Value;
	}	
	//-----------------------------------------------------------
	// 
	/**
	* @return void
	* @param string $Query
	* @desc selects the row from the DB (using the quert $Query)
	* and unpacks inwards
	*/
	function SelectUnpack($Query){
		global $Db;
		$this->UnpackVars($Db->Select($Query));
	}

	function SendP3P()
	{
		$this->P3PSent=true;
		$Str=GetParam("P3P", "STRVAL");
		$Ref=GetParam("P3P_REF", "STRVAL");
		if (!ValidVar($Str)) return;
		if (ValidVar($Ref)) $Ref="policyref=\"$Ref\", ";
		else $Ref="";
		Header ("P3P: $Ref CP=\"$Str\"");
	}


	function SetCookie($Name=false, $Value=false, $Expire=false, $Path=false, $Domain=false, $Secure=false)
	{
		$this->HeadersSent=true;
		$this->CookieSent=true;
		if (!$this->P3PSent) $this->SendP3P();
		return setcookie($Name, $Value, $Expire, $Path, $Domain, $Secure);
	}

}
//================================================================
//================================================================
?>