	if (TransMenu.isSupported()) {
		var ms = new TransMenuSet(TransMenu.direction.down, 1, 0, TransMenu.reference.bottomLeft);
		var menu1 = ms.addMenu(document.getElementById("account"));
		menu1.addItem("- account information", "/user/?profile"); 
		menu1.addItem("- account billing", "");
		menu1.addItem("- change password", "/user/?password");
		menu1.addItem("- logout", "/?logout");
		//==================================================================================================
		var menu2 = ms.addMenu(document.getElementById("campaigns"));
		menu2.addItem("- overview", "");
		menu2.addItem("- new campaign", "");
		menu2.addItem("- edit campaign", "");
		menu2.addItem("- delete campaign", "");
		menu2.addItem("- code generation", "");
		//==================================================================================================
		var menu3 = ms.addMenu(document.getElementById("reports"));
		menu3.addItem("- general reports", "");
		menu3.addItem("- visitor reports", "");
		menu3.addItem("- financial reports", "");
		//==================================================================================================
		var menu4 = ms.addMenu(document.getElementById("resources"));
		menu4.addItem("- documentation", "");
		menu4.addItem("- where to advertise", "");
		menu4.addItem("- downloads", "");
		//==================================================================================================
		var menu5 = ms.addMenu(document.getElementById("support"));
		menu5.addItem("- helpdesk main", "/support/?helpdesk");
		menu5.addItem("- submit new ticket", "/support/?helpdesk&new");
		menu5.addItem("- view open tickets", "/support/?helpdesk");
		menu5.addItem("- view closed tickets", "/support/?helpdesk&closed");		
		TransMenu.renderAll();
	}