<?php 
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
include 'includes/header.php';

require_once('includes/function.php');
$headerInclude= "blank";	
include 'includes/topheader.php';
$invalidlogin = "";
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

	if($sessionlogin!=""){
		$company = (isset($HTTP_POST_VARS['company'])?Trim($HTTP_POST_VARS['company']):"");
		$companyname = (isset($HTTP_POST_VARS['companyname'])?Trim($HTTP_POST_VARS['companyname']):"");
		$phonenumber = (isset($HTTP_POST_VARS['phonenumber'])?Trim($HTTP_POST_VARS['phonenumber']):"");
		$address = (isset($HTTP_POST_VARS['address'])?Trim($HTTP_POST_VARS['address']):"");
		$city = (isset($HTTP_POST_VARS['city'])?Trim($HTTP_POST_VARS['city']):"");
		$state = (isset($HTTP_POST_VARS['state'])?Trim($HTTP_POST_VARS['state']):"");
		$ostate = (isset($HTTP_POST_VARS['ostate'])?Trim($HTTP_POST_VARS['ostate']):"");
		$country = (isset($HTTP_POST_VARS['country'])?Trim($HTTP_POST_VARS['country']):"");
		$zipcode = (isset($HTTP_POST_VARS['zipcode'])?Trim($HTTP_POST_VARS['zipcode']):"");
		$email = (isset($HTTP_POST_VARS['email'])?Trim($HTTP_POST_VARS['email']):"");
		$userid = (isset($HTTP_POST_VARS['userid'])?Trim($HTTP_POST_VARS['userid']):"");
		$username = (isset($HTTP_POST_VARS['username'])?Trim($HTTP_POST_VARS['username']):"");
		//$cd_member_username = (isset($HTTP_POST_VARS['cd_member_username'])?Trim($HTTP_POST_VARS['cd_member_username']):"");
		//$cd_member_password = (isset($HTTP_POST_VARS['cd_member_password'])?Trim($HTTP_POST_VARS['cd_member_password']):"");
		
	
		$url1= (isset($HTTP_POST_VARS['url1'])?Trim($HTTP_POST_VARS['url1']):"");
		$url2= (isset($HTTP_POST_VARS['url2'])?Trim($HTTP_POST_VARS['url2']):"");
		$url3= (isset($HTTP_POST_VARS['url3'])?Trim($HTTP_POST_VARS['url3']):"");
		$volumesales= (isset($HTTP_POST_VARS['volume'])?Trim($HTTP_POST_VARS['volume']):"");
		$avgticket= (isset($HTTP_POST_VARS['avgticket'])?Trim($HTTP_POST_VARS['avgticket']):"");
		$chargebackper= (isset($HTTP_POST_VARS['chargeper'])?Trim($HTTP_POST_VARS['chargeper']):"");
		$transaction_type = (isset($HTTP_POST_VARS['rad_order_type'])?Trim($HTTP_POST_VARS['rad_order_type']):"");

		$preprocess= (isset($HTTP_POST_VARS['prepro'])?Trim($HTTP_POST_VARS['prepro']):"No");
		$recurbilling= (isset($HTTP_POST_VARS['rebill'])?Trim($HTTP_POST_VARS['rebill']):"No");
		$currentprocess= (isset($HTTP_POST_VARS['currpro'])?Trim($HTTP_POST_VARS['currpro']):"No");
		if($volumesales=="") 
			$volumesales=0;
		if($avgticket=="")
			$avgticket=0;
		if($chargebackper=="")
			$chargebackper=0;
		
		if($company)
		{
			if($state=="")
			{
				$state= $ostate;
			}
			
			$show_sql =mysql_query("update cs_companydetails set companyname='$companyname',phonenumber='$phonenumber',address='$address',city='$city',state='$state',ostate='$ostate',country='$country',zipcode='$zipcode',email='$email',volumenumber=$volumesales,avgticket=$avgticket,chargebackper=$chargebackper,preprocess='$preprocess',recurbilling='$recurbilling',currprocessing='$currentprocess',url1='$url1',url2='$url2',url3='$url3',transaction_type='$transaction_type' where userid=$userid",$cnn_cs);
			//echo("modify customerdetails set companyname='$companyname',phonenumber='$phonenumber',address='$address',email='$email' where userid=$userid");
			//echo mysql_errno().": ".mysql_error()."<BR>";
			$outhtml="y";
			$msgtodisplay="Merchant Details for '".$username."' has been modified";
?>
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
			<tr>
			<td width="83%" valign="top" align="center">&nbsp;
			<table border="0" cellpadding="0" cellspacing="0" width="50%" >
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
			<td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Message</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
			<form>
			<?php	
			if($outhtml=="y"){
			?>
			<table height='50%' width='90%' cellspacing='0' cellpadding='0'>
			<tr><td  width='100%'  align='center'><p><font face='verdana' size='3'></font></p>
			<table width='400' border='0' cellpadding='0' >
			<tr><td align='CENTER' valign='center' height='50' ><font face='verdana' size='1'><?php print $msgtodisplay; ?></font>
			</td></tr></table></td></tr>
			<tr><td height="50" valign="center" align="center">
			<a href="uploadDocuments.php"><img src="images/continue.gif" border="0"></img></a>
			</td></tr></table>
			<tr>
			<td width="1%"><img src="images/menubtmleft.gif"></td>
			<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
			<td width="1%" ><img src="images/menubtmright.gif"></td>
			</tr>
			</form>
			</td></tr>
			</table>
			</td></tr>
			</table>
			<?php
			include("includes/footer.php");
			}
			//message($msgtodisplay,$outhtml,$headerInclude);									
			exit();
		}		     
$show_sql =mysql_query("select *  from cs_companydetails where userid=$sessionlogin",$cnn_cs);	
?>
<script language="javascript">

function emailsubmit() {
document.Frmcompany.action="userBottom.php?sub=email";
document.Frmcompany.method="POST";
document.Frmcompany.submit();
}

function validation(){
  if(document.Frmcompany.companyname.value==""){
    alert("Please enter company name")
    document.Frmcompany.companyname.focus();
	return false;
  }
  if(document.Frmcompany.phonenumber.value==""){
    alert("Please enter phone number")
    document.Frmcompany.phonenumber.focus();
	return false;
  }
   if(document.Frmcompany.email.value==""){
    alert("Please enter email")
    document.Frmcompany.email.focus();
	return false;
  }
  if(document.Frmcompany.address.value==""){
    alert("Please enter address")
    document.Frmcompany.address.focus();
	return false;
  }
 
}
function validator(){
	if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
		document.Frmcompany.ostate.disabled= true;
		document.Frmcompany.ostate.value= "";
		document.Frmcompany.state.disabled = false;
	} else {
		document.Frmcompany.state.disabled = true;
		document.Frmcompany.state.value= "";
		document.Frmcompany.ostate.disabled= false;
	}
	return false;
}

</script>

<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  height="333">
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" height="303">
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="images/menucenterbg.gif" nowrap><img border="0" src="images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="images/menucenterbg.gif" ><span class="whitehd">Merchant Application</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="images/menutoprightbg.gif" ><img alt="" src="images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="images/menuright.gif" width="10" height="22"></td>
	</tr>

      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
           <form action="merchantApplication.php?" method="post" onsubmit="return validation()" name="Frmcompany">
		  <table height="100%" width="70%" cellspacing="0" cellpadding="0" ><tr><td align="left"><?=$invalidlogin?>
		<?
			  if($showval = mysql_fetch_array($show_sql)){ 
			  ?>
				 <input type="hidden" name="username" value="<?=$showval[1]?>"></input>
			  <table width="400" border="0" cellpadding="0"  height="100">
                      <input type="hidden" name="userid" value="<?=$showval[0]?>"></input>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><hr><font face="verdana" size="1"><b>Company Information</b></font><hr></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Company 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="100" name="companyname" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[3]?>"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">User 
                          Name &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><font face="verdana" size="1"><b>
                          <?=$showval[1]?>
                          </b></font></td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Address 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="address" value="<?=$showval[5]?>" style="font-family:arial;font-size:10px;width:240px"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">City 
                          &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="city" value="<?=$showval[6]?>" style="font-family:arial;font-size:10px;width:240px"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Country&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="country"  style="font-family:arial;font-size:10px;width:240px" onchange="return validator()">
						<?=func_get_country_select($showval[8]) ?>
                          </select> 
						  <script language="javascript">
					     	document.Frmcompany.country.value='<?=$showval[8]?>';	
						  </script> 
						</td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">State&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"> <select name="state"  style="font-family:arial;font-size:10px;width:240px">
							<?=func_get_state_select($showval[7]) ?>
						  </select> 
						</td>
                      </tr>
                      <input type="hidden" name="company" value="company"></input>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Other 
                          State&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="ostate" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[12]?>"></input>
                        </td>
                      </tr>
                      <script language="javascript">
				if(document.Frmcompany.country.options[document.Frmcompany.country.selectedIndex].text=="United States") {
					document.Frmcompany.ostate.disabled= true;
					document.Frmcompany.ostate.value= "";
					document.Frmcompany.state.disabled = false;
				} else {
					document.Frmcompany.state.disabled = true;
					document.Frmcompany.state.value= "";
					document.Frmcompany.ostate.disabled= false;
				}
			</script>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Zipcode&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" name="zipcode" value="<?=$showval[9]?>" style="font-family:arial;font-size:10px;width:140px"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Phone 
                          Number &nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="25" name="phonenumber" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[4]?>"></input>
                        </td>
                      </tr>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><hr><font face="verdana" size="1"><b>Web Site Information</b></font><hr></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">Email 
                          &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="100" name="email" style="font-family:arial;font-size:10px;width:240px" value="<?=$showval[10]?>"></input>
                        </td>
                      </tr>                      <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">URL 
                          &nbsp;&nbsp;</font></td>
                        <td align="left" height="50" width="50%"><input type="text" name="url1" value="<?=$showval[43]?>" style="font-family:arial;font-size:10px;width:230px">
                          <br>
                          <input type="text" name="url2" value="<?=$showval[44]?>" style="font-family:arial;font-size:10px;width:230px">
                          <br>
                          <input type="text" name="url3" value="<?=$showval[45]?>" style="font-family:arial;font-size:10px;width:230px"></td>
                      </tr>
                      
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><hr><font face="verdana" size="1"><b>Processing Information</b></font><hr></td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Projected monthly sales&nbsp;&nbsp; volume $&nbsp;&nbsp;</font></td>
                        <td align="left" valign="middle" height="30" width="50%"><input type="text" maxlength="100" name="volume" style="font-family:arial;font-size:10px;width:80px" value="<?=$showval[30]?>"></input>
                        </td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Average ticket&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="10" name="avgticket" style="font-family:arial;font-size:10px;width:80px" value="<?=$showval[38]?>"></input>
                        </td>
                      </tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Charge back %&nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><input type="text" maxlength="10" name="chargeper" style="font-family:arial;font-size:10px;width:80px" value="<?=$showval[39]?>"></input>
                        </td>
                      </tr>
					<tr>
                        <td align="left" valign="middle" height="30" width="50%"><font face="verdana" size="1">Merchant 
                          Type &nbsp;&nbsp;</font></td>
						  <td align="left" height="30"  width="50%" valign="middle"><select name="rad_order_type" style="font-family:arial;font-size:10px;width:100px">
							<option value="">Select</option>
							<option value="ecom">General Ecommerce</option>
							<option value="trvl">Travel</option>
							<option value="phrm">Pharmacy</option>
							<option value="game">Gaming</option>
							<option value="adlt">Adult</option>
							<option value="tele">Telemarketing</option>
							<option value="pmtg">Gateway</option>
							<!--option value="crds">Card swipe</option-->
						  </select></td>
						<script language="javascript">
							 document.Frmcompany.rad_order_type.value='<?=$showval[27]?>';	
						</script>
                      </tr>	
					  <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Previous processing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="prepro" style="font-family:verdana;font-size:10px;width:50px">
						<option value="">&nbsp;</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
						</select>
                        </td>
						 <script language="javascript">
							 document.Frmcompany.prepro.value='<?=$showval[40]?>';
						</script> 
                      </tr>	
					  <tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Recurring billing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="rebill" style="font-family:verdana;font-size:10px;width:50px">
						<option value="">&nbsp;</option>
						<option value="Yes">Yes</option>
						<option value="No">No</option>
						</select></input>
                        </td>
 						<script language="javascript">
							 document.Frmcompany.rebill.value='<?=$showval[41]?>';
						</script>                       
						</tr>
						<tr>
                        <td align="left" valign="center" height="30" width="50%"><font face="verdana" size="1">	
                          Currently Processing &nbsp;&nbsp;</font></td>
                        <td align="left" height="30" width="50%"><select name="currpro" style="font-family:verdana;font-size:10px;width:50px">
                            <option value="">&nbsp;</option>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          </select>
						</td>
 						<script language="javascript">
							 document.Frmcompany.currpro.value='<?=$showval[42]?>';
						</script>                       
						</tr>

                        <input type="hidden" name="company" value="company"></input>
                      <tr>
                        <td align="center" valign="center" height="30" colspan="2"><a href="javascript:window.history.back();"><img border="0" src="images/back.jpg"></a>
						&nbsp;&nbsp;<input type="image" id="modifycompany" src="images/submit.jpg"></input>
                        </td>
                      </tr>
                    </table>
		<?
		  }
					  ?>
		  </td></tr></table></form>
	</td>
      </tr>
		<tr>
		<td width="1%"><img src="images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="images/menubtmcenter.gif"><img border="0" src="images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="images/menubtmright.gif"></td>
		</tr>
    </table>
    </td>
     </tr>
</table><br>
<?
include 'includes/footer.php';
}
?>