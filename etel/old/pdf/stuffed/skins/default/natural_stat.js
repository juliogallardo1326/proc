var CurGraph=false;
function SwitchGraph(ID)
{
	if (typeof(CurGraph)=="object"&&CurGraph.id!=ID) CurGraph.style.display="none";
	CurGraph=GetObj(ID);
	CurGraph.style.display="";
}

function ShowExportForm()
{
	var Obj=GetObj("ExportForm");
	if (Obj.style.display=="none") Obj.style.display="";
	else Obj.style.display="none";
}

var CurrentCol="";
var CurrentPos=-1;
var PrevCol="";
var PrevPos=-1;

var CurrentDiv=-2;
var CurrentDivId=false;

function HighLightRow(Col, Position)
{
	if (CurrentPos>-1) return false;
	Obj=GetObj("TR_"+Position);
	Obj.style.background="#f0f0f0";
}

function RemoveLight(Col, Position, CheckCols)
{
	if (CurrentPos>-1) return false;
	Obj=GetObj("TR_"+Position);
	Obj.style.background="";

	if (!CheckCols) return true;

	for (var i=0;i<Columns.length;i++) {
		Obj=GetObj(Columns[i]+"_"+Position);
		if (i==0||i==2||i==3||i==5||i==7) Class="ReportSimpleTd2";
		else Class="ReportSimpleTd";
		Obj.className=Class;
	}
}

function CheckRowCol(Col, Position)
{
	if (Col==CurrentCol&&CurrentPos==Position) {
		RemoveCheck(CurrentCol, CurrentPos);
		HighLightRow(Col, Position);
		return;
	}
	RemoveCheck(CurrentCol, CurrentPos);
	var Inx=FindIndex(Col); HighLightRow(Col, Position);
	for (var i=0; i<=Inx;i++) {
		Obj=GetObj(Columns[i]+"_"+Position);
		if (i==0||i==2||i==3||i==5||i==7) Class="ConstCheckCol2";
		else Class="ConstCheckCol";
		Obj.className=Class;
	}
	for(var i=0;i<=Position;i++) {
		Obj=GetObj(Col+"_"+i);
		if (Inx==0||Inx==2||Inx==3||Inx==5||Inx==7) Class="ConstCheckCol2";
		else Class="ConstCheckCol";
		Obj.className=Class;
	}
	if (PrevCol&&PrevPos!=Position) {
		Obj=GetObj(PrevCol+"_"+Position);
		Inx1=FindIndex(PrevCol);
		if (Inx1>Inx) {
			if (Inx1==0||Inx1==2||Inx1==3||Inx1==5||Inx1==7) Class="ConstLightRow2";
			else Class="ConstLightRow";
			Obj.className=Class;
		}
	}
	Obj=GetObj("Row_"+Position);
	Obj.className="ConstCheckCol";
	CurrentCol=Col;
	CurrentPos=Position;
	PrevCol="";
	PrevPos=-1;
}

function RemoveCheck(Col, Position) {
	if (!Col) return false;
	var Inx=FindIndex(Col);
	for(var i=0;i<=Position;i++) {
		Obj=GetObj(Col+"_"+i);
		if (Inx==0||Inx==2||Inx==3||Inx==5||Inx==7) Class="ReportSimpleTd2";
		else Class="ReportSimpleTd";
		Obj.className=Class;
	}
	Obj=GetObj("Row_"+Position);
	Obj.className="ReportNameTd";
	PrevCol=CurrentCol;
	PrevPos=CurrentPos;
	CurrentCol="";
	CurrentPos=-1;
	RemoveLight(Col, Position, true);
}

function FindIndex(Col)
{
	for (var i=0;i<Columns.length;i++) {
		if (Columns[i]==Col) return i;
	}
	return 0;
}


function CheckGroupItem(Position, ID)
{
	if (CurrentDiv==-1) return false;
	TdObj=GetObj("Row_"+Position);
	if (CurrentDiv!=-2) {
		HideFloatForm(GetObj("RowDiv_"+CurrentDiv));
		PrevTdObj=GetObj("Row_"+CurrentDiv);
		PrevTdObj.style.background="";
	}
	if (CurrentDiv==Position) {
		TdObj.style.background="";
		CurrentDiv=-2;
		CurrentDivId=false;
		return;
	}
	TdObj.style.background="#FBEEBB";
	ShowFloatForm(GetObj("RowDiv_"+Position), ID);
	CurrentDiv=Position;
	CurrentDivId=ID;
}

function ShowFloatForm(DivObj, ID)
{
	Sample=GetObj("FloatFormSample");
	DivObj.innerHTML=Sample.innerHTML;
	DivObj.style.display="";
}

function HideFloatForm(DivObj)
{
	DivObj.innerHTML="";
	DivObj.style.display="none";
}