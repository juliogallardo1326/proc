{config_load file="lang/eng/language.conf" section="OrderPage"}



{if $mt_language != 'eng'}{config_load file="lang/$mt_language/language.conf" section="OrderPage"}{/if}



<!--Header-->



<html>



<head>



<TITLE>{#OP_PaymentPage#}</TITLE>



<meta http-equiv="Content-Type" content="text/html; charset={#GL_Charset#}">



    <script language="javascript" src="{$rootdir}/scripts/general.js"></script>



    <script language="javascript" src="{$rootdir}/scripts/creditcard.js"></script>



    <script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>



    <script language="javascript" src="{$rootdir}/scripts/prototype.js"></script>



	<link href="{$tempdir}styles/orderpage.css" type="text/css" rel="stylesheet">



{literal}



<script>



function yyyysel(){



	document.write('<select name="yyyy" style="font-family:verdana;font-size:10px;WIDTH: 60px" onChange="updatevalid(this)" title="reqmenu" >')



	document.write('<OPTION value="select">{/literal}{#GL_Year#}{literal}</option>') 



	var str



		for (var i = 2005; i <=2012;  i++){



		



			str=str + '<option value=' + (i) + ' >' +   (i)  + '</option>'



			



		}



	document.write(str)



	document.write ('</select>&nbsp;')



}



function mmsel(){



	document.write('<select name="mm" style="font-family:verdana;font-size:10px;WIDTH: 50px" onChange="updatevalid(this)" title="reqmenu"  >')



	document.write('<OPTION value="select">{/literal}{#GL_Month#}{literal}</option>') 



	var str



		for (var i = 0; i <=11;  i++){



		



			str=str + '<option value=' + (i+1) + ' >' +    (i+1)  + '</option>'



			



		}



	document.write(str)



	document.write ('</select>&nbsp;')



	yyyysel()



}



function setDescriptor(typeOfCard)



{



	if (typeOfCard.toUpperCase() == "VISA") 



	{



		document.getElementById('cardtype').value = "Visa";



		document.getElementById('bill_desc').innerHTML = "{/literal}{$bill_des_visa}{literal}";



		document.getElementById('bill_desc2').innerHTML = "{/literal}{$bill_des_visa}{literal}";



	}



	else if (typeOfCard.toUpperCase() == "MASTER") 



	{



		document.getElementById('cardtype').value = "Master";



		document.getElementById('bill_desc').innerHTML = "{/literal}{$bill_des_master}{literal}";



		document.getElementById('bill_desc2').innerHTML = "{/literal}{$bill_des_master}{literal}";



	}



	else alert("{/literal}{#OP_SorryWeDoNotTake#} "+typeOfCard+" {#GL_Cards#}{literal}");







}



function setCCType(objValue)



{            



    objValue.value = objValue.value.replace(new RegExp (' ', 'gi'), '');



    objValue.value = objValue.value.replace(new RegExp ('-', 'gi'), '');



	



	if (!isValidCreditCard(objValue.value)) return;



	typeOfCard = typeOfCard(objValue.value);



	setDescriptor(typeOfCard);



}



function updateCountry(obj)



{



	if(obj.value == "US") 



	{



		document.getElementById('zipcode').src='zipcode';



		document.getElementById('state').disabled = false;



		document.getElementById('td_bank_number').src='phone';



		



	}



	else



	{



		document.getElementById('zipcode').src='req';



		document.getElementById('state').disabled = true;



		document.getElementById('td_bank_number').src='';



	}



}







function submitOrder(thisform)



{



	var validated = submitform(thisform);







	if(validated)



	{



		thisform.style.display='none';



		document.getElementById('processMessage').style.display='block';



		document.getElementById('formSubmit').disabled=true;



		



	}



	return validated;







}







</script>



{/literal}







</head>



<body dir="LTR"  bgcolor="#F5F5F5">



<table border="0" cellpadding="0" cellspacing="0" width="650" align="center">



<tr>



  <td width="100%" valign="top" align="left"><table width="100%"  border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td width="60%">



		



		{if !mt_hide_logo}



		<a href="https://www.NicheBill.com"><img src='{$tempdir}images/index_01.gif' alt='' border='0'></a>{/if}</td>



        <td align="right">







		



		<img src="{$tempdir}/images/visa.jpg" alt="v" width="30" height="18">&nbsp;&nbsp;<img border="0" src="{$tempdir}/images/mastercard.jpg">&nbsp;<br>



          <font size="2" face="Verdana"> </font>&nbsp;&nbsp;&nbsp; </td>



      </tr>



    </table>



	{if $ShowPaymentInfo}



    <table width="100%"  border="0" cellspacing="0" cellpadding="0">



      <tr>



        <td colspan="2"><table width="100%"  border="0" cellspacing="0" cellpadding="0">



            <tr>



              <td height="10" align="left" valign="top" class="normaltext"><span class="large">{#OP_ProductInformation#}



                {$TestMode}



                </span></td>



            </tr>



            <tr>



              <td bgcolor="#009999"><img src="https://www.NicheBill.com/images/spacer.gif" alt="sp" width="20" height="4"></td>



            </tr>



            <tr>



              <td width="85%" align="left" valign="top"><table width="100%"  border="0" cellspacing="4" cellpadding="0">



                  <tr>



                    <td colspan="2" align="right" valign="top"> 



					<form id="frm_language" action="" method="post">



	   <span  class="fieldname1">{$str_posted_variables}{#GL_Language#}:</span>



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



                  </tr>                  <tr>



                    <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> {#GL_Website#}: </font></span></div></td>



                    <td width="50%">&nbsp;



                      {$URL}



                    </td>



                  </tr>



                  { if $Description }



                  <tr>



                    <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> {#GL_ProductName#}: </font></span></div></td>



                    <td width="50%">&nbsp;



                      {$Description}



                    </td>



                  </tr>



                  {/if }



                  <tr>



                    <td width="50%" align="right" valign="top" class="fieldname1"><div align="right"><span class="style1"><font size="2" face="Verdana"> {#OP_ProductInformation#}: </font></span></div></td>



                    <td width="50%">&nbsp;



                      {$ProdDescription}



                    </td>



                  </tr>



                  { if $isSubAccount}



                  <tr>



                    { if $isSubscription}



                    <td width="50%" align="right" valign="top"><div align="right"><span class="style5"><font size="2" face="Verdana"> Transaction Detail:</font></span>



                        <table width="200" border="2">



                          <tr bgcolor="#B9FDCF">



                            <td width="82"><span class="style1">{#OP_Amount#}:</span></td>



                            <td width="100"><span class="style1"><strong>



                              { $InitialAmount}</strong>



                              </span></td>



                          </tr>



                          



                          <tr>



                            <td><span class="style1">{#OP_Period#}</span></td>



                            <td><span class="style1"><strong>



                              {$TrialDays}</strong>



                              </span></td>



                          </tr>



                        </table>



                        <span class="style1"><font size="2" face="Verdana"> </font></span></div></td>



                  {/if }



                  { if $isRecurring}



                    <td width="50%"><font size="1" face="Verdana" color="#000000"><span class="style5"><font size="2" face="Verdana">{#OP_RecurBilling#}: </font></span></font>



                      <table width="200" border="2">



                        <tr bgcolor="#B9FDCF">



                          <td width="100"><span class="style1">{#OP_Amount#}:</span></td>



                          <td><span class="style1"><strong>



                            { $RecurAmount}



                            </strong> </span></td>



                        </tr>



                        <tr>



                          <td><span class="style1">{#OP_BilledEvery#} </span></td>



                          <td><span class="style1">



                            {$RecurDays}



                            </span></td>



                        </tr>



                        <tr>



                          <td><span class="style1">{#OP_NextBillOn#} </span></td>



                          <td><span class="style1">



                            {$NextDate}



                            </span> </td>



                        </tr>



                      </table></td>



                  </tr>



                  <tr align="left">



                    <td colspan="2" valign="top"></td>



                    {/if }



                  </tr>



                  {/if }



                </table></td>



            </tr>



            <tr>



              <td align="center">{#OP_TotalCharge#}: $<strong>{$TotalCharge}



                </strong> </td>



            </tr>



          </table></td>



      </tr>



{if $gkard_support}



      <tr>



        <td class="normaltext" valign="middle" height="10"><span class="large">{#OP_MemberServicesPaymentOption#}</span><BR>{#OP_GKardSignUp#}</td>



        <td class="normaltext" valign="middle" height="10"><img src='{$tempdir}images/gkard_credit.gif' alt='' border='0'></td>



      </tr>



{/if }







    </table>



	{/if}



    <!--End Header-->



