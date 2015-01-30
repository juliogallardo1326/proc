<?php
//******************************************************************//
//  This file is part of the Skillshire development package.        //
//  Copyright (C) Company Setup 2003-2004, All Rights Reserved.    //
//                                                                  //
//******************************************************************//
// Package:         Skillshire
// Description:     Online Company Setup
// Files:           index.php,companyAdd.php,excelout.php,massmail.php,modifyCompany.php,modifyCompanyNext.php,report.php,reportTop.php, 
//				config.php,reportBottom.php,reportBottomNext.php,useraccount.php,viewCompany.php,viewCompanyNext.php,ViewreportPage.php
//report.php:	The admin page functions for selecting the type of report view  for the company. 
include("includes/sessioncheck.php");

include("includes/header.php");

?>
<?php
		$qry_select_ledger_constant = "SELECT ledger_id,Charge_back,Credit,Discount_rate,Transaction_fee,Reserve FROM cs_ledger_constant";
		$rssel_qry = mysql_query($qry_select_ledger_constant);
		if (mysql_num_rows($rssel_qry)>0)
		{
			$i_id = mysql_result($rssel_qry,0,0);
			$i_charge_back = mysql_result($rssel_qry,0,1);
			$i_credit	   = mysql_result($rssel_qry,0,2);
			$i_discount_rate = mysql_result($rssel_qry,0,3);
			$i_trans_fee = mysql_result($rssel_qry,0,4);
			$i_reserve = mysql_result($rssel_qry,0,5);
		}					
?>
	
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
<tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
  	  <table border="0" cellpadding="0" cellspacing="0" width="50%" >
    	  <tr>
        	<td width="100%" height="22">
          		<table border="0" cellpadding="0" cellspacing="0" width="100%" height="22">
            <tr>
              <td width="17" height="22"><img border="0" src="file://///GTS3/projects/companysetup/images/leftcurve.gif" width="17" height="22"></td>
              <td bgcolor="#1c5abc" >&nbsp;</td>
              <td width="17" height="22"><img border="0" src="file://///GTS3/projects/companysetup/images/rightcurve.gif" width="17" height="22"></td>
            </tr>
       </table>
       </td>
</tr>
<tr>
    <td width="100%"  valign="top" align="center" style="border:1px solid #1c5abc">
    <form name="frm_ledger_constant"  method="POST" action="file://///GTS3/projects/companysetup/admin/ledgerconstantfbk.php">
	   <table align="center" cellpadding="0" cellspacing="0" width="100%" border="0">  
	      <tr>
		      <td   height="30" valign="middle"   align="right" bgcolor="#ffffff" width="40%"><font face="verdana" size="1">Charge back </font></td><td align="left" width="60%"  height="30" >&nbsp;
			  <input type="text" name="txt_Charge" style="font-family:verdana;font-size:10px;WIDTH: 140px" value="<?php echo $i_charge_back; ?>"></input><font face="verdana" size="1" >$</font>&nbsp;&nbsp;
		      </td>
 		  </tr>
          <tr>
		  <td   height="30" valign="middle" align="right" bgcolor="#ffffff" width="40%"><font face="verdana" size="1">Credit</font></td><td align="left" width="60%"  height="30"  >&nbsp;
			<input type="text" name="txt_Credit" style="font-family:verdana;font-size:10px;WIDTH: 140px" value="<?php echo $i_credit; ?>" ></input><font face="verdana" size="1" >$</font>&nbsp;&nbsp;
		  </td>   
          </tr>

		 <tr>
			<td  height="30"  valign="middle" align="right" bgcolor="#ffffff" width="40%">
			  <font face="verdana" size="1" >Discount rate </font></td><td align="left" width="60%">&nbsp;
			    <input type="text" name="txt_discount_rate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value="<?php echo $i_discount_rate; ?>" ></input><font face="verdana" size="1" >%</font>&nbsp;&nbsp;
			  </font>
			</td>
         </tr>
         <tr>
		   <td height="30"  valign="middle" align="right"  bgcolor="#ffffff">
		   <font face="verdana" size="1">Transaction Fee</font></td><td align="left" >&nbsp;
		   <input type="text" name="txt_trans_fee" style="font-family:verdana;font-size:10px;WIDTH: 140px" value="<?php echo $i_trans_fee; ?>" ></input><font face="verdana" size="1" >$</font>&nbsp;&nbsp;
		   </font>
		   <input type="hidden" name="hid_ledger" value="ledgerconstants">
		   <input type="hidden" name="hid_id" value="<?php echo $i_id; ?>">		  
		   </td>
		</tr>
		<tr>
			<td  height="30"  valign="middle" align="right" bgcolor="#ffffff" width="40%">
			  <font face="verdana" size="1" >Reserve</font></td><td align="left" width="60%">&nbsp;
			  <input type="text" name="txt_reserve" style="font-family:verdana;font-size:10px;WIDTH: 140px" value="<?php echo $i_reserve; ?>"></input><font face="verdana" size="1" >%</font>&nbsp;&nbsp;
			  </font>
			</td>
	   </tr>		
<!-- *********  -->
        <tr>
		 <td  height="50"  valign="middle" align="center" bgcolor="#ffffff" colspan='2'>
			 <input type="image" id="reportview" src="file://///GTS3/projects/companysetup/images/update.jpg" ></input>
			 
		</td>
		</tr>     
	</table>
	</form>
      </tr>
    </table>
     </tr>
</table>	
<?php
	include("includes/footer.php");		
?>
	