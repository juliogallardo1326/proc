{config_load file="lang/eng/language.conf" section="OrderPage"}

{if $mt_language != 'eng'}
  {config_load file="lang/$mt_language/language.conf" section="OrderPage"}
{/if}
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Payment Page</title>
{literal}
<style>
td
{
font-family:arial; font-size:12px; color:#333333
}
.background
{
background-color:#7C8593;
}
.header
{
font-family:arial; font-size:13px; color:#333333
}
input,select
{
height:20px; width:160px;
}
</style>
{/literal}
</head>

<body>
<table width="770" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
	  <table width="100%" cellspacing="0" cellpadding="0">
	    <tr>
		  <td width="92">
		    <img src="{$tempdir}images/invoicelogo.jpg" height="65" width="92">
		  </td>
		  <td>
		    <table width="100%" cellspacing="0" cellpadding="0">
			  <tr>
			    <td width="3">
				  <img src="{$tempdir}images/spacer.gif" width="3">
				</td>
				<td class="background">
				  NicheBill 
                  Web Payment Service is the designated internet payment processor 
                  and <br>
                  anti-fraud prevention system for &quot;{$cs_URL}&quot;
				</td>
				<td width="3">
				  <img src="{$tempdir}images/spacer.gif" width="3">
				</td>
			  </tr>
			</table>
		  </td>
		</tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td valign="top">
	  <table width="100%" cellspacing="0" cellpadding="0">
	    <tr>
		  <td align="right">
		  <table width="384" cellspacing="0" cellpadding="2" align="right">
			<tr>
			  <td colspan="2" align="right" class="header">
				 YOUR PERSONAL DATA &bull;<img src="{$tempdir}images/spacer.gif" width="20">
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">First Name:
			  </td>
			  <td>
				<input name="firstname" type="text" value="{$str_firstname}">
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">Last Name:
			  </td>
			  <td>
				<input name="lastname" type="text" value="{$str_lastname}">
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">E-mail:
			  </td>
			  <td>
				<input name="email" type="text" value="{$str_emailaddress}">
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">Phone:
			  </td>
			  <td>
				<input name="phonenumber" type="text" value="{$str_phonenumber}">
			  </td>
			</tr>
			<tr>
			  <td colspan="2" align="right" class="header">
				 BILLING ADDRESS &bull;<img src="{$tempdir}images/spacer.gif" width="20">
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">Street:
			  </td>
			  <td>
				<input name="address" type="text" value="{$str_address}">
			  </td>
			</tr>
			<tr>
			  <td align="right" colspan="2">
				<table width="100%" cellspacing="0" cellpadding="2">
				  <tr>
					<td align="right">
					  <img src="{$tempdir}images/spacer.gif" width="40">City:
					</td>
					<td>
					  <input name="city" type="text" value="{$str_city}" style="height:20px; width:65px;">
					</td>
					<td align="right">
					  Zip:
					</td>
					<td>
					  <input name="zipcode" type="text" value="{$str_zipcode}" style="height:20px; width:25px;">
					</td>
				  </tr>
				</table>
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">Country:
			  </td>
			  <td>
				<select name="country">
				  {$opt_Countrys}
				</select>
			  </td>
			</tr>
			<tr>
			  <td align="right">
				<img src="{$tempdir}images/spacer.gif" width="40">State:
			  </td>
			  <td>
				<select name="country">
				  {$opt_States}
				</select>
			  </td>
			</tr>		
		  </table>
		  </td>
		  <td width="1" class="background">
		    <img src="{$tempdir}images/spacer.gif" width="1">
		  </td>
		  <td valign="top">
		    <table width="385" cellspacing="0" cellpadding="0">
			  <tr>
			    <td colspan="2" class="header">
				  <img src="{$tempdir}images/spacer.gif" width="20">&bull; CREDIT CARD INFO
				</td>
			  </tr>
			  <tr>
			    <td align="right">
				  Credit Card:
				</td>
				<td>
				  <input name="number" type="text">
				</td>
			  </tr>
			  <tr>
			    <td align="right">
				  Expiration Date:
				</td>
				<td>
				  <select name="mm">
				    <option value="">Select Month</option>
				    <option value="1">1</option>
					<option value="2">2</option>
					<option value="3">3</option>
					<option value="4">4</option>
					<option value="5">5</option>
					<option value="6">6</option>
					<option value="7">7</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
				  </select>
				  <select name="yyyy">
				    <option value="">Select Year</option>
					<option value="2006">2006</option>
					<option value="2007">2007</option>
					<option value="2008">2008</option>
					<option value="2009">2009</option>
					<option value="2010">2010</option>
					<option value="2011">2011</option>
					<option value="2012">2012</option>
					<option value="2013">2013</option>
					<option value="2014">2014</option>
					<option value="2015">2015</option>
				  </select>
				</td>
			  </tr>
			  <tr>
			    <td align="right">
				  CVV2:
				</td>
				<td>
				  <input type="text" name="cvv2" style="height:20px; width:25px;">
				</td>
			  </tr>
			</table>
		  </td>
	    </tr>	  
	  </table>
	</td>
  </tr>
</table>
</body>
</html>
