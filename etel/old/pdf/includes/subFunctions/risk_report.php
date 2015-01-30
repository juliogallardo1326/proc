<?
include_once("color_manip.php");
include_once("risk_management.php");

class risk_report_main extends risk_management
{
	function risk_report_main($report_name = "")
	{
		$this->risk_management_init();
		
		$this->global_row	= 1;

		$this->report_custom = $this->get_custom_report($report_name);
		
		$this->report_date = $this->get_date_ranges();
		$this->report_proj = $this->get_projections();
		$this->report_calc = $this->get_calculations();
	}
	
	function display_summary_report($company_ids = 0)
	{
		$summary = $this->generate_summary($company_ids);
		$this->render_risk_report($summary);
	}
	
	function display_risk_report($company_ids = 0)
	{
		$report_data = $this->generate_report($company_ids);
		$this->render_risk_report($report_data);
	}
	
	function render_risk_report($report_data)
	{
		$color_manip = new smart_colors();
		
		foreach($report_data as $index => $info)
		{
			$info['rr_results'] = unserialize(stripslashes($info['rr_results']));
			$data[str_pad(number_format($info['rr_risk_value'],2),10,"0",STR_PAD_LEFT) . "|" . $info['companyname']] = $info;
		}
		
		$row_color[] = "FCFCFC";
		$row_color[] = "ACACAC";
		$row_num = 0;
		
		$col_color[] = "ACACAC";
		$col_color[] = "ECECEC";
		$col_num = 0;
		
		krsort($data);
		reset($data);
		foreach($data as $index => $info)
		{
			$index = explode("|",$index);
			$index = $index[1];

			$set_date = $this->report_date;
			$set_proj = $this->report_proj;

			if(isset($this->report_custom['settings']))
			{
				$cust_date = $this->report_custom['settings']['dates'];
				$cust_proj = $this->report_custom['settings']['projections'];
				
				$num_date = sizeof($this->report_custom['settings']['dates']);
				$num_proj = sizeof($this->report_custom['settings']['projections']);
			}
			else
			{
				$num_date = sizeof($this->report_date);
				$num_proj = sizeof($this->report_proj);
			}

			$pixels = (($num_date + $num_proj + 2) * 150) . "px";
			echo "<p><table class='report' style='border: #000000 1px solid;' cellpadding=0 cellspacing=0 width='$pixels'>";
			echo "<tr><td colspan=2></td>";
				
			if($num_date > 0)
				echo "<td align='center' colspan=" . $num_date . "><b>Actual Values</b></td>";
			if($num_proj > 0)
				echo "<td align='center' colspan=" . $num_proj . "><b>Projected Values</b></td>";
			
			echo "</tr>";
			echo "<tr><td><b>Company Name</b></td><td><b>Report</b></td>";
			foreach($set_date as $report_name => $report_range)
				if(!isset($cust_date) || in_array($report_name,$cust_date))
					echo "<td><b>" . $report_range['title'] . "</b></td>";		
			foreach($set_proj as $report_proj_title => $report_proj_info)
				if(!isset($cust_proj) || in_array($report_proj_title,$cust_proj))
					echo "<td><b>" . $report_proj_info['title'] . "</b></td>";		
			echo "<td><b>Total Score</b></td>";
			echo "</tr>";
	
			echo "<tr>";

			$risk = $info['rr_risk_value'];
			
			echo "<td valign='top' rowspan=" . sizeof($info['rr_results']) . ">$index<br><b>Risk:</b> $risk</td>";
			
			krsort($info['rr_results']);
			reset($info['rr_results']);
			//$this->array_print($info);

			foreach($info['rr_results'] as $key => $report_info)
			{
				$report_title = explode("|",$key);
				$report_title = $report_title[1];
				if(!isset($this->report_custom['settings']['calculations']) || isset($this->report_custom['settings']['calculations'][$report_title]))
				{
					$col_num = 0;
					$color = $color_manip->blend($row_color[$row_num%sizeof($row_color)],$col_color[$col_num%sizeof($col_color)]);
					$color = $color_manip->blend($color,"FFFFFF");
					
					echo "<td bgcolor='#$color' valign='top'>$report_title</td>";
					$col_num++;
					foreach($set_date as $report_name => $report_range)
						if(!isset($cust_date) || in_array($report_name,$cust_date))
						{
							$cell = $report_info[$report_name];
							
							$color = $color_manip->blend($row_color[$row_num%sizeof($row_color)],$col_color[$col_num%sizeof($col_color)]);
							$color = $color_manip->blend($color,"CCCCFF");
							if($cell['color'] != "" && $cell['score'] != 0)
								$color = $color_manip->blend($color,$cell['color'],0.5);
							$col_num++;
							
							echo "<td bgcolor='#$color'>";
							echo ($cell['display'] != "" ? $cell['display'] : $cell['value']);
							echo "</td bgcolor='#$color'>";
						}
	
					foreach($set_proj as $report_proj_title => $report_proj_info)
						if(!isset($cust_proj) || in_array($report_proj_title,$cust_proj))
						{
							$cell = $report_info[$report_proj_title];
		
							$color = $color_manip->blend($row_color[$row_num%sizeof($row_color)],$col_color[$col_num%sizeof($col_color)]);
							$color = $color_manip->blend($color,"CCFFCC");
							if($cell['color'] != "" && $cell['score'] != 0)
								$color = $color_manip->blend($color,$cell['color'],0.5);
							$col_num++;
		
							echo "<td bgcolor='#$color'>";
							echo ($cell['display'] != "" ? $cell['display'] : $cell['value']);
							echo "</td bgcolor='#$color'>";
						}
	
					$cell = $report_info['total'];
					$color = $color_manip->blend($row_color[$row_num%sizeof($row_color)],$col_color[$col_num%sizeof($col_color)]);
	
					if($cell['color'] != "" && $cell['score'] != 0)
						$color = $color_manip->blend($color,$cell['color'],0.5);
	
					echo "<td bgcolor='#$color'>";
					echo $cell['risk'] . " (" . $cell['score'] . ")";
					echo "</td>";				
					echo "</tr><tr>";
					$row_num++;
				}
			}
			echo "</tr>";
			echo "</table></p>";		
		}
	}
}
?>