<!--Footer-->
<div align="center" style="display:none; height:300;" id="processMessage"><br>
  <br>
  <br>
  <br>
  <img src="{$tempdir}images/transactionWait.gif"></div>
<table  width="650"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="60%" bgcolor="#009999"><img src="{$tempdir}images/spacer.gif" alt="sp" width="20" height="4"></td>
    <td align="right" bgcolor="#009999"><img src="{$tempdir}images/spacer.gif" alt="sp" width="20" height="4"></td>
  </tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="style1">
  {if $ShowSubmitButton}
  {if $gkard_support}
  <tr>
    <td height="20" align="center">{#OP_GkardUsed#}</td>
  </tr>
  {/if}
  <tr>
    <td height="20" align="center">{#OP_PleaseSubmitOnce#}</td>
  </tr>
  <tr>
    <td width="100%" align="center" height="23" valign="center"><input type="image" name="add" id="formSubmit" src="{$tempdir}images/submit.jpg" onClick="submitOrder(document.getElementById('processingFrm'))">
      </input>
    </td>
  </tr>
  {/if}
  <tr>
    <td align="center">{$custom_text}<BR></td>
  </tr>
  <tr>
    <td align="center">{#OP_CSMessage#}</td>
  </tr>
  <tr>
    <td align="center" style="color:#00CCFF; font-size:10px;"><br />
      Red Door International Marketing Ing., Unit 110 Alpha Bldg. Subic International Hotel Rizal cor. Sta. Rita Road, Subic Bay Freeport<BR />
	Olongapo City Philippines 2200</td>
  </tr>
</table>

</td>
</tr>
</table>
<!--End Footer-->
