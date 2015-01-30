<?

class PageNums
{
	function PageNums($RecCount = false, $Limit = false, $Index=false)
	{
		global $nsProduct, $nsTemplate;
		if ($Index) {
			$PageStart=(isset($GLOBALS['_GET']['Pg'][$Index]['PS']))?$GLOBALS['_GET']['Pg'][$Index]['PS']:false;
			$PageCurrent=(isset($GLOBALS['_GET']['Pg'][$Index]['PC']))?$GLOBALS['_GET']['Pg'][$Index]['PC']:false;
			$this->Index=$Index;
			$this->StartStr="Pg[$Index][PS]";
			$this->CurStr="Pg[$Index][PC]";
		}
		else {
			$PageStart=(isset($GLOBALS['_GET']['PS']))?$GLOBALS['_GET']['PS']:false;
			$PageCurrent=(isset($GLOBALS['_GET']['PC']))?$GLOBALS['_GET']['PC']:false;
			$this->StartStr="PS";
			$this->CurStr="PC";
		}

		if (!$PageCurrent) $PageCurrent = 0;
		if (!$PageStart) $PageStart = 0;
		if (!$Limit || $Limit <= 0) $Limit = 20;
		
		$this->Filename = $nsProduct->SelfAction();
		
		$this->NoText=false;
		$this->NoPrev=false;
		$this->NoPrevPrev=false;
		$this->NoNext=false;
		$this->NoNextNext=false;
		$this->NoPageLink=false;
		$this->Limit = $Limit;
		$this->RecCount = $RecCount;
		$this->PageCurrent = $PageCurrent;
		$this->PageStart = $PageStart;
		$this->Args = "";
		$this->LastRec = $PageStart+$Limit-1;
		$this->Pages = 0;
		$this->Get=$this->StartStr."=".$this->PageStart."&".$this->CurStr."=".$this->PageCurrent;

		$this->Calculated=false;

		if (@file_exists($nsTemplate->LinkToFile("pages/config.php"))) {
			include_once $nsTemplate->LinkToFile("pages/config.php");
		}
		$this->TblBorder=(isset($TblBorder))?$TblBorder:0;
		$this->TblSpacing=(isset($TblSpacing))?$TblSpacing:1;
		$this->TblPadding=(isset($TblPadding))?$TblPadding:1;
		$this->TblBg=(isset($TblBg))?$TblBg:"#ffffff";
		$this->TDBg=(isset($TDBg))?$TDBg:"#ffffff";
		$this->Separator=(isset($Separator))?$Separator:"&nbsp;|&nbsp;";
		$this->TblClass=(isset($TblClass))?$TblClass:"";
		$this->TDClass=(isset($TDClass))?$TDClass:"";
		$this->PrevNextClass=(isset($PrevNextClass))?$PrevNextClass:"";
		$this->AClass=(isset($AClass))?$AClass:"";
		$this->CurrClass=(isset($CurrClass))?$CurrClass:"";
		$this->SeparatorClass=(isset($SeparatorClass))?$SeparatorClass:"";
		//$this->IMG="http://".HOST.SYS."/".$nsProduct->FOLDER."/skins/".$nsProduct->SKIN."/pages/";
	}

	function Calculate()
	{
		if ($this->Limit >= $this->RecCount) $this->Pages = 0;
		$this->Pages = floor($this->RecCount/$this->Limit);
		if ($this->RecCount%$this->Limit > 0) $this->Pages++;
		$this->Calculated=true;
	}

	function Dump()
	{
		if (!$this->Calculated) $this->Calculate();
		if ($this->Pages <= 1) return;
		echo "<table cellpadding=".$this->TblPadding." cellspacing=".$this->TblSpacing." border=".$this->TblBorder." bgcolor=\"".$this->TblBg."\" class=\"".$this->TblClass."\">";
		$this->DumpPages();
		if (!$this->NoText) $this->DumpArrows();
		echo "</table>";
	}



	function DumpArrows()
	{
		global $nsLang, $nsTemplate;
		$Lang=$nsLang->TplReturn("pagenums.class");
		echo "<tr><td align=center><IMG SRC=\"".FileLink("images/0.gif")."\" WIDTH=10 HEIGHT=1 BORDER=0 ALT=\"\">";
		if ($this->PageCurrent > 0) {
			$Prev = $this->Filename;
			if (MOD_R) $Prev.="?";
			$Prev .= $this->StartStr."=0";
			$Prev .= "&".$this->CurStr."=0";
			$Prev .= $this->Args;
			if (!$this->NoPrevPrev) echo "<span title=\"".$Lang['First']."\" class=\"".$this->PrevNextClass."\"><a href=\"".$Prev."\"><IMG SRC=\"".FileLink("images/page_first.gif")."\" WIDTH=11 HEIGHT=11 BORDER=0 ALT=\"\">&nbsp;".$Lang['First']."</a></span><IMG SRC=\"".FileLink("images/0.gif")."\" WIDTH=10 HEIGHT=1 BORDER=0 ALT=\"\">";

			$Prev = $this->Filename;
			if (MOD_R) $Prev.="?";
			$Prev .= $this->StartStr."=".(($this->PageCurrent-1)*$this->Limit);
			$Prev .= "&".$this->CurStr."=".($this->PageCurrent-1);
			$Prev .= $this->Args;
			if (!$this->NoPrev) echo "<span title=\"".$Lang['Prev']."\" class=\"".$this->PrevNextClass."\"><a href=\"".$Prev."\"><IMG SRC=\"".FileLink("images/page_prev.gif")."\" WIDTH=11 HEIGHT=11 BORDER=0 ALT=\"\">&nbsp;".$Lang['Prev']."</a></span><IMG SRC=\"".FileLink("images/0.gif")."\" WIDTH=10 HEIGHT=1 BORDER=0 ALT=\"\">";
		}

		$To=$this->PageStart+$this->Limit;
		if ($To>$this->RecCount) $To=$this->RecCount;
		echo " <span style=\"font-size:10px;\">".($this->PageStart+1)." &mdash; $To ".$Lang['Of']." ".$this->RecCount."</span> ";

		if ($this->PageCurrent < $this->Pages-1) {
			$Prev = $this->Filename;
			if (MOD_R) $Prev.="?";
			$Prev .= $this->StartStr."=".(($this->PageCurrent+1)*$this->Limit);
			$Prev .= "&".$this->CurStr."=".($this->PageCurrent+1);
			$Prev .= $this->Args;
			if (!$this->NoNext) echo "<IMG SRC=\"".FileLink("images/0.gif")."\" WIDTH=10 HEIGHT=1 BORDER=0 ALT=\"\"><span title=\"".$Lang['Next']."\" class=\"".$this->PrevNextClass."\"><a href=\"".$Prev."\">".$Lang['Next']."&nbsp;<IMG SRC=\"".FileLink("images/page_next.gif")."\" WIDTH=11 HEIGHT=11 BORDER=0 ALT=\"\"></a></span><IMG SRC=\"".FileLink("images/0.gif")."\" WIDTH=10 HEIGHT=1 BORDER=0 ALT=\"\">";

			$Prev = $this->Filename;
			if (MOD_R) $Prev.="?";
			$Prev .= $this->StartStr."=".(($this->Pages-1)*$this->Limit);
			$Prev .= "&".$this->CurStr."=".($this->Pages-1);
			$Prev .= $this->Args;
			if (!$this->NoNextNext) echo " <span title=\"".$Lang['Last']."\" class=\"".$this->PrevNextClass."\"><a href=\"".$Prev."\">".$Lang['Last']."&nbsp;<IMG SRC=\"".FileLink("images/page_last.gif")."\" WIDTH=11 HEIGHT=11 BORDER=0 ALT=\"\"></a></span><IMG SRC=\"".FileLink("images/0.gif")."\" WIDTH=10 HEIGHT=1 BORDER=0 ALT=\"\">";
		}

		echo "</td></tr>";
	}

	function DumpPages()
	{
		global $nsLang;
		$Lang=$nsLang->TplReturn("pagenums.class");
		echo "<tr>";
		echo "<td class=\"".$this->TDClass."\"  align=center><p>";


		for ($i = 0; $i < $this->Pages; $i++) {
			$Get = $this->Filename;
			if (MOD_R) $Get.="?";
			$PageStart = $i * $this->Limit;
			$Get .= $this->StartStr."=".$PageStart;
			$Get .= "&".$this->CurStr."=".$i;
			$Get .= $this->Args;

			if ($i>0&&$this->Separator) {
				echo "<span class=\"".$this->SeparatorClass."\">";
				echo $this->Separator;
				echo "</span>";
			}

			if ($this->PageCurrent == $i) {
				echo "<span class=\"".$this->CurrClass."\">[".($i+1)."]</span>";
			}
			else {
				if (!$this->NoPageLink) echo "<a href=\"".$Get."\" class=\"".$this->AClass."\">";
				echo ($i+1);
				if (!$this->NoPageLink) echo "</a>";
			}
		}
		echo "</p></td>";
		echo "</tr>";

	}
}

?>