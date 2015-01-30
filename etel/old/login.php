<?php include 'includes/function1.php'; ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td background="images_new/login_bg_01.jpg"> <form name="Frmlogin" method="post" action="index.php" onsubmit="return validation();">
        <table width="100%" border="0" cellspacing="0" cellpadding="3">
          <tr> 
            <td><img src="images_new/transparent.gif" width="1" height="5"></td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images_new/login.gif" width="77" height="25"></td>
          </tr>
		  <?php if($invalidlogin) { ?>
          <tr> 
            <td>&nbsp;</td>
            <td colspan="2" bgcolor="#CCCCCC"><?=$invalidlogin?></td>
          </tr>
		  <?php }?>
          <tr> 
            <td><img src="images_new/user_name.gif"></td>
            <td colspan="2"><input name="username" id="username" type="text" class="textbox" tabindex="1"></td>
          </tr>
 		<tr> 
            <td><img src="images_new/password.gif" ></td>
            <td colspan="2"><input name="password" type="password" class="textbox" tabindex="2"></td>
          </tr>
			<tr> 
            <td><img src="images_new/user_type.gif"></td>
            <td colspan="2"><select name="usertype" style="font-family:verdana;font-size:10px;WIDTH:125px" tabindex="3">
					<option value="merchant">Normal Users</option>
					<option value="customerservice">Customer Service</option>
					<option value="gateway">Gateway Users</option>
					<option value="reseller">Reseller</option>
					<option value="tele">Telemarketing users</option>
				</select>
			</td>
          </tr> 
		  <tr> 
            <td colspan="3"><span class="whitetext"><strong>Enter the security code given below</strong>(Codes are case sensitive)</span></td>
            
          </tr>
		  <?php
		  $str_securecode1 = get_rand_id(1);
		  $str_securecode2 = get_rand_id(1);
		  $str_securecode3 = get_rand_id(1);
		  $str_securecode4 = get_rand_id(1);
		  $str_securecode = "$str_securecode1$str_securecode2$str_securecode3$str_securecode4";
		  ?> 
		  <tr> 
            <td><img src="images/securitycode/<?=$str_securecode1?>.gif" border="0"><img src="images/securitycode/<?=$str_securecode2?>.gif" border="0"><img src="images/securitycode/<?=$str_securecode3?>.gif" border="0"><img src="images/securitycode/<?=$str_securecode4?>.gif" border="0"></td>
			<input name="securitycode_original" type="hidden" value="<?= $str_securecode?>" >
			<td colspan="2">
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<tr>
				<td><input name="securitycode" type="text" class="textbox" maxlength="4" style="WIDTH:50px" tabindex="4"></td>
				<td class="textbox" align="right"><input type=image src="images_new/go.gif" width="26" height="26"></td>
			</tr>
			</table>
			</td>
          </tr> 		  
		  <tr> 
            <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td><img src="images_new/forgotpassword.gif" width="124" height="12"></td>
                  <td><a href="forgotpassword.php"><img border="0" src="images_new/click_here.gif" width="81" height="26"></a></td>
                </tr>
              </table></td>
          </tr>
          <tr> 
            <td><img src="images_new/transparent.gif" width="1" height="5"></td>
          </tr>
        </table>
      </form></td>
  </tr>
  <tr> 
    <td><p><img src="images_new/need_an_account.gif" width="214" height="28"></p></td>
  </tr>
  <tr> 
    <td background="images_new/login_bg_02.jpg"><table width="100%" border="0" cellspacing="0" cellpadding="3">
        <tr> 
          <td><img src="images_new/transparent.gif" width="1" height="5"></td>
        </tr>
        <tr> 
          <td><img src="images_new/sign_up_now.gif" width="129" height="26"></td>
        </tr>
        <tr> 
          <td><div align="center"><a href="merchant_account.html"><img src="images_new/click_here.gif" width="81" height="26" border="0"></a></div></td>
        </tr>
        <tr> 
          <td><img src="images_new/transparent.gif" width="1" height="5"></td>
        </tr>
      </table></td>
  </tr>
  <tr> 
    <td><p><img src="images_new/see_online_demo.gif" width="214" height="27"></p></td>
  </tr>
  <tr>
    <td background="images_new/customer_service_bg.jpg"><table width="100%" border="0" cellpadding="6" cellspacing="0">
        <tr> 
          <td height="88"><div align="justify">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="57%" height="24"><strong><font color="#FFFFFF">Merchants</font></strong></td>
                  <td width="43%"><div align="right"><a href="Demo/blank.php"><img src="images_new/o_d_b_2.gif" width="111" height="24" border="0"></a></div></td>
                </tr>
                <tr> 
                  <td width="57%" height="24"><strong><font color="#FFFFFF">Resellers</font></strong></td>
                  <td width="43%"><div align="right"><a href="Demo/reseller/blank.php"><img src="images_new/o_d_b_2.gif" width="111" height="24" border="0"></a></div></td>
                </tr>
              </table>
              <br>
             <!-- <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="55%"><strong><font color="#FFFFFF">Tele Sales Merchants</font></strong></td>
                  <td width="45%"><div align="right"><a href="Demo/teledemo.htm"><img src="images_new/o_d_b_2.gif" width="111" height="24" border="0"></a></div></td>
                </tr>
              </table>-->
            </div></td>
        </tr>
      </table></td>
  </tr>
</table>
<script language="javascript">document.getElementById('username').focus();</script>
