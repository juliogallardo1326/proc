var CurrentLight=0;

function CheckAllPath(VisitorId) {
	if (CurrentLight==VisitorId) Class="LogRecordTR";
	else {
		if (CurrentLight!=0) CheckAllPath(CurrentLight);
		Class="LogRecordTR3";
	}
	var VisitorArr=VisLogArr[VisitorId];
	for (var i=0; i<VisitorArr.length;i++) {
		Obj=GetObj("Log_"+VisitorArr[i]);
		Obj.className=Class;
	}
	if (CurrentLight!=VisitorId) 	CurrentLight=VisitorId;
	else CurrentLight=0;
}

function HighlightNext(CurrentId, VisitorId)
{
	if (CurrentLight==VisitorId) return false;
	Obj= FindNextRecord(CurrentId, VisitorId);
	Obj.className="LogRecordTR2";
	Obj2=GetObj("Log_"+CurrentId);
	Obj2.className="LogRecordTR2";
}

function RemoveLight(CurrentId, VisitorId)
{
	if (CurrentLight==VisitorId) return false;
	Obj= FindNextRecord(CurrentId, VisitorId);
	Obj.className="LogRecordTR";
	Obj2=GetObj("Log_"+CurrentId);
	Obj2.className="LogRecordTR";
}

function FindNextRecord(CurrentId, VisitorId)
{
	var VisitorArr=VisLogArr[VisitorId];
	var NextId=0;
	for (var i=0; i<VisitorArr.length;i++) {
		if (i==0&&VisitorArr[i]==CurrentId) NextId=CurrentId;
		if (i>0&&VisitorArr[i]==CurrentId) NextId=VisitorArr[i-1];
	}
	if (NextId==0) NextId=CurrentId;
	Obj=GetObj("Log_"+NextId);
	return Obj;
}