<?php
//******************************************************************//
//  This file is part of the Zerone-consulting development package. //
//  Copyright (C) Etelegate.com 2003-2004, All Rights Reserved.     //
//                                                                  //
//******************************************************************//
// Package:         Zerone-consulting
// Description:     Online Payment Gateway
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
// batchuploads.php:	This admin page functions for updating the company transaction details. 
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
$headerInclude = "transactions";
include 'includes/header.php';

require_once( '../includes/function.php');

$insert_data_status = array();
$company_id = isset($HTTP_POST_VARS['companyname'])?quote_smart($HTTP_POST_VARS['companyname']):"";
$trans_type = isset($HTTP_POST_VARS['trans_type'])?quote_smart($HTTP_POST_VARS['trans_type']):"";
if(isset($_FILES['fle_attachment'])) {
	extract($_FILES['fle_attachment'], EXTR_PREFIX_ALL, 'uf2');
	if ($uf2_name != "" ) {
		$svr = $_SERVER["PATH_TRANSLATED"];
		$path_parts = pathinfo($svr); 
		$str_current_path = $path_parts["dirname"];
		$str_file_name = $uf2_name;
		$str_current_path .= "\\csv\\".$str_file_name;
	if(filesize($uf2_tmp_name) != 0)
		{
			if(copy($uf2_tmp_name,$str_current_path))
			{
				$fd = fopen ($str_current_path,"r"); 
				$contents = fread ($fd,filesize($str_current_path)); 
				fclose ($fd); 
				$delimiter = "\n"; 
				$splitcontents = explode($delimiter, $contents);
				//print count($splitcontents);
				$counter =0;
				$str_message = "";
				if($splitcontents) {
?>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%" >
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
<table width="95%" border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
          <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Batch 
            Processing Report</span></td>
<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
</tr>
<tr>
<td class="lgnbd" colspan="5">

<?php				
			print("<br><table align=center width='98%'>");
			print"<tr bgcolor='#CCCCCC'><td class='cl1'><span class='subhd'>First Name</span></td><td class='cl1'><span class='subhd'>Last Name</span></td><td class='cl1'><span class='subhd'>Address</span></td><td class='cl1'><span class='subhd'>Email Address</span></td><td class='cl1'><span class='subhd'>Telephone Number</span></td><td class='cl1'><span class='subhd'>Voice Authorization #</span></td><td class='cl1'><span class='subhd'>Status</span></td></tr>";
				}
				foreach ( $splitcontents as $str_line_data )
				{
					if(Trim($str_line_data) != "")
					{
						if($counter > 0)
						{
							// print($counter.". ".$str_line_data."<br>");
							$delimiter = "\t"; 
							$str_data = explode($delimiter, $str_line_data);
							
							$i_array_count = 0;
							for($iLoop = 0;$iLoop<count($str_data);$iLoop++)
							{
								if(Trim($str_data[$iLoop])!="")
								{
									$arr_real[$i_array_count] = Trim($str_data[$iLoop]);
									$i_array_count++;
								}
								$array_transdetails = explode(",",$str_data[$iLoop]);
								func_insert_transactiondata($array_transdetails,$company_id,$trans_type,$cnn_cs);
								//print $insert_data_status."-return status";
							} 
						}
						$counter++;
					}
				}
				if($counter+1 == count($splitcontents)) {
					print("<tr><td colspan='7' align='center' height='30' valign='middle'><a href='javascript:window.history.back();'><img border=0 src='../images/back.jpg'></a></td></tr></table>");
				} 
			}
			else
			{
				$msgtodisplay = "Error in copying file";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();	
			}
		}
		else
		{
			$msgtodisplay = "Invalid File";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	
		}
	}
?>
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
</table><br>

<?php	
 }
 
 function func_insert_transactiondata($array_transdetails,$company_id_val,$trans_type,$cnn_connection) {
	$trans_insert_status="";
	if($trans_type =="Check") {
		if(count($array_transdetails)>0) {
			$authorisationno =$array_transdetails[16]; 
			$telephoneno = $array_transdetails[7];
			$trans_date_enter = "$array_transdetails[21]:00";
			$trans_date_enter = func_format_date_time($trans_date_enter);
			$qrt_insert_details = "insert into cs_transactiondetails (name,surname,address,city,state,zipcode,country,phonenumber,email,amount,checkorcard,CCnumber,checktype,accounttype,bankname,bankroutingcode,bankaccountnumber,voiceAuthorizationno,shippingTrackingno,socialSecurity,licensestate,driversLicense,transactionDate,billingDate,misc,userid,passStatus,pass_count,cancelstatus,status) 
								values('$array_transdetails[0]','$array_transdetails[1]','$array_transdetails[2]','$array_transdetails[3]','$array_transdetails[4]','$array_transdetails[5]','$array_transdetails[6]','$array_transdetails[7]','$array_transdetails[8]',$array_transdetails[9],'C','$array_transdetails[10]','$array_transdetails[11]','$array_transdetails[12]','$array_transdetails[13]','$array_transdetails[14]','$array_transdetails[15]','$array_transdetails[16]','$array_transdetails[17]','$array_transdetails[18]','$array_transdetails[19]','$array_transdetails[20]','$trans_date_enter','$array_transdetails[22]','$array_transdetails[23]',$company_id_val,'PE',0,'N','P')"; 
			//	 print $qrt_insert_details ."<br>";
		}
	} else {
		if(count($array_transdetails)>0) {
			$authorisationno =$array_transdetails[14]; 
			$telephoneno = $array_transdetails[7];
			$trans_date_enter = "$array_transdetails[19]:00";
			$trans_date_enter = func_format_date_time($trans_date_enter);
			$qrt_insert_details = "insert into cs_transactiondetails (name,surname,address,city,state,zipcode,country,phonenumber,email,amount,checkorcard,cardtype,CCnumber,cvv,validupto,voiceAuthorizationno,shippingTrackingno,socialSecurity,licensestate,driversLicense,transactionDate,billingDate,misc,userid,passStatus,pass_count,cancelstatus,status)
								  values('$array_transdetails[0]','$array_transdetails[1]','$array_transdetails[2]','$array_transdetails[3]','$array_transdetails[4]','$array_transdetails[5]','$array_transdetails[6]','$array_transdetails[7]','$array_transdetails[8]',$array_transdetails[9],'H','$array_transdetails[10]','$array_transdetails[11]','$array_transdetails[12]','$array_transdetails[13]','$array_transdetails[14]','$array_transdetails[15]','$array_transdetails[16]','$array_transdetails[17]','$array_transdetails[18]','$trans_date_enter','$array_transdetails[20]','$array_transdetails[21]',$company_id_val,'PE',0,'N','P')"; 
		 //	   print $qrt_insert_details ."<br>";
		}
	}
	$auth_status = func_isauthorisationno_check($authorisationno,$telephoneno,$company_id_val,$cnn_connection);
	if ($auth_status == "")
	{	
		if($authorisationno !="" || $telephoneno !="") {
			if(!$sql_insert_data = mysql_query($qrt_insert_details)) {
				$trans_insert_status = "failure";
			} else {
				$trans_insert_status = "success";
			}
		} else {
			$trans_insert_status = "success";
		}
	} else {
			$trans_insert_status = "failure";
	}
	print "<tr><td class='leftbottomright'>&nbsp;$array_transdetails[0]</td><td class='cl1'>&nbsp;$array_transdetails[2]</td><td class='cl1'>&nbsp;$array_transdetails[3]</td><td class='cl1'>&nbsp;$array_transdetails[8]</td><td class='cl1'>&nbsp;$array_transdetails[7]</td><td class='cl1'>&nbsp;$authorisationno</td><td class='cl1'>&nbsp;$trans_insert_status</td></tr>";
}
include("includes/footer.php");
?>
