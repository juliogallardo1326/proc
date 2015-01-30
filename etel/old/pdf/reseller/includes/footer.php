<?php
if(!$printable_version){	
	$gen_time = "Page Generated in ".round(microtime_float()-$etel_generate_page_time,5)." Seconds.";
	if ($etel_generate_page_time) $smarty->assign('page_generate_time',$gen_time);
	etel_smarty_display('cp_footer.tpl');
}	
?>