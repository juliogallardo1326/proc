<?php
$day = (isset($HTTP_GET_VARS['day'])?quote_smart($HTTP_GET_VARS['day']):"");
$recur_charge = (isset($HTTP_GET_VARS['recur_charge'])?quote_smart($HTTP_GET_VARS['recur_charge']):"");
$mon=(isset($HTTP_GET_VARS['mon'])?quote_smart($HTTP_GET_VARS['mon']):"");
 $curr=(isset($HTTP_GET_VARS['curr'])?quote_smart($HTTP_GET_VARS['curr']):"");
 $week=(isset($HTTP_GET_VARS['week'])?quote_smart($HTTP_GET_VARS['week']):"");
$i_recur_start_month = (isset($HTTP_GET_VARS['opt_recur_month'])?quote_smart($HTTP_GET_VARS['opt_recur_month']):"");
$i_recur_start_day = (isset($HTTP_GET_VARS['opt_recur_day'])?quote_smart($HTTP_GET_VARS['opt_recur_day']):"");
$i_recur_start_year = (isset($HTTP_GET_VARS['opt_recur_year'])?quote_smart($HTTP_GET_VARS['opt_recur_year']):"");
$productdescription= (isset($HTTP_GET_VARS['productdescription'])?quote_smart($HTTP_GET_VARS['productdescription']):"");
$mode= (isset($HTTP_GET_VARS['mode'])?quote_smart($HTTP_GET_VARS['mode']):"");

$text="";
$recurtimes= (isset($HTTP_GET_VARS['recur_times'])?quote_smart($HTTP_GET_VARS['recur_times']):"");			
$strtdate=$i_recur_start_day."-".$i_recur_start_month."-".$i_recur_start_year;
if ($mode=="D"){
$text=" and will continue to recur after every $day days ($recurtimes times or until you cancel it.) (For cancellation go to Rebilling Transaction and select the check box of the transaction to be cancelled and then click on the submit button.)";
}
else if($mode=="W"){
if($week==1){$week="Sunday";}elseif($week==2){$week="Monday";} elseif($week==3){$week="Tuesday";} elseif($week==4){$week="Wednesday";}  elseif($week==5){$week="Thursday";}  elseif($week==6){$week="Friday";} elseif($week==7){$week="Saturday";}    
$text=" and will continue to recur on every $week ($recurtimes times or until you cancel it.) (For cancellation go to Rebilling Transaction and select the check box of the transaction to be cancelled and then click on the submit button.";}
else if($mode=="M"){
/*if($mon==1){$mon= "January";}elseif($mon==2){$mon= "February";}  
 elseif($mon==3){$mon= "March";}  elseif($mon==4){$mon= "April";}                      
elseif($mon==5){$mon= "May";}  elseif($mon==6){$mon= "June";}       
elseif($mon==7){$mon= "July";} elseif($mon==8){$mon= "August";}
elseif($mon==9){$mon= "September";} elseif($mon==10){$mon= "October";}
elseif($mon==11){$mon= "November";} elseif($mon==12){$mon= "December";}*/
 

$text=" and will continue to recur on every $mon<sup>th</sup> of every month. ($recurtimes times or until you cancel it.) (For cancellation go to Rebilling Transaction and select the check box of the transaction to be cancelled and then click on the submit button.";}
else if($mode=="Y"){$text=" and will continue to recur after every year ($recurtimes times or until you cancel it.) (For cancellation go to Rebilling Transaction and select the check box of the transaction to be cancelled and then click on the submit button.";}

//$text=" and will continue to recur according to the mode you have selected.($recurtimes times or until you cancel it) .(For cancellation go to Rebilling Transaction and select the check box of the transaction to be cancelled and then click on the submit button)";
?>
<html>
<head>
<title>Payment Details</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function closewindow(){
self.close();}</script>
</head>
<body bgcolor="#CCCCCC">
<table  bgcolor="#CCCCCC">  
<tr bgcolor="#99CCCC"><td width=300 align='left'><font color="#006633" size="3" face="Verdana, Arial, Helvetica, sans-serif">Message</font></td></tr>
 <tr><td ><p align="justify"><font color="#CC3300" size="2"  face="Verdana, Arial, Helvetica, sans-serif">You have selected automatic rebilling .The rebilling will start on <i><?=$strtdate?></i>,<?=$text?></font></p> </td>
<tr><td><font color="#CC3300" size="2"  face="Verdana, Arial, Helvetica, sans-serif">A recur charge of <?=$curr?> <?=$recur_charge?> willbe charged per rebilling.</font></td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td align="center"><input type="button" value="Close" onClick="closewindow()"></td></tr>
</table>


</body>
</html>
