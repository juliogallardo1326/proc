<?php
		$rootdir="../";
		$headerInclude = "service";
		include($rootdir."includes/sessioncheckserviceuser.php");
		include($rootdir."includes/dbconnection.php");
		require_once($rootdir."includes/function.php");
		include($rootdir."includes/header.php");

?>
<script>
	function validate()
	{
		if(document.search.txt_phone.value == "")
		{
			alert("Please enter either a telephone number or Voice Authorization Id");
			return false;
		}
	}
</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <p>&nbsp;    </p>
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
      <tr>
        <td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
        <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Transaction&nbsp; Search</span></td>
        <td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
        <td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
        <td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
      </tr>
      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><p>&nbsp;
            </p>
          <p>
              <input onClick="javascript:document.location.href='customerservice.php';" type="submit" name="Submit" value="Start Call" style="width:150;height:40; ">
            </p>
          <p><br>
              </p></td>
      </tr>
      <tr>
        <td width="1%"><img src="../images/menubtmleft.gif"></td>
        <td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
        <td width="1%" ><img src="../images/menubtmright.gif"></td>
      </tr>
    </table>    
    <p>&nbsp;    </p></td>
  </tr>
</table>	
<?php
	include("../admin/includes/footer.php");
?>	
