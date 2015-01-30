<script language="JavaScript" src="<?=FileLink("dobj.js");?>"></script>
<SCRIPT LANGUAGE="JavaScript">

var WeekDays = new Array();
WeekDays[1] = '<?=$Lang['DayOfWeekCal'][1]?>';
WeekDays[2] = '<?=$Lang['DayOfWeekCal'][2]?>';
WeekDays[3] = '<?=$Lang['DayOfWeekCal'][3]?>';
WeekDays[4] = '<?=$Lang['DayOfWeekCal'][4]?>';
WeekDays[5] = '<?=$Lang['DayOfWeekCal'][5]?>';
WeekDays[6] = '<?=$Lang['DayOfWeekCal'][6]?>';
WeekDays[7] = '<?=$Lang['DayOfWeekCal'][0]?>';

var Months = new Array();
Months[1] = '<?=$Lang['MonthName'][1]?>';
Months[2] = '<?=$Lang['MonthName'][2]?>';
Months[3] = '<?=$Lang['MonthName'][3]?>';
Months[4] = '<?=$Lang['MonthName'][4]?>';
Months[5] = '<?=$Lang['MonthName'][5]?>';
Months[6] = '<?=$Lang['MonthName'][6]?>';
Months[7] = '<?=$Lang['MonthName'][7]?>';
Months[8] = '<?=$Lang['MonthName'][8]?>';
Months[9] = '<?=$Lang['MonthName'][9]?>';
Months[10] = '<?=$Lang['MonthName'][10]?>';
Months[11] = '<?=$Lang['MonthName'][11]?>';
Months[12] = '<?=$Lang['MonthName'][12]?>';

var DF = "Y-m-d";
var DFD = "-";
var MinYear=<?=((isset($MinYear))?$MinYear:2005)?>;
var MaxYear=<?=((isset($MaxYear))?$MaxYear:date('Y'))?>;
var CloseAfterSelect=true;
var CalendarShowed=true;
var PrevReturnField=false;
var TodayIs="<?=$Lang['TodayIs']?>";
var SelectedTZ=false;

if (getCookie('<?=COOKIE_PFX?>auto_tz')) SelectedTZ=0;
else SelectedTZ = <?=$nsUser->TZ?>; 



</SCRIPT>
<script language="JavaScript" src="<?=FileLink("calendar.js");?>"></script>
