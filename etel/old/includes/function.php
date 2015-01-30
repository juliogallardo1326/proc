<?php
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//


include_once("constants.php");
include_once("JSON_functions.php");
include_once("subFunctions/email.php");
include_once("subFunctions/table.php");
include_once("subFunctions/report.php");
include_once("subFunctions/login.php");
include_once("subFunctions/transaction.php");
include_once("subFunctions/db.functions.php");
include_once("subFunctions/db.tracking.php");
include_once("subFunctions/db.merchant.php");
include_once("subFunctions/db.sites.php");
include_once("subFunctions/db.subscription.php");
include_once("subFunctions/db.bank.php");
include_once("subFunctions/gen_refnumbers.php");
include_once("subFunctions/rates_fees.php");
include_once("subFunctions/post.php");
include_once("subFunctions/http.post.php");
require_once("subFunctions/smart_search.php");
require_once("updateAccess.php");


function etel_set_cookie($Name, $Value = '', $Expires = 0, $Path = '', $Domain = '', $Secure = false, $HTTPOnly = false)
{
	if (!empty($Domain))
	{
		// Fix the domain to accept domains with and without 'www.'.
		if (strtolower(substr($Domain, 0, 4)) == 'www.')  $Domain = substr($Domain, 4);
		$Domain = '.' . $Domain;
		
		// Remove port information.
		$Port = strpos($Domain, ':');
		if ($Port !== false)  $Domain = substr($Domain, 0, $Port);
	}
	
	header('Set-Cookie: ' . rawurlencode($Name) . '=' . rawurlencode($Value)
		. (empty($Expires) ? '' : '; expires=' . gmdate('D, d-M-Y H:i:s', $Expires) . ' GMT')
		. (empty($Path) ? '' : '; path=' . $Path)
		. (empty($Domain) ? '' : '; domain=' . $Domain)
		. (!$Secure ? '' : '; secure')
		. (!$HTTPOnly ? '' : '; HttpOnly'), false);
}

function etel_format_variable($var)
{
	$var = preg_replace('/[_]/',' ',$var);
	$var = ucwords(strtolower($var));
	return $var;
}

function etel_getRow($force=false)
{
	global $etel_row;
	if(!$etel_row) $etel_row = 1;
	if($force) $etel_row = $force;
	$etel_row = 3 - $etel_row;
	return $etel_row;
}

function etel_error_handler($errno, $errstr, $errfile, $errline)
{
  switch ($errno) {
  case E_USER_ERROR:
   $log.= "<b>My ERROR</b> [$errno] $errstr<br />\n";
   $log.= "  Fatal error in line $errline of file $errfile";
   $log.= ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
   toLog('error','misc',$log);
   break;
  case E_USER_WARNING:
   toLog('error','misc',$log);
   break;
  case E_USER_NOTICE:
  // $log.= "<b>My NOTICE</b> [$errno] $errstr<br />\n";
   break;
  default:
   //$log.= "Unkown error type: [$errno] $errstr<br />\n";
   //dieLog($log);
   break;
  }
}

function getRequestHash()
{
	$req = $_REQUEST;
	unset($req['PHPSESSID']);
	unset($req['Submit']);
	return substr(md5(serialize($req)),0,4);
}

function etelDie($val="die()")
{
	global $etel_debug_mode;
	if($etel_debug_mode) die($val);
}

function etelPrint($val)
{
	global $etel_debug_mode;
	if($etel_debug_mode) print_r($val);
	if($etel_debug_mode) print("<BR>");
	flush();
}

function quote_smart($value)
{
   // Stripslashes
   if (get_magic_quotes_gpc()) {
       $value = stripslashes($value);
   }
   // Quote if not integer
   if (!is_numeric($value)) {
       $value = mysql_real_escape_string($value);
   }
   return trim($value);
}

function csv_parse($str,$f_delim = ',',$r_delim = "\n",$qual = '"')
{
   $output = array();
   $row = array();
   $word = '';
  
   $len = strlen($str);
   $inside = false;
  
   $skipchars = array($qual,'\\');
  
   for ($i = 0; $i < $len; ++$i) {
       $c = $str[$i];
       if (!$inside && $c == $f_delim) {
           $row[] = $word;
           $word = '';
       } elseif (!$inside && $c == $r_delim) {
           $row[] = $word;
           $word = '';
           $output[] = $row;
           $row = array();
       } else if ($inside && in_array($c,$skipchars) && ($i+1 < $len && $str[$i+1] == $qual)) {
           $word .= $qual;
           ++$i;
       } else if ($c == $qual) {
           $inside = !$inside;
       } else {
           $word .= $c;
       }
   }
  
   $row[] = $word;
   $output[] = $row;
  
   return $output;
}

function xml2array($raw_xml) {
   $xml_parser = xml_parser_create();
   xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
   xml_parser_set_option($xml_parser, XML_OPTION_SKIP_WHITE, 1);
   xml_parse_into_struct($xml_parser, $raw_xml, $vals);
   xml_parser_free($xml_parser);

   $_tmp='';
   foreach ($vals as $xml_elem) {
       $x_tag=$xml_elem['tag'];
       $x_level=$xml_elem['level'];
       $x_type=$xml_elem['type'];
       if ($x_level!=1 && $x_type == 'close') {
           if (isset($multi_key[$x_tag][$x_level])) {
               $multi_key[$x_tag][$x_level]=1;
           } else {
               $multi_key[$x_tag][$x_level]=0;
           }
       }
       if ($x_level!=1 && $x_type == 'complete') {
           if ($_tmp==$x_tag) {
               $multi_key[$x_tag][$x_level]=1;
           }
           $_tmp=$x_tag;
       }
   }

   foreach ($vals as $xml_elem) {
       $x_tag=$xml_elem['tag'];
       $x_level=$xml_elem['level'];
       $x_type=$xml_elem['type'];
       if ($x_type == 'open') {
           $level[$x_level] = $x_tag;
       }
       $start_level = 1;
       $php_stmt = '$xml_array';
       if ($x_type=='close' && $x_level!=1) {
           $multi_key[$x_tag][$x_level]++;
       }
       while($start_level < $x_level) {
           $php_stmt .= '[$level['.$start_level.']]';
           if (isset($multi_key[$level[$start_level]][$start_level]) && $multi_key[$level[$start_level]][$start_level]) {
             $php_stmt .= '['.($multi_key[$level[$start_level]][$start_level]-1).']';
           }
           $start_level++;
       }
       $add='';
       if (isset($multi_key[$x_tag][$x_level]) && $multi_key[$x_tag][$x_level] && ($x_type=='open' || $x_type=='complete')) {
           if (!isset($multi_key2[$x_tag][$x_level])) {
               $multi_key2[$x_tag][$x_level]=0;
           } else {
               $multi_key2[$x_tag][$x_level]++;
           }
           $add='['.$multi_key2[$x_tag][$x_level].']';
       }
       if (isset($xml_elem['value']) && trim($xml_elem['value'])!='' && !array_key_exists('attributes',$xml_elem)) {
           if ($x_type == 'open') {
               $php_stmt_main=$php_stmt.'[$x_type]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
           } else {
               $php_stmt_main=$php_stmt.'[$x_tag]'.$add.' = $xml_elem[\'value\'];';
           }
           eval($php_stmt_main);
       }
       if (array_key_exists('attributes',$xml_elem)) {
           if (isset($xml_elem['value'])) {
               $php_stmt_main=$php_stmt.'[$x_tag]'.$add.'[\'content\'] = $xml_elem[\'value\'];';
               eval($php_stmt_main);
           }

           foreach ($xml_elem['attributes'] as $key=>$value) {
               $php_stmt_att=$php_stmt.'[$x_tag]'.$add.'[\'attributes\'][$key] = $value;';
               eval($php_stmt_att);
           }
       }
   }
   return $xml_array;
}

function toLog(	$type,$actor,$txt,$id=-1)
{
	
	global $etel_debug_mode;
	global $etel_root_path;
	$txt=addslashes($txt);
	if($txt) $txt = "'$txt'";
	else $txt = "NULL";
	$sql= "INSERT INTO `cs_log` ( `lg_action` , `lg_actor` , `lg_txt` , `lg_timestamp`, `lg_item_id` )VALUES ('$type','$actor', $txt, '".time()."', '$id')";
	$log_result=sql_query_write($sql) or die(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	$lg_id = mysql_insert_id();
	$logFile = $etel_root_path."logs/EtelLog_".$_SESSION["gw_id"]."_".date("y-m-d").".txt";
	//$txt = str_replace("\n","|",$txt);
	//$txt = str_replace("\r","|",$txt);
	$txt = str_replace("\'","'",$txt);
	//if(!file_exists($logFile)) $header = "       Etelegate Log for ".date("F-jS \o\f Y")."\n\n";
	//$fh = @fopen($logFile, 'a+');
	//$stringData = date("D M j G:i:s")." $type-$actor:  $txt";
	//@fwrite($fh, $header.$stringData."\n");
	//@fclose($fh);
	if($etel_debug_mode) echo "<font size='1' >".wordwrap( htmlentities($txt), 250, "<br />\n",1)."</font><BR>";
	$emailInfo['et_subject'] = ucfirst($type)." Error Found: ".substr($txt,0,50);
	$emailInfo['et_htmlformat'] = nl2br($txt); // => html
	$emailInfo['et_from'] = "error@etelegate.com"; // => sales@etelegate.com 
	$emailInfo['et_from_title'] = "Etelegate"; // => Etelegate Sales 
	$emailInfo['et_textformat']= $txt;
	$emailInfo['et_to'] = "error@etelegate.com"; // => techsupport@ecommerceglobal.com 
	$emailInfo['full_name'] = "Etelegate Support"; // => Etelegate Merchant )
	if(!$etel_debug_mode && ($type=='erroralert' || $type=='hackattempt')) send_email_data($emailInfo,NULL);
	return $lg_id;
}


function toLogAppend($txt,$lg_id,$id=NULL)
{
	
	global $etel_debug_mode;
	$txt=addslashes($txt);
	if($txt) $txt = "'\n$txt'";
	else $txt = "NULL";
	if($id) $id_SQL = ", lg_item_id='$id'";
	$sql= "UPDATE `cs_log`  set `lg_txt` = concat(`lg_txt`,$txt) $id_SQL where lg_id = '$lg_id' ";
	$log_result=sql_query_write($sql) or die(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");
	if($etel_debug_mode) echo "<font size='1'>$txt</font><BR>";
}


function formatPhone($phone) {
       if (empty($phone)) return "";
       if (strlen($phone) == 7)
               sscanf($phone, "%3s%4s", $prefix, $exchange);
       else if (strlen($phone) == 10)
               sscanf($phone, "%3s%3s%4s", $area, $prefix, $exchange);
       else if (strlen($phone) > 10)
               sscanf($phone, "%3s%3s%4s%s", $area, $prefix, $exchange, $extension);
       else
               return "$phone";
       $out = "";
       $out .= isset($area) ? '(' . $area . ') ' : "";
       $out .= $prefix . '-' . $exchange;
       $out .= isset($extension) ? ' x' . $extension : "";
       return $out;
}

function formatPerc($val)
{
	return number_format($val,2,".",",")."%";
}
	
function formatMoney($val)
{
	$sign = "";
	if ($val<0)
	{
		$sign = "(-";
		$endsign = ")";
		$val = abs($val);
	}
	return $sign.number_format($val,2,".",",").$endsign;

}

function formatCreditCard($val)
{
	$length = strlen($val);
	$val = substr($val,0,4). str_repeat("*",$length-8).substr($val,-4,4);
	return $val;

}


function func_update_rate($userId,$transInfo,$connection_string,$trans_type = "cc",$mode = "Test"){

	$int_table = "cs_test_transactiondetails";
	if ($mode == "Live") $int_table = "cs_transactiondetails";

	$returnstring=true;
	$qry_transfee = ", ".$trans_type."_merchant_trans_fees, ".$trans_type."_reseller_trans_fees, ".$trans_type."_total_trans_fees ";
	$qry_extra = ", ".$trans_type."_chargeback, ".$trans_type."_discountrate, ".$trans_type."_reserve";
	if ($trans_type != "web") $qry_discount = ", ".$trans_type."_merchant_discount_rate, ".$trans_type."_reseller_discount_rate, ".$trans_type."_total_discount_rate ";
	$qry_select="select chargeback,credit,total_discount_rate,cd.transactionfee ,".$trans_type."_reserve ,merchant_discount_rate,reseller_discount_rate,total_trans_fees ,reseller_trans_fees,cd.discountrate,merchant_trans_fees $qry_transfee $qry_discount $qry_extra, bk_fee_low_risk, bk_fee_high_risk, bk_fee_approve, bk_fee_approve, bk_fee_decline  from cs_companydetails as cd left join cs_bank as bk on bank_id = '".$transInfo['bank_id']."'  where userId =$userId";
	//die($qry_select);
	$sel_res=sql_query_read($qry_select);
	if(!$sel_res)
		{
			$returnstring=false;
		}
	$sel_val = mysql_fetch_array($sel_res);
	
	// Teir / Custom
	
	$typeArray = array('cc' => 'creditcard', 'ch'=>'check', 'web'=>'web900');
	$cr_transtype = $typeArray[$trans_type];
	
	$sql = "SELECT * FROM `cs_company_rates` WHERE `cr_userId` = '$userId' AND (`cr_transtype` = '$cr_transtype' OR `cr_transtype` = 'all')";
	$result=sql_query_read($sql);

	
	
	$chargeback=$sel_val[$trans_type."_chargeback"];
	$credit=$sel_val[$trans_type."_discountrate"];
	$total_discount_rate=$sel_val[$trans_type."_total_discount_rate"];
	$transactionfee=$sel_val[3];
	$reserve=$sel_val[$trans_type."_reserve"];
	$merchant_discount_rate=$sel_val[$trans_type."_merchant_discount_rate"];
	$reseller_discount_rate=$sel_val[$trans_type."_reseller_discount_rate"];
	$total_trans_fees=$sel_val[$trans_type."_total_trans_fees"];
	$reseller_trans_fees=$sel_val[$trans_type."_reseller_trans_fees"];
	$discountrate=$sel_val[9];
	$merchant_trans_fees=$sel_val[$trans_type."_merchant_trans_fees"];
	$r_bank_discount_rate=$sel_val["bk_fee_low_risk"];
	if($sel_val["transaction_type"]=='game' || $sel_val["transaction_type"]=='adlt') $r_bank_discount_rate=$sel_val["bk_fee_high_risk"];
	if($transInfo['status']=='A') $r_bank_trans_fee=$sel_val["bk_fee_approve"];
	else $r_bank_trans_fee=$sel_val["bk_fee_decline"];
	// Apply Custom Rates
	while($customRates = mysql_fetch_assoc($result))
	{
		switch ($customRates['cr_feetype']) 
		{
			case "decline transaction fee":
				if($transInfo['status']=='D')
				{
					$merchant_trans_fees=$customRates['cr_merchant'];
					$reseller_trans_fees=$customRates['cr_reseller'];
					$transactionfee=$customRates['cr_total'];
					$total_trans_fees=$customRates['cr_total'];
				}
				break;
		}
	}
	$qry_insert="update $int_table set r_bank_trans_fee='$r_bank_trans_fee', r_bank_discount_rate='$r_bank_discount_rate', r_chargeback='$chargeback',r_credit='$credit',r_total_discount_rate='$total_discount_rate',r_transactionfee='$transactionfee' ,r_reserve='$reserve' ,r_merchant_discount_rate='$merchant_discount_rate',r_reseller_discount_rate='$reseller_discount_rate',r_total_trans_fees='$total_trans_fees',r_reseller_trans_fees='$reseller_trans_fees',r_discountrate='$discountrate',r_merchant_trans_fees='$merchant_trans_fees' where transactionId = '".$transInfo['transactionId']."' AND `userId` = '$userId'";
	sql_query_write($qry_insert) or dieLog(mysql_error()." ~ $qry_insert");

	return $returnstring;

}
	
	//Function to get the current date time in sql insert format
		function func_get_current_date_time()
		{
			$str_return_date = date("Y-m-d g:i:s");
			return($str_return_date);
		}
	
		//function to get get date 12 hour format
		function func_get_date_time_12hr($str_date)
		{
			if ($str_date == "0000-00-00 00:00:00") return "";
			if ($str_date == "") return "";
			$str_year = substr($str_date,0,4);
			$str_month = substr($str_date,5,2);
			$str_day = substr($str_date,8,2);
			$str_hour = substr($str_date,11,2);
			$str_minute = substr($str_date,14,2);
			$str_second = substr($str_date,17,2);
			$str_return_date = @date("m-d-Y h:i:s A",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
			return($str_return_date);
		}
		function func_get_date_inmmddyy($str_date)
		{
			if($str_date != ""){
				$str_year = substr($str_date,0,4);
				$str_month = substr($str_date,5,2);
				$str_day = substr($str_date,8,2);
				$str_return_date = "";
				if($str_day !="00" && $str_month !="00"){
				$str_return_date = date("m-d-Y",mktime(0,0,0,$str_month,$str_day,$str_year));
				}
				return($str_return_date);
			}else{
				return $str_date;
			}
		}
	
		function func_get_date_inyyyymmdd_time($str_date)
		{
			if($str_date != ""){
				$str_month = substr($str_date,0,2);
				$str_day = substr($str_date,3,2);
				$str_year = substr($str_date,6,4);
				$str_hour = substr($str_date,11,2);
				$str_min = substr($str_date,14,2);
				$str_sec = substr($str_date,17,2);
			//	print($str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_min.":".$str_sec);
				$str_return_date = "";
				if(is_numeric($str_day) && is_numeric($str_month) && is_numeric($str_year) && checkdate( $str_month, $str_day, $str_year )) {
					$str_return_date = date("Y-m-d H:i:s",mktime($str_hour,$str_min,$str_sec,$str_month,$str_day,$str_year));
				}
				return($str_return_date);
			}else{
				return $str_date;
			}
		}
	
		 function func_format_date_time($str_date)
		 {
			$str_year = substr($str_date,0,4);
			$str_month = substr($str_date,5,2);
			$str_day = substr($str_date,8,2);
			$str_hour = substr($str_date,11,2);
			$str_hour = $str_hour - 5;
			$str_minute = substr($str_date,14,2);
			$str_second = substr($str_date,17,2);
			$str_return_date = "";
			if(is_numeric($str_day) && is_numeric($str_month) && is_numeric($str_year) && checkdate( $str_month, $str_day, $str_year )) {
				$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
			} else {
				return $str_return_date;
			}
			$str_year = substr($str_return_date,0,4);
			$str_month = substr($str_return_date,5,2);
			$str_day = substr($str_return_date,8,2);
			$str_hour = substr($str_return_date,11,2);
			$str_minute = substr($str_return_date,14,2);
			$str_second = substr($str_return_date,17,2);
			$str_return_date = $str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_minute.":".$str_second;
			return ($str_return_date);
		 }
	
		 function func_get_current_date()
		{
			$str_time_difference = date("O");
			$str_sign = substr($str_time_difference,0,1);
			$str_hour_difference = substr($str_time_difference,1,2);
			$str_minute_difference = substr($str_time_difference,3,2);
			$str_year = date("Y");
			$str_month = date("m");
			$str_day = date("d");
			$str_hour = date("G");
			$str_minute = date("i");
			$str_second = date("s");
			if($str_sign == "+")
			{
				$str_hour = $str_hour - $str_hour_difference;
				$str_minute = $str_minute - $str_minute_difference;
			}
			else
			{
				$str_hour = $str_hour + $str_hour_difference;
				$str_minute = $str_minute + $str_minute_difference;
			}
			$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
			$str_year = substr($str_return_date,0,4);
			$str_month = substr($str_return_date,5,2);
			$str_day = substr($str_return_date,8,2);
			$str_hour = substr($str_return_date,11,2);
			$str_minute = substr($str_return_date,14,2);
			$str_second = substr($str_return_date,17,2);
			$str_current_date_time = $str_year."-".$str_month."-".$str_day." ".$str_hour.":".$str_minute.":".$str_second;
			$str_current_date_time = func_format_date($str_current_date_time);
			return($str_current_date_time);
		}
		  function func_format_date($str_date)
		 {
			$str_year = substr($str_date,0,4);
			$str_month = substr($str_date,5,2);
			$str_day = substr($str_date,8,2);
			$str_hour = substr($str_date,11,2);
			$str_hour = $str_hour - 5;
			$str_minute = substr($str_date,14,2);
			$str_second = substr($str_date,17,2);
			$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
	
			$str_year = substr($str_return_date,0,4);
			$str_month = substr($str_return_date,5,2);
			$str_day = substr($str_return_date,8,2);
			$str_hour = substr($str_return_date,11,2);
			$str_minute = substr($str_return_date,14,2);
			$str_second = substr($str_return_date,17,2);
			$str_return_date = $str_year."-".$str_month."-".$str_day;
			return ($str_return_date);
		 }
	
		 function func_show_format_date_time($str_date)
		 {
			$str_year = substr($str_date,0,4);
			$str_month = substr($str_date,5,2);
			$str_day = substr($str_date,8,2);
			$str_hour = substr($str_date,11,2);
			$str_minute = substr($str_date,14,2);
			$str_second = substr($str_date,17,2);
			$str_return_date = date("Y m d H:i:s",mktime($str_hour,$str_minute,$str_second,$str_month,$str_day,$str_year));
	
			$str_year = substr($str_return_date,0,4);
			$str_month = substr($str_return_date,5,2);
			$str_day = substr($str_return_date,8,2);
			$str_hour = substr($str_return_date,11,2);
			$str_minute = substr($str_return_date,14,2);

			$str_second = substr($str_return_date,17,2);
			$str_return_date = $str_month."-".$str_day."-".$str_year." ".$str_hour.":".$str_minute.":".$str_second;
			return ($str_return_date);
		 }
	
	
		//******************** Function for getting the value of a field if query is passed***************
		//**********************************************************************************************
		function funcGetValueByQuery($show_sql,$cnnConnection)
		{
			$strReturn = "";
			if(!($rstSelect = sql_query_read($show_sql)))
			{
				print("Can not execute query");
				exit();
			}
			if(mysql_num_rows($rstSelect)>0)
			{
				$strReturn = mysql_result($rstSelect,0,0);
			}
			return $strReturn;
		}
	
	 //function to get the value of a field if id is passed
	 function func_get_value_of_field($cnn_connection,$str_table,$str_get_field,$str_compare_field,$str_value)
	 {
		if(!$str_value) return false;
		$qry_select = "SELECT ".$str_get_field." FROM ".$str_table." WHERE ".$str_compare_field." = '".$str_value."'";
		$rst_select = sql_query_read($qry_select);
		$str_return_value = "";
		if(mysql_num_rows($rst_select)>0)
		{
			$str_return_value = mysql_result($rst_select,0,0);
		}
		return($str_return_value );
	 }
	
	
	function func_fill_year($i_year,$ret = false)
	{
		$str = "";
		for($i_loop=2003;$i_loop<$i_year+10;$i_loop++)
			if($i_year == $i_loop)
				$str .= "<option value='".$i_loop."' selected>".$i_loop."</option>";
			else
				$str .= "<option value='".$i_loop."'>".$i_loop."</option>";
		if($ret)
			return $str;
		print($str);
	}
	
	function func_fill_month($i_month,$ret = false)
	{
		$str = "";
		for($i_loop=1;$i_loop<13;$i_loop++)
			if($i_month == $i_loop)
				$str .= "<option value='".$i_loop."' selected>".date("F",mktime(0,0,0,$i_loop,1,2000))."</option>";
			else
				$str .= "<option value='".$i_loop."'>".date("F",mktime(0,0,0,$i_loop,1,2000))."</option>";
		if($ret)
			return $str;
		print($str);

	}
	
	function func_fill_day($i_day,$num=32,$ret = false,$months=false)
	{
		$str = "";
		if($months)
		{
			for($i_loop=30;$i_loop<=$num;$i_loop+=30)
				$str .= "<option value='".$i_loop."' ".($i_day == $i_loop?"selected":"").">".intval($i_loop/30)." Months</option>";
		}
		for($i_loop=1;$i_loop<=$num;$i_loop++)
			$str .= "<option value='".$i_loop."' ".($i_day == $i_loop?"selected":"").">".$i_loop."</option>";
		if($ret)
			return $str;
		print($str);
	}
	

	
		 //******************	Function for filling a combo with result from a query ***************
		 //******************************************************************************************
		function func_fill_combo_conditionally($str_qry,$str_selected_value,$cnn_connection="")
		{
			print get_fill_combo_conditionally($str_qry,$str_selected_value);
		}
		function get_fill_combo_conditionally($str_qry,$str_selected_value, $cnn_connection="", $extrafield='class')
		{
			$out = "";
			$rst_select = sql_query_read($str_qry);
			if(mysql_num_rows($rst_select)>0)
			{
				for($i_loop=0;$i_loop<mysql_num_rows($rst_select);$i_loop++)
				{
					$vals = array();
					$keys = array();
					$option = mysql_fetch_assoc($rst_select);
					foreach($option as $key=>$val)
					{$vals[]=$val;$keys[]=$key;}
					if(!$keys[2]) $keys[2]='class';
					if(!$vals[1]) $vals[1]=$vals[0];
					if($vals[2]) $str_add_class = " ".$keys[2]."='".str_replace("'","`",$vals[2])."'";
					else $str_add_class = "";
					if($vals[0] || $vals[0]=="0")
						$out .="<option $str_add_class value='".$vals[0]."' ".($str_selected_value == $vals[0]?"selected":"").">".$vals[1]."</option>";
				}
			}
			return $out;
		}
	
		 //******************	Function for calculating the total call duration for found or unfound calls ***************

	
	function func_get_country_select($cur,$showBlacklist=false)
	{
		
		if(!$cur) $cur='US';
		$blacklist_sql = " AND `co_blacklisted`=0 ";
		if($showBlacklist) $blacklist_sql = "";
		$sql = "SELECT * FROM `cs_country` WHERE 1 $blacklist_sql order by 'co_full' ASC";
		$result = sql_query_read($sql);
		$list="<option value=''>- - -Select- - -</option>\n";
		while($cntry=mysql_fetch_assoc($result))
		{
			$selected = (($cntry['co_ISO']==$cur || $cntry['co_full']==$cur)?" selected":"");
			$list.="<option value='".$cntry['co_ISO']."' $selected>".$cntry['co_full']."</option>\n";
		}
	 	return $list;
	}
	
	function func_get_country($cur,$field="")
	{
		
		$sql = "SELECT * FROM `cs_country` Where `co_ISO` = '$cur' OR `co_full` = '$cur' OR `co_3char` = '$cur' OR `co_3dig` = '$cur'";
		$result = sql_query_read($sql);
		$cntry=mysql_fetch_assoc($result);
		if($field!="") return $cntry[$field];
		return $cntry;
	}
	
	
	function func_get_state($cur,$field="")
	{
		$sql = "SELECT * FROM `cs_states` Where `st_full` = '$cur' OR `st_abbrev` = '$cur' ";
		$result = sql_query_read($sql);
		if (!mysql_num_rows($result)) return $cur;
		$state=mysql_fetch_assoc($result);
		if($field!="") return $state[$field];
		return $state;
	}
	
	function func_get_state_select($cur)
	{
		
		$sql = "SELECT * FROM `cs_states` order by 'st_full' ASC";
		$result = sql_query_read($sql);
		$list="<option value='' >- - -Select- - -</option>\n";
		while($state=mysql_fetch_assoc($result))
		{
			$selected = (($state['st_abbrev']==$cur || $state['st_full']==$cur)?" selected":"");
			$list.="<option value='".$state['st_abbrev']."' $selected>".$state['st_full']."</option>\n";
		}
	 	return $list;
	}
	
	function func_get_bank_select($cur)
	{
		
		$hide_list = '/(sign up|provided|temporarily|this week|personal account|alphebetical|[?!]|etelegate|select|other)/i';
		$sql = "(SELECT distinct `company_bank` as bank 
FROM `cs_companydetails`)
UNION
(SELECT distinct `reseller_bankname` as bank 
FROM `cs_resellerdetails` )
ORDER BY bank
";
		$result = sql_query_read($sql);
		$list="<option value='select' >- - -Select- - -</option>\n";
		while($bank=mysql_fetch_assoc($result))
		{
			$selected = (($bank['bank']==$cur)?" selected":"");
			if(!preg_match($hide_list,$bank['bank'])) $list.="<option value='".$bank['bank']."' $selected>". ucwords($bank['bank'])."</option>\n";
		}
	 	return $list;
	}

	
	
	
	function func_check_isnumber($strnum)
	{
		//returns 1 if valid number (only numeric string), 0 if not
		if (ereg('^[[:digit:]]+$', $strnum))
			return 1;
		else
			return 0;
	}
	
	//returns 1 if valid number (only numeric string including dot val), 0 if not
	function func_check_isnumberdot($strnum)
	{
	   $b_return = true;
	   for ($i=0;$i<strlen($strnum);$i++)
	   {
		   $ascii_code=ord($strnum[$i]);
		  if (($ascii_code >=48 && $ascii_code <=57) || $ascii_code ==46 || $ascii_code ==32 ) {
		   } else {
			  $b_return = false;
		  }
	   }
			return $b_return;
	}
	
	
	
	function func_checkUsernameExistInAnyTable($UserName,$cnnConnection)
	{
		$str_return=0;
		// in company details table
		$qry_select = "Select userId from cs_companydetails where username='$UserName'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
		// in merchant user table
		$qry_select = "Select id from cs_customerserviceusers where username='$UserName'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
		// in admin table
		$qry_select = "Select userid from cs_login where username='$UserName'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
		//in callcenter user table
		$qry_select = "Select cc_usersid from cs_callcenterusers where user_name='$UserName'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
	
	
		//in reseller details table
	
		$qrySelect = "select reseller_id from cs_resellerdetails where reseller_username ='$UserName'";
		$rstSelect = sql_query_read($qrySelect);
		if ( mysql_num_rows($rstSelect) > 0 ) {
			$str_return=1;
			return $str_return;
		}
	
		return $str_return;
	}
	
	function func_get_merchant_name($str_merchant_type) {

		return $str_merchant_name;
	}
	

	
	 //function for filling combo box
		 function func_fill_combo($qry,$cnn_connection,$i_select_id) 
		 {
			$rst = sql_query_read($qry);
			if(mysql_num_rows($rst)>0)
			{
				for($i_loop=0;$i_loop<mysql_num_rows($rst);$i_loop++)
				{
					$str_value = mysql_result($rst,$i_loop,0);
					$str_text = func_htmlencode(mysql_result($rst,$i_loop,1));
					if($str_value == $i_select_id)
					{
						echo("<option value='".$str_value."' selected>".$str_text."</option>");
					}
					else
					{
						echo("<option value='".$str_value."'>".$str_text."</option>");
					}
				}
			}
		 }
	//to check existing email
	function func_checkEmailExistInAnyTable($Email,$cnnConnection)
	{
		$str_return=0;
	
		// in company details table
		$qry_select = "Select email from cs_companydetails where email='$Email'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
	
	
		//in reseller details table
	
		$qrySelect = "select reseller_email  from cs_resellerdetails where reseller_email  ='$Email'";
		$rstSelect = sql_query_read($qrySelect);
		if ( mysql_num_rows($rstSelect) > 0 ) {
			$str_return=1;
			return $str_return;
		}
	
		return $str_return;
	}
	//to check existing companyname
	function func_checkCompanynameExistInAnyTable($CompanyName,$cnnConnection)
	{
		$str_return=0;
	
		// in company details table
		$qry_select = "Select companyname from cs_companydetails where companyname='$CompanyName'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
	
		//in callcenter user table
		$qry_select = "Select comany_name from cs_callcenterusers where comany_name='$CompanyName'";
		$rst_select = sql_query_read($qry_select);
		if (mysql_num_rows($rst_select)>0)
		{
			$str_return=1;
			return $str_return;
		}
	
	
		//in reseller details table
	
		$qrySelect = "select reseller_companyname  from cs_resellerdetails where reseller_companyname  ='$CompanyName'";
		$rstSelect = sql_query_read($qrySelect);
		if ( mysql_num_rows($rstSelect) > 0 ) {
			$str_return=1;
			return $str_return;
		}
	
		return $str_return;
	}

	function exec_refund_request($transID,$service_notes="Administrator Refund",$customer_notes="")
	{
		$trans = new transaction_class(false);
		$trans->pull_transaction($transID);
		$error_msg = $trans->process_refund_request(array("actor"=>$service_notes,'notes'=>"$customer_notes"));
		return $error_msg['status'];
		die();

		$error_msg = "Refund Request Created Successfully";
		
		$sql="SELECT td.`transactionId`, td.`reference_number`, cd.`companyname`,cd.`username`,
					cd.`password`,cd.`ReferenceNumber`, cd.`email`, td.`email` as customer_email, `note_id`,cs_URL, name, surname
				
				FROM `cs_transactiondetails` AS td
				LEFT JOIN `cs_callnotes` AS cn ON cn.`transaction_id` = td.`transactionId` AND cn.cn_type = 'refundrequest'
				LEFT JOIN `cs_companydetails` AS cd ON td.`userId` = cd.`userId`  
				LEFT JOIN `cs_company_sites` AS cs ON td.td_site_ID = cs.cs_ID 
				Where  `transactionId` = '$transID'";
		$result=sql_query_read($sql);
		if (mysql_num_rows($result)==0) return "Error: Transaction $transID Not Found";
		$statusInfo = mysql_fetch_assoc($result);
		if(!$statusInfo['note_id'])
		{
			$sql="REPLACE INTO `cs_callnotes` (`transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type` )
			VALUES ( '$transID', NOW() , '$service_notes', '', '$customer_notes', '' , '', '', '', '', '', 'refundrequest');";
			$qry_callnotes = sql_query_write($sql) or dieLog("Cannot execute query ");
			
			$data['companyname'] = $statusInfo['companyname'];
			$data['Reference_ID'] = $statusInfo['ReferenceNumber'];
			$data['reference_number'] = $statusInfo['reference_number'];
			$data['username'] = $statusInfo['username'];
			$data['password'] = $statusInfo['password'];
			$data['cancel_reference_number'] = $statusInfo['ReferenceNumber'];
			$data['email'] = $statusInfo['email'];
			$data['reason'] = $service_notes.": ".$customer_notes;
			$data["gateway_select"] = $statusInfo['gateway_id'];
			$data['site_URL'] = $statusInfo['cs_URL'];
			$data['full_name'] = $statusInfo['name']." ".$statusInfo['surname'];
			send_email_template('merchant_refund_request_notification_email',$data);
			
			$data['email'] = $statusInfo['customer_email'];
			send_email_template('customer_refund_confirmation',$data);

		}
		else $error_msg = "Refund Request Already Exists";
		return $error_msg;
	}
	//these will be deprecated based on using random salts for two new functions listed below...burrito
	function etelEnc($string) {
		$key = md5("23l4k23jsjd0f9=");
		$result = '';
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result.=$char;
		}

		return base64_encode($result);
	}

	function etelDec($string) {
		$key = md5("23l4k23jsjd0f9=");
		$result = '';
		$string = base64_decode($string);
		
		for($i=0; $i<strlen($string); $i++) {
			$char = substr($string, $i, 1);
			$keychar = substr($key, ($i % strlen($key))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result.=$char;
		}
		
		return $result;
	}
	// end deprecated functions...burrito

	
	function func_get_enum_data($table,$field)
	{
		
		$sql = "SHOW COLUMNS FROM `$table` like '$field'";
		$result=sql_query_read($sql) or dieLog(mysql_error()." ~ $sql");
		$list = mysql_fetch_assoc($result);
		$datastr = str_replace("enum(","",$list['Type']);
		$datastr = str_replace(")","",$datastr);
		$datastr = str_replace("'","",$datastr);
		$enum_list = split(',',$datastr);
		return($enum_list);

	}	
	
	function func_get_enum_values($table,$field,$selectedVal=false,$show=false,$showvalue=false)
	{
		$enum_list = func_get_enum_data($table,$field);
		if($show)$options .= "<option value='$showvalue' class='$show' >$show</option>\n";
		foreach($enum_list as $enum)
		$options .= "<option value='$enum' class='$enum' ".($selectedVal==$enum?"selected":"").">".ucwords(str_replace("_"," ",$enum))."</option>\n";
		return $options;
	}	
	
	function func_get_enum_radio($table,$field,$name='',$selectedVal=false)
	{
		$enum_list = func_get_enum_data($table,$field);
		if(!$name) $name = $field;
		foreach($enum_list as $enum)
		$options .= ucfirst($enum).": <input type='radio' value='$enum' name='$name' ".($selectedVal==$enum?"checked":"")." >\n ";
		return $options;
	}	
	
	
	function dieLog($val="",$msg=false,$report=true)
	{
		global $etel_debug_mode;
		global $smarty;
		global $companyInfo;
		global $adminInfo;
		if(!$msg) $msg = "You have reached a page that is currently not available. Please try again later.";
			
		$debug_array = debug_backtrace ();
		$debug_info= "\nUser: ".$adminInfo['username'].$companyInfo['username']."\n";
		foreach($debug_array as $lv=>$data)
			foreach($data as $key=>$txt)
				if(is_array($txt))
					foreach($txt as $key2=>$txt2)
						$debug_info.= " $key.$key2: ".substr(print_r($txt2,true),0,100)." \n";
				else
					$debug_info.= $key.": ".substr(print_r($txt,true),0,100)." \n";
				
		$val = "Error found: ".$debug_info." ~ ".$val;
		if($etel_debug_mode) $msg.="<BR>".$val;
		doTable($msg,"Page Unavailable",NULL,false,true,true);
		if($report) toLog('erroralert','misc',$val,-1);
		if(file_exists('includes/footer.php') && $smarty) 
		{
			include('includes/footer.php');
		}
		exit();

	}
	// below function moved by Burrito from testfunction.php (4-11-2006)
	function func_update_single_field($tablename,$fieldname,$fieldvalue,$cnn,$comparefield,$comparefieldvalue,$cnn_cs=false)
	{
		$sql = "update $tablename set $fieldname='$fieldvalue' where $comparefield=$comparefieldvalue";
		if(!sql_query_write($sql,1))
		{
			//echo $qryUpdate;
			dieLog(mysql_error()." $sql");
		}
	}
	
	//to find card currency it checks companydetails_ext first if not found takes the processing curreny 
//if these 2 are not found it assumes euro fo mastercard and usd for visa
function func_get_cardcurrency($cardtype,$company_id,$connetion)
{	$currency = "";$str_field="";
	if($cardtype=='Master')	{
	$str_field='processingcurrency_master'; 
	}
	elseif($cardtype=='Visa'){
	$str_field='processingcurrency_visa'; 	
	}
	else return "USD*";
	  $qry_currecy="select $str_field from cs_companydetails_ext  where userId ='$company_id'";
	
	if(!$rst_currency=sql_query_read($qry_currecy,$connetion))
	{
		print("Cannot execute select query");
	}
	else
	{
		 $rst_processcurrency=mysql_fetch_array($rst_currency);
			$currency =$rst_processcurrency[0];
		
		if($currency=="")
		{			
				if($cardtype=='Master')	{
					
					$currency='EURO'; 
				}
				else
				{
					$currency='USD';
					 	
				}
			} else if ($currency == "EUR") {
				$currency = "EURO";
			}
		
		
		
	}
	if ($currency == "EUR") {
		$currency = "EURO";
		}
	//exit();
	return $currency;
}
// function to generate a value to fetch a row for cross-sales based on current value of total hits for current merchant...burrito
function grab_cross_hits($amt)
{
	return $amt;
}
?>
