<?php
if (!$printable_version){

if (!isset($headerInclude)) {
	$headerInclude = "blank";
}
if ($headerInclude=="blank") {
?>
		<!--submenu starts-->
		<style type="text/css">
<!--
.style2 {font-size: 12px}
-->
        </style>
		
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td width="100%" height="20" align="left">&nbsp;</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		<tr>
		<td bgcolor="#78B6C2" height="25" class="blackbd" valign="middle" align="center">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" height="10">
		<tr>
		<td  height="20" valign="middle" align="center">&nbsp;</td>
		</tr>
		</table>
		</td>
		</tr>
		</table>
		<!--submenu ends-->
		<!--submenu starts-->
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
		<tr>
		<td height="5" background="images/menubtmbg.gif"><img alt="" src="images/spacer.gif" width="1" height="1"></td>
		</tr>
		</table>
		<!--submenu ends-->

<?php


}//Printable?

if ($display_stat_wait == true){
?>
<div id="hidewait" align="center"><br><img src="images/stats_wait.gif" width="355" height="33"></div>
<?php } ?>
