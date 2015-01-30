<?php
//******************************************************************//
//  This file was created/modified by Ari Asulin.        	        //
//                                                                  //
//******************************************************************//
 
include("includes/sessioncheck.php");

$headerInclude = "customerservice";
include("includes/header.php");

//$headerInclude="companies";

include("includes/message.php");
?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<script language="JavaScript" type="text/JavaScript">
function addvalue(dateSelected,monthSelected,yearSelected,obj_element)
{
	var obj_form = document.frm_enquires;
	if (obj_element.name == "from_date"){
		obj_form.opt_from_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_from_month.selectedIndex = monthSelected ;
		obj_form.opt_from_year.selectedIndex = func_returnselectedindex(yearSelected) ;
	}
	if (obj_element.name == "from_to"){
		obj_form.opt_to_day.selectedIndex = dateSelected-1 ;
		obj_form.opt_to_month.selectedIndex = monthSelected ;
		obj_form.opt_to_year.selectedIndex = func_returnselectedindex(yearSelected);
	}
}
function func_returnselectedindex(par_selected)
{
	var dt_new =  new Date();
	var str_year = dt_new.getFullYear()
	for(i=2003,j=0;i<str_year+10;i++,j++)
	{
		if (i==par_selected)
		{
			return j;
		}
	}
}
</script>
<?php

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
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day;
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day;$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 
	
$i_from_year = (isset($HTTP_POST_VARS["opt_from_year"])?quote_smart($HTTP_POST_VARS["opt_from_year"]):$i_from_year);
$i_from_month = (isset($HTTP_POST_VARS["opt_from_month"])?quote_smart($HTTP_POST_VARS["opt_from_month"]):$i_from_month);
$i_from_day = (isset($HTTP_POST_VARS["opt_from_day"])?quote_smart($HTTP_POST_VARS["opt_from_day"]):$i_from_day);
$i_to_year = (isset($HTTP_POST_VARS["opt_to_year"])?quote_smart($HTTP_POST_VARS["opt_to_year"]):$i_to_year);
$i_to_month = (isset($HTTP_POST_VARS["opt_to_month"])?quote_smart($HTTP_POST_VARS["opt_to_month"]):$i_to_month);
$i_to_day = (isset($HTTP_POST_VARS["opt_to_day"])?quote_smart($HTTP_POST_VARS["opt_to_day"]):$i_to_day);
$str_enquiry = (isset($HTTP_POST_VARS["hid_enquiry"])?quote_smart($HTTP_POST_VARS["hid_enquiry"]):"");
$str_duplicates = (isset($HTTP_POST_VARS["chk_duplicates"])?quote_smart($HTTP_POST_VARS["chk_duplicates"]):"");
$str_cancelled = (isset($HTTP_POST_VARS["chk_cancelled"])?quote_smart($HTTP_POST_VARS["chk_cancelled"]):"");

$str_from_date = $i_from_year."-".$i_from_month."-".$i_from_day." 00:00:00";
$str_to_date = $i_to_year."-".$i_to_month."-".$i_to_day." 23:59:59";
//$iCount =  func_sel_count($str_from_date,$str_to_date);
?>
<?php
	if ($str_enquiry == "")
	{
 ?>	
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
			<tr>
			   <td width="95%" valign="top" align="center" ><br>
			<form name="frm_enquires" action="enquires.php" method="post">
			<table width="50%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
		     <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Unfound Calls</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table border="0" align="center" cellpadding="0" cellspacing="0" width="100%" height="10">
			     <tr><td height="10"  valign="middle" align="center" width="50%">
			  	</td> 
			    </tr>  
			    <tr>
			  		<td height="40"  valign="middle" align="center" width="100%">			  			  
				     <font face="verdana" size="1">Start Date</font>&nbsp; 
			 	      <select name="opt_from_month" class="lineborderselect" style="font-size:10px">
			 	      <?php func_fill_month($i_from_month); ?>
		              </select>
					  <select name="opt_from_day" class="lineborderselect" style="font-size:10px">
		   			  <?php func_fill_day($i_from_day); ?>
			   	      </select>
		     		  <select name="opt_from_year" class="lineborderselect" style="font-size:10px">
		   			 <?php func_fill_year($i_from_year); ?>
		   			 </select>
					 <input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					 <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,280,document.frm_enquires.from_date)">
				   </td>
				</tr>
		       <tr>
		    <td height="40"  valign="middle" align="center"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font face="verdana" size="1">End Date&nbsp;</font>  
           
		  <select name="opt_to_month" class="lineborderselect" style="font-size:10px">
			<?php func_fill_month($i_to_month); ?>
		  </select>
		  <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
			<?php func_fill_day($i_to_day); ?>	
		  </select>
		  <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
			<?php func_fill_year($i_to_year); ?>
		  </select>
		   <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
		   <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(625,320,document.frm_enquires.from_to)">
		  &nbsp;&nbsp;&nbsp;&nbsp;
		  </td>
		 </tr>
		 <tr>
		 <td>
			<table width="282" align="center" border="0">
			<tr>
			  <td>&nbsp;<font face="verdana" size="1">Duplicates</font>
				<input type="checkbox" name="chk_duplicates" value="Y">
			  </td>
		  </tr>
		  </table>

		 </td>
		 </tr>
		 <tr>
		 <td align="center" height="30">
		  <input type="image" id="viewcompany" SRC="<?=$tmpl_dir?>/images/view.jpg"></input>
			</td>
		 </tr>
		 </table>      
		</td>
		</tr>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
		</table>    
<?php
 }
if ($str_enquiry != "" )
{
	if ($str_from_date != "")
	{
		if($str_duplicates == "Y")
		{
			$qry_select = "select customerPhone, count( customerPhone ) as num_calls";
			$qry_select .= " from cs_unfound_calls";
			$qry_select .= " where currentDateTime >= '".$str_from_date."'";
			if($str_to_date != "")
			{
				$qry_select .= " and currentDateTime <= '".$str_to_date."'";
			}
			if($str_cancelled == "Y")
			{
				$qry_select .= " and cancel_status = 'Y'";
			}
			$qry_select .= " group by customerPhone order by num_calls desc, customerPhone asc";
//			print($qry_select);
			$rssel_report = mysql_query($qry_select);
			//$i_count = mysql_num_rows($rssel_report);
			$str_report = "";
?>
			
			<?
			  $i_count = 0;
			  for($i=0;$i<mysql_num_rows($rssel_report);$i++)
			  {
				$str_phone	= mysql_result($rssel_report,$i,0);
				$i_call_count = mysql_result($rssel_report,$i,1);
				if($i_call_count > 1)
				{
					 $i_count++;
					 $str_report .= "<tr>";
					 $str_report .= "<td bgcolor='#E2E2E2' height='30'><font size='1' face='Verdana'>$i_count</font></td>";
					 $str_report .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana'>$str_phone</font></td>";
					 $str_report .= "<td bgcolor='#E2E2E2'><font size='1' face='Verdana' >$i_call_count&nbsp;</font></td>";
					 $str_report .= "</tr>";
			    }
			  }
			  
			 if ($str_report == "")
			 {
				$msgtodisplay="No Unfound Calls for this period.";
				$outhtml="y";				
				message($msgtodisplay,$outhtml,$headerInclude);									
				exit();	   
			 }

		 ?>
			<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
			<tr>
			   <td width="95%" valign="top" align="center" ><br>
			<form name="frm_enquires" action="enquires.php" method="post">

			<table width="75%" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
				  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif" ><span class="whitehd">Duplicate Call Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
			<table width="100%" cellspacing="1" cellpadding="1" border="0" align="center"  class="lgnbd">
			 <tr>
				  <td width="1%" bgcolor="#CCCCCC" height="30"><span class="subhd">No.</span></td>
				  <td width="9%" bgcolor="#CCCCCC"><span class="subhd">Phone 
					Number</span></td>
				  <td width="9%" bgcolor="#CCCCCC"><span class="subhd">Number of Calls</span></td>
			</tr>
			
			<?= $str_report;?>

			<tr>
			<td colspan="14" align="center" valign="middle" height="50"><a href="enquires.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td>
			</tr>
			</table>							
			</td>
			</tr>
			<tr>
			<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
			<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
			<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
			</tr>
			</table>							
	<?php
		}
		else
		{
			$str_data = "";
			$arr_call_duration = Array();
			$qry_select="Select unfound_id,customerName,customerAddress,customerPhone,notes,currentDateTime,call_duration,customer_service_id,cancel_status from cs_unfound_calls where currentDateTime >= '".$str_from_date."'";
			if($str_to_date != "")
			{
				$qry_select .= " and currentDateTime <= '".$str_to_date."'";
			}
			if($str_cancelled == "Y")
			{
				$qry_select .= " and cancel_status = 'Y'";
			}
			$qry_select .= " Order by currentDateTime desc, customerPhone asc";
	//		print($qry_select);
			$rssel_enqiry = mysql_query($qry_select);
			$i_count = mysql_num_rows($rssel_enqiry);
			if ($i_count == 0)
			{					
			$msgtodisplay="No Unfound Calls for this period.";
			$outhtml="y";				
			message($msgtodisplay,$outhtml,$headerInclude);									
			exit();	   
			}

			if ($i_count>0)
			{
			  for($i=0;$i<mysql_num_rows($rssel_enqiry);$i++)
			  {
				$i_unfound_id = mysql_result($rssel_enqiry,$i,0);
				$str_customerName = mysql_result($rssel_enqiry,$i,1);
				$str_customerAddress = mysql_result($rssel_enqiry,$i,2);
				$str_customerPhone  = mysql_result($rssel_enqiry,$i,3);
				$str_notes  = mysql_result($rssel_enqiry,$i,4);
				$str_currentDateTime =  mysql_result($rssel_enqiry,$i,5);
				$str_callDuration =  mysql_result($rssel_enqiry,$i,6);
				$i_customer_service_id = mysql_result($rssel_enqiry,$i,7);
				$str_cancel_status = mysql_result($rssel_enqiry,$i,8);

				$arr_call_duration[$i] = $str_callDuration;
				$str_customer_service_rep = "";
				if($i_customer_service_id == 0)
				{
					$str_customer_service_rep = "service";
				}
				else
				{
					$str_customer_service_rep = func_get_value_of_field($cnn_cs,"cs_customerserviceusers","username","id",$i_customer_service_id);
				}
			 
				$str_data .= "<tr>";
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >".($i + 1)."</font></td>";
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_customerName</font></td>";
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_customerAddress</font></td>";
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_customerPhone</font></td>";
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_notes</font></td>";
			  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >".func_get_date_time_12hr($str_currentDateTime)."</font></td>";		 		 
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_callDuration</font></td>";		 		 
				  $str_data .= "<td height='30' bgcolor='#E2E2E2'><font size='1' face='Verdana' >$str_customer_service_rep</font></td>";		 		 
				$str_data .= "</tr>";	 
	   		}
?>

		<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%" align="center">
		<tr>
		   <td width="95%" valign="top" align="center" ><br>
		<form name="frm_enquires" action="enquires.php" method="post">

		<table width="90%" border="0" cellspacing="0" cellpadding="0" height="10">
			<tr>
			<td height="22" align="left" valign="top" width="1%" background="../images/menucenterbg.gif" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopleft.gif" width="8" height="22"></td>
			  <td height="22" align="center" valign="middle" width="50%" background="../images/menucenterbg.gif"><span class="whitehd">Unfound Call&nbsp;Details</span></td>
			<td height="22" align="left" valign="top" width="3%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menutopcurve.gif" width="49" height="22"></td>
			<td height="22" align="left" valign="top" width="45%" background="../images/menutoprightbg.gif" ><img alt="" SRC="<?=$tmpl_dir?>/images/spacer.gif" width="1" height="1"></td>
			<td height="22" align="right" valign="top" background="../images/menutoprightbg.gif"  width="1%" nowrap><img border="0" SRC="<?=$tmpl_dir?>/images/menuright.gif" width="10" height="22"></td>
			</tr>
			<tr>
			<td class="lgnbd" colspan="5">
		  <table width="99%" cellspacing="3" cellpadding="3" border="0" align="center" height="61%">
			<tr>
			  <td colspan="9"><font size="1" face="Verdana" ><strong>Total Records are :
				<?=$i_count; ?>&nbsp;&nbsp;&nbsp;Total Call Duration : <?php print(func_get_total_call_duration($arr_call_duration)); ?></strong></font>
			  </td>
		   </tr>	  
			<tr align="center" valign="middle"> 
			  <td width="2%" height="30" bgcolor="#CCCCCC"><span class="subhd">No.</span></td>
			  <td width="8%" height="30" bgcolor="#CCCCCC"><span class="subhd">Customer Name</span></td> 
			  <td width="15%" height="30" bgcolor="#CCCCCC"><span class="subhd">Address</span></td>
			  <td width="8%" height="30" bgcolor="#CCCCCC"><span class="subhd">Phone</span></td>
			  <td width="23%" height="30" bgcolor="#CCCCCC"><span class="subhd">Notes</span></td>
			  <td width="19%" height="30" bgcolor="#CCCCCC"><span class="subhd">Date & Time</span></td>
			  <td width="5%" height="30" bgcolor="#CCCCCC"><span class="subhd">Call Duration</span></td>
			  <td width="12%" bgcolor="#CCCCCC"><span class="subhd">Customer Service Rep.</span></td>		 
	   </tr>

		<?php print($str_data);?>

		<tr>
		<td colspan="14" align="center" valign="middle" height="50"><a href="enquires.php"><img border="0" SRC="<?=$tmpl_dir?>/images/back.jpg"></a></td>
		</tr>
		 </table>
  		</td>
		</tr>
		<tr>
		<td width="1%"><img SRC="<?=$tmpl_dir?>/images/menubtmleft.gif"></td>
		<td colspan="3" width="98%" background="../images/menubtmcenter.gif"><img border="0" SRC="<?=$tmpl_dir?>/images/menubtmcenter.gif" width="6" height="11"></td>
		<td width="1%" ><img SRC="<?=$tmpl_dir?>/images/menubtmright.gif"></td>
		</tr>
	</table>
	<br>
<?php
         }
		}
	 } 
 } 
 ?>
  <input type="hidden" name="hid_enquiry" value="enquirey">
</form>
   </td>
</tr>
</table> <br>
<?php
include("includes/footer.php");
?>
<?php

function func_sel_count($str_from_date,$str_to_date)
	{
		$qry_count = "SELECT count(*) from cs_unfound_calls where  currentDateTime >= '".$str_from_date."' AND  currentDateTime <= '".$str_to_date."'";
		$rssel_count = mysql_query($qry_count);
		if (mysql_num_rows($rssel_count)>0)
		{
			$i_count = mysql_result($rssel_count,0,0);
			
		}
		else
		{
			$i_count = 0;
		}
		return $i_count;	
			
		  
	}		
?>