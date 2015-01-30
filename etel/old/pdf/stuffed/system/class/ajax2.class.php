<?


class nsAjax2{

	//---------------------------------------------------------------------------------
	function nsAjax2(){
		$this->DumpXmlRequestCode();
	}		
	//---------------------------------------------------------------------------------
	function do_request($url, $return_function, $ret_func_additional_parameters){
		?><script>do_ajax2_request('<?=$url?>', '<?=$return_function?>', 
									'<?=$ret_func_additional_parameters?>')</script><?
	}
	//---------------------------------------------------------------------------------
		function DumpXmlRequestCode(){ global $TemplatePath;
		if(!isset($GLOBALS['dump_ajax_code_done'])){
			$GLOBALS['dump_ajax_code_done']=true;?>
					<script language="JavaScript" type="text/javascript" src="<?=$TemplatePath?>static/ajax2.js"></script>	
	<?}
	}
	//---------------------------------------------------------------------------------

};


?>