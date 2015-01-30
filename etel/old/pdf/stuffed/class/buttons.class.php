<?

class nsButtons
{
	function nsButtons()
	{
		global $nsTemplate, $nsProduct;
		if (@file_exists($nsTemplate->LinkToFile("buttons/config.php"))) {
			include_once $nsTemplate->LinkToFile("buttons/config.php");
		}
		
		
		$this->MouseOverEnabled=(isset($MouseOverEnabled))?$MouseOverEnabled:false;
		$this->PostName=(isset($PostName))?$PostName:false;

		$this->TblBorder=(isset($TblBorder))?$TblBorder:0;
		$this->TblSpacing=(isset($TblSpacing))?$TblSpacing:1;
		$this->TblPadding=(isset($TblPadding))?$TblPadding:1;
		$this->TblBg=(isset($TblBg))?$TblBg:"#ffffff";

		$this->MouseOverEvent=(isset($MouseOverEvent))?$MouseOverEvent:"this.style.background=666666;";
		$this->MouseOutEvent=(isset($MouseOutEvent))?$MouseOutEvent:"this.style.background=ffffff";

		$this->TDBg=(isset($TDBg))?$TDBg:"#ffffff";
		$this->ImgWidth=(isset($ImgWidth))?$ImgWidth:18;
		$this->ImgHeight=(isset($ImgHeight))?$ImgHeight:18;
		$this->ImgBorder=(isset($ImgBorder))?$ImgBorder:0;
		$this->Separator=(isset($Separator))?$Separator:"&nbsp;|&nbsp;";

		$this->TblClass=(isset($TblClass))?$TblClass:"";
		$this->TDClass=(isset($TDClass))?$TDClass:"";
		$this->ImgClass=(isset($ImgClass))?$ImgClass:"";
		$this->AClass=(isset($AClass))?$AClass:"";
		$this->SeparatorClass=(isset($SeparatorClass))?$SeparatorClass:"";

		//$this->IMG="http://".HOST.PATH."/skins/".$nsProduct->SKIN."/buttons/";


		$this->Header = "<table cellpadding=".$this->TblPadding." cellspacing=".$this->TblSpacing." border=".$this->TblBorder." bgcolor=\"".$this->TblBg."\" class=\"".$this->TblClass."\"><tr>\n";

		$this->Footer = "</tr></table>\n ";
		$this->Empty = "<td class=".$this->TDClass."></td>\n";
		$this->Row = 0;
	}

	function Reset()
	{
		$this->Row = 0;
		$this->RowSize=0;
		unset($this->Buttons);
	}

	function Add($File, $Alt = false, $Query = false, $Cnf = false, $Target = false)
	{
		if (!$File) return false;
		global $nsTemplate;
		if ($Target) $Target = "target=$Target";
		if ($Cnf) $Cnf = "onclick=\"return confirm('".$Cnf."');\"";
		$Button = "<td bgcolor=\"".$this->TDBg."\"  class=\"".$this->TDClass."\" ";
		if ($this->MouseOverEnabled) {
				if ($this->MouseOverEvent) $Button.=" onmouseover=\"".$this->MouseOverEvent."\" ";
				if ($this->MouseOutEvent) $Button.=" onmouseout=\"".$this->MouseOutEvent."\" ";
		}
		$Button.=" >";
		$Button .="<a href=\"$Query\" $Target $Cnf class=\"".$this->AClass."\">\n";
		$Button .= "<img src=\"".FileLink("buttons/".$File)."\" width=".$this->ImgWidth." height=".$this->ImgHeight." border=".$this->ImgBorder." alt=\"$Alt\" title=\"$Alt\" class=\"".$this->ImgClass."\">";
		$Button .= "</a></td>\n";
		if ($this->PostName) {
			$Button.="<td><p>";
			$Button .="<a href=\"$Query\" $Target $Cnf class=\"".$this->AClass."\">\n";
			$Button .=$Alt."</a></p></td>";
		}
		$this->DoAdd($Button);
	}


	function AddJava($File, $Alt, $Java)
	{
		if (!$File) return false;
		$Button = "<td bgcolor=\"".$this->TDBg."\" class=\"".$this->TDClass."\" ";
		if ($this->MouseOverEnabled) {
				if ($this->MouseOverEvent) $Button.=" onmouseover=\"".$this->MouseOverEvent."\" ";
				if ($this->MouseOutEvent) $Button.=" onmouseout=\"".$this->MouseOutEvent."\" ";
		}
		$Button.= " >\n";
		$Button .="<a href=\"javascript:;\" $Java class=\"".$this->AClass."\">\n";
		$Button .= "<img src=\"".FileLink("buttons/".$File)."\" width=".$this->ImgWidth." height=".$this->ImgHeight." border=".$this->ImgBorder." alt=\"$Alt\" title=\"$Alt\" class=\"".$this->ImgClass."\">";
		$Button .= "</a></td>\n";
		$this->DoAdd($Button);	
	}

	function Row()
	{
		if ($this->Row == 0) $this->RowSize = count($this->Buttons);
		if ($this->RowSize == 0) return false;
		$this->Row++;
	}

	function Separator()
	{
		$Button = "<td class=".$this->TDClass." ><p class=".$this->SeparatorClass.">".$this->Separator."</p></td>\n";
		$this->DoAdd($Button);
	}

	function AddEmpty()
	{
		$this->DoAdd($this->Empty);
	}


	function DoAdd($Button)
	{
		$this->Buttons[] = $Button;
	}

	function Dump()
	{
		if (count($this->Buttons) < 1) return false;
		echo $this->Header;
		$i = 0;
		foreach($this->Buttons as $Value) {
			if ($this->Row > 0 && $i == $this->RowSize) {
				echo "</tr><tr>";
				$i = 0;
			}
			echo $Value;
			$i++;
		}
		
		if ($this->Row > 0) for ($j = 0; $j < ($this->RowSize-$i); $j++) echo $this->Empty;
		echo $this->Footer;
		flush();
		$this->Reset();
	}
}


?>