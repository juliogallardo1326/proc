<?php
function genCompanyViewTable($redirectOne,$redirectAll,$mode='minimal',$submittable=true)
{
	global $etel_completion_array;
	global $adminInfo;
	global $adminConfig;
	global $etel_gw_list;
	global $bank_sql_limit;
	global $etel_domain_path;
	require_once("completion.php");

	if($adminInfo['li_user_view']=='hide' && $adminConfig['vList']) $uview_sql = " AND userId not in (".implode(",",$adminConfig['vList']).")";
	if($adminInfo['li_user_view']=='show' && $adminConfig['vList']) $uview_sql = " AND userId in (".implode(",",$adminConfig['vList']).")";

	$etel_completion_array[-1]['txt']="Old Company [No Status]";

	if(!$redirectOne) $redirectOne= basename($_SERVER['PHP_SELF']);
	if(!$redirectAll) $redirectAll= basename($_SERVER['PHP_SELF']);

	$ignore = "cd_ignore=0 ";
	if($_GET['showall']) $ignore = "1";

	if(!$_REQUEST['cp']) $_REQUEST['cp'] = '';

	ob_start();

?>
<script language="JavaScript">
var company = new Array();
var cs_URL = new Array();

function selectCompany(obj)
{
	obj_element = $('companyname');
	obj_element.value = obj.value;
}

function Displaycompany(){

	$('companyname').selectedIndex = 0;
	$('activename').selectedIndex = 0;
	$('nonactivename').selectedIndex = 0;

}
function viewCompany(obj) {
	var liststr = "";
	var curval = '';
	var curUserId = 0;
	var length = $('companyname').length;
	if($('companyname').value=="") {
	 //alert("Please select the company.");
	 return 0;
	}
	for(i=0;i<length;i++)
	{
		if($('companyname').value == 'AL' || $('companyname').options[i].selected)
		{
			curUserId = $('companyname').options[i].value;
			if (curUserId == 'A') {curVal='A'; break;}
			if (!window.redirectTo)
			{
				if(curUserId!='AL')
				{
					if(!liststr) liststr=curUserId;
					else liststr += '|'+curUserId;
				}
			}
			else userId[curval] = curUserId;
			curval++;
		}
	}
	if($('companyname').value == 'A')
		curval='A';
	if($('companyname').value == 'AL')
		curval='AL';
	if (window.redirectTo) redirectTo(userId);

	if(curval==1) obj.form.action='<?=$redirectOne?>';
	else obj.form.action='<?=$redirectAll?>';

	$('cd_view').value = curval;
	$('companyname').value = '';
	$('batchfield').value = '';
	func_remove_all();
	$('userIdList').value = liststr;



	obj.form.submit();
}

function cropRemove(crop) {
	var liststr = "";
	var curval = 0;
	var curUserId = 0;
	var length = $('companyname').length;
	if($('companyname').value=="") {
	 //alert("Please select the company.");
	 return 0;
	}
	for(i=length-1;i>=0;i--)
	{
		var doCrop = false;
		doCrop = ($('companyname').options[i].selected != crop);
		if(doCrop)
		{
			$('companyname').options[i] = null;
		}
	}

}

function Displaycompanytype() {
	$('trans_type').value="Submit";
	//document.dates.action = "viewCompany.php";
	//document.dates.submit();
}



function func_fillcompanyname_response(response)
{
	$('query_time').innerHTML = "Updating List...";
	//alert(JSON);
	var data = JSON.parse(response.responseText);
	if(data['func'] != "getCompanyInfo")
	{
		$('query_time').innerHTML = "Error Querying Database. Your session may have expired. Please log in again.";
		return 0;
	}
	obj_element = $('companyname');
	cs_URL_select = $('cs_URL');
	obj_element.options.length=0;

	if(data['show_option_all'])
	{
		obj_element.options.length=obj_element.options.length+1;
		obj_element.options[obj_element.options.length-1].value="A";
		obj_element.options[obj_element.options.length-1].text="All Companies";
		obj_element.options[0].selected=true;
	}
	else
	{
		obj_element.options.length=obj_element.options.length+1;
		obj_element.options[obj_element.options.length-1].value="AL";
		obj_element.options[obj_element.options.length-1].text="All Companies In List";
		obj_element.options[0].selected=true;
	}

	var cp_ar = data['completion'];

	if(data['company_list'])
	{
		var len =data['company_list'].length;
		for (var i = 0;i<len;i++)
		{
			if(data['company_list'][i]['ui'])
			{
				obj_element.options.length=obj_element.options.length+1;
				obj_element.options[obj_element.options.length-1].value=data['company_list'][i]['ui'];
				obj_element.options[obj_element.options.length-1].text=data['company_list'][i]['cn'];
				if(cp_ar[data['company_list'][i]['cp']])
				{
					if(cp_ar[data['company_list'][i]['cp']]['txt'])
						obj_element.options[obj_element.options.length-1].title=cp_ar[data['company_list'][i]['cp']]['txt'];
					if(cp_ar[data['company_list'][i]['cp']]['style'])					
						obj_element.options[obj_element.options.length-1].style.fontWeight=(cp_ar[data['company_list'][i]['cp']]['style']?"bold":"");
	
				}
			}
		}
	}

	cs_URL_select.options.length=0;
	for (var ci in data['site_list'])
	{
		if(data['site_list'][ci]['ci'])
		{
			cs_URL_select.options.length=cs_URL_select.options.length+1;
			cs_URL_select.options[cs_URL_select.options.length-1].value=data['site_list'][ci]['cui'];
			cs_URL_select.options[cs_URL_select.options.length-1].text=data['site_list'][ci]['cn'];
		}
	}
	$('query_time').innerHTML = "("+data['num_rows']+") Results<BR> in ("+(Math.round(data['duration']*100)/100)+") seconds."
	updateList(false);
}
var refresh_timeout = null;
function func_fillcompanyname(initialDisplay)
{
	clearInterval(refresh_timeout);
	refresh_timeout = setTimeout('func_fillcompanyname_sub("'+initialDisplay+'")',500);
}

function func_fillcompanyname_sub(initialDisplay)
{
	var searchv = $F('search');
	var searchby = $F('searchby');
	var limit_to = $F('limit_to');
	var tt = $F('tt');
	var cp = $F('cp');
	var gi = $F('gi');
	var bi = $F('bi');
	var ig = ($('ig').checked==true?1:0);
	var jl = ($('jl').checked==true?24:0);

	var url = '<?=$etel_domain_path?>/admin/admin_JOSN.php';
	var pars = 'func=getCompanyInfo&search='+searchv+'&searchby='+searchby+'&tt='+tt+'&cp='+cp+'&bi='+bi+'&limit_to='+limit_to+'&gi='+gi+'&ig='+ig+'&jl='+jl;
	//document.location.href=url+'?'+pars;
	//alert(url+'?'+pars);
	var myAjax = new Ajax.Request( url, { method: 'post', parameters: pars, onComplete: func_fillcompanyname_response });
	$('query_time').innerHTML = "Querying Database...";

}
function func_remove_all()
{
	obj_element = $('companyname');
	obj_element.options.length=0;
	cs_URL_select = $('cs_URL');
	cs_URL_select.options.length=0;
}
function updateBatch(obj)
{
	var str = "";
	if(obj.value != 'A')
	{
		for(var i = 0; i<obj.options.length;i++)
		{
			if(obj.options[i].selected)
			{
				if(str) str+=", ";
				str += obj.options[i].value;
			}
		}
	}
	$('batchfield').value = str;
}

function updateList(alert_result)
{
	obj = $('batchfield');
	re = /[^\d]+/;
	var list = obj.value.split(re);
	var total = 0;
	var clist = $('companyname');
	if(clist.options.length<2 && 0)
		func_fillcompanyname();
	

	for(i=0;i<clist.length;i++)
	{
		clist[i].selected = false;
		for(j=0;j<list.length;j++)
		{
			if(clist[i].value==list[j])
			{
				total++;
				clist[i].selected = true;
			}
		}
	}
	if(total>1 && alert_result) alert(total+" Companys Selected.");
	if(total==0) clist[0].selected = true;
}

function togglebatch()
{
	if($('batchfieldtd').style.display == 'none')
		$('batchfieldtd').style.display = 'table-row';
	else
		$('batchfieldtd').style.display = 'none'
}
</script>

<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">
  <tr>
    <td width="50%"  valign="top"><table border="0" align="center" cellpadding="0" cellspacing="0">
        <tr >
          <td width="50%" height="25" align="right" valign="middle"><font face="verdana" size="1"> Type&nbsp;</font> </td>
          <td height="25"><select name="tt" id="tt" style="font-family:verdana;font-size:10px;width: 160px" onChange="func_fillcompanyname();">
		  	<option value="">All Merchant Types</option>
             <?=func_fill_combo_conditionally("select `transaction_type`, CONCAT(`transaction_type`,' (',count(userId),')') as out from cs_companydetails where $ignore $bank_sql_limit $uview_sql GROUP BY `transaction_type`",$_REQUEST['tt'],$cnn_cs); ?>
            </select>          </td>
        </tr>
        <tr>
          <td height="25" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Status </font> </td>
          <td height="25"><select name="cp" id="cp" style="font-family:verdana;font-size:10px;width: 160px" onChange="func_fillcompanyname();">
              <option value="" >Any Status</option>
              <?php
			  	$sql = "select `cd_completion`, count(*) as cnt from cs_companydetails where $ignore $bank_sql_limit $uview_sql GROUP BY `cd_completion` ORDER BY `cd_completion` DESC";
				$result = mysql_query($sql) or dieLog(mysql_error());
			  	while($cpl = mysql_fetch_assoc($result))
				{
					$key = $cpl['cd_completion'];
					$data = $etel_completion_array[intval($key)];
					if(!$data) $data = array('txt'=>'Invalid Status');
					echo "<option value='$key' style='".$data['style']."' ".($_REQUEST['cp']==$key?"selected":"").">".$data['txt']." (".$cpl['cnt'].")</option>\n";
				}
			 ?>
            </select></td>
        </tr>
        <tr style="visibility:<?=$mode=='full'?"visible":"hidden"?>;">
          <td height="25" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Bank </font> </td>
          <td height="25"><select name="bi" id="bi" style="font-family:verdana;font-size:10px;width: 160px" onChange="func_fillcompanyname();">
              <option value="" >Any Bank</option>
             <?php if ($mode=='full') func_fill_combo_conditionally("select bank_id, bank_name from cs_bank ORDER BY `bank_id` ASC ",$_REQUEST['bi'],$cnn_cs); ?>
            </select></td>
        </tr>
        <tr align="left" style="visibility:<?=$mode=='full'?"visible":"hidden"?>;">
          <td height="25" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Gateway </font> </td>
		  <td height="25">
		  <select name='gi' id='gi' style="font-family:verdana;font-size:10px;width: 160px" onChange="func_fillcompanyname();">
		  <option value="">Any Gateway</option>
             <?php if ($mode=='full') func_fill_combo_conditionally("select gateway_id, concat(gw_title,' - (',count(*),')') from cs_companydetails left join etel_gateways on gw_id = gateway_id where $ignore $bank_sql_limit $uview_sql and gw_database = '".$_SESSION['gw_database']."' GROUP BY `gateway_id` ",$_REQUEST['gi'],$cnn_cs); ?>
		</select>		</td>
        </tr>
        <tr align="left">
          <td height="25" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
            <input id="ig" name="ig" type="checkbox" value="1" <?=($_REQUEST['ig']?"checked":"")?> onChange="func_fillcompanyname();">
          </font></td>
          <td height="25"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"> Show Ignored Companys</font></td>
        </tr>
        <tr align="left" style="visibility:<?=$mode=='full'?"visible":"hidden"?>;">
          <td height="25" align="right"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">
            <input id="jl" name="jl" type="checkbox" value="1" <?=($_REQUEST['jl']?"checked":"")?> onChange="func_fillcompanyname();" <?=$_REQUEST['jl']?"checked":""?>>
            </font> </td>
          <td height="25"><font size="1" face="Verdana, Arial, Helvetica, sans-serif">Company Joined in  last 24 hours. </font></td>
        </tr>
        <tr align="left" style="visibility:<?=$mode=='full'?"visible":"hidden"?>;">
          <td height="25" align="right">&nbsp;</td>
          <td height="25"><font face="verdana" size="1"><a href="javascript:togglebatch()" >Show Batch Companys</a></font></td>
        </tr>
        
      </table>
    <font face="verdana" size="1"><a href="javascript:togglebatch()" ></a></font>	</td>
    <td width="50%" valign="top"><table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="204">
        <tr>
          <td valign="middle" align="right" width="40%"><font face="verdana" size="1">Search With:&nbsp;</font> </td>
          <td width="60%"><input value="<?=$_REQUEST['search']?>" type="text" name="search" id="search" size="10" style="font-family:verdana;font-size:10px;width: 160px" onKeyUp="func_fillcompanyname();" >          </td>
        </tr>
        <tr>
          <td height="41" valign="middle" align="right" width="40%"><font face="verdana" size="1">Search By:&nbsp;</font> </td>
          <td width="60%"><select name="searchby" id="searchby" style="font-family:verdana;font-size:10px;width: 160px" onChange="func_fillcompanyname();">
			<option value="ca">Any Field</option>
            <optgroup label="Company Data" >
			<option value="cn">Merchant Name</option>
            <option value="ri">Reference ID</option>
            <option value="id">Merchant ID (CSV List)</option>
            <option value="mn">Merchant Notes </option>
            <option value="un">Login UserName</option>
            <option value="em">Contact Email</option>
            <option value="bn">Beneficiary Name</option>
            <option value="an">Account Number</option>
            <option value="st">Active Status (Yes or No)</option>
            <option value="ps">Payable Status (Yes or No)</option>
            <option value="lp">Processed in Last # Days</option>
			</optgroup>
			<optgroup label="Website Data">
            <option value="wn">Website Name</option>
            <option value="wr">Website Reference ID</option>
            <option value="ws">Website Status</option>
			</optgroup>
            </select>
			<script>
				$('searchby').value = '<?=$_REQUEST['searchby']?$_REQUEST['searchby']:'ca'?>';
			</script>			</td>
        </tr>
        <tr>
          <td valign="middle" align="right" width="40%"><font face="verdana" size="1">Website:&nbsp;</font> </td>
          <td width="60%"><select name="cs_URL" id="cs_URL" style="font-family:verdana;font-size:10px;width: 160px" onChange="selectCompany(this)" onBlur="selectCompany(this)" onFocus="selectCompany(this)">

            </select>          </td>
        </tr>
        <tr>
          <td height="30"  valign="top" align="right"><p><font face="verdana" size="1">Select Company:&nbsp;</font></p>
          <p>
            <input name="" type="button" id="Crop" value="Crop to Selected" style="font-family:verdana;font-size:10px;" onClick="cropRemove(true);"> <br>
            <input name="" type="button" id="Crop" value="Remove Selected" style="font-family:verdana;font-size:10px;" onClick="cropRemove(false);">
			<br>
			<font face="verdana" size="1">Limit Result:</font><input name="limit_to" type="text" id="limit_to" style="font-family:verdana;font-size:10px;" value="300" size="6" maxlength="4" onKeyUp="func_fillcompanyname();">
			<br />
			<font face="verdana" size="1"><label id="query_time"></label> </font>          </p></td>
          <td><select name="companyname[]" size="10" multiple id="companyname" style="font-family:verdana;font-size:10px;width: 160px" onChange="updateBatch(this)">
            <option value="A" selected>All Companys</option>

            </select>          </td>
        </tr>
      </table></td>
  </tr>
  <tr id="batchfieldtd" style="display:none">
    <td align="left" colspan="2"><textarea name="batchfield" rows="1" wrap="virtual" id="batchfield" style="font-family:verdana;font-size:8px;WIDTH: 550px" onmouseover="this.rows=this.value.length/145+1" onmouseout="this.rows=1" onchange="updateList(true)"><?=quote_smart($_REQUEST['batchfield'])?></textarea>
  </tr>
  <?php if($submittable) { ?>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;
      <input type="button" id="viewcompany" src="../images/view.jpg" value="View" onClick="viewCompany(this)">
  </tr>
  <?php } ?>
</table>
      <input type="hidden" value="" id="cd_view" name="cd_view">
  <input type="hidden" value="" id="userIdList" name="userIdList">  
<script language="javascript">
<!--
func_fillcompanyname(1);

-->
</script>
<?php

	$form_html = ob_get_clean ();
	return $form_html;
}


?>
