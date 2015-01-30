	<!--Main-->

<form id="processingFrm" action="{$rootdir}/secure/FinalProcessing.php" method="post" onsubmit="return submitOrder(this)">

  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan='2'  height="10" valign="middle" class="normaltext">&nbsp;</td>
    </tr>
    <tr>
      <td colspan='2'  width="60%" bgcolor="#009999"><img src="https://www.etelegate.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
    </tr>
  <tr>
    <td height="20" class="fieldname" align="left" style="font-size:24px; font-weight:bold">{#GL_Step#} #2</td>
  </tr>
    <tr>
      <td colspan='2'  class="fieldname" align="center" style="font-size:16px; font-weight:bold">      {#OP_ChargeAccount#}:</td>
    </tr>
    <tr >
      <td colspan='2' align="center" style="font-size:16px; font-weight:bold"><table width="400" border="1">
          <tr>
            <td class="fieldname">{#GL_YourEmail#}</td>
            <td><input name="login_ca_email" type="text" value="{$login_ca_email}" size="35" src="email"></td>
          </tr>
          <tr>
            <td class="fieldname">{#GL_YourPass#}</td>
            <td><input name="login_ca_password" type="password" value="{$login_ca_password}" src="minlen|6"></td>
          </tr>
		  
          {if $isPasswordManagement}
          <tr align="center">
            <td colspan="2" class="fieldname">{#OP_EnterSubscriptionInfo#}:</td>
          </tr>
          <tr>
            <td width="25%" valign="top" class="fieldname">{#GL_ChooseUser#} </td>
            <td><input name="td_username" type="text" id="td_username" value="{$str_username}" size="30" maxlength="30"     src="req">
              <strong><span class="terms"> </span></strong></td>
          </tr>
          <tr>
            <td width="25%" valign="top" class="fieldname">{#GL_ChoosePass#} </td>
            <td><input name="td_password" type="password" id="td_password" value="{$td_password}" size="30" maxlength="30"    src="req">
              <strong><span class="terms"> </span></strong></td>
          </tr>
          {/if}
      </table>
      <p> <font class="text" face="Verdana,Arial,Times New I2"><font size="2"><b>Forgot Password?</b></font></font></p></td>
    </tr>
  {include file="int_subinfo.tpl"}
  </table>
  <script language="javascript">
	setupForm(document.getElementById('processingFrm'));
	updateCountry(document.getElementById('country'));
</script>
	<!--End Main-->
</form>	