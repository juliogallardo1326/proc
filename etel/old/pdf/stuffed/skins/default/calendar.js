
var DCArr = new Array();

function GetDynamicObj(ReturnField) {
	var ID="D"+ReturnField;
	for( var i=0;i<DCArr.length;i++) {
		if (DCArr[i].id!=ID&&DCArr[i].Visible) DCArr[i].Close();
	}
	for( var i=0;i<DCArr.length;i++) if (DCArr[i].id==ID) return DCArr[i];
	var Inx=DCArr.length;
	DC=new DynamicObj(ID);
	DC.HasContent=false;
	DC.HideUnder=false;
	DC.SkipId[1]="YearSelect";
	DC.SkipId[2]="MonthSelect";
	DC.SetRelativePosition(ReturnField, 0, 2, 2);
	DC.SetStyle("position", "absolute");
	DC.LastDate=false;
	DC.el.onclick= function () {
		CalendarShowed=true;
	}
	DCArr[Inx]=DC;
	return DC;
}



// Return type: 1 to DateFormat, 2: from DateFormat
function PrepareDate(ReturnType, DateStr)
{
	if (!DateStr) return false;
	NewDateStr = DF;
	NewDateStr.replace(".", DFD);
	var Arr = new Array();
	var Templ = new Array();
	if (!ReturnType || ReturnType < 1 || ReturnType > 2) return false;
	if (ReturnType == 1) {
		Arr = DateStr.split("/");
		if (Arr[1].length==1) Arr[1] = "0"+Arr[1];
		if (Arr[0].length==1) Arr[0] = "0"+Arr[0];
		NewDateStr = NewDateStr.replace("d", Arr[1]);
		NewDateStr = NewDateStr.replace("m", Arr[0]);
		NewDateStr = NewDateStr.replace("Y", Arr[2]);
	}
	if (ReturnType == 2) {
		Templ = DF.split(DFD);
		Arr = DateStr.split(DFD);
		for (var i = 0; i < Templ.length; i++) {
			if (Templ[i] == "d") var d = i;
			if (Templ[i] == "m") var m = i;
			if (Templ[i] == "Y") var Y = i;
		}
		NewDateStr = Arr[m]+"/"+Arr[d]+"/"+Arr[Y];		
	}
	return NewDateStr;
}

function ReturnDate(ReturnField, DateStr, DoNotClose)
{
	RF = GetObj(ReturnField);
	RF.value = PrepareDate(1, DateStr);
	if (CloseAfterSelect&&!DoNotClose) HideCalendar(ReturnField);
}

function ShowCalendar(ReturnField)
{
	DC=GetDynamicObj(ReturnField);
	if (DC.Visible) {
		HideCalendar(ReturnField);
		return;
	}
	var SelectDate = PrepareDate(2, GetObj(ReturnField).value);
	if (!DC.HasContent || (!DC.LastDate || DC.LastDate!=SelectDate) ) MakeCalendar(ReturnField);
	DC.Show();
	CalendarShowed=true;

	document.onkeypress = function (event) {
		if (!event) event=window.event;
		if (event && event.keyCode == 27) CloseAll();
	}
	document.onclick= function () {
		if (!CalendarShowed) CloseAll();
		CalendarShowed = false;
	}
}

function CloseAll()
{
	for( var i=0;i<DCArr.length;i++) {
		if (DCArr[i].Visible) DCArr[i].Close();
	}
}

function HideCalendar(ReturnField)
{
	DC = GetDynamicObj(ReturnField);
	DC.Close();
	CalendarShowed = true;
}


function ChangeMonth(ReturnField, Month, SelectDate)
{
	SelectDate = Month+"/"+SelectDate;
	if (!CloseAfterSelect) ReturnDate(ReturnField, SelectDate, true);
	MakeCalendar(ReturnField, SelectDate);
}

function ChangeYear(ReturnField, SelectDate, Year)
{
	SelectDate = SelectDate+"/"+Year;
	if (!CloseAfterSelect) ReturnDate(ReturnField, SelectDate, true);
	MakeCalendar(ReturnField, SelectDate);
}


function MakeCalendar(ReturnField, SelectDate)
{
	DC = GetDynamicObj(ReturnField);
	RF = GetObj(ReturnField);
	if (!SelectDate) var SelectDate = PrepareDate(2, RF.value);
	DC.LastDate=SelectDate;
	var CurDate = new Date();
	CurDate.setSeconds(CurDate.getSeconds()+(SelectedTZ*60*60));
	var DateObj = new Date(SelectDate);

	var HTML = "";
	
	if (SelectDate) {
		var Month = DateObj.getMonth()+1;
		var Year = DateObj.getFullYear();
		var Day = DateObj.getDate();
	}
	else {
		var Month = CurDate.getMonth()+1;
		var Year = CurDate.getFullYear();
		var Day = CurDate.getDate();
	}
	
	PrevMonth=(Month==1)?12:Month-1;
	NextMonth=(Month==12)?1:Month+1;

	var DaysCount = DaysInMonth(Month, Year);


	HTML += "<table cellpadding=0 cellspacing=1 border=0 class=CldrMainTable><tr><td>";

	HTML += "<table border=0 cellpadding=2 cellspacing=0 class=CldrInnerTable><tr>";
	HTML += "<td align=center><B><a href=\"javascript:;\" ";
	HTML += "onclick=\"ChangeMonth('"+ReturnField+"', "+PrevMonth+", '1/"+Year+"');\"";
	HTML += " title=\"Prev month\">&laquo;</a></B></td>";
	HTML += "<td colspan=5 class=CldrHeader>";
	HTML += Months[Month]+", "+Day;
	HTML += "</td>";
	HTML += "<td align=center><B><a href=\"javascript:;\" ";
	HTML += "onclick=\"ChangeMonth('"+ReturnField+"', "+NextMonth+", '1/"+Year+"');\"";
	HTML += " title=\"Next month\">&raquo;</a></B></td>";
	HTML += "</tr>";


	HTML += "<tr><td colspan=4>";
	HTML += "<select class=\"CldrMonthSelect\" id=\"MonthSelect\" name=\"SelectMonth\"";
	HTML += " onchange=\"ChangeMonth('"+ReturnField+"', this.value, '"+Day+"/"+Year+"');\">";

	for (var i =1; i < Months.length; i++) {
		HTML += "<option value=\""+i+"\"";
		if (i == Month) HTML += " selected";
		HTML += ">"+Months[i]+"</option>";
	}
	HTML += "</select>";
	HTML += "</td><td colspan=3 align=right>";
	HTML += "<select class=\"CldrYearSelect\" id=\"YearSelect\" name=\"SelectYear\" ";
	HTML += "onchange=\"ChangeYear('"+ReturnField+"', '"+Month+"/"+Day+"', this.value);\">";
	for (var i = MinYear; i <= MaxYear; i++) {
		HTML += "<option value=\""+i+"\"";
		if (i == Year) HTML += " selected";
		HTML += ">"+i+"</option>";
	}

	HTML += "</select>";
	HTML += "</td></tr>";



	HTML += "<tr>";
	for (var i = 1; i < WeekDays.length; i++) HTML += "<td class=CldrWeekDay>"+WeekDays[i]+"</td>";
	HTML += "</tr>";

	var FirstDay = new Date(Month+"/1/"+Year);
	var FirstDayNum = FirstDay.getDay();
	var LastDay = new Date(Month+"/"+DaysCount+"/"+Year);
	var LastDayNum = LastDay.getDay();
	if (FirstDayNum == 0) FirstDayNum = 7;
	if (LastDayNum == 0) LastDayNum = 7;

	if (FirstDayNum > 1) {
		if (Month == 1) {	var PrevMonth=12; var PrevYear=Year-1; }
		else {	var PrevMonth=Month-1;	var PrevYear=Year;}
		var PrevMonthDCount = DaysInMonth(PrevMonth, PrevYear);
		//alert(FirstDayNum);

		for (var i = (PrevMonthDCount-(FirstDayNum-2)); i <= PrevMonthDCount; i++) 
		{
			HTML+="<td class=CldrPrevMonth>";
			HTML+="<a href=\"javascript:;\" onclick=\"";
			HTML+="ReturnDate('"+ReturnField+"', '"+PrevMonth+"/"+i+"/"+PrevYear+"');";
			HTML+="MakeCalendar('"+ReturnField+"', '"+PrevMonth+"/"+i+"/"+PrevYear+"');";
			HTML+="\">";
			HTML+= i;			
			HTML+="</a>";
			HTML+="</td>";
		}

	}


	var WD = FirstDayNum;
	for (var i = 1; i <= DaysCount; i++) {
		if (WD > 7) {
			HTML += "</tr><tr>";
			WD = 1;
		}
		HTML += "<td ";

		if (i == CurDate.getDate() && Month==CurDate.getMonth()+1 && Year==CurDate.getFullYear() && i != Day) {
			HTML += " class=CldrCurrentDay ";
		}
		else {if (i == Day) {
				HTML += " class=CldrCurrentSel ";
			}
			else HTML += " class=CldrDay ";
		}

		HTML += ">";
		HTML += "<a href=\"javascript:;\" onclick=\"";
		HTML += "ReturnDate('"+ReturnField+"', '"+Month+"/"+i+"/"+Year+"');";
		HTML += "MakeCalendar('"+ReturnField+"', '"+Month+"/"+i+"/"+Year+"');";
		HTML += "\">";
		HTML += i;
		HTML += "</a></td>";
		WD++;
	}

	if (WD < 8) {
		if (Month == 12) {	var NextMonth=1; var NextYear=Year+1; }
		else {	var NextMonth=Month+1;	var NextYear=Year;}
		//var NextMonthDCount = DaysInMonth(NextMonth, NextYear);

		for (var i = 1; i <= (7-(WD-1)); i++) 
		{
			HTML+="<td class=CldrNextMonth>";
			HTML+="<a href=\"javascript:;\" onclick=\"";
			HTML+="ReturnDate('"+ReturnField+"', '"+NextMonth+"/"+i+"/"+NextYear+"');";
			HTML+="MakeCalendar('"+ReturnField+"', '"+NextMonth+"/"+i+"/"+NextYear+"');";
			HTML+="\">";
			HTML+= i;			
			HTML+="</a>";
			HTML+="</td>";
		}

	}


	HTML += "</tr>";

	HTML += "<tr><td  colspan=7 class=CldrToday>";
	HTML += "<a href=\"javascript:;\" onclick=\"";
	HTML += " ReturnDate('"+ReturnField+"', '"+(CurDate.getMonth()+1)+"/"+CurDate.getDate()+"/"+CurDate.getFullYear()+"');";
	HTML += "MakeCalendar('"+ReturnField+"');";
	HTML += "\">";
	HTML += TodayIs+" "+Months[CurDate.getMonth()+1]+", "+CurDate.getDate()+"</a>";
	HTML += "</td></tr>";
	HTML += "</table>";
	HTML += "</td></tr></table>";


	

	DC.SetContent(HTML);
	DC.HasContent=true;
}


function DaysInMonth(Month, Year)
{
	var endDate = new Date (Year, Month, 1);
	endDate = new Date (endDate - (24*60*60*1000));
	DaysCount = endDate.getDate();
	return DaysCount;
}
