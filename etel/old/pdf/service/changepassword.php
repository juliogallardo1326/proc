<?php
		include("../includes/sessioncheckserviceuser.php");
		
		require_once("../includes/function.php");
		$headerInclude = "service";
		include("../admin/includes/serviceheader.php");
		include("../admin/includes/topheader.php");
		include("../admin/includes/message.php");

?>
<script language="JavaScript">
function func_validate(obj_form)
{

	var b_correct = true;
	obj_element = obj_form.txt_current;
	trimSpace(obj_element);
	if(b_correct && (obj_element.value == ""))
	{
		b_correct = false;
		alert("Please enter current password");
		obj_element.focus();
	}

	obj_element = obj_form.txt_new;
	trimSpace(obj_element);
	if(b_correct && (obj_element.value == ""))
	{
		b_correct = false;
		alert("Please enter new password");
		obj_element.focus();
	}

if (obj_form.txt_new.value!=""&&(!func_vali_pass(obj_form.txt_new)))
		{
		
		alert("Special characters not allowed for password");
		obj_form.txt_new.focus();
		obj_form.txt_new.select();
		return false;
		}


	obj_element = obj_form.txt_retype;
	trimSpace(obj_element);
	if(b_correct && (obj_element.value == ""))
	{
		b_correct = false;
		alert("Please re-type the new password");
		obj_element.focus();
	}
	obj_element1 = obj_form.txt_new;
	obj_element2 = obj_form.txt_retype;
	if(b_correct && (obj_element1.value != obj_element2.value))
	{
		b_correct = false;
		alert("New password re-type does not match");
		obj_element.focus();
	}
	
	return b_correct;
}

function func_vali_pass(frmelement)
{ 
 var invalid="!`~@#$%^&*()_-+={}[]|\"':;?/>.<,";
 var inp=frmelement.value;
 var b_flag=true;
for(var i=0;((i<inp.length)&&b_flag);i++)
{
var temp= inp.charAt(i);
var j=invalid.indexOf(temp);
if(j!=-1)
{
b_flag =false;
return false;
}
}
if (b_flag==true)return true;
}

function trimSpace(frmElement)
{
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}



</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" src="../images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Change&nbsp; 
            Password</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="../images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" src="../images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="../images/menuright.gif" width="10" height="22"></td>
	</tr>
      <tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5"><br>
		<form name="frm_changepassword"  method="post"  action="changepasswordfb.php" onSubmit="return func_validate(document.frm_changepassword)">

	          <table align="center" cellpadding="0" cellspacing="0" width="500">
                <tr>
                  <td height="30"  valign="middle" align="right" bgcolor="#ffffff"><font face="verdana" size="1">Current Password</font> </td>
                  <td align="left">&nbsp;&nbsp;<input type="password" name="txt_current" size="20"></td>
                </tr>
				 <tr>
                  <td height="30"  valign="middle" align="right" bgcolor="#ffffff"><font face="verdana" size="1">New Password</font> </td>
                  <td align="left">&nbsp;&nbsp;<input type="password" name="txt_new" size="20"></td>
                </tr>
				 <tr>
                  <td height="30"  valign="middle" align="right" bgcolor="#ffffff"><font face="verdana" size="1">Re-Type New password</font> </td>
                  <td align="left">&nbsp;&nbsp;<input type="password" name="txt_retype" size="20"></td>
                </tr>
                <tr> 
                  <td height="50"  valign="middle" align="center" bgcolor="#ffffff" colspan='2'><input type="image" src="../images/submit.jpg" border="0"></td>
                </tr>
              </table>
		</form>
		</td>
      </tr>
		<tr>
		<td width="1%"><img src="../images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" src="../images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img src="../images/menubtmright.gif"></td>
		</tr>
    </table>
    </td>
     </tr>
</table>	
<?php
	include("../admin/includes/footer.php");
?>	