<?php

include("../includes/completion.php");

foreach($etel_completion_array as $key=>$data)
$cd_completion_options .="<option value='$key' style='".$data['style']."' >".$data['txt']."</option>\n";

$etel_completion_array[-1]['txt']="Old Company [No Status]";

require_once("../includes/function2.php");

function genCompanyViewTable($qrt_select_company,$mode='minimal')
{
	require_once("completion.php");
	global $bank_sql_limit;

	//$qrt_select_company ="select distinct userId,companyname,ReferenceNumber,cd_completion,cd_reseller_rates_request from cs_companydetails where 1 $bank_sql_limit order by companyname";

	$show_company_sql =mysql_query($qrt_select_company) or dieLog(mysql_errno().": ".mysql_error()."<BR>");
	
	foreach($etel_completion_array as $key=>$data)
	$cd_completion_options .="<option value='$key' style='font-weight:".$data['style']."' >".$data['txt']."</option>\n";
	
	$etel_completion_array[-1]['txt']="Old Company [No Status]";


?>
<script language="JavaScript">
var company = new Array();
var cs_URL = new Array();

function selectCompany(obj)
{
	obj_element = document.getElementById('companyname');
	obj_element.value = obj.value;
}

function Displaycompany(){
	if(document.getElementById('companymode').value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.getElementById('companymode').value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.getElementById('companymode').value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('companyname').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;

}
function validate() {
	return true;
	if(document.getElementById('companyname').value=="") {
	 alert("Please select the company.");
	 return false;
	} else {
		var selected_company = document.getElementById('companyname')[document.getElementById('companyname').selectedIndex].value;
		if (selected_company!="A") {
			document.dates.action="editCompanyProfileAccess.php";
		}
		return true;
	}
}

function Displaycompanytype() {
	document.getElementById('trans_type').value="Submit";
	//document.dates.action = "viewCompany.php";
	//document.dates.submit();
}
function func_fillcompanyname()
{
	var str_comparison;
	obj_element = document.getElementById('companyname');
	var str_search = document.getElementById('txt_companyname').value;
	var str_search_refNum = document.getElementById('txt_companyrefNum').value;
	var i_length = str_search.length;
	var i_length_refNum = str_search_refNum.length;
	var i_arraylength = company.length
	var statusSelect = 1;
	func_removeitem();
	
	var cs_URL_select = document.getElementById('cs_URL');
	cs_URL_select.length = 0;
	
	
	if(str_search == "" && <?=$mode='full'?"1":"0"?>){
		obj_element.options.length=obj_element.options.length+1;
		obj_element.options[obj_element.options.length-1].value="A";
		obj_element.options[obj_element.options.length-1].text="All Companies";
		obj_element.options[0].selected=true;
	}
	for (i=0;i<i_arraylength;i++)
	{
		str_comparison = company[i]['name'].substring(0, i_length);
		str_comparison_refNum = company[i]['refNum'].substring(0, i_length_refNum);
		if(document.getElementById('cd_completion').value > -1) statusSelect=(company[i]['completion']==document.getElementById('cd_completion').value);
		else statusSelect=1;
		if(document.getElementById('resellerRatesRequest').checked) reseller_rates_request=(company_[i]['reseller_rates_request']);
		else reseller_rates_request=1;
		if(document.getElementById('resellerMarkedUp').checked) resellerMarkedUp=(company[i]['resellerMarkedUp']);
		else resellerMarkedUp=1;
		
		activeSelect=1;
		if(document.getElementById('companymode').value == 'AC') activeSelect=(company[i]['active']==1);
		else if(document.getElementById('companymode').value == 'NC') activeSelect=(company[i]['active']!=1);
		else if(document.getElementById('companymode').value == 'RE') activeSelect=(company[i]['reseller_id']>0);
		

		if(document.getElementById('companytrans_type').value == '-1') typeSelect=1;
		else activeSelect=(company[i]['transaction_type'] == document.getElementById('companytrans_type').value);

		if(activeSelect &&resellerMarkedUp && reseller_rates_request && statusSelect && str_search.toLowerCase()==str_comparison.toLowerCase() && str_search_refNum.toLowerCase()==str_comparison_refNum.toLowerCase())
		{
			obj_element.options.length=obj_element.options.length+1;
			obj_element.options[obj_element.options.length-1].value=company[i]['id'];
			obj_element.options[obj_element.options.length-1].text=company[i]['name'];
			obj_element.options[obj_element.options.length-1].title=company[i]['request'];
			obj_element.options[obj_element.options.length-1].style.fontWeight=company[i]['style'];
			obj_element.options[obj_element.options.length-1].onmouseclick="window.alert('Go Back To Home Page');";
			
			for(j=0;j<cs_URL.length;j++)
			{
				if(cs_URL[j]['company_id']==company[i]['id'])
				{
					cs_URL_select.options.length=cs_URL_select.options.length+1;
					cs_URL_select.options[cs_URL_select.options.length-1].value=cs_URL[j]['company_id'];
					cs_URL_select.options[cs_URL_select.options.length-1].text=cs_URL[j]['URL'];
				}
			}
		}
	}
}
function func_removeitem()
{
	obj_element = document.getElementById('companyname');
	obj_element.options.length=0;
}
</script>

<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">
  <tr>
    <td width="50%"  valign="top"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td height="39" valign="middle" align="right" width="50%"><font face="verdana" size="1">Company Type&nbsp;:&nbsp;</font> </td>
          <td><select name="companymode" id="companymode" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="func_fillcompanyname();">
              <?php print func_select_mailcompanytype($companytype,$adminInfo['li_bank']);  ?>
            </select>
          </td>
        </tr>
        <tr style="visibility:<?=$mode='full'?"visible":"hidden"?>;">
          <td height="40" valign="middle" align="right" width="50%"><font face="verdana" size="1">Merchant Type&nbsp;:&nbsp;</font> </td>
          <td><select name="companytrans_type" id="companytrans_type" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="func_fillcompanyname();">
              <?php print func_get_enum_values('cs_companydetails','transaction_type','All Merchant Types','-1'); ?>
            </select>
          </td>
        </tr>
        <tr style="visibility:<?=$mode='full'?"visible":"hidden"?>;">
          <td height="40" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Status :</font> </td>
          <td><select name="cd_completion" id="cd_completion" onChange="func_fillcompanyname();">
              <option value="-1" >Any Status</option>
              <?=$cd_completion_options?>
            </select></td>
        </tr>
        <tr align="left" style="visibility:<?=$mode='full'?"visible":"hidden"?>;">
          <td height="40" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
            <input id="resellerRatesRequest" name="resellerRatesRequest" type="checkbox" value="1" onChange="func_fillcompanyname();">
            </font> </td>
          <td height="40"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> Reseller has Submitted Rates and Fees Markup</font></td>
        </tr>
        <tr align="left" style="visibility:<?=$mode='full'?"visible":"hidden"?>;">
          <td height="40" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
            <input id="resellerMarkedUp" name="resellerMarkedUp" type="checkbox" value="1" onChange="func_fillcompanyname();">
            </font> </td>
          <td height="40"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Merchant has requested that Reseller Markup Ratse and Fees</font></td>
        </tr>
      </table></td>
    <td width="50%" valign="top"><table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">
        <tr>
          <td valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Name&nbsp;:&nbsp;</font> </td>
          <td width="60%"><input type="text" name="txt_companyname" id="txt_companyname" size="10" style="font-family:verdana;font-size:10px;WIDTH: 210px" onKeyUp="javascript:func_fillcompanyname();">
          </td>
        </tr>
        <tr style="visibility:<?=$mode='full'?"visible":"hidden"?>;">
          <td height="40" valign="middle" align="right" width="40%"><font face="verdana" size="1">Company Reference Num&nbsp;:&nbsp;</font> </td>
          <td width="60%"><input name="txt_companyrefNum" type="text" id="txt_companyrefNum" style="font-family:verdana;font-size:10px;WIDTH: 210px" onKeyUp="javascript:func_fillcompanyname();" size="10">
          </td>
        </tr>
        <tr>
          <td valign="middle" align="right" width="40%"><font face="verdana" size="1">Website&nbsp;:&nbsp;</font> </td>
          <td width="60%"><select name="cs_URL" id="cs_URL" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="selectCompany(this)" onBlur="selectCompany(this)" onFocus="selectCompany(this)">
            </select>
          </td>
        </tr>
        <tr>
          <td height="40"  valign="top" align="right"><font face="verdana" size="1">Select Company&nbsp;:&nbsp;</font> </td>
          <td><select name="companyname" id="companyname" size="10" style="font-family:verdana;font-size:10px;WIDTH: 210px">
            </select>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;
      <input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg">
      </input></td>
  </tr>
</table>
<?php

}

?>
