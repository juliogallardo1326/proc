<!--Header Start-->
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="Content-Language" content="en-us">
<title>Etelegate Transactons</title>
<link href="{$tempdir}/styles/comp_set.css" type="text/css" rel="stylesheet">
{literal}
<style>
.Button
{
    BORDER-RIGHT: #D4D0C8 1px solid;
    BORDER-TOP: #D4D0C8 1px solid;
    BORDER-LEFT: #D4D0C8 1px solid;
    BORDER-BOTTOM: #D4D0C8 1px solid;
    FONT-SIZE: 8pt;
    FONT-FAMILY: verdana;
    COLOR: white;
	FONT-WEIGHT:bold;
    BACKGROUND-COLOR: #999999 
}
.TextBox
{
font-face:verdana;font-size:10px
}
.TextBox1 {font-face:verdana;font-size:10px
}
            .TextBox2 {font-face:verdana;font-size:10px
}
            .style1 {font-size: 12px}
            .style2 {font-weight: bold}
</style>
<script>
function validatecc() {
return true;
trimSpace(document.FrmName.cardno)
trimSpace(document.FrmName.cc_reference_number)

if (document.FrmName.cc_reference_number.value == "") {
		alert("Please enter reference #");
		document.FrmName.cc_reference_number.focus();
		return false;
	}
	

	if(document.FrmName.cc_emailid.value == "" && document.FrmName.cardno.value =="") {
		alert("Please enter the creditcard# or Email id");
		document.FrmName.cardno.focus();
		return false;
	} 
	
	else {
		document.FrmName.payment_mode.value="credit";
		document.FrmName.submit();
	}
}
function validationch() {
return true;
trimSpace(document.FrmName.accountno)
trimSpace(document.FrmName.ch_routingno)
trimSpace(document.FrmName.ch_reference_number)
trimSpace(document.FrmName.ch_emailid)
if(document.FrmName.ch_reference_number.value == "" ) {
		alert("Please enter the reference#");
		document.FrmName.ch_reference_number.focus();
		return false;
	}
	if(document.FrmName.accountno.value == "" && document.FrmName.ch_emailid.value == "") {
		alert("Please enter the account # or emailid #.");
		document.FrmName.accountno.focus();
		return false;
	}else {
		document.FrmName.payment_mode.value="check";
		document.FrmName.submit();
	}
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

{/literal}
</head>
<body topmargin="0" leftmargin="0"> 

  <table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1"> 
    <tr> 
      <td class="whitebtbd"><img border="0" src="{$tempdir}/images/index_01.gif"><img border="0" src="{$tempdir}images/cards_tran.gif" width="199" height="23"></td> 
    </tr> 
  </table> 
  <table border="0" cellpadding="0" cellspacing="0" width="772" align="center" bgcolor="#658343" class="blkbd1"> 
    <tr> 
      <td height="15" class="blackbtbd" bgcolor="#4A9FA6"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
    </tr> 
  </table> 
  <table border="0" cellpadding="0" cellspacing="0" width="772" align="center" class="blkbd1" height="460"> 
    <tr> 
      <td height="25" valign="top" align="center" width="179" bgcolor="#FFFFFF"><table border="0" cellpadding="0" width="100%" height="249"> 
          <tr> 
            <td width="99%" bgcolor="#B7D0DD" height="14"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
            <td width="1%"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
          </tr> 
          <tr> 
            <td width="99%" bgcolor="#85AFBC" height="16"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
            <td width="1%"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
          </tr> 
          <tr> 
            <td width="99%" bgcolor="#85AFBC" height="18"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
            <td width="1%"><img border="0" src="{$tempdir}images/spacer.gif" width="1" height="1"></td> 
          </tr> 
          <tr> 
            <td width="100%" height="178" colspan="2" valign="top"><img border="0" src="{$tempdir}images/service_pic.jpg" width="160" height="176"></td> 
          </tr> 
        </table> 
        <p><strong>Customer Support is available 24/7/365 Toll Free at</strong></p>
        <p><span class="style2 style1">1-(888)- 557-1548</span></p>        
        <p class="style2 style1">or call us direct at 212-631-4223</p> 
        <p class="style2 style1">Email Support: <br> 
          <span class="style1"><a href="mailto:CustomerService@Etelegate.com">CustomerService@Etelegate.com</a></span></td> 
      <td height="25" valign="top" align="center" width="418">
				  <!--Header End -->