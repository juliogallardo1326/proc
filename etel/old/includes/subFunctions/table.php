<?php
	
	function message($message,$isback=true,$messageHeader,$redir='',$footer=false){
		global $smarty,$printable_version;
		$issubmit = !$isback;
		if(!$method) $method = 'post';
		if($method == 'post') $enctype = 'multipart/form-data';
		if($redir=='') $redir = $_SERVER['HTTP_REFERER'];
		$smarty->assign("message", $message);
		$smarty->assign("header", $messageHeader);
		$smarty->assign("redir", $redir);
		$smarty->assign("isback", $isback);
		$smarty->assign("issubmit", $issubmit);
		$smarty->assign("method", $method);
		$smarty->assign("enctype", $enctype);
		$smarty->assign("width", "50%");
		$smarty->assign("formName", substr(md5(time()),0,8));
		$smarty->assign("printable", $printable_version);
		etel_smarty_display('cp_table.tpl');
		if($footer) etel_smarty_display('cp_footer.tpl');
	}
	
	function doTable($message,$messageHeader,$redir=NULL,$footer=false,$isback=false,$issubmit=false,$frmname=false,$footer_message='',$method='post'){
		global $smarty,$printable_version;
		if(!$frmname) $frmname = 'msgform'.substr(md5(time()),0,8);
		if(!$smarty) die($messageHeader."<BR>".$message);
		if(!$redir && $redir!=='') $redir = $_SERVER['HTTP_REFERER'];
		if(!$method) $method = 'post';
		if($method == 'post') $enctype = 'multipart/form-data';
		$smarty->assign("message", $message);
		$smarty->assign("header", $messageHeader);
		$smarty->assign("redir", $redir);
		$smarty->assign("isback", $isback);
		$smarty->assign("issubmit", $issubmit);
		$smarty->assign("width", "90%");
		$smarty->assign("formName", $frmname);
		$smarty->assign("method", $method);
		$smarty->assign("enctype", $enctype);
		$smarty->assign("printable", $printable_version);
		$smarty->assign("footer_message", $footer_message);
		etel_smarty_display('cp_table.tpl');
		if($footer) etel_smarty_display('cp_footer.tpl');
	}
	
	function beginTable()
	{
		ob_start();
	}
	
	function endTable($messageHeader,$redir=NULL,$footer=false,$isback=false,$issubmit=false,$frmname=false,$showTable=true,$method='post')
	{
	
		$TableContents= ob_get_contents();
		ob_end_clean();
		if($showTable)
			doTable($TableContents,$messageHeader,$redir,$footer,$isback,$issubmit,$frmname,'',$method);
		else
			echo $TableContents;
	}
	
	function beginCacheTable($cachename=NULL,$expire=NULL)
	{
		global $beginCacheTable_global;
		
		$beginCacheTable_global['hash'] = $cachename;
		$beginCacheTable_global['key'] = '';
		$beginCacheTable_global['found'] = NULL;
		$beginCacheTable_global['expire'] = $expire;
		$beginCacheTable_global['microtime'] = microtime_float();
		if($cachename && !$_REQUEST['etel_disable_cache'])
		{
			$sql = "SELECT uncompress(`ce_cache`),`ce_expire`
			FROM 
			`cs_cache` WHERE `ce_hash` = '$cachename' AND `ce_expire` > ".time();
			$result = mysql_query($sql) or dieLog(mysql_error());
			if ( mysql_num_rows($result)>0)
			{
				$beginCacheTable_global['found'] = ((mysql_result($result,0,0)));	
				$beginCacheTable_global['ce_expire'] = ((mysql_result($result,0,1)));	
				return 0;
			}
		}
		ob_start();
		return 1;
	}
	
	function endCacheTable($tableData)
	{
		global $beginCacheTable_global;
		
		$messageHeader = $tableData['messageHeader'];
		$redir = $tableData['redir'];
		$footer = $tableData['footer'];
		$isback = $tableData['isback'];
		$issubmit = $tableData['issubmit'];
		$frmname = $tableData['frmname'];
		$showTable = true;
		if(isset($tableData['showTable'])) $showTable  = $tableData['showTable'];
		
		$from_cache = ".";
		if(!$beginCacheTable_global['found'])
		{
			$beginCacheTable_global['found']= ob_get_contents();
			ob_end_clean();
			
			if($beginCacheTable_global['hash'] && $beginCacheTable_global['ce_expire']>time())
			{
				$sql = "REPLACE INTO `cs_cache` set `ce_hash` = '".$beginCacheTable_global['hash']."', `ce_expire` = '".$beginCacheTable_global['expire']."',`ce_cache` = compress('". addslashes(($beginCacheTable_global['found']))."') ";

				$result = mysql_query($sql) or dieLog(mysql_error());
			}
		
		}
		else $from_cache = " from Cache (Expires ".($beginCacheTable_global['ce_expire']-time())."s)";
		
		
		$footer_message = "Table Generated in ".round(microtime_float()-$beginCacheTable_global['microtime'],4)." Seconds $from_cache";
		if($showTable)
			doTable($beginCacheTable_global['found'],$messageHeader,$redir,$footer,$isback,$issubmit,$frmname,$footer_message);
		else
			echo $beginCacheTable_global['found'];
		
		if(time()%92==1) optimizeCache();
	}
		
	function optimizeCache()
	{
		$sql = "DELETE FROM `cs_cache` where `ce_expire` < ".time();
		$result = mysql_query($sql) or dieLog(mysql_error());
		$sql = "OPTIMIZE TABLE `cs_cache` ";
		$result = mysql_query($sql) or dieLog(mysql_error());
	}
?>