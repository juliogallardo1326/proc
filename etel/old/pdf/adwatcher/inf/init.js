		function init() {
			if (TransMenu.isSupported()) {
				TransMenu.initialize();
				menu1.onactivate = function() { document.getElementById("account").className = "hover"; document.getElementById("account").style.background = '#FFFFFF'; };
				menu1.ondeactivate = function() { document.getElementById("account").className = ""; document.getElementById("account").style.background = ''; };
				menu2.onactivate = function() { document.getElementById("campaigns").className = "hover"; document.getElementById("campaigns").style.background = '#FFFFFF';};
				menu2.ondeactivate = function() { document.getElementById("campaigns").className = ""; document.getElementById("campaigns").style.background = ''; };
				menu3.onactivate = function() { document.getElementById("reports").className = "hover"; document.getElementById("reports").style.background = '#FFFFFF';};
				menu3.ondeactivate = function() { document.getElementById("reports").className = ""; document.getElementById("reports").style.background = ''; };
				menu4.onactivate = function() { document.getElementById("resources").className = "hover"; document.getElementById("resources").style.background = '#FFFFFF';};
				menu4.ondeactivate = function() { document.getElementById("resources").className = ""; document.getElementById("resources").style.background = ''; };
				menu5.onactivate = function() { document.getElementById("support").className = "hover"; document.getElementById("support").style.background = '#FFFFFF';};
				menu5.ondeactivate = function() { document.getElementById("support").className = ""; document.getElementById("support").style.background = ''; };
				menu6.onactivate = function() { document.getElementById("fraud").className = "hover"; document.getElementById("fraud").style.background = '#FFFFFF';};
				menu6.ondeactivate = function() { document.getElementById("fraud").className = ""; document.getElementById("fraud").style.background = ''; };
				document.getElementById("home").onmouseover = function() { ms.hideCurrent(); this.className = "hover"; document.getElementById("home").style.background = '#FFFFFF';}
				document.getElementById("home").onmouseout = function() { this.className = ""; document.getElementById("home").style.background = ''; }
				document.getElementById("logout").onmouseover = function() { ms.hideCurrent(); this.className = "hover"; document.getElementById("logout").style.background = '#FFFFFF';}
				document.getElementById("logout").onmouseout = function() { this.className = ""; document.getElementById("logout").style.background = ''; }					
			}
		}