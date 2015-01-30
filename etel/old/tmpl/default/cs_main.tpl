 
				  {include file='cs_header.tpl'}
				  <form name="FrmName" action="service/searchresult.php" method="post"> 
				  
<table border="0" cellpadding="0" cellspacing="0" width="98%" height="100"> 
          <tr> 
            <td width="100%" align="center" class="tx1" height="25"></td> 
          </tr> 
          <tr> 
            <td width="100%" align="center" class="tx1"><img border="0" src="images/welcome.gif" width="348" height="34"></td> 
          </tr> 
          <tr> 
            <td width="100%" align="center" class="tx3" height="50">Lost your subscription username and password? retrieve it <a href="service/lostpassword.php">here</a>..</td> 
          </tr> 
          <tr> 
            <td width="100%" align="center" class="tx3" height="50">To lookup your transaction, please enter the following information.</td> 
          </tr> 
          <tr> 
            <td width="100%"><table width="95%" style="border: 1px solid #376C84" CELLPADDING="0" CELLSPACING="0" align="center"> 
                <tr> 
                  <td width="446"><table WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" valign="top"> 
                      <tr> 
                        <td ALIGN="LEFT" BGCOLOR="#3D8287" style="border-bottom:1px solid #376C84"><font style="font-family:verdana;font-size:12px;color:white;font-weight:bold">Search for Credit CardTransaction</font> </td> 
                      </tr> 
                    </table> 
                    <table width="100%" border="0" align="center"> 
                      <tr> 
                        <td colspan="2"><font style="font-family:verdana;font-size:11px;color:black"><font color="red">*</font>Choose <strong>2</strong> of the following fields:</font> </td> 
                      </tr> 
                      <tr> 
                        <td width="179"><font style="font-family:verdana;font-size:11px;color:black">Reference #</font></td> 
                        <td width="208"><font style="font-family:verdana;font-size:11px;color:black">Telephone #</font> </td> 
                      </tr> 
                      <tr> 
                        <td valign="middle" width="179" align="center"><input type="text" name="cc_reference_number"  class="TextBox"> </td> 
                        <td width="208" align="center"><input type="text" name="cc_telephoneno"  class="TextBox1"> </td> 
                      </tr> 
                      <tr> 
                        <td width="179"><font style="font-family:verdana;font-size:11px;color:black">&nbsp;</font><font style="font-family:verdana;font-size:11px;color:black">Credit Card #</font> </td> 
                        <td width="208"><font style="font-family:verdana;font-size:11px;color:black">&nbsp;</font><font style="font-family:verdana;font-size:11px;color:black">Email Id</font> </td> 
                      </tr> 
                      <tr> 
                        <td width="179" align="center"><input type="password" name="cardno"   class="TextBox"> </td> 
                        <td valign="middle" width="208" align="center"><input type="text" name="cc_emailid"  class="TextBox2"> </td> 
                      </tr> 
                      <tr> 
                        <td colspan="2" align="center" valign="middle" height="30"><input type="submit" name="Submit Transaction" value="Find Transaction" class="Button" onClick="javascript:validatecc();"></td> 
                      </tr> 
                    </table></td> 
                </tr> 
              </table> 
              <br> 
              <table width="95%" style="border: 1px solid #376C84" CELLPADDING="0" CELLSPACING="0" align="center"> 
                <tr> 
                  <td width="446"><table WIDTH="100%" BORDER="0" CELLPADDING="0" CELLSPACING="0" valign="top"> 
                      <tr> 
                        <td ALIGN="LEFT" BGCOLOR="#3D8287" style="border-bottom:1px solid #376C84"><font style="font-family:verdana;font-size:12px;color:white;font-weight:bold">Search for&nbsp; Check Transaction</font> </td> 
                      </tr> 
                    </table> 
                    <table width="100%" border="0" align="center"> 
                      <tr> 
                        <td colspan="2"><font style="font-family:verdana;font-size:11px;color:black"><font color="red">*</font>Choose <strong>2</strong> of the following fields:</font> </td> 
                      </tr> 
                      <tr> 
                        <td width="179"><font style="font-family:verdana;font-size:11px;color:black">Reference #</font></td> 
                        <td width="208"><font style="font-family:verdana;font-size:11px;color:black">Bank Routing #</font></td> 
                      </tr> 
                      <tr> 
                        <td valign="middle" width="179" align="center"><input type="text" name="ch_reference_number"   class="TextBox"> </td> 
                        <td width="208" align="center"><input type="text" name="ch_routingno"   class="TextBox"> </td> 
                      </tr> 
                      <tr> 
                        <td width="179"><font style="font-family:verdana;font-size:11px;color:black">&nbsp;</font><font style="font-family:verdana;font-size:11px;color:black">Account #</font> </td> 
                        <td width="208"><font style="font-family:verdana;font-size:11px;color:black">Telephone #</font> </td> 
                      </tr> 
                      <tr> 
                        <td width="179" align="center"><input type="text" name="accountno" class="TextBox"> </td> 
                        <td valign="middle" width="208" align="center"><input type="text" name="ch_telephoneno"  class="TextBox"> </td> 
                      </tr> 
                      <tr> 
                        <td><font style="font-family:verdana;font-size:11px;color:black">Email Id</font> </td> 
                        <td ><font style="font-family:verdana;font-size:11px;color:black">&nbsp;</font> </td> 
                      </tr> 
                      <tr> 
                        <td align="center"><input type="text" name="ch_emailid"  class="TextBox" > </td> 
                        <td valign="middle"  align="center">&nbsp;</td> 
                      </tr> 
                      <tr> 
                        <td colspan="2" align="center" valign="middle" height="30"><input type="submit" name="Submit Transaction" value="Find Transaction" class="Button" onClick="javascript:validationch();"></td> 
                      </tr> 
                    </table></td> 
                </tr> 
              </table> 
              <input type="hidden" name="payment_mode"> </td> 
          </tr> 
          <tr> 
            <td width="100%" height="100" class="tx3"><p style="margin-left: 10; margin-right: 10; margin-top: 5; margin-bottom: 5" align="justify"> If your billing statement refers to www.etelegate.net, or you recieved a confirmation email, then you have made a purchase from a web site that uses etelegate.com. For billing inquiries, please use the form above to search for your transaction 24/7/365. </td> 
          </tr> 
        </table>
</form> 
				  {include file='cs_footer.tpl'}