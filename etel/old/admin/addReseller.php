<?php 
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,home.php,cheque.php,creditcard.php,report.php,reportBottom.php, reportBottomSummary.php,companyEdit.php
//    				config.php,useraccount.php,viewCompany.php,topheader.php,ViewreportPage.php,addcheque.php,blank.php,administration_blank.php,virtualterminal.php,creditcardfb.php,negativedatabase.php
//					viewreportpage_negative.php,batchuploads.php,voicesystem.php,voicesystemreport.php,voicesystemdetails.php,companyAdd.php,companies_blank.php
//					modifycompany.php,companyuser.php,orderemail.php,enquires.php,report_custom.php,service_users.php,export.php,ledger.php
//					labels.php,printemailforms.php,maileditor.php,shipping.php,shippingdetails.php,logout.php
// addReseller.php:	This admin page functions for adding  the company user. 
include("includes/sessioncheck.php");

if(!$headerInclude) $headerInclude="reseller";
include("includes/header.php");
	beginTable();
?>

<table border="0" cellpadding="0" cellspacing="1" height="339" width="550">
                          <tbody>
                            <tr>
                              <td bgcolor="#a9a792" height="6"></td>
                            </tr>
                            <tr>
                              <td class="rs_fbd" height="330">
                                <table align="center" border="0" cellpadding="0" cellspacing="0" height="100" width="100%">
                                  <tbody>
                                    <tr>
                                      <td align="center" height="70" valign="center" width="50%"><font face="verdana" size="1">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                          <tbody>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Company name:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="companyname" style="font-size: 10px; width: 200px; font-family: arial;"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="<?=$_POST['companyname']?>" maxlength="250" src="req"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Contact name:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="contactname" style="font-size: 10px; width: 200px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="<?=$_POST['contactname']?>" maxlength="250" src="req"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">User name:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="username" style="font-size: 10px; width: 150px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="<?=$_POST['username']?>" maxlength="50" src="req"></td>
                                            </tr>
                                            <!--  <tr> 
                              <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Password:&nbsp;</font></td>
                              <td align="left" valign="center" height="30" width="50%"><input name="password" type="text" style="font-family:arial;font-size:10px;width:150px" value="" maxlength="50"></td>
                            </tr>
                            <tr> 
                              <td align="right" valign="center" height="30" width="50%"><font face="verdana" size="1">Confirm password:&nbsp;</font></td>
                              
                        <td align="left" valign="center" height="30" width="50%"><input name="repassword" type="text" style="font-family:arial;font-size:10px;width:150px" ></td>
                            </tr>
							<tr> -->
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Email address:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="email"  id="email" style="font-size: 10px; width: 200px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="<?=$_POST['email']?>" src="email"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Confirm email address:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input style="font-size: 10px; width: 200px; font-family: arial;" name="confirmemail" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" src="confirm|email"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">New merchant applications monthly:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><select style="font-size: 10px; width: 70px; font-family: arial;" name="merchantmonthly" id="merchantmonthly" title="reqmenu"  onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)">
                                                  <option value="select" selected>Select</option>
                                                  <option value="1-5">1-5</option>
                                                  <option value="5-10">5-10</option>
                                                  <option value="10-25">10-25</option>
                                                  <option value="25-50">25-50</option>
                                                  <option value="50-100">50-100</option>
                                                  <option value="100+">100+</option>
                                                </select>
                                                  <script language="javascript">
									  document.getElementById('merchantmonthly').value = '<?=$_POST['merchantmonthly']?>';
									  </script>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">Phone number:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="phone" style="font-size: 10px; width: 120px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="<?=$_POST['phone']?>" maxlength="25" src="phone"></td>
                                            </tr>
                                            <tr>
                                              <td class="bodytext" align="right" height="30" valign="center" width="27%">URL:&nbsp;</td>
                                              <td align="left" height="30" valign="center" width="73%"><input name="url" style="font-size: 10px; width: 200px; font-family: arial;" onFocus="updatevalid(this);" onChange="updatevalid(this)" onKeyUp="updatevalid(this)" value="<?=$_POST['url']?>" src="url"></td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </font></td>
                                    </tr>
                                    <tr>
                                      <td align="center"><div align="center">
                                          <input class="Button" value="Submit" name="addcompany" type="submit">
&nbsp;&nbsp;&nbsp; </div></td>
                                    </tr>
                                  </tbody>
                              </table></td>
                            </tr>
                          </tbody>
                        </table>
						
<?php
	endTable("Create Reseller","addResellerFb.php");

include("includes/footer.php");
?>