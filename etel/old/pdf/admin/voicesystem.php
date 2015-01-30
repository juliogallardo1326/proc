<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// voicesystem.php:	This admin page functions for uploading the voice system. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "voicesystem";
include 'includes/header.php';


require_once( '../includes/function.php');

$Transtype = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
$sessionAdmin =isset($HTTP_SESSION_VARS["sessionAdmin"])?$HTTP_SESSION_VARS["sessionAdmin"]:"";
$msgtodisplay = "";
$str_current_path = "";
$str_file_name = "";
if($sessionAdmin!=""){
 if ($Transtype == "Submit") {
	if($companytype =="AC") {
		$qrt_select_subqry = " activeuser=1";
	} else if($companytype =="NC") {
		$qrt_select_subqry = " activeuser=0";	
	} else if($companytype =="RE") {
		$qrt_select_subqry = " reseller_id <> ''";	
	} else if($companytype =="ET") {
		$qrt_select_subqry = " reseller_id is null";	
	} else {
		$qrt_select_subqry = "";	
	}
	if($companytrans_type =="A") {
		$qrt_select_merchant_qry = "";
	} else {
		if($qrt_select_subqry =="") {
			$qrt_select_merchant_qry = " transaction_type='$companytrans_type'";
		} else {
			$qrt_select_merchant_qry = " and transaction_type='$companytrans_type'";
		}
	}

	$str_total_query = "";
	if ($qrt_select_subqry != "" || $qrt_select_merchant_qry != "") {
		$str_total_query = "where 1 and $qrt_select_subqry $qrt_select_merchant_qry";
	} else {
		$str_total_query = "where 1 ";
	}
$qrt_select_company="select distinct userId,companyname from cs_companydetails $str_total_query order by companyname";
} else {
	$qrt_select_company ="select distinct userId,companyname from cs_companydetails where 1 order by companyname";
}
//print($qrt_select_company);

// $i_company_id = isset($HTTP_POST_VARS['company'])?quote_smart($HTTP_POST_VARS['company']):"";
$str_pass = isset($HTTP_POST_VARS['chk_pass'])?quote_smart($HTTP_POST_VARS['chk_pass']):"";
$str_nopass = isset($HTTP_POST_VARS['chk_nopass'])?quote_smart($HTTP_POST_VARS['chk_nopass']):"";
$str_two_nopass = isset($HTTP_POST_VARS['chk_two_nopass'])?quote_smart($HTTP_POST_VARS['chk_two_nopass']):"";
$i_company_id = (isset($HTTP_POST_VARS['companyname'])?($HTTP_POST_VARS['companyname']):"");

if($i_company_id){
$arr_voice_ids = "";
$arr_comments = "";
$str_report = "";
$is_valid_report = false;
$i_num = 0;
if(isset($_FILES['fle_attachment2'])) {
	extract($_FILES['fle_attachment2'], EXTR_PREFIX_ALL, 'uf2');
	if ($uf2_name != "" ) {
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		$str_current_path = $path_parts["dirname"];
		$str_file_name = $uf2_name;
		$str_current_path .= "\\".$str_file_name;
		if(filesize($uf2_tmp_name) != 0)
		{
			if(copy($uf2_tmp_name,$str_current_path))
			{
				$fd = fopen ($str_current_path,"r"); 
				$contents = fread ($fd,filesize($str_current_path)); 
				fclose ($fd); 
				$delimiter = "\n"; 
				$splitcontents = explode($delimiter, $contents); 
				$counter =0;
				$str_message = "";
				foreach ( $splitcontents as $str_line_data )
				{
					if(Trim($str_line_data) != "")
					{
						if($counter > 0)
						{
							//print($counter.". ".$str_line_data."<br>");
							$delimiter = "\t"; 
							$str_data = explode($delimiter, $str_line_data);
							$i_array_count = 0;
							for($iLoop = 0;$iLoop<count($str_data);$iLoop++)
							{
								//print(Trim($str_data[$iLoop])."<br>");							
								if(Trim($str_data[$iLoop])!="")
								{
									$arr_real[$i_array_count] = Trim($str_data[$iLoop]);
									$i_array_count++;
								}
							}
							if(count($arr_real) == 6)
							{
								$arr_voice_ids[$counter-1] = $arr_real[2];
								$arr_comments[$counter-1] = ereg_replace("'","\'",$arr_real[5]);
							}
						}
						$counter++;
					}
				}
				//print($counter);
			}
			else
			{
				$msgtodisplay = "Error in copying file";
			}
		}
		else
		{
			$msgtodisplay = "Invalid File";
		}
	}
 }
if($msgtodisplay != "")
{
	$outhtml="y";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();		
}

if(isset($_FILES['fle_attachment1'])) {
	extract($_FILES['fle_attachment1'], EXTR_PREFIX_ALL, 'uf1');
	if ($uf1_name != "" ) {
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		$str_current_path = $path_parts["dirname"];
		$str_file_name = $uf1_name;
		$str_current_path .= "\\csv\\".$str_file_name;
		if(filesize($uf1_tmp_name) != 0)
		{
			if(copy($uf1_tmp_name,$str_current_path))
			{
				//$msgtodisplay = "Pass Status updated Successfully";
				$i_upload_batch_id = func_get_next_upload_batch_id($cnn_cs);
				$str_current_date_time = func_get_current_date_time();
				$str_current_date = func_get_current_date();
				$fd = fopen ($str_current_path,"r"); 
				$contents = fread ($fd,filesize($str_current_path)); 
				fclose ($fd); 
				$delimiter = "\n"; 
				$splitcontents = explode($delimiter, $contents); 
				$counter =0;
				$str_message = "";
				$i_invalid_vid_count = 0;
				foreach ( $splitcontents as $str_line_data )
				{
					if($counter > 1)
					{
						$delimiter = " "; 
						$str_data = explode($delimiter, $str_line_data);
						$i_array_count = 0;
						$arr_real = "";
						for($iLoop = 0;$iLoop<count($str_data);$iLoop++)
						{
							if(Trim($str_data[$iLoop])!="")
							{
								$arr_real[$i_array_count] = Trim($str_data[$iLoop]);
								$i_array_count++;
							}
						}
						if(count($arr_real) == 8)
						{
							$str_comments = "";
							$str_updated = "N";
							$str_payment_type = "";
							$str_phone_number = $arr_real[0];
							if($str_phone_number == "none")
								continue;
							$str_voice_id = $arr_real[4];
							$str_status = $arr_real[6];
							$str_status_code = "";
							if($str_status == "Y")
								$str_status_code = "PA";
							else
								$str_status_code = "NP";
							if(strpos($str_phone_number,"---") === 0)
								break;
							$i_num++;
							if($str_pass != "Y" || $str_nopass != "Y")
							{
								if($str_pass == "Y")
								{
									if($str_status_code != "PA")
										continue;
								}
								if($str_nopass == "Y")
								{
									if($str_status_code != "NP")
										continue;
								}
							}
							$str_exists_query = "select checkorcard,billingDate from cs_transactiondetails where voiceAuthorizationno='$str_voice_id' and userid = $i_company_id";
							if(!($rstSelect = mysql_query($str_exists_query,$cnn_cs)))
							{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

							}
							if(mysql_num_rows($rstSelect) > 0)
							{
								$str_payment_type = mysql_result($rstSelect,0,0);
								$str_billing_date = mysql_result($rstSelect,0,1);
								$str_query = "update cs_transactiondetails set ";
								if($str_status_code == "PA")
								{
									if($str_payment_type == "C" && func_is_auto_approved($cnn_cs,$i_company_id))
									{
										if($str_billing_date == $str_current_date) 
										{
											$str_query .= "status = 'A',approvaldate = '$str_current_date',";
										}
									}
								}
								if($str_status_code == "NP")
								{
									if($arr_voice_ids != "")
									{
										for($i_loop1 = 0;$i_loop1<count($arr_voice_ids);$i_loop1++)
										{
											if($arr_voice_ids[$i_loop1] == $str_voice_id)
											{
												$str_comments = Trim($arr_comments[$i_loop1]);
												if(strpos($str_comments,"|") == 0)
												{
													$str_comments = substr($str_comments,1);
												}
												//print($str_comments."<br>");
												break;
											}
										}
									}

									/*if(func_is_rebilled($cnn_cs,$str_voice_id,$i_company_id) || func_is_nopass_2times($cnn_cs,$str_phone_number))
									{
										$str_status_code = "ND";
									}*/
									if($str_two_nopass == "Y")
									{
										if(func_is_nopass_2times($cnn_cs,$str_voice_id,$i_company_id))
										{
											$str_status_code = "ND";
										}
									}
									if($str_comments == "")
									{
										$str_comments = "Incomplete";
									}
									if($str_comments != "")
									{
										$str_query .= "pass_count=pass_count+1,nopasscomments='$str_comments',"; 
									}
								}
								$str_query .= "passStatus='$str_status_code'";
								$str_pass_status = "";
								if($str_status_code == "PA")
								{
									$str_pass_status = "Pass";
								}
								else if($str_status_code == "NP")
								{
									$str_pass_status = "No Pass";
								}
								else if($str_status_code == "ND")
								{
									$str_pass_status = "Negative Database";
								}
								$is_valid_report = true;
								/*$str_report .= "<tr>";
								$str_report .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana'>"; 
								$str_report .= $i_num."</font></td>";
								$str_report .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana'>"; 
								$str_report .= $str_phone_number."</font></td>";
								$str_report .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana'>"; 
								$str_report .= $str_voice_id."</font></td>";
								$str_report .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana'>"; 
								$str_report .= $str_pass_status."</font></td>";
								$str_report .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana'>"; 
								$str_report .= $str_comments."</font></td>";
								$str_report .= "</tr>";	*/ 

								$str_query .= " where voiceAuthorizationno='$str_voice_id' and userid = $i_company_id";
								//print($str_query."<br>");
								if(!mysql_query($str_query,$cnn_cs))
								{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

								}
								else
								{
									if(mysql_affected_rows($cnn_cs) > 0)
									{
										$str_updated = "Y";
									}
								}
							}
							else
							{
								$i_invalid_vid_count++;
								$str_report .= $i_invalid_vid_count.". ".$str_voice_id."<br>";
							}
							$str_upload_log_query = "insert into cs_voice_system_upload_log(upload_batch_id,user_id,voice_authorization_id,telephone_number,pass_status,comments,upload_date_time,updated) values($i_upload_batch_id,$i_company_id,'$str_voice_id','$str_phone_number','$str_status_code','$str_comments','$str_current_date_time','$str_updated')";
				
							if(!mysql_query($str_upload_log_query,$cnn_cs))
							{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

							}
							//print($str_phone_number." - ".$str_voice_id." - ".$str_status."<br>");
						}
					}
					$counter++;
				}
				//print($counter);
			}
			else
			{
				$msgtodisplay = "Error in copying file";
			}
		}
		else
		{
			$msgtodisplay = "Invalid File";
		}
	}
	else
	{
		$msgtodisplay = "No file uploaded";
	}
 }
 else
 {
	 $msgtodisplay = "No file uploaded";
 }
 if($msgtodisplay != "")
 {
	$outhtml="y";
	message($msgtodisplay,$outhtml,$headerInclude);									
	exit();
 }
 else
 {
	 $str_company_name = func_get_value_of_field($cnn_cs,"cs_companydetails","companyname","userId",$i_company_id);
	 if(!$is_valid_report)
	 {
		$msgtodisplay = "Uploaded report is not valid for $str_company_name";
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	 }
	 else if($str_report != "")
	 {
		$msgtodisplay = "The following Voice Authorization Id(s) were not found for $str_company_name:<br><br>";
		$msgtodisplay .= $str_report;
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	 }
	 else
	 {
		$msgtodisplay = "Voice System Reports Updated Successfully";
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	 }
 }
}

if(!($show_sql =mysql_query("select distinct userid,companyname from cs_companydetails order by companyname",$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

}

?>
<script language="javascript">
function validation(){
	if(document.Frmlogin.companyname.value=="") {
	 alert("Please select the company.");
	 return false;
	}
  if(document.Frmlogin.fle_attachment1.value==""){
    alert("Please upload Verification Results")
    document.Frmlogin.fle_attachment1.focus();
	return false;
  }
  if(!document.Frmlogin.chk_pass.checked && !document.Frmlogin.chk_nopass.checked){
    alert("Please click either 'Pass', 'No Pass' or both")
    document.Frmlogin.chk_pass.focus();
	return false;
  }
}
function Displaycompany(){
	if(document.Frmlogin.companymode.value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.Frmlogin.companymode.value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.Frmlogin.companymode.value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('all').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;
}

function Displaycompanytype() {
	document.Frmlogin.trans_type.value="Submit";
	document.Frmlogin.action = "voicesystem.php";
	document.Frmlogin.submit();
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table width="50%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Voice&nbsp;System</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">
  <form action="voicesystem.php" method="post" onsubmit="return validation()" name="Frmlogin"  enctype="multipart/form-data" >
	<input type="hidden" name="trans_type" value="">
<br>  <table  width="100%" cellspacing="0" cellpadding="0">
  <tr><td  width="100%" valign="center" align="center">     
      <table width="500" border="0" cellpadding="0">	  
	  
	  	<tr><td align="center" valign="middle" width="100%" colspan="2">
			<table  cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td  width="150" height="30" align="right" valign="middle"><font face="verdana" size="1">Company 
                 Type</font>&nbsp;&nbsp;&nbsp;</td>
				 <td  width="350" align="left"> 
                 <select name="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_mailcompanytype($companytype); ?>
					</select></td>
				</tr>
				<tr>
				<td  width="150" height="30" align="right" valign="middle"><font face="verdana" size="1">Merchant 
                 Type</font>&nbsp;&nbsp;&nbsp;</td>
				 <td  width="350" align="left" valign="middle"> 
                 <select name="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="Displaycompanytype();">
				<?php print func_select_companytrans_type($companytrans_type); ?>
					</select></td>
				</tr>
				<tr>
				<td valign="middle" align="right" height="30" width="150"><font face="verdana" size="1">Select Company&nbsp;&nbsp;&nbsp;</font></td>
				 <td align="left"  width="350" ><select id="all" name="companyname" style="font-family:verdana;font-size:10px;WIDTH: 210px">
				<?php func_select_company_from_query($qrt_select_company);
				?>
				</select>
				</td></tr>
			</table>
			</td></tr>
		  
<!--		  <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Select Company &nbsp;&nbsp;</font></td><td align="left" height="30" width="350"><select name="company" style="font-family:verdana;font-size:10px;WIDTH: 287px">
		  <option value="">Select Company</option>
		<?while($show_val = mysql_fetch_array($show_sql)) {
			  ?>
			<option value='<?=$show_val[0]?>'  ><?=$show_val[1]?></option>	  
        <? 
				  }
		?>
		  </select></td></tr>
-->		  
         <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Update Only &nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="350"><font face="verdana" size="1">Pass</font> <input type="checkbox" name="chk_pass" value="Y">&nbsp;&nbsp;&nbsp;<font face="verdana" size="1">No Pass</font> <input type="checkbox" name="chk_nopass" value="Y">
		</td>
	  </tr>
         <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Put into negative&nbsp;&nbsp;&nbsp; database if &nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="350"><font face="verdana" size="1">2 or more No Pass</font> &nbsp;<input type="checkbox" name="chk_two_nopass" value="Y">
		</td>
	  </tr>
         <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Verification Results &nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="350"><input type="file" name="fle_attachment1" size="30"></input>
		</td>
	  </tr>
         <tr><td align="right" valign="center" height="30" width="150"><font face="verdana" size="1">Responses &nbsp;&nbsp;</font></td>
		<td align="left" height="30" width="350"><input type="file" name="fle_attachment2" size="30"></input>
		</td>
	  </tr>
		  <tr><td align="center" valign="center" height="30" colspan="2"><input type="image" id="sendmail" SRC="<?=$tmpl_dir?>/images/submit.jpg"></input></td></tr>
	  </table>
  </td></tr></table></form>
	</td>
      </tr>
	<tr>
	<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
	<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
	<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
	</tr>
    </table>
    </td>
     </tr>
</table>
<?php
}
include 'includes/footer.php';

function func_get_next_upload_batch_id($cnn_connection)
{
	$i_batch_id = 0;
	$qry_select_no = "Select Max(upload_batch_id)+1 from cs_voice_system_upload_log" ;
	$rst_select_no = mysql_query($qry_select_no,$cnn_connection);
	if(mysql_num_rows($rst_select_no)>0)
	{
		$i_batch_id = mysql_result($rst_select_no,0,0);
	}
	if($i_batch_id == "")
		$i_batch_id = 1;
	return $i_batch_id;
}
?>