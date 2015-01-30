<!--Main-->

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%" bgcolor="#009999"><img src="https://www.NicheBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="https://www.NicheBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="20" colspan="2" align="right" valign="bottom">{#GL_Language#}: </td>
  </tr>
  <tr>
    <td colspan="2" align="right"><form id="frm_language" action="" method="post">
        {$str_posted_variables}
        <select name="mt_language" id="mt_language" onChange="document.getElementById('frm_language').submit()">
          <option value="eng">English</option>
          <option value="spa">Spanish</option>
          <option value="fre">French</option>
          <option value="ger">German</option>
          <option value="ita">Italian</option>
          <option value="por">Portuguese</option>
          <option value="kor">Korean</option>
        </select>
        <script language="javascript">
	document.getElementById('mt_language').value = '{$mt_language}';
	 </script>
    </form></td>
  </tr>
  <form name="Frmname" action="{$rootdir}/secure/PaymentProcessing.php" method="post">
  <tr>
    <td height="20" class="fieldname" align="left" style="font-size:24px; font-weight:bold">{#GL_Step#} #1</td>
  </tr>
  <tr>
    <td align="center" class="fieldname" style="font-size:16px; font-weight:bold"><br>
      {#OP_PaymentType#}:</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
		  <p>
          <input name="ProcessingMode" id="ProcessingMode" value="" type="hidden">
          <input name="mt_language" value="{$mt_language}" type="hidden">
		  
        {if $cd_orderpage_useracount }
</p>
		  <p>{#OP_UserAccount_Msg#} <a target="_blank" href="{$AccountSignupPage}">{#GL_SignUp#}</a></p>
		  <p>
          <input name="Submit" value="{#OP_UserAccount#}" type="button" onClick="document.getElementById('ProcessingMode').value='UserAccount';this.form.submit();" style="font-size:24px; font-weight:bold">
        </p>
		  <p>{#OP_NotUserAccount_Msg#} </p>
		  <p>{/if}
		  {if $cs_creditcards }
	      </p>
		  <p>
          <input name="Submit" value="{#OP_CreditCardTitle#}" type="button" onClick="document.getElementById('ProcessingMode').value='Credit';this.form.submit();" style="font-size:24px; font-weight:bold">
        </p>
		{/if}
		{if $cs_echeck }
        <p>
          <input name="Submit" value="{#OP_CheckTitle#}" type="button" onClick="document.getElementById('ProcessingMode').value='Check';this.form.submit();" style="font-size:24px; font-weight:bold">
          <br>
        {#OP_US_CANADA#}</p>
        {/if}{if $cs_web900 }
        <p>
          <input name="Submit" value="{#OP_Web900Title#}" type="button" onClick="document.getElementById('ProcessingMode').value='Web900';this.form.submit();" style="font-size:24px; font-weight:bold">
          <br>
          {#OP_US_ONLY#}
        </p>
	  {/if}</td>
  </tr>
  </form>
</table>
<!--End Main-->
