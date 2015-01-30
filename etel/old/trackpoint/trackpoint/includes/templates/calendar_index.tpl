<form name="customDateForm" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" style="margin: 0px;">
	<table width=100% border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td class=text width="90" bgcolor="#EEEEEE" style="padding-top:3pt" nowrap>&nbsp;<img src="images/dateicon.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_DateRange%%: </td>
			<td width="90" bgcolor="#EEEEEE" style="padding-top:5pt">
				<select name="Calendar[DateType]" class=Text onChange="doCustomDate(this)" style="margin-bottom:3px">
				%%GLOBAL_CalendarOptions%%
				</select>
			</td>
			<td width="100%" bgcolor="#EEEEEE" style="padding-top:5pt"><input type=submit value=%%LNG_Go%% class=text style="margin-bottom:5px; margin-left:5px;">&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" bgcolor="#EEEEEE" nowrap id="customDate" style="display:%%GLOBAL_CustomDateDisplay%%">
				<div style="padding-top: 5px;">
				&nbsp; <select name="Calendar[From][Day]" class=text style="margin-bottom:3px;">%%GLOBAL_CustomDayFrom%%</select>
				<select name="Calendar[From][Mth]" class=text style="margin-bottom:3px;">%%GLOBAL_CustomMthFrom%%</select>
				<select name="Calendar[From][Yr]" class=text style="margin-bottom:3px;">%%GLOBAL_CustomYrFrom%%</select>
				<span class=body>%%LNG_To%%</span><br>
				&nbsp; <select name="Calendar[To][Day]" class=text style="margin-bottom:3px;">%%GLOBAL_CustomDayTo%%</select>
				<select name="Calendar[To][Mth]" class=text style="margin-bottom:3px;">%%GLOBAL_CustomMthTo%%</select>
				<select name="Calendar[To][Yr]" class=text style="margin-bottom:3px;">%%GLOBAL_CustomYrTo%%</select>
				</div>
			</td>
		</tr>
		<tr style="display:%%GLOBAL_ShowDateDisplay%%;">
			<td bgcolor=#EEEEEE colspan="3" nowrap class=body valign=bottom id="showDate" style="padding-bottom: 5px">&nbsp;&nbsp;<i>%%GLOBAL_DateRange%%</i></td>
		</tr>
	</table>
</form>
