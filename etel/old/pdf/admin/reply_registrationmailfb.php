<?php
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
require_once( '../includes/function.php');

include("includes/mailbody_replytemplate.php"); 
$headerInclude="adminemail";
include("includes/header.php");
 

$companytype = isset($HTTP_POST_VARS['companymode'])?$HTTP_POST_VARS['companymode']:"A";
$companytrans_type = isset($HTTP_POST_VARS['companytrans_type'])?quote_smart($HTTP_POST_VARS['companytrans_type']):"A";
$companyname = isset($HTTP_POST_VARS['companyname'])?$HTTP_POST_VARS['companyname']:"";
// $str_mail_send = isset($HTTP_POST_VARS["chk_sent"])?$HTTP_POST_VARS["chk_sent"]:"0";
$i_mail_id = isset($HTTP_POST_VARS["hid_id"])?$HTTP_POST_VARS["hid_id"]:"";
$fromaddress =$_SESSION['gw_emails_sales'];
$mail_confirm = 0;
$subject = "Confirmation Letter";
if ($i_mail_id!="")
{
	if($companyname[0] =="A") {	
		if($companytype =="A") {
			if($companytrans_type =="A") {
				$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails order by email";
			} else {
				$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails where transaction_type ='$companytrans_type' ";
			}
		} else if($companytype =="AC") {
			if($companytrans_type =="A") {
				$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails where activeuser=1 ";
			} else {
				$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails where activeuser=1 and transaction_type ='$companytrans_type' ";
			}
		} else if($companytype =="NC") {
			if($companytrans_type =="A") {
				$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails where activeuser=0 ";
			} else {
				$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails where activeuser=0 and transaction_type ='$companytrans_type' ";
			}
		}
		if(!($run_sql_qry = mysql_query($select_mail_sql,$cnn_cs))) {			dieLog(mysql_errno().": ".mysql_error()."<BR>");

		} else {
			if(mysql_num_rows($run_sql_qry)>0)
			{
				while($show_select_val = mysql_fetch_row($run_sql_qry)) {
					$to_id=$show_select_val[0];
					$company_name=$show_select_val[1];
					$user_name =$show_select_val[2];
					$pass_word=$show_select_val[3];
					$mail_confirm = $show_val[4];
					$strMaildata =  func_getreplymailbody($company_name,$user_name,$pass_word);
				/*	if($strMailConfir !="") {
						$str_current_path = "csv/confirmationmail.htm";
						//	print $str_current_path;
						$create_file = fopen($str_current_path,'w');
						//	print $create_file;
						$file_content = $strMaildata;
						fwrite($create_file,$file_content);
						fclose($create_file);
					}  */
						$txtBody = $strMaildata;	
					if($mail_confirm ==1 ) {
						if(!func_send_mail($fromaddress,$to_id,$subject,$txtBody))
						{
							$msgtodisplay = "Sorry, some of the emails could not be sent.";
						}else {
							$msgtodisplay = "Mails successfully send to the users.";
						}
					}
				}
				$outhtml="y";
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();
			}
		}
	} else {
		foreach($companyname as $company_id)
		{	
			$select_mail_sql ="select distinct email,companyname,username,password,send_mail from cs_companydetails where userId=$company_id ";
			$show_sql =mysql_query($select_mail_sql,$cnn_cs);
			$to_id=mysql_result($show_sql,0,0);
			$company_name=mysql_result($show_sql,0,1);
			$user_name =mysql_result($show_sql,0,2);
			$pass_word=mysql_result($show_sql,0,3);
			$mail_confirm =mysql_result($show_sql,0,4);
			$strMaildata =  func_getreplymailbody($company_name,$user_name,$pass_word);
/*			$str_current_path = "csv/confirmationmail.htm";
			//	print $str_current_path;
			$create_file = fopen($str_current_path,'w');
			//	print $create_file;
			$file_content = $strMaildata;
			fwrite($create_file,$file_content);
			fclose($create_file);
*/			
			$txtBody = $strMaildata;
			if($mail_confirm==1) {
				if(!func_send_mail($fromaddress,$to_id,$subject,$txtBody))
				{
					$msgtodisplay = "Sorry, some of the emails could not be sent.";
				}else {
					$msgtodisplay = "Mails successfully send to the users.";
				}
			}
		}
		$outhtml="y";
		message($msgtodisplay,$outhtml,$headerInclude);									
		exit();
	}

/*	if ($i_mail_id==0)
	{
		$qry_query = "Insert into cs_registrationmail (mail_sent) values ($str_mail_send)";
	}
	else
	{
		$qry_query = "Update cs_registrationmail set mail_sent=$str_mail_send";
	}
	if(!mysql_query($qry_query,$cnn_cs))
	{
		print ("Cannot execute query <br>");
		print ($qry_query);
	}
*/
}
?>
	<table border="0" cellpadding="0" cellspacing="0" width="100%" height="60%">
    <tr>
   		 <td width="100%">&nbsp;</td>
  	</tr>
	</table>
<?php
	include("includes/footer.php");
?>