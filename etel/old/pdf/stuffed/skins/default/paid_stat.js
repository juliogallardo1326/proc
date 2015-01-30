
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
		if (i!=1&&i!=3&&i!=5&&i!=8&&i!=10) Class="ReportSimpleTd2";
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
		if (i!=1&&i!=3&&i!=5&&i!=8&&i!=10) Class="ConstCheckCol2";
		else Class="ConstCheckCol";
		Obj.className=Class;
	}
	for(var i=0;i<=Position;i++) {
		Obj=GetObj(Col+"_"+i);
		if (Inx!=1&&Inx!=3&&Inx!=5&&Inx!=8&&Inx!=10) Class="ConstCheckCol2";
		else Class="ConstCheckCol";
		Obj.className=Class;
	}
	if (PrevCol&&PrevPos!=Position) {
		Obj=GetObj(PrevCol+"_"+Position);
		Inx1=FindIndex(PrevCol);
		if (Inx1>Inx) {
			if (Inx1!=1&&Inx1!=3&&Inx1!=5&&Inx1!=8&&Inx1!=10) Class="ConstLightRow2";
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
		if (Inx!=1&&Inx!=3&&Inx!=5&&Inx!=8&&Inx!=10) Class="ReportSimpleTd2";
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
