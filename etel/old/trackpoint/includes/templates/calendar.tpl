<form name="customDateForm" method="post" action="index.php?Page=%%PAGE%%&%%GLOBAL_FormAction%%" style="margin: 0px;">
	<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td class=text width=90 bgcolor=#EEEEEE style="padding-top:3pt" nowrap>&nbsp;<img src="images/dateicon.gif" width="20" height="20" align="absMiddle">&nbsp;%%LNG_DateRange%%: </td>
			<td width=90 bgcolor="#EEEEEE" style="padding-top:5pt">
				<select name="Calendar[DateType]" class=Text onChange="doCustomDate(this)" style="margin-bottom:3px">
				%%GLOBAL_CalendarOptions%%
				</select>
			</td>
			<td width=50 bgcolor="#EEEEEE" style="padding-top:5pt"><input type=submit value=%%LNG_Go%% class=text style="margin-bottom:5px; margin-left:5px;"></td>
			<td bgcolor="#EEEEEE" nowrap style="padding-top:5pt">
				<span id=customDate style="display:%%GLOBAL_CustomDateDisplay%%">&nbsp;
				<select name="Calendar[From][Day]" class=text style="margin-bottom:3px">%%GLOBAL_CustomDayFrom%%</select>
				<select name="Calendar[From][Mth]" class=text style="margin-bottom:3px">%%GLOBAL_CustomMthFrom%%</select>
				<select name="Calendar[From][Yr]" class=text style="margin-bottom:3px">%%GLOBAL_CustomYrFrom%%</select>
				<span class=body>%%LNG_To%%</span>
				<select name="Calendar[To][Day]" class=text style="margin-bottom:3px">%%GLOBAL_CustomDayTo%%</select>
				<select name="Calendar[To][Mth]" class=text style="margin-bottom:3px">%%GLOBAL_CustomMthTo%%</select>
				<select name="Calendar[To][Yr]" class=text style="margin-bottom:3px">%%GLOBAL_CustomYrTo%%</select>
				</span>&nbsp;
			</td>
			<td nowrap class=body id="showDate" style="display:%%GLOBAL_ShowDateDisplay%%; padding-top: 6pt;">&nbsp;&nbsp;<i>%%GLOBAL_DateRange%%</i></span></td>
		</tr>
	</table>
</form>
