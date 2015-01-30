<?
class calendar_class 
{
	var $date_from;
	var $date_to;
	var $cells;
	var $months;
	
	function array_print($text)
	{
		echo "<table width='100%'><tr><td><pre>";
		print_r($text);
		echo "</pre></td></tr></table>";
	}
	
	function set_cells($cells)
	{
		$this->cells = $cells;
	}

	function set_months($months)
	{
		$this->months = $months;
	}
	
	function set_date_range($from,$to)
	{
		$this->date_from = $from;
		$this->date_to = $to;
	}
	
	function render()
	{
		foreach($this->months as $date => $set)
		{
			$this->render_month($date);
			echo "<br><br>";
		}
	}
	
	function render_month($this_date)
	{
		$cell_base = date("Y/m",strtotime($this_date));
		
		echo "
		<table width='546px' border='0' cellpadding='0' cellspacing=1 align='center' bgcolor='#000000'>
			<tr align='center' valign='middle' height='20px'> 
				<td colspan=7 height='20'><font color='#FFFFFF'><b>" . date("F Y",strtotime($this_date)) . "</b></font></td>
			</tr>
			<tr align='center' valign='middle' height='20px'> 
				<td width='78px' height='20'><font color='#FFFFFF'>Sun</font></td>
				<td width='78px'><font color='#FFFFFF'>Mon</font></td>
				<td width='78px'><font color='#FFFFFF'>Tue</font></td>
				<td width='78px'><font color='#FFFFFF'>Wed</font></td>
				<td width='78px'><font color='#FFFFFF'>Thur</font></td>
				<td width='78px'><font color='#FFFFFF'>Fri</font></td>
				<td width='78px'><font color='#FFFFFF'>Sat</font></td>
			</tr>
		";

		$iStartDate =	date("w", strtotime($this_date));
		$iDaysInMonth =  date("t", strtotime($this_date));
		
		$iColCount=1;
		$iMaxColCount = ($iDaysInMonth + $iStartDate -  (($iDaysInMonth + $iStartDate) % 7) + 7);

		if ((($iDaysInMonth + $iStartDate) % 7) == 0)
			$iMaxColCount = $iMaxColCount -7;

		while($iColCount<=$iMaxColCount)
		{
			$iDisplayNumber = 0;
			if (($iColCount>$iStartDate) && ($iDaysInMonth >= ($iColCount-$iStartDate)))
				$iDisplayNumber = $iColCount - $iStartDate;

			if(($iColCount % 7) == 1)
				print("<tr align=center valign=middle bgcolor='#E6F2F2'>");

			$key = $cell_base . "/" . str_pad($iDisplayNumber,2,"0",STR_PAD_LEFT);

			print("<td  align='left' valign='top' width='78px' height='30px'>");  
			if($iDisplayNumber)
			{	
				$color = isset($this->cells[$key]['color']) ? $this->cells[$key]['color'] : "#FFFFFF";
				
				if(isset($this->cells[$key]['link']))
					$iDisplayNumber = "<a href='" . $this->cells[$key]['link'] . "'>$iDisplayNumber</a>";
				
				echo "
					<table height='100%' width='100%' border='0' cellpadding='0' cellspacing=1>
						<tr>
							<td bgcolor='#CCCCCC'>$iDisplayNumber</td>
						</tr>
						<tr>
							<td height='100%' bgcolor='$color'>";
				if(isset($this->cells[$key]['text']))
					echo $this->cells[$key]['text'];
				else
					echo "&nbsp;";
				echo "</td></tr></table>";
			}
			print("</td>");

			if(($iColCount % 7) == 0)
				print("</tr>");
			$iColCount = $iColCount + 1;
		}
		
		if(isset($this->legend))
		echo "
				<tr align='center' valign='middle' height='20'> 
				<td height='20' colspan='1' class='infoBold'>Legend:</td>
				<td height='20' colspan='6' class='infoBold' align='right'>" . $this->legend . "</td>
			</tr>
			";
		echo "
		</table>
		";
	}
}
?>