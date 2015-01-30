
function nsFlashVersion() {
	var Vers="";
	var n=navigator;
	if (n.plugins && n.plugins.length) {
		for (var i=0;i<n.plugins.length;i++) {
			if (n.plugins[i].name.indexOf('Shockwave Flash')!=-1) { Vers=n.plugins[i].description.split('Shockwave Flash ')[1];  break;  }
		}
	}
	else if (window.ActiveXObject) {
		for (var i=10;i>=2;i--) {
			try {
				var fl=eval("new ActiveXObject('ShockwaveFlash.ShockwaveFlash."+i+"');");
				if (fl) { Vers=i + '.0'; break; }
		   }
		   catch(e) {}
	  }
	}
	return Vers;
}



var nsTrackEnable=true;
if (!nsSiteId) nsTrackEnable=false;
if (!nsTrackPath) nsTrackEnable=false;
if (!nsTrackMode) nsTrackEnable=false;
if (!nsCode) nsCode=1;
if (nsCode!=1&&nsCode!=2&&nsCode!='js') nsCode=1;

var nsFV=nsFlashVersion();
var nsRes=screen.width+"x"+screen.height;
if (typeof(nsPresetIP) == 'undefined') var nsPresetIP="";
if (typeof(nsPresetID) == 'undefined') var nsPresetID="";
if (typeof(nsCounter) == 'undefined') var nsCounter= new Array();
if (typeof(nsCustomRun) == 'undefined') var nsCustomRun=false;
var nsAmp=unescape('%26');
var nsRef='';
try { nsRef = escape(parent.document.referrer); }
catch(e) {}
var nsCur=escape(window.location.href);
var nsNa=(navigator.appName.substring(0,2)=="Mi")?0:1;
var nsPxDh=(nsNa==0)?screen.colorDepth:screen.pixelDepth; 
var nsFrame=(self==top)?false:true;


function nsDoTrack() {
	var nsInx=nsCounter.length;
	var nsTrackSrc=nsTrackPath;
	var nsRnd=Math.random();
	nsTrackSrc+="st="+nsSiteId+nsAmp;
	nsTrackSrc+="rn="+nsRnd+nsAmp;
	nsTrackSrc+="wr="+nsRes+nsAmp;
	nsTrackSrc+="fl="+nsFV+nsAmp;
	nsTrackSrc+="px="+nsPxDh+nsAmp;
	if (nsFrame) nsTrackSrc+="frame=1"+nsAmp;
	if (nsPresetIP) nsTrackSrc+="presetIP="+nsPresetIP+nsAmp;
	if (nsPresetID) nsTrackSrc+="presetID="+nsPresetID+nsAmp;

	if (nsTrackMode=="default") {
		nsTrackSrc+="cur="+nsCur+nsAmp;
		nsTrackSrc+="ref="+nsRef+nsAmp;
	}

	if (nsTrackMode=="sale") {
		nsTrackSrc+="cur="+nsCur+nsAmp;
		nsTrackSrc+="ref="+nsRef+nsAmp;
		nsTrackSrc+="cs="+nsCost+nsAmp;
		nsTrackSrc+="oid="+nsOrderId+nsAmp;
		nsTrackSrc+="oinfo="+nsOrderInfo+nsAmp;
		if (nsOrderItems) {
			for (var i=0; i < nsOrderItems.length; i++) nsTrackSrc+="itm["+i+"]="+nsOrderItems[i];
		}
	}

	if (nsTrackMode=="event") {
		nsTrackSrc+="code=1"+nsAmp;
		nsTrackSrc+="eid="+nsEvent+nsAmp;
		if (typeof(nsEventItm)!= "undefined") nsTrackSrc+="itm="+nsEventItm;
	}

	if (nsCode==1) {
		nsCounter[nsInx] = new Image();
		nsCounter[nsInx].src = nsTrackSrc;
	}
	if (nsCode==2) {
		document.write("<img src=\""+nsTrackSrc+"\" width=1 height=1 border=0 style=\"display:none\">");
	}
	if (nsCode=='js') {
		document.write("<scr"+"ipt src=\""+nsTrackSrc+"\" type=\"text/javascript\"></scr"+"ipt>");
	}

}
if (nsTrackEnable && !nsCustomRun) nsDoTrack();