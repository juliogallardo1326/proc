<?php
require_once("includes/sessioncheck.php");
include("../includes/completion.php");
include("../includes/companySubView.php");

$cd_completion_array[-1]['txt']="Old Company [No Status]";
$headerInclude = "ledgers";
require_once("includes/header.php");


$global_row = 1;
function gen_row($change = false)
{
	global $global_row;
	if($change) $global_row = 3-$global_row;
	return $global_row;
}


$i_to_day = substr($str_return_date,8,2);

$i_from_year = (isset($_REQUEST["opt_from_year"])?quote_smart($_REQUEST["opt_from_year"]):date("Y"));
$i_from_month = (isset($_REQUEST["opt_from_month"])?quote_smart($_REQUEST["opt_from_month"]):date("m"));
$i_from_day = (isset($_REQUEST["opt_from_day"])?quote_smart($_REQUEST["opt_from_day"]):date("d"));
$i_to_year = (isset($_REQUEST["opt_to_year"])?quote_smart($_REQUEST["opt_to_year"]):date("Y"));
$i_to_month = (isset($_REQUEST["opt_to_month"])?quote_smart($_REQUEST["opt_to_month"]):date("m"));
$i_to_day = (isset($_REQUEST["opt_to_day"])?quote_smart($_REQUEST["opt_to_day"]):date("d"));

$from_time = mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year);
$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_from_month,$i_from_day,$i_from_year));
$i_from_year = substr($str_return_date,0,4);
$i_from_month = substr($str_return_date,5,2);
$i_from_day = substr($str_return_date,8,2);

$to_time = mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year)+24*60*60;
$str_return_date = date("Y m d H:i",mktime(0,0,0,$i_to_month,$i_to_day,$i_to_year));
$i_to_year = substr($str_return_date,0,4);
$i_to_month = substr($str_return_date,5,2);
$i_to_day = substr($str_return_date,8,2); 


$sql = "SELECT  
COUNT(tc.tc_IP) as Clicks,
COUNT(distinct tc.tc_IP) as UniqueClicks
FROM `cs_tracking_click` AS tc
LEFT JOIN `cs_tracking_url` AS tu ON `tu_ID` = `tc_refer_ID`
LEFT JOIN `cs_tracking_host` AS th ON `th_ID` = `tu_host`
WHERE  th_internal=0 AND tc.tc_time between '$from_time' AND '$to_time'
";
$result = mysql_query($sql) or dieLog(mysql_error());
$totalClickInfo = mysql_fetch_assoc($result);

$duration = microtime_float();
$sql = "SELECT  
COUNT(tc.tc_IP) as Clicks,
COUNT(distinct tc.tc_IP) as UniqueClicks,
th_host, tu_URL, th_ID, 
COUNT(distinct userId) as Signups
FROM `cs_tracking_host` AS th  FORCE INDEX ( PRIMARY)
left join `cs_tracking_url` AS tu ON `th_ID` = `tu_host`
left join `cs_tracking_click` AS tc ON `tu_ID` = `tc_refer_ID`
left join `cs_companydetails` AS cd on th_ID=cd_th_ID AND cd_th_ID is not null
 AND cd.date_added between '".date("Y-m-d g:i:s",$from_time)."' AND '".date("Y-m-d g:i:s",$to_time)."'
WHERE  th_internal=0 AND tc.tc_time between '$from_time' AND '$to_time'
GROUP BY th.th_ID
ORDER BY 
 `Signups`  DESC,
 `UniqueClicks`  DESC,
 tu_URL ASC,
`Clicks`  DESC
";

$result = mysql_query($sql) or dieLog(mysql_error());
$duration = microtime_float()-$duration;


	beginTable(); 
?>
<script language="javascript" src="../scripts/calendar_new.js"></script>
<table width="100%" cellpadding="0" class="websites">
            <tr>
                        <td height="22" valign="middle"   align="right" width="124">From<font face="verdana" size="1">&nbsp; 
                          </font></td>
                        <td align="left" width="228"  height="22" ><select name="opt_from_month" style="font-size:10px">
                      <?php func_fill_month($i_from_month); ?>
                    </select> <select name="opt_from_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_from_day); ?>
                    </select></font>
                    <select name="opt_from_year" style="font-size:10px">
                      <?php func_fill_year($i_from_year); ?>
                    </select>
					<input type="hidden" name="from_date" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					<input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(350,90,this.form.from_date)">
					</td> </tr><tr> 
                        <td height="30" valign="middle" align="right" width="124">To<font face="verdana" size="1">&nbsp; 
                          </font></td>
                        <td align="left" width="228"  height="30"><select name="opt_to_month" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_month($i_to_month); ?>
                    </select> <select name="opt_to_day" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_day($i_to_day); ?>
                    </select> <select name="opt_to_year" class="lineborderselect" style="font-size:10px">
                      <?php func_fill_year($i_to_year); ?>
                    </select> 
					 <input type="hidden" name="from_to" size="17" style="font-family: Verdana; font-size: 8pt; border: 1 solid " value="">
					  <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init(350,90,this.form.from_to)">

			</td>
			</tr>
			<tr align="center" valign="middle">
			
              <td height="30" class="subheader" colspan="4"><input type="submit" name="Submit" value="Submit"></td>
            </tr>
			<tr align="center" valign="middle">
			
              <td height="30" class="header" colspan="4"><?=$totalClickInfo['Clicks']?> Clicks. <?=$totalClickInfo['UniqueClicks']?> Unique. 
              <BR><?=$msg?></td>
            </tr>
            <tr align="center" valign="middle">
              <td height="30" class="subheader" colspan="4">Click Information in <?=$duration?> Seconds<hr>

              </td>
            </tr>
            <tr align="center" valign="middle">
              <td height="30">Referer</td>
              <td height="30">Signups </td>
              <td height="30">Unique Clicks </td>
              <td height="30">Total Clicks </td>
            </tr>
            <?php
	if(mysql_num_rows($result)>0)
	{
	
		while ($clickInfo = mysql_fetch_assoc($result))
		{	

		$url_parts = parse_url($clickInfo['tu_URL']);
		
		$host_display = $clickInfo['th_host'];
		if($clickInfo['th_name']) $host_display = $clickInfo['th_name'];
		
		if ($click_URL_host != $url_parts['host'] && 0) {
		$click_URL_host = $url_parts['host'];
		?>
				<tr align="center" valign="middle">
				  <td height="30" colspan="3" align="center">
				  <?=$click_URL_host?></td>
				</tr>
		<?php }?>
				<tr align="center" valign="middle" class="row<?=gen_row(1)?>">
				  <td height="30"><a href="http://<?=$clickInfo['th_host']?><?=$clickInfo['tu_URL']?>"><?=substr($host_display,0,50)?></a></td>
				  <td height="30"><?=($clickInfo['Signups']?$clickInfo['Signups']:"- None - ")?></td>
				   <td height="30"><?=($clickInfo['UniqueClicks']?$clickInfo['UniqueClicks']:"- None - ")?>                <BR>                <label id='rr_label_<?=$clickInfo['cs_ID']?>'></label>            </td>
				  <td height="30"><?=($clickInfo['Clicks']?$clickInfo['Clicks']:"- None - ")?></td>
			   </tr>
				<?php
		
		}
	}

?>
</table>
<?php 
	endTable("Tracking Information","trackingReport.php");
include("includes/footer.php");
?>
