<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,profile_blank.php
//					VT_blank.php,creditcardfb.php,customerservice_blank.php,report_custom.php,callback.php,ledger.php
// companyEdit.php:	The  page used to modify the company profile. 
//include 'includes/sessioncheck.php';
session_start();

include 'includes/header.php';


$type = (isset($HTTP_GET_VARS['type'])?Trim($HTTP_GET_VARS['type']):"edit");
$headerInclude= $type == "testMode" ? "testMode" : "profile";	
include 'includes/topheader.php';
$invalidlogin = "";
$resellerLogin =isset($HTTP_SESSION_VARS["sessionReseller"])?$HTTP_SESSION_VARS["sessionReseller"]:"";
$qry_company="select * from cs_resellerdetails where reseller_id ='$resellerLogin'";
$show_sql =mysql_query($qry_company,$cnn_cs);	
$companyInfo = mysql_fetch_assoc($show_sql);

if($resellerLogin!=""){
	
function get_random_id($length)
{
  if($length>0) 
  { 
  $rand_id="";
   for($i=1; $i<=$length; $i++)
   {
   mt_srand((double)microtime() * 1000000);
   $num = mt_rand(1,31);
   $rand_id .= assign_random_value($num);
   }
  }
return $rand_id;
} 
function assign_random_value($num)
{
// accepts 1 - 31
  switch($num)
  {
    case "1":
     $rand_value = "A";
    break;
    case "2":
     $rand_value = "B";
    break;
    case "3":
     $rand_value = "C";
    break;
    case "4":
     $rand_value = "D";
    break;
    case "5":
     $rand_value = "E";
    break;
    case "6":
     $rand_value = "F";
    break;
    case "7":
     $rand_value = "G";
    break;
    case "8":
     $rand_value = "H";
    break;
    case "9":
     $rand_value = "J";
    break;
    case "10":
     $rand_value = "K";
    break;
    case "11":
     $rand_value = "L";
    break;
    case "12":
     $rand_value = "M";
    break;
    case "13":
     $rand_value = "N";
    break;
    case "14":
     $rand_value = "P";
    break;
    case "15":
     $rand_value = "R";
    break;
    case "16":
     $rand_value = "S";
    break;
    case "17":
     $rand_value = "T";
    break;
    case "18":
     $rand_value = "U";
    break;
    case "19":
     $rand_value = "V";
    break;
    case "20":
     $rand_value = "W";
    break;
    case "21":
     $rand_value = "X";
    break;
    case "22":
     $rand_value = "Y";
    break;
    case "23":
     $rand_value = "Z";
    break;
    case "24":
     $rand_value = "2";
    break;
    case "25":
     $rand_value = "3";
    break;
    case "26":
     $rand_value = "4";
    break;
    case "27":
     $rand_value = "5";
    break;
    case "28":
     $rand_value = "6";
    break;
    case "29":
     $rand_value = "7";
    break;
    case "30":
     $rand_value = "8";
    break;
    case "31":
     $rand_value = "9";
    break;
  }
return $rand_value;
}
include_once("../includes/function.php");
//include_once("includes/function1.php");
include_once("../includes/function2.php");
include_once("../admin/includes/mailbody_replytemplate.php");
$act = (isset($HTTP_POST_VARS["act"])?Trim($HTTP_POST_VARS["act"]):"");
$contact_help = (isset($HTTP_POST_VARS["contact_help"])?Trim($HTTP_POST_VARS["contact_help"]):"");
$contact_company_name = $companyInfo['reseller_companyname'];
$contact_name = $companyInfo['reseller_contactname'];
$contact_email = $companyInfo['reseller_email'];
$contact_phone = $companyInfo['reseller_phone'];
$contact_fax = $companyInfo['reseller_faxnumber'];
$questions_charge = (isset($HTTP_POST_VARS["questions_charge"])?Trim($HTTP_POST_VARS["questions_charge"]):"");
$return_val ="";
$msg="";
$message ="";
$username=$contact_name;
$email=$contact_email;
$companyname =$contact_company_name;
$how_about_us="";
$send_ecommercemail=1;
if($act=="mail")
{
	$subject="Bug Report - ".substr($questions_charge,0,70);
	$msg=$msg."
	<table width=60% border=\"1\" cellpadding=\"4\" cellspacing=\"0\" bordercolor=\"C0C0C0\" bgcolor=\"FFFFFF\">
  <tr> 
    <td width=\"100%\" valign=\"top\" align=\"center\" bgcolor=\"C0C0C0\" colspan=\"2\"><font size=\"6\" face=\"Verdana\"> 
      <center>
        <strong>Bug Report</strong> 
      </center>
      </font></td>
  </tr>
  <tr> 
    <td width=\"50%\" valign=\"top\" align=\"left\" colspan=\"2\"><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">
        <tr> 
          <td width=104><strong>Company Name</strong></td>
          <td>$contact_company_name</td>
        </tr>
        <tr> 
          <td width=104><strong>Contact Name</strong></td>
          <td>$contact_name</td>
        </tr>
        <tr> 
          <td width=104><strong>Contact Type</strong></td>
          <td>$contact_help</td>
        </tr>
				<tr> 
          <td width=104><strong>Contact Email</strong></td>
          <td>$contact_email</td>
        </tr>
				<tr> 
          <td><strong>Contact Phone</strong></td>
          <td>$contact_phone</td>
        </tr>
				<tr> 
          <td><strong>Contact Fax</strong></td>
          <td>$contact_fax</td>
        </tr>
        <tr> 
          <td valign='top'><strong>Question</strong></td>
          <td>$questions_charge</td>
        </tr>
      </table>
      <strong> </strong></td>
  </tr>
</table>";

$help_email="support@etelegate.com";
$help2_email="sales@etelegate.com";
			
    	$user_nameexist =func_checkUsernameExistInAnyTable($username,$cnn_cs);
		$user_emailexist=func_checkEmailExistInAnyTable($email,$cnn_cs);
		$user_companyexist=func_checkCompanynameExistInAnyTable($companyname,$cnn_cs);
					//echo "$email_message"."<br>";exit();

				$headers  = "MIME-Version: 1.0\r\n";
				$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
				$headers .= "To: <$help_email>\r\n";
				$headers .= "CC: <$help2_email>\r\n";
				$headers .= "From: $contact_name <$contact_email>\r\n";
				mail($help_email, $subject, $msg, $headers);
				
				echo "
					<script>
						location.href='thankyou2.php'
					</script>
					";
					exit();
}
}
?>
<script language="JavaScript" type="text/JavaScript">
<!--


function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
<script>
function show_page(op)
{
	if(op==6)
	{
		location.href='https://www.etelegate.net';
	}
}
</script>


</head>

<body onLoad="MM_preloadImages('images_new/apply_now_r.gif','images_new/services_r.gif','images_new/contact_us_r.gif','images_new/demos_r.gif','images_new/home_r.gif')" >
<script language="javascript" src="scripts/sublink.js"></script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    </td>
    <td width="5"><img src="images_new/transparent.gif" width="5" height="1"></td>
    <td width="70%" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#008FEA"><table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                    <tr bgcolor="#E5F4FD"> 
                      <td width="100%" bgcolor="#E5F4FD">
					  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                         <tr>
						 <td>
						 <table cellpadding = "0" cellspacing="0" border="0" width="100%">
						 <tr>
						 <td><strong> Bug Report </strong></td>
						 <td>&nbsp;</td>
						 </tr>
						 </table>					 
						 </td>
						 </tr>
                          <tr> 
                            <td> 
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr> 
                                    <td bgcolor="#FFFFFF">
									<table width="100%" border="0" cellspacing="1" cellpadding="3">
                                        <form name="contact_form" method="post" action="bugreport.php" onsubmit="return contact_validation();">
                                          <input type="hidden" name="act" value="mail">
                                          
										<tr bgcolor="#E5F4FD">
										 <td align="left" colspan="2"><strong>Technical Support  :&nbsp;</strong></td>
					 					  </tr>
                                          <tr bgcolor="#E5F4FD"> 
                                            <td colspan=2><?if($return_val==1){?> 
                                              <font color="ff0000"><?echo $message;?></font> <?}?>
                                            </td>
                                          </tr>
                                          <!--
                                          <tr bgcolor="#E5F4FD"> 
                                            <td><font color="<?if($err7==1){?>ff0000<?}?>">Contact 
                                              type</font>&nbsp;<font color="ff0000">*</font></td>
                                            <td> <select name="contact_help" onChange="show_page(this.value);">
                                                <option value="">- - - - Choose Your Type - - - -</option>
                                                <option value="New Merchants">New Merchants</option>
                                                <option value="Existing Merchants">Existing Merchants</option>
                                                <option value="Re-Sellers">Re-Sellers</option>
                                                <option value="Technical">Technical</option>
                                                <option value="Webmaster">Webmaster</option>
                                                <option value="Customer Service">Customer Service</option>
                                              </select></td>
                                          </tr>
                                          <tr bgcolor="#E5F4FD"> 
                                            <td><font color="<?if($err6==1){?>ff0000<?}?>">Company 
                                              Name</font></td>
                                            <td><input name="contact_company_name" type="text" value="<?echo $contact_company_name;?>"></td>
                                          </tr>
                                          <tr bgcolor="#E5F4FD"> 
                                            <td><font color="<?if($err1==1){?>ff0000<?}?>">Contact 
                                              Name</font>&nbsp;<font color="ff0000">*</font></td>
                                            <td><input name="contact_name" type="text" value="<?echo $contact_name;?>"></td>
                                          </tr>
                                          <tr bgcolor="#E5F4FD"> 
                                            <td><font color="<?if($err2==1){?>ff0000<?}?>">Contact 
                                              Email</font>&nbsp;<font color="ff0000">*</font></td>
                                            <td><input name="contact_email" type="text" value="<?echo $contact_email;?>"></td>
                                          </tr>
										  <tr bgcolor="#E5F4FD"> 
                                            <td><font color="<?if($err2==1){?>ff0000<?}?>">C</font><font color="<?if($err2==1){?>ff0000<?}?>">onfirm 
                                              Email</font>&nbsp;<font color="ff0000">*</font></td>
                                            <td><input name="contact_email_confirm" type="text" value="<?echo $contact_email_confirm;?>"></td>
                                          </tr>
                                          <tr bgcolor="#E5F4FD"> 
                                            <td><font color="<?if($err5==1){?>ff0000<?}?>">Contact 
                                              Phone</font></td>
                                            <td><input name="contact_phone" type="text"  value="<?echo $contact_phone;?>"></td>
                                          </tr>
                                          <tr bgcolor="#E5F4FD"> 
                                            <td>Contact Fax&nbsp;</td>
                                            <td><input name="contact_fax" type="text" value="<?echo $contact_fax;?>"></td>
                                          </tr>
										  -->
                                          <tr bgcolor="#E5F4FD"> 
                                            <td>Please Describe the bug here:</td>
                                            <td> <textarea name="questions_charge" rows="5" cols="35" value="<?echo $questions_charge;?>"><?echo $questions_charge;?></textarea> 
                                            </td>
                                          </tr>
                                          <tr align="left" valign="top" bgcolor="#E5F4FD"> 
                                            <td colspan=2><div align="center">
                                                <input type="image" src="../images_new/submit.gif" width="76" height="24">
                                              </div></td>
                                          </tr>
                                        </form>
                                      </table></td>
                                  </tr>
                               </table>
                            </td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="5"><img src="images_new/transparent.gif" width="5" height="1"></td>
  </tr>
</table>
<?php include 'includes/footer.php'; ?>
</body>
</html>