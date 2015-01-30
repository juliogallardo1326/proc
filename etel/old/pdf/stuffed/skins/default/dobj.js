var ie = document.all;
//-----------------------------------------------------------------
dom = (document.getElementById) ? true : false; 
ns5 = ((navigator.userAgent.indexOf("Gecko")>-1) && dom) ? true: false; 
ie5 = ((navigator.userAgent.indexOf("MSIE")>-1) && dom) ? true : false; 
ns4 = (document.layers && !dom) ? true : false; 
ie4 = (document.all && !dom) ? true : false; 
nodyn = (!ns5 && !ns4 && !ie4 && !ie5) ? true : false; 
//-----------------------------------------------------------------
function ObjRef(id) { 
	if (dom) return document.getElementById(id);
	return (ns4) ? document.layers[id] : (ie4) ? document.all[id] : (ns5||ie5) ? document.getElementById(id) : null; 
} 


function GetTarg(Ev, NoParent) {
	if (!Ev) return false;
	if (Ev.SpecialTarget) var Target = Ev.SpecialTarget;
	else var Target = (Ev && Ev.target) ? Ev.target : window.event.srcElement;
	if (TemporaryObjLink)	Target=TemporaryObjLink;
	if (NoParent) return Target;
	return (Target.ParentStop)?Target:GetFirstParent(Target);
}

function GetFirstParent(el) {
	if (!el) return false;
	Parent=el;
	while (Parent.offsetParent) {
		Parent=Parent.offsetParent;
		if (Parent.ParentLink) return Parent.ParentLink;
		if (Parent.ParentStop) return Parent;
	}
	return Parent;
}

var TemporaryObjLink=false;
var DDMove=false;
var DDMoveObj=false;
var DDResize=false;
var DDResizeObj=false;

/////////////////////////////////////////////////////////
/// DYNAMIC DIV


function DynamicObj(ID, ELEM, ELEM_TYPE)
{
	var Obj= ObjRef(ID);
	if(!Obj) {
		if (!ELEM) ELEM="DIV";
		Obj=document.createElement(ELEM);
		Obj.id=ID;
		if (ELEM_TYPE) Obj.type=ELEM_TYPE;
		Obj.style.display="none";
		document.body.appendChild(Obj);
	}
	this.el=Obj;
	this.id=ID;
	this.el.id=ID;
	this.style=this.el.style;
	this.el.ParentStop=true;
	this.el.Parent=this;
	this.MLeftX=-1;
	this.MRightX=-1;
	this.MTopY=-1;
	this.MBottomY=-1;
	this.UseBody=true;
	this.Visible=false;

	this.DockingObjects= new Array();
	this.Dockable = false;

	this.Interactive=false;
	
	this.HideUnder=false;
	this.HideTags= new Array();
	this.HideTags[0]="SELECT";
	this.SkipId= new Array();
	this.SkipId[0]=this.el.id;

	this.OnMoveStart="";
	this.OnMoveEnd="";
	this.OnMove="";
	this.OnResizeStart="";
	this.OnResizeEnd="";
	this.OnResize="";
	this.OnShow="";
	this.OnClose="";

	this.MoveDirR=true;
	this.MoveDirL=true;
	this.MoveDirT=true;
	this.MoveDirB=true;
	this.DoNotMove=false;
}


/////////////////////////////////////////////////////
/// Set properties



DynamicObj.prototype.SetSize = function(W, H) {
	this.style.width=W;
	this.style.height=H;
	if (this.OnResize) this.OnEvent(this.OnResize);
}

DynamicObj.prototype.GetWidth = function () {
	return parseInt(this.style.width);
}
DynamicObj.prototype.SetWidth = function (Width) {
	this.style.width=Width;
}
DynamicObj.prototype.GetHeight = function () {
	return parseInt(this.style.height);
}
DynamicObj.prototype.SetHeight = function (Height) {
	this.style.height=Height;
}

DynamicObj.prototype.SetPosition = function (L, T) {
	this.style.left=L;
	this.style.top=T;
	if (this.HideUnder&&!DDMove) this.HideTagsUnder();
}

DynamicObj.prototype.GetLeft = function () {
	return parseInt(this.style.left);
}

DynamicObj.prototype.GetTop = function () {
	return parseInt(this.style.top);
}


DynamicObj.prototype.SetRelativePosition = function (ObjID, OffsetLeft, OffsetTop, OnCorn) {
	if (ObjID.el) ObjID=ObjID.el;
	if (typeof(ObjID)=="object") var OffsetObj=ObjID;
	else 	var OffsetObj=ObjRef(ObjID);
	if (!OffsetObj) return false;
	var FirstObj=OffsetObj;
	var leftpos=0;
	var toppos=0;
	do {
		OffsetObj = OffsetObj.offsetParent;
		leftpos += OffsetObj.offsetLeft;
		toppos += OffsetObj.offsetTop;
	} while(OffsetObj.tagName != "BODY");

	if (OnCorn==1||OnCorn==3) leftpos+=parseInt(FirstObj.offsetWidth);
	if (OnCorn==2||OnCorn==4) toppos+=parseInt(FirstObj.offsetHeight);

	this.SetPosition(FirstObj.offsetLeft + leftpos + OffsetLeft, FirstObj.offsetTop + toppos + OffsetTop);
}

DynamicObj.prototype.SetStyle = function (Key, Value) {
	this.style[Key]=Value;
}

DynamicObj.prototype.SetValue = function (Key, Value) {
	this[Key]=Value;
}



///////////////////////////////////////////////////////
////// Showing

DynamicObj.prototype.Show = function () {
	this.style.display="";
	this.Visible=true;
	if (this.HideUnder) this.HideTagsUnder();
	if (this.OnShow) this.OnEvent(this.OnShow);
}

DynamicObj.prototype.Close = function () {
	this.style.display="none";
	this.Visible=false;
	if (this.HideUnder) this.ShowTagsUnder();
	if (this.OnClose) this.OnEvent(this.OnClose);
}

////////////////////////////////////////////////////
///// Resizing

DynamicObj.prototype.SetResizeObject = function (ObjID, oResize) {
	if (!oResize) var oResize=ObjRef(ObjID);
	if (!oResize) return false;
	oResize.ParentLink=this.el;
	oResize.onmousedown=this.SetResizeable;
	oResize.onmouseup=this.SetNotResizeable;
	this.ResizeObj=oResize;
}

DynamicObj.prototype.SetResizeable = function (Ev) {
	if (!Ev) Ev=window.event;
	Targ=GetTarg(Ev);
	if (!Targ) return false;
	Parent=Targ.Parent;
	Parent.ResizeStartX=Ev.clientX;
	Parent.ResizeStartY=Ev.clientY;
	var ResizeObj=Targ.Parent.ResizeObj;
	if (Parent.UseBody) {
		TemporaryObjLink=ResizeObj;
		document.body.onmousemove = Parent.ResizeHandler;
		document.body.onmouseup = Parent.SetNotResizeable;
	} else {
		ResizeObj.onmousemove = Parent.ResizeHandler;
		ResizeObj.onmouseout = Parent.ResizeHandler;
	}
	ResizeObj.ondrag = Parent.FalseHandler;
	if (Parent.OnResizeStart) Parent.OnEvent(Parent.OnResizeStart, Ev);
}

DynamicObj.prototype.SetNotResizeable = function (Ev) {
	if (!Ev) Ev=window.event;
	Targ=GetTarg(Ev);
	if (!Targ) return false;
	Parent=Targ.Parent;
	Parent.ResizeStartX=false;
	Parent.ResizeStartY=false;
	var ResizeObj=Parent.ResizeObj;
	if (Parent.UseBody) {
		TemporaryObjLink=false;
		document.body.onmousemove = '';
		document.body.onmouseup = '';
	}
	ResizeObj.onmousemove = '';
	ResizeObj.onmouseout = '';
	if (Parent.OnResizeEnd) Parent.OnEvent(Parent.OnResizeEnd, Ev);
}

DynamicObj.prototype.ResizeHandler = function (Ev) {
	if (!Ev) Ev=window.event;
	Targ=GetTarg(Ev);
	if (!Targ) return false;
	Parent=Targ.Parent;
	if (Ev.clientX<=Parent.GetLeft()) return false;
	if (Ev.clientY<=Parent.GetTop()) return false;
	Parent.SetSize(
		Parent.GetWidth()+(Ev.clientX-Parent.ResizeStartX),
		Parent.GetHeight()+(Ev.clientY-Parent.ResizeStartY)
		);
	Parent.ResizeStartX=Ev.clientX;
	Parent.ResizeStartY=Ev.clientY;
}


////////////////////////////////////////////////////
///// Content

DynamicObj.prototype.SetContent = function (HTML) {
	this.el.innerHTML=HTML;
}

DynamicObj.prototype.AddContent = function (HTML) {
	this.el.innerHTML+=HTML;
}

DynamicObj.prototype.GetContent = function (Clear) {
	var HTML=this.el.innerHTML;
	if (Clear) this.el.innerHTML="";
	return HTML;
}

DynamicObj.prototype.ImportContent = function (ObjID, oImport) {
	if (!oImport) var oImport=ObjRef(ObjID);
	if (!oImport) return false;
	this.el.appendChild(oImport);
}

DynamicObj.prototype.CopyContent = function (ObjID, oImport) {
	if (!oImport) var oImport=ObjRef(ObjID);
	if (!oImport) return false;
	this.el.innerHTML=oImport.innerHTML;
}

DynamicObj.prototype.ReleaseContent = function (ObjID, oOutput) {
	if (!oOutput) var oOutput=ObjRef(ObjID);
	if (!oOutput) return false;
	oOutput.innerHTML=this.el.innerHTML;
	this.el.innerHTML="";
}

///////////////////////////////////////////////////////
/////// Moving



DynamicObj.prototype.Move = function(Ev) {
	if (!Ev) Ev=window.event;
	Targ=GetTarg(Ev);

	//document.getElementById("test").innerHTML+="1";

	if (!Targ) return false;
	Parent=Targ.Parent;
	if (Parent.Interactive) {
		var DObj=Parent.FindDock(Ev.clientX, Ev.clientY);
		if (DObj) DObj.MovingOver();
		if (Parent.LastDObj && DObj && Parent.LastDObj.id != DObj.id ) {
			Parent.LastDObj.MovingOut();
			Parent.LastDObj=false;
		}
		if (Parent.LastDObj && !DObj) {
			Parent.LastDObj.MovingOut(); 
			Parent.LastDObj=false;
		}
		if (DObj) Parent.LastDObj=DObj;
	}
	NewLeft=Ev.clientX-(Parent.MouseStartX-Parent.ObjStartX);
	NewTop=Ev.clientY-(Parent.MouseStartY-Parent.ObjStartY);

	if ((!Parent.MoveDirT && NewTop < parseInt(Parent.style.top)) || Parent.DoNotMove) NewTop=parseInt(Parent.style.top);
	if ((!Parent.MoveDirL && NewLeft < parseInt(Parent.style.left)) || Parent.DoNotMove) NewLeft=parseInt(Parent.style.left);
	if ((!Parent.MoveDirB && NewTop > parseInt(Parent.style.top)) || Parent.DoNotMove) NewTop=parseInt(Parent.style.top);
	if ((!Parent.MoveDirR && NewLeft > parseInt(Parent.style.left)) || Parent.DoNotMove) NewLeft=parseInt(Parent.style.left);

	if (Parent.GetLeft()!=NewLeft) Parent.ShiftH=Ev.clientX-Parent.LastMouseX;
	else Parent.ShiftH=0;
	if (Parent.GetTop()!=NewTop) Parent.ShiftV=Ev.clientY-Parent.LastMouseY;
	else Parent.ShiftV=0;

	Parent.LastMouseX=Ev.clientX;
	Parent.LastMouseY=Ev.clientY;
	Parent.SetPosition(	NewLeft , NewTop );
	if (Parent.HideUnder) Parent.HideTagsUnder(true);
	if (Parent.OnMove) Parent.OnEvent(Parent.OnMove, Ev);
}

DynamicObj.prototype.SetMoveable = function () {
	var MoveObj=false;
	MoveObj= (this.MoveObj)? this.MoveObj:this.el;
	MoveObj.onmousedown = this.SetMoveHandler;
	MoveObj.onmouseup = this.SetNoMoveHandler;
}

DynamicObj.prototype.SetNotMoveable = function () {
	var MoveObj=false;
	MoveObj= (this.MoveObj)? this.MoveObj:this.el;
	MoveObj.onmousedown = '';
	MoveObj.onmouseup = '';
	this.el.onmouseout = '';
	this.el.onmousemove = '';
	this.MLeftX=-1;
	this.MRightX=-1;
	this.MTopY=-1;
	this.MBottomY=-1;
}

DynamicObj.prototype.SetMoveHandler = function (Ev) {
	if (!Ev) Ev=window.event;
	Targ=GetTarg(Ev);
	if (!Targ) return false;
	Parent=Targ.Parent;
	Parent.MouseStartX=Ev.clientX;
	Parent.MouseStartY=Ev.clientY;
	Parent.LastMouseX=Ev.clientX;
	Parent.LastMouseY=Ev.clientY;
	Parent.ShiftH=false;
	Parent.ShiftV=false;
	Parent.ObjStartX=parseInt(Parent.style.left);
	Parent.ObjStartY=parseInt(Parent.style.top);
	Parent.SetStyle("position", "absolute");

	Parent.MouseXOffset=Parent.MouseStartX-Parent.ObjStartX;
	Parent.MouseYOffset=Parent.MouseStartY-Parent.ObjStartY;

	if (
		(Parent.MLeftX>-1&&Parent.MRightX>-1&&Parent.MTopY>-1&&Parent.MBottomY>-1) &&
		!(	Parent.MouseXOffset >=  Parent.MLeftX &&
			Parent.MouseXOffset <=  Parent.MRightX && 
			Parent.MouseYOffset >=  Parent.MTopY &&
			Parent.MouseYOffset <=  Parent.MBottomY)
			) return false;
	
	if (Parent.UseBody) {
		TemporaryObjLink=(Parent.MoveObj)?Parent.MoveObj:Targ;
		document.body.onmousemove=Parent.Move;
		document.body.onmouseup=Parent.SetNoMoveHandler;
		Parent.el.onmouseup=Parent.SetNoMoveHandler;
		Targ.onmouseout = Parent.Move;
	} else {
		Targ.onmousemove = Parent.Move;
		Targ.onmouseout = Parent.Move;
	}

	if (Parent.OnMoveStart) Parent.OnEvent(Parent.OnMoveStart, Ev);

	DDMove=true;
	DDMoveObj=Parent;
}

DynamicObj.prototype.SetNoMoveHandler = function (Ev) {
	if (!Ev) Ev=window.event;
	Targ=GetTarg(Ev);
	if (!Targ) return false;
	if (Targ.tagName=="BODY") return false;
	var Parent = Targ.Parent;
	if (Parent.UseBody) {
		TemporaryObjLink=false;
		document.body.onmousemove='';
		document.body.onmouseup='';
		Targ.onmouseout = '';
	} else {
		Targ.onmousemove = '';
		Targ.onmouseout = '';
	}
	Parent.MouseStartX=false;
	Parent.MouseStartY=false;
	Parent.ObjStartX=false;
	Parent.ObjStartY=false;


	Parent.LastDObj=false;
	if (Parent.OnMoveEnd) Parent.OnEvent(Parent.OnMoveEnd, Ev);

	if (Parent.Dockable) {
		var DObj=Parent.FindDock(Ev.clientX, Ev.clientY);
		if (DObj) DObj.DockObj(Parent);
	}

	DDMove=false;
	DDMoveObj=false;
}



DynamicObj.prototype.SetMoveObject = function (ObjID, oMove) {
	if (!oMove) var oMove=ObjRef(ObjID);
	if (!oMove) return false;
	oMove.ParentLink=this.el;
	oMove.ondrag = this.FalseHandler;
	this.MoveObj=oMove;
}

DynamicObj.prototype.ReleaseMoveObject = function (ObjID, oMove) {
	if (!oMove) var oMove=ObjRef(ObjID);
	if (!oMove) return false;
	oMove.ParentLink=false;
	this.MoveObj=false;
}

DynamicObj.prototype.SetMoveArea = function (LeftX, RightX, TopY, BottomY) {
	this.MLeftX=LeftX;
	this.MRightX=RightX;
	this.MTopY=TopY;
	this.MBottomY=BottomY;
}

DynamicObj.prototype.SetMoveAreaY = function (TopY, BottomY) {
	this.MTopY=TopY;
	this.MBottomY=BottomY;
	this.MLeftX=0;
	this.MRightX=parseInt(this.style.width);
}

DynamicObj.prototype.SetMoveAreaX = function (LeftX, RightX) {
	this.MLeftX=LeftX;
	this.MRightX=RightX;
	this.MTopY=0;
	this.MBottomY=parseInt(this.style.height);
}


////////////////////////////////////////////////////


DynamicObj.prototype.FalseHandler = function () {
	return false;
}

DynamicObj.prototype.OnEvent = function (Code, Ev) {
	if (!Code) return false;
	if (typeof(Code)=="object") {
		for(i in Code) {
			eval(Code[i]);
		}
	}
	if (typeof(Code)=="string") eval(Code);
}


/////////////////////////////////////////////////////////


DynamicObj.prototype.MovingOver = function () {
	if (this.OnMoveOver) this.OnEvent(this.OnMoveOver);
}
DynamicObj.prototype.MovingOut = function () {
	if (this.OnMoveOut) this.OnEvent(this.OnMoveOut);
}


DynamicObj.prototype.DockObj = function (oObject) {
	if (!oObject) return false;
	if (this.OnDock) this.OnEvent(this.OnDock);
	if (this.MovedOnDock) oObject.OnEvent(this.MovedOnDock);
	if (!this.OnDock) this.el.appendChild(oObject.el);
}

DynamicObj.prototype.FindDock = function (MouseX, MouseY) {
	if (!this.Dockable&&!this.Interactive) return false;
	if (!MouseX||!MouseY) return false;
	for (var i=0; i<this.DockingObjects.length; i++) {
		var DObj=this.DockingObjects[i].el;
		if (this.IsObjectUnder(DObj, MouseX, MouseY)) return DObj.Parent;
	}
	return false;
}


//////////////////////////////////////////////////////////



DynamicObj.prototype.IsObjectUnder = function (oObj, X, Y, ObjMatch) {
	if(!oObj.offsetParent) return false;

	var objLeft		 =	 oObj.offsetLeft;
	var objTop		 =  oObj.offsetTop;
	var objHeight  =  oObj.offsetHeight;
	var objWidth   =  oObj.offsetWidth;
	var objParent	 =	 oObj.offsetParent;

	while (objParent.tagName.toUpperCase() != "BODY") {
		objLeft		+= objParent.offsetLeft;
		objTop		+= objParent.offsetTop;
		objParent = objParent.offsetParent;
	}

	if (!ObjMatch) {
		if (X >= objLeft && X <= objLeft + objWidth &&
			Y >= objTop && Y <= objTop + objHeight) {
			return true;
		}
	}
	if (ObjMatch) {
		if ((ObjMatch.offsetLeft + ObjMatch.offsetWidth) <= objLeft);
		else if ((ObjMatch.offsetTop + ObjMatch.offsetHeight) <= objTop);
		else if (ObjMatch.offsetTop >= (objTop + objHeight));
		else if (ObjMatch.offsetLeft >= (objLeft + objWidth));
		else {
		  return true;
		}
	}

	return false;
}

DynamicObj.prototype.HideTagsUnder = function (OnMove) {
	if (!ie) return;
	for (var i = 0; i< this.HideTags.length; i++) {
		Tag=this.HideTags[i];
		Tag=Tag.toUpperCase();
		TagsC:
		for (j = 0; j < document.all.tags(Tag).length; j++) {
			obj = document.all.tags(Tag)[j];
			for (z=0; z < this.SkipId.length; z++) {
				if (obj.id==this.SkipId[z]) continue TagsC;
			}
			if (this.IsObjectUnder(obj, false, false, this.el)) obj.style.visibility="hidden";
			else if (OnMove) obj.style.visibility="";
		}
	}
}

DynamicObj.prototype.ShowTagsUnder = function () {
	if (!ie) return;
	for (var i = 0; i< this.HideTags.length; i++) {
		Tag=this.HideTags[i];
		Tag=Tag.toUpperCase();
		TagsC:
		for (j = 0; j < document.all.tags(Tag).length; j++) {
			obj = document.all.tags(Tag)[j];
			for (z=0; z < this.SkipId.length; z++) {
				if (obj.id==this.SkipId[z]) continue TagsC;
			}
			obj.style.visibility="";
		}
	}
}
