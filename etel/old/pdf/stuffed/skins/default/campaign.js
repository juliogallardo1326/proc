var CampIds= new Array();
var AllowIds= new Array();
var CurrentNode=0;
CampIds[0]="ROOT";

function SubmitMoveForm(TO)
{
	var Obj=GetObj("MOVE_FORM");
	var MoveId=GetObj("MoveId");
	MoveId.value=CurrentNode;
	var MoveTo=GetObj("MoveTo");
	if (TO=='ROOT') TO=0;
	MoveTo.value=TO;
	Obj.submit();
}


function ShowRadioBtns(ID)
{
	for(var i=0;i<CampIds.length;i++) {
		Obj=GetObj("MoveRadio["+CampIds[i]+"]");
		if (CurrentNode==ID&&Obj.style.display=="")  Obj.style.display="none";
		else if (CurrentNode==ID&&Obj.style.display=="none"&&CheckAllow(ID, CampIds[i]))  Obj.style.display="";
		if (CurrentNode!=ID) {
			if (CheckAllow(ID, CampIds[i])) Obj.style.display="";
			else Obj.style.display="none";
		}
	}
	CurrentNode=ID;
}


function CheckAllow(ID, TO)
{
	if (ID==TO) return false;
	if (TO=='ROOT'&&AllowIds[ID]==0) return false;
	if (TO=='ROOT') return true;
	if (AllowIds[ID]==TO) return false;
	if (AllowIds[TO]==ID) return false;
	if (AllowIds[TO]==0)return true;
	if (AllowIds[ID]==AllowIds[TO]) return true;
	return CheckAllow(ID, AllowIds[TO]);
	return false;
}