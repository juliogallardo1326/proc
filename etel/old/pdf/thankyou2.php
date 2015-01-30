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
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

?>

<script>
function show_page(op)
{
	if(op==7)
	{
		location.href='//www.etelegate.com';
	}
}
</script>


</head>

<body onLoad="MM_preloadImages('images_new/apply_now_r.gif','images_new/services_r.gif','images_new/contact_us_r.gif','images_new/demos_r.gif','images_new/home_r.gif')" >
<div align="center">
  <script language="javascript" src="sublink.js"></script>
  
<table width="60%" border="0" cellspacing="0" cellpadding="0">
  <tr>
     <td width="70%" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#008FEA"><table width="100%" border="0" cellspacing="1" cellpadding="0">
              <tr>
                <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="0">
                    <tr bgcolor="#E5F4FD"> 
                      <td width="60%" bgcolor="#E5F4FD"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                          <tr> 
                            <td width="60%"><div align="justify"> 
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <p align="center"><strong>Your information has 
                                  been sent successfully.</strong></p>
                                <p align="center">&nbsp;</p>
                                <p align="center"><strong>Thank you</strong></p>
                                <p align="center"><strong>www.Etelegate.com<br>
                                  <br>
                                  <br>
                                  <br>
                                  <br>
                                  <br>
                                  <br>
                                  <br>
                                  <br>
                                  <br>
                                  </strong></p>
                                </div>
                            </td>
                          </tr>
                      </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
</div>
<?php include 'includes/footer.php'; ?>
<div align="center"></div>
</body>
</html>