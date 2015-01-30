// an object oriented news scroller

var mArray = [
	"Welcome to Hushmail!",
	"<a href=\"login.php?" + G_sess + "&subloc=extrastorage\">Add space for your <b>File Sharing</b> and <b>Secure Document Storage</b> needs!</a>",
	"<a href=\"login.php?" + G_sess + "&subloc=imap\"><b>IMAP Access</b> and <b>Hushmail for Outlook</b> plugin now available!</a>",
	"Submit forms in <a href=\"login.php?" + G_sess + "&subloc=secureforms\">complete privacy with Hush Secure Forms</a>.",
	"Join the <a href=\"about.php?" + G_sess + "&subloc=affiliate\">Hushmail Affiliate Program</a> and make money promoting security!",
	"<b>Hush Messenger.  Instant Messaging.  Secure.  <a href=\"login.php?" + G_sess + "&subloc=messenger\">Get started now.</a>",
	"<a href=\"login.php?" + G_sess + "&subloc=newfeatures\">Use <b>Spam Control</b> and keep your Inbox clean - click for information!</a>",
	"<b>Important notice:</b> If your account has been deactivated for 3 weeks of inactivity, <a href=\"login.php?" + G_sess + "&subloc=upgrade\">reactivate it by upgrading to a Premium Account</a>."
	];

itemsArray = [];
moveArray = [];
waitArray = [];

function scrollActive(){
	var i = moveArray.length;
	while(i--){
		moveArray[i].scrollBy(2);
	}
};


function sizeScrollingNews(){
	newsWindowWidth = (ns4) ? innerWidth : document.body.clientWidth-5;
	var h = getItem("scrollHolder");
	if(ns4) h.style = h;
	h.style.width = newsWindowWidth;
	setClipArea(h,0,newsWindowWidth,50,0);
	for(i=0;i<waitArray.length;i++){
		waitArray[i].style.left = newsWindowWidth;
	}
}

function makeScrollingNews(){
	newsWindowWidth = (ns4) ? innerWidth : document.body.clientWidth;
	var d;
	document.write('<div id="scrollHolder">');
	for(i=0;i<mArray.length;i++){
		d = new NewsItem(i);
		d.divObject = d.writeNewsItem(i);
		d.style = (ns4) ? d.divObject : d.divObject.style;
		d.message = mArray[i];
		d.initScroll(i);
		itemsArray.push(d);
//		itemsArray[itemsArray.length] = d;
	}
	waitArray = itemsArray.slice();
	document.write('</div>');
};

function initScrollingNews(){
	if(!ns4) window.onresize = sizeScrollingNews;
	var h = getItem("scrollHolder");
	if(ns4){ h.style = h; var hi=18; var t=10; var l=10; }
	else{ var hi="18px"; var t="10px"; var l="10px"; }
	h.style.height = hi;
	h.style.top = t;
	h.style.left = l;
	sizeScrollingNews();
	moveArray.push(itemsArray[0]);
	waitArray.removeItem(itemsArray[0]);
	scrollingMachine = setInterval("scrollActive()",30);
}

function NewsItem(i){
	this.className = "newsItem";
};

NewsItem.prototype.writeNewsItem = function(i){
	var div = '<div id="news'+i+'" class="newsItem"><table><tr><td NOWRAP><p class="newsItem">';
	div += mArray[i];
	div += '</p></td></tr></table></div>';
	document.write(div);
	return getScrollItem("news"+i);
}

NewsItem.prototype.initScroll = function(i,s){
	this.index = i;
	this.anchor = 0;
	this.width = (ns4) ? this.divObject.clip.right : this.divObject.offsetWidth;
	this.triggered = 0;
	if(moz)this.style.top = "-14px";
	this.style.left = (newsWindowWidth+this.anchor) + ((css) ? "px" : "");
};


NewsItem.prototype.scrollBy = function(a){
	var p = (parseInt(this.style.left)-a);
	if(!this.triggered && newsWindowWidth-p > this.width + 10){
		this.triggered = 1;
		var t = itemsArray[(this.index+1)%itemsArray.length];
//		alert(this.width);
		if(waitArray.length){
			moveArray.push(t);
			waitArray.removeItem(t);
		}
	}
	
	if(p < -this.width){
		p = newsWindowWidth+this.anchor;
		this.triggered = 0;
		if(waitArray.length || !moveArray[moveArray.length-1].triggered){
			waitArray.push(this);
			moveArray.removeItem(this);
		}
	}
	var l = this.cLeft - p;
//	this.setClipArea(this.cTop, l+this.cWide, this.cTop+this.cHigh, l);
	this.style.left = (ns4) ? p : p+"px";
	this.style.visibility = "visible";
//	window.status = this.width + " : " + newsWindowWidth + " : "+ p;
};


function setClipArea(targ,t,r,b,l){
//	alert("targ="+targ.name+" t="+t+" r="+r+" b="+b+" l="+l);
	if(ns4){
//		targ = getItem("scrollHolder");
		targ.clip.top = t;
		targ.clip.right = newsWindowWidth-30;
		targ.clip.bottom = b;
		targ.clip.left = l;
	}else{
		targ.style.clip = 'rect('+t+'px,'+r+'px,'+b+'px,'+l+'px)';
	}
};


// misc. -----------

Array.prototype.getplace = function(item){
	for(i in this){
		if(this[i]==item){ return(i);}
	}
	return(-1);
};

Array.prototype.removeItem = function(item){
	var p = this.getplace(item);
	if(p>-1) this.splice(p,1);
};

function getScrollItem(targ){
	if(css) return document.getElementById(targ);
	else if(ns4) return document.scrollHolder.document[targ];
	else return targ;
};

