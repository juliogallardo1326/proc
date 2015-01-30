<?php
function genResellerViewTable($qrt_select_reseller,$redirectOne,$redirectAll,$mode='minimal',$title='Select Reseller',$showTable=true)
{
	if(!$redirectOne) $redirectOne= basename($_SERVER['PHP_SELF']);
	if(!$redirectAll) $redirectAll= basename($_SERVER['PHP_SELF']);
	
	require_once("../includes/function2.php");

	global $bank_sql_limit;

	if(!$qrt_select_reseller) $qrt_select_reseller =" from cs_resellerdetails order by reseller_companyname";

	$qrt_select_reseller = "select reseller_id,reseller_companyname,rd_referenceNumber".$qrt_select_reseller;

	//$show_reseller_sql =mysql_query($qrt_select_reseller) or dieLog(mysql_errno().": ".mysql_error()."<BR>");

if($showTable) beginTable();
?>
<script language="JavaScript">
var Reseller = new Array();
var cs_URL = new Array();

function selectReseller(obj)
{
	obj_element = document.getElementById('resellername');
	obj_element.value = obj.value;
}

function DisplayReseller(){
	if(document.getElementById('Resellermode').value=="A") {
		document.getElementById('allC').style.display = "";
		document.getElementById('active').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.getElementById('Resellermode').value=="AC") {
		document.getElementById('active').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('nonactive').style.display = "none";
	} else if(document.getElementById('Resellermode').value=="NC") {
		document.getElementById('nonactive').style.display = "";
		document.getElementById('allC').style.display = "none";
		document.getElementById('active').style.display = "none";
	}
	document.getElementById('resellername').selectedIndex = 0;
	document.getElementById('activename').selectedIndex = 0;
	document.getElementById('nonactivename').selectedIndex = 0;

}
function viewReseller() {
	var liststr = "";
	var curval = 0;
	var curreselId = 0;
	var length = document.getElementById('resellername').length;
	if(document.getElementById('resellername').value=="") {
	 //alert("Please select the Reseller.");
	 return 0;
	}
	if(document.getElementById('resellername').value == 'A')
	{
		curval='A';
	}
	for(i=0;i<length;i++)
	{
		if(document.getElementById('resellername').value == 'AL' || document.getElementById('resellername').options[i].selected) 
		{
			curreselId = document.getElementById('resellername').options[i].value;
			if (curreselId == 'A') {curVal='A'; break;}
			if (!window.redirectTo) 
			{
				if(curreselId!='AL')
				{
					if(!liststr) liststr=curreselId;
					else liststr += '|'+curreselId;
				}
			}
			else reselId[curval] = curreselId;
			curval++;
		}
	}
	if (window.redirectTo) redirectTo(reselId);
	
	if(curval==1) document.getElementById('frmSelComp').action='<?=$redirectOne?>';
	else document.getElementById('frmSelComp').action='<?=$redirectAll?>';
	
	document.getElementById('rd_view').value = curval;
	document.getElementById('resellername').value = '';
	resFunc_removeitem();
	document.getElementById('reselIdList').value = liststr;
	
	
	
	document.getElementById('frmSelComp').submit();
}

function DisplayResellertype() {
	document.getElementById('trans_type').value="Submit";
	//document.dates.action = "viewReseller.php";
	//document.dates.submit();
}
function func_fillresellername()
{
	var str_comparison;
	obj_element = document.getElementById('resellername');
	var str_search = document.getElementById('txt_resellername').value.toLowerCase();
	var str_search_refNum = document.getElementById('txt_ResellerrefNum').value.toLowerCase();
	var i_length = str_search.length;
	var i_length_refNum = str_search_refNum.length;
	var i_arraylength = Reseller.length
	var statusSelect = 1;
	resFunc_removeitem();
	
	

	obj_element.options.length=obj_element.options.length+1;
	obj_element.options[obj_element.options.length-1].value="AL";
	obj_element.options[obj_element.options.length-1].text="All Resellers in List";
	obj_element.options[0].selected=true;

	for (i=0;i<i_arraylength;i++)
	{
		str_comparison = Reseller[i]['name'].toLowerCase();
		str_comparison_refNum = Reseller[i]['refNum'].toLowerCase();
		
		comparison = str_comparison.indexOf(str_search) != -1;
		refcomparison = str_search_refNum==str_comparison_refNum;
		if(str_search.length == 0) comparison = true;
		if(str_search_refNum.length == 0) refcomparison = true;


		if(comparison && refcomparison)
		{
			obj_element.options.length=obj_element.options.length+1;
			obj_element.options[obj_element.options.length-1].value=Reseller[i]['id'];
			obj_element.options[obj_element.options.length-1].text=Reseller[i]['name'];
			obj_element.options[obj_element.options.length-1].title=Reseller[i]['request'];
			obj_element.options[obj_element.options.length-1].style.fontWeight=Reseller[i]['style'];
		}
	}
	<?php if($_REQUEST['resubmit']) { ?>
	document.getElementById('resellername').value == 'AL';
	viewReseller();
	<?php } ?>
}
function resFunc_removeitem()
{
	obj_element = document.getElementById('resellername');
	obj_element.options.length=0;
}
function loadReseller(cnt,id,name,refNum)
{
	Reseller[cnt] = new Array(); 
	Reseller[cnt]['id'] = id; 
	Reseller[cnt]['name'] = name;
	Reseller[cnt]['refNum'] = refNum; 
}
function resLoadWebsite(cnt,URL,Reseller_id)
{
	cs_URL[cnt] = new Array(); 
	cs_URL[cnt]['URL'] = URL; 
	cs_URL[cnt]['Reseller_id'] = Reseller_id;

}
function resUpdateBatch(obj)
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
	document.getElementById('resbatchfield').value = str;
}

function resUpdateList(obj)
{
	re = /[^\d]+/;
	var list = obj.value.split(re);
	var total = 0;
	var clist = document.getElementById('resellername');
	
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
	if(total>1) alert(total+" Resellers Selected.");
}
</script>

<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="100">
  <tr>
    <td width="50%" valign="top"><table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="90">
        <tr>
          <td valign="top" align="right"><font face="verdana" size="1">Reseller Name&nbsp;:</font> </td>
          <td valign="top"><input value="<?=$_REQUEST['txt_resellername']?>" type="text" name="txt_resellername" id="txt_resellername" size="10" style="font-family:verdana;font-size:10px;WIDTH: 210px" onKeyUp="func_fillresellername();">
          </td>
          <td width="10%" height="30" rowspan="3" align="right"  valign="top"> <font face="verdana" size="1">Select Reseller&nbsp;:&nbsp;</font></td>
          <td rowspan="3"><select name="resellername" size="6" multiple id="resellername" style="font-family:verdana;font-size:10px;WIDTH: 210px" onChange="resUpdateBatch(this)">
              
            </select>
          </td>
        </tr>
        <tr>
          <td align="right" valign="top"><font face="verdana" size="1">Reseller Reference Num&nbsp;:</font> </td>
          <td valign="top"><input value="<?=$_REQUEST['txt_ResellerrefNum']?>" name="txt_ResellerrefNum" type="text" id="txt_ResellerrefNum" style="font-family:verdana;font-size:10px;WIDTH: 210px" onKeyUp="func_fillresellername();" size="10"></td>
        </tr>
        <tr>
          <td align="right" valign="top"><font face="verdana" size="1">Batch Resellers : </font></td>
          <td valign="top"><textarea name="resbatchfield" rows="1" wrap="VIRTUAL" id="resbatchfield" style="font-family:verdana;font-size:8px;WIDTH: 210px" onMouseOver="this.rows=this.value.length/45+2" onMouseOut="this.rows=1" onChange="resUpdateList(this)"><?=quote_smart($_REQUEST['toresbatchfield'])?>
          </textarea></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="center" colspan="2">&nbsp;&nbsp;&nbsp;
      <input type="button" id="viewreseller" src="../images/view.jpg" value="View" onClick="viewReseller()">
      <input type="hidden" value="" id="rd_view" name="rd_view">
      <input type="hidden" value="" id="reselIdList" name="reselIdList"> </td>
  </tr>
</table>
<script language="javascript">
<!--
<?php func_multiselect_reseller_jsarray($qrt_select_reseller);?>
func_fillresellername();
<?php if($_REQUEST['toresbatchfield']) { ?>resUpdateList(document.getElementById('resbatchfield'));<?php } ?>

-->
</script>
<?php

if($showTable) endTable($title,'',false,false,false,'frmSelComp');

}


function func_multiselect_reseller_jsarray($qrt_select_reseller) {

	global $adminInfo;
	global $rd_completion_array;

   	if(!($show_nonactive_sql = mysql_query($qrt_select_reseller)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if (mysql_num_rows($show_nonactive_sql) > 0 && $mode=='full') {
		print"<option value='A' selected>All Resellers</option>";	  
	}
	$icount=0;
	while($ResellerInfo = mysql_fetch_array($show_nonactive_sql)) 
	{

?>
loadReseller('<?=$icount?>','<?=$ResellerInfo['reseller_id']?>','<?=addslashes($ResellerInfo['reseller_companyname'])?>','<?=$ResellerInfo['rd_referenceNumber']?>');
<?php	

		$icount++; 
	}	
	//print $options;
}

?>
