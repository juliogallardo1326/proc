<?

class ReportParent
{



	function GetSiteIds($CpId)
	{
		$Arr=array();
		$Str="";
		$Query = "SELECT ID FROM ".PFX."_tracker_site WHERE COMPANY_ID = $CpId";
		$Sql=new Query($Query);
		while ($Row=$Sql->Row()) $Arr[]=$Row->ID;
		if (count($Arr)==0) return false;
		$Str=implode(",",$Arr);
		return $Str;
	}

	function GetRatio($UniVisit=false, $UniSome=false)
	{
		if (!$UniVisit||!$UniSome) return 0;
		return round((100/$UniVisit)*$UniSome, 2);
	}
	function GetMicrotime() 
	{ 
		list($usec, $sec) = explode(" ", microtime()); 
		return ((float)$usec + (float)$sec); 
	} 

	function OneOf($Current=false, $Total=false)
	{
		if ($Total<=0) return "0/0";
		if ($Current<=0) return "0/$Total";
		return "1/".ceil($Total/$Current);
	}

	function EnableAll()
	{	
		$this->ShowVisitors=true;
		$this->ShowActions=true;
		$this->ShowSales=true;
		$this->ShowActionConv=true;
		$this->ShowSaleConv=true;
		$this->ShowROI=true;

	}

	function DisableAll()
	{
		$this->ShowVisitors=false;
		$this->ShowActions=false;
		$this->ShowSales=false;
		$this->ShowActionConv=false;
		$this->ShowSaleConv=false;
		$this->ShowROI=false;
	}

}


?>