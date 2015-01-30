<?php
	$noheader=true;
	$headerInclude="reports";
	$periodhead="Ledgers";
	include("quickstats.php");
	exit();
include("includes/sessioncheck.php");

require_once("includes/function.php");
include("includes/header.php");
$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";

$i_from_day = date("d");
$i_from_month = date("m");
$i_from_year = date("Y");
$i_to_day = date("d");
$i_to_month = date("m");
$i_to_year = date("Y");

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2);


$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;
	
$qry_details="SELECT * FROM `cs_company_sites` WHERE `cs_company_id` = '$sessionlogin'  AND `cs_gatewayId` = ".$_SESSION["gw_id"]."  AND cs_hide = '0' ORDER BY `cs_URL` ASC";	
$rst_details=mysql_query($qry_details,$cnn_cs) or dieLog(mysql_errno().": ".mysql_error()."<BR>Cannot execute query");

$siteList = "";

while($site = mysql_fetch_assoc($rst_details))
{
	$siteList.= "<option value='".$site['cs_ID']."' ".($site['cs_ID']==$siteID?"selected":"").">".str_replace('http://','',$site['cs_URL'])."</option>";
}

	$qrt_select_company = "select distinct userid,companyname from cs_companydetails order by companyname";
	
    if(!($show_select_sql =mysql_query($qrt_select_company)))
	{			dieLog(mysql_errno().": ".mysql_error()."<BR>");

	}
	if(!isset($period))
	{
	  $period="p";      
	}
    if($period=="p")
	{
	   $periodstring="Start Date";
	   $endperiodstring = "End Date";
       
	}
	
?>
<!--<script language="javascript" src="scripts/calendar1.js"></script>
<script language="javascript" src="scripts/general.js"></script> -->
<script language="javascript">
function datefn(){   
checkval=true          
datestring=document.forms[0].txtDate1.value  	
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate1'
	   }
	 datestring=document.forms[0].txtDate.value
	 if(!checkDateString(datestring,'mdy','/')){
		 checkval=false
		 fname='txtDate'
	   }
	  if(!checkval){
		 alert("Please enter correct date") 
		 eval("document.forms[0]." + fname + ".focus()");
		 return false
	  }
	  else{
		return true
	  }
  
}

function showType(){
	if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="C") {
		document.ledger.type[0] = new Option("All","A");
		document.ledger.type[1] = new Option("Savings Account","S");
		document.ledger.type[2] = new Option("Checking Account","C");
		document.ledger.type.disabled = false;
	} else if(document.ledger.crorcq.options[document.ledger.crorcq.selectedIndex].value=="H") {
		document.ledger.type[0] = new Option("All","A");
		document.ledger.type[1] = new Option("Master Card","M");
		document.ledger.type[2] = new Option("Visa","V");
		document.ledger.type.disabled = false;
	}
	else{
		document.ledger.type.value= "";
		document.ledger.type.disabled = true;
	}
	return false;
}

</script>
<table border="0" cellpadding="0" width="100%" cellspacing="0" height="60%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="75%" >
	<tr>
	<td height="22" align="left" valign="top" width="1%" background="<?=$tmpl_dir?>images/menucenterbg.gif" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopleft.gif" width="8" height="22"></td>
	      <td height="22" align="center" valign="middle" width="50%" background="<?=$tmpl_dir?>images/menucenterbg.gif" ><span class="whitehd">Ledgers</span></td>
	<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menutopcurve.gif" width="49" height="22"></td>
	<td height="22" align="left" valign="top" width="45%" background="<?=$tmpl_dir?>images/menutoprightbg.gif" ><img alt="" src="<?=$tmpl_dir?>images/spacer.gif" width="1" height="1"></td>
	<td height="22" align="right" valign="top" background="<?=$tmpl_dir?>images/menutoprightbg.gif"  width="1%" nowrap><img border="0" src="<?=$tmpl_dir?>images/menuright.gif" width="10" height="22"></td>
	</tr>      
	<tr>
        <td width="100%"  valign="top" align="center" class="lgnbd" colspan="5">
		<form name="ledger" action="reportBottomSummary.php" method="get">
		<input type="hidden" name="period" value="<?=$period?>"></input>
	 <br> <table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%">  
	
	   <tr>
		  <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1"><?=$periodstring?></font></td><td align="left" width="60%"  height="30" >&nbsp;
		<!--	 <input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;"> -->
		   <select name="opt_from_month" style="font-size:10px">
			<?php func_fill_month($i_from_month); ?>
		   </select>
			<select name="opt_from_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_from_day); ?>
		   </select>
			 <select name="opt_from_year" style="font-size:10px">
			<?php func_fill_year($i_from_year); ?>
		   </select>
		   </td>
		</tr>
        <tr>
		  <td height="30" valign="middle" align="right" width="40%"><font face="verdana" size="1"><?=$endperiodstring?></font></td><td align="left" width="60%"  height="30"  >&nbsp;
		<!--	<input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>&nbsp;&nbsp;<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()">-->
			  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
			  </select>
			<select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
			  </select>
			  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
			  </select>
		 </td>   
        </tr>
		<tr>
			<td  height="30"  valign="middle" align="right">
		  <font face="verdana" size="1">Websites</font></td>
			<td align="left" width="400">&nbsp;
			  <select style="font-family:verdana;font-size:10px;WIDTH: 140px" name="selectSite" id="selectSite">
            <option value="-1">All Sites</option>
            <?=$siteList?>
          </select>
</td>
        </tr>
		<tr>
			<td  height="30"  valign="middle" align="right">
		  <font face="verdana" size="1">Payment Type</font></td><td align="left" width="400">&nbsp;&nbsp;<select name="crorcq" style="font-family:verdana;font-size:10px;WIDTH: 140px" onChange="javascript:showType()">
		  <option value='A' selected  >All</option>
		  <option value='C'  >Check</option>
          <option value='H'  >Credit Card</option>
          <option value="W">ETEL900</option>
		  </select></font>
		   </td>
        </tr>
		<tr> 
		  <td  height="30"  valign="middle" align="right"> <font face="verdana" size="1">Card/Check Type</font></td>
		  <td align="left" width="400">&nbsp;
			<select name="type" style="font-family:verdana;font-size:10px;WIDTH: 140px" disabled>
			</select></font>
			</td>
		</tr>

	<input type="hidden" value="" name="id" ></input>
	<input type="hidden" value="" name="cnumber"></input>
	<tr>
	 <td  height="50"  valign="middle" align="center" colspan='2'>
	  <?php
		  		if ( trim($_SESSION["sessionactivity_type"]) != "Test Mode" ) {
	  ?> 
		 <input type="image" id="reportview" src="<?=$tmpl_dir?>images/view.jpg"></input>
	  <?php
	  	} else {
				print("<font size=\"1\" face=\"Verdana\" color=\"Red\">Only active companies can view ledgers.</font>");
		}
	   ?>		 
		</td>
	</tr>
	</table>
	</form>
	</td>
 </tr>
<tr>
<td width="1%"><img src="<?=$tmpl_dir?>images/menubtmleft.gif"></td>
<td colspan="3" width="98%" background="<?=$tmpl_dir?>images/menubtmcenter.gif"><img border="0" src="<?=$tmpl_dir?>images/menubtmcenter.gif" width="6" height="11"></td>
<td width="1%" ><img src="<?=$tmpl_dir?>images/menubtmright.gif"></td>
</tr>
</table>
</td>
</tr>
</table>
	
<?php
include("includes/footer.php");
?>