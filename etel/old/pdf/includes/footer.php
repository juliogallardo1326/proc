<?php
if(!$printable_version)
{	
	$query_info = "";
	if($etel_debug_mode == 1)
	{
		$query_info = "<table width='800px'><tr><td><font style='font-size:8pt;'>";
		if(isset($etel_query_info['results']))
		{
			$total_time = 0;
			foreach($etel_query_info['results'] as $index => $info)
			{
				$query_info .= "<p>Query " . $info['sql'] . " Took " . $info['duration'] . " Seconds".($info['error']?" With Error:".$info['error']:'').".</p>";
				$total_time += $info['duration'];
			}
			$query_info .= "<p>Total Time: " . $total_time . "seconds</p>";
		}
		$query_info .= "</font></td></tr></table>";
	}
		
	$gen_time = "Page Generated in ".round(microtime_float()-$etel_generate_page_time,5)." Seconds.";
	if ($etel_generate_page_time) $smarty->assign('page_generate_time',$gen_time);
	etel_smarty_display('cp_footer.tpl');
	echo wordwrap($query_info, 250, "<br />\n",1);
}	
?>