<?


class StatGraph
{
	var $ID;
	var $Vars;
	var $FlashVars;
	var $DumpMode;
	var $Path;
	var $ZoneCnt;
	var $Display;
	var $CanDump;

	function StatGraph($ID=false, $Filename=false)
	{
		global $nsProduct, $nsTemplate, $Lang;
		if (!$ID) $ID=time();
		$this->ID=$ID;
		if (!$Filename) $Filename="graph1.swf";
		$this->Path=FileLink("graph/".$Filename);
		//$this->Path=PATH."/skins/".$nsProduct->SKIN."/graph/".$Filename;
		$this->ZoneCnt=5;
		$this->Vars=array();
		$this->Vars['choose_text']=$Lang['ChooseGraphType'];
		// Flash/GD
		$this->DumpMode="Flash";
		$this->Display=false;
		$this->CanDump=false;
		$this->Name=false;
	}

	
	


	function GraphZone($Min, $Max)
	{
		$Coef=false;
		$Zone=false;
		if ($Max<10&&!$Coef) $Coef=2;
		if ($Max<100&&!$Coef) $Coef=6;
		if ($Max<1000&&!$Coef) $Coef=10;
		if ($Max>=1000&&$Max<10000&&!$Coef) $Coef=100;
		if ($Max>=10000&&!$Coef) $Coef=1000;
		if ($Max==0) return false;
		if ($Max<1) $Zone=1/($this->ZoneCnt-1);
		if ($Max<10&&!$Zone) $Zone=10/($this->ZoneCnt-1);
		//if ($Max>=10&&!$Zone) $Zone=ceil(round(($Max-$Min)/($this->ZoneCnt-1))/$Coef)*$Coef;
		if ($Max>=10&&!$Zone) $Zone=ceil(($Max-$Min)/($this->ZoneCnt-1)/$Coef)*$Coef;
		$Zones=array();
		for($i=0;$i<$this->ZoneCnt;$i++) $Zones[$i]=$Zone*$i;
		return $Zones;
	}

	function ZoneValue($Zones, $Value)
	{
		for($i=1;$i<count($Zones);$i++) {
			if ($Value>=$Zones[$i-1]&&$Value<$Zones[$i]) {
				$Var1=$Value-$Zones[$i-1];
				$Var2=$Zones[$i]-$Zones[$i-1];
				$ZoneValue=($i-1)+($Var1/$Var2);
				return round($ZoneValue, 2);
			}
		}
	}



	function Dump()
	{
		if (!$this->CanDump) return false;
		$Vars=array();
		foreach($this->Vars as $Key=>$Val) $Vars[]="$Key=$Val";
		$this->FlashVars=implode("&", $Vars);

		if ($this->DumpMode=="Flash") $this->DumpFlash();
		if ($this->DumpMode=="GD") $this->DumpGD();
	}

	function DumpFlash()
	{
		global $nsTemplate;
		include $nsTemplate->Inc("constructor/graph.inc");
	}

	function DumpGD()
	{

	}

}


function DrawLines($Param, &$DayStat, &$Graph)
{
	global $Lang;
	$Max=0;
	if (!ValidArr($DayStat)) return false;
	foreach ($DayStat as $Day=>$Row) if ($Max<$Row[$Param]) $Max=$Row[$Param];
	if ($Max<1) return false;
	$VisZones=$Graph->GraphZone(0, $Max);
	for($i=0;$i<count($VisZones);$i++) $Graph->Vars["disp_y$i"]=$VisZones[$i];
	$i=count($DayStat)-1;
	$j=0;
	foreach ($DayStat as $Day=>$Row) {
		$Graph->Vars["disp_x$i"]=date("d.m",$Row['Stamp']).",".$Lang['DayOfWeekShort'][date("w", $Row['Stamp'])];
		$ZoneValue=$Graph->ZoneValue($VisZones, $Row[$Param]);
		$Graph->Vars['line0_points'].="$i:$ZoneValue,";
		$i--; $j++;
		if ($j>1) $Graph->CanDump=true;
	}

}




?>