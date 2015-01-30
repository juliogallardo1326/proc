var img_cache = new Object();

function parse_images() {
	if (document.getElementById) {
		var ar = document.getElementsByTagName('img');
		for( var x = 0; ar[x]; x++ ) {
			var im = ar[x];
			if(im.getAttribute) { 
				im.hoversrc = im.getAttribute('hoversrc');
			}
			if(im.hoversrc) {
				im.rootsrc = im.src;				
				im.onmouseout = function () {
					this.src = this.rootsrc;
				}
				
				if(!img_cache[im.hoversrc]) {
					img_cache[im.hoversrc] = new Image();
					img_cache[im.hoversrc].src = im.hoversrc;
				}

				im.onmouseover = function () {			
					this.src = this.hoversrc;								
				}
			}
		}
	}
}

function addBookmark(title,url) {
	if (window.sidebar) { 
		window.sidebar.addPanel(title, url,""); 
	} else if( document.all ) {
		window.external.AddFavorite( url, title);
	} else if( window.opera && window.print ) {
	return true;
	}
}

function Menu(id, pid) {
	x = 14;
	if (document.getElementById(id).className == 'noact') {
		for (i = 1; i <= x; i++) {
			document.getElementById('a_' + i).className = 'noact';
			document.getElementById('z_' + i).style.display = 'none';
		}
		document.getElementById(id).className = 'act';
			document.getElementById(pid).style.display = 'block';
	}
	else {
		for (i = 1; i <= x; i++) {
			document.getElementById('a_' + i).className = 'noact';
			document.getElementById('z_' + i).style.display = 'none';
		}
		document.getElementById(id).className = 'noact';
		document.getElementById(pid).style.display = 'none';
	}
}