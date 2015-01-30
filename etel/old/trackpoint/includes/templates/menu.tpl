<script language="JavaScript" type="text/javascript" src="includes/templates/mm_menu.js"></script>
<script language="JavaScript">
		function mmLoadMenus()
				{
					window.mm_menu_campaigns = new Menu("root",120,20,"Tahoma, Verdana, Arial, Helvetica, sans-serif",11,"#000000","#FFFFFF","#FFFFD6","#0D8655","left","middle",3,0,200,-5,7,true,true,true,0,true,true);
			
					mm_menu_campaigns.hideOnMouseOut=true;
					mm_menu_campaigns.bgColor='#0D8655';
					mm_menu_campaigns.menuBorder=1;
					mm_menu_campaigns.menuLiteBgColor='#0D8655';
					mm_menu_campaigns.menuBorderBgColor='';
					mm_menu_campaigns.addMenuItem("%%LNG_CampaignStats%%","location='index.php?Page=Campaigns'");
					mm_menu_campaigns.addMenuItem("%%LNG_CreateCampaign%%","location='index.php?Page=Createcampaign'");

					window.mm_menu_ppc = new Menu("root",135,20,"Tahoma, Verdana, Arial, Helvetica, sans-serif",11,"#000000","#FFFFFF","#FFFFD6","#0D8655","left","middle",3,0,200,-5,7,true,true,true,0,true,true);
			
					mm_menu_ppc.hideOnMouseOut=true;
					mm_menu_ppc.bgColor='#0D8655';
					mm_menu_ppc.menuBorder=1;
					mm_menu_ppc.menuLiteBgColor='#0D8655';
					mm_menu_ppc.menuBorderBgColor='';
					mm_menu_ppc.addMenuItem("%%LNG_PPCStats%%","location='index.php?Page=ppc'");
					mm_menu_ppc.addMenuItem("%%LNG_CreatePPC%%","location='index.php?Page=Createppc'");

					window.mm_menu_search = new Menu("root",155,20,"Tahoma, Verdana, Arial, Helvetica, sans-serif",11,"#000000","#FFFFFF","#FFFFD6","#0D8655","left","middle",3,0,220,-5,7,true,true,true,0,true,true);
			
					mm_menu_search.hideOnMouseOut=true;
					mm_menu_search.bgColor='#0D8655';
					mm_menu_search.menuBorder=1;
					mm_menu_search.menuLiteBgColor='#0D8655';
					mm_menu_search.menuBorderBgColor='';
					mm_menu_search.addMenuItem("%%LNG_SearchStatsKeyword%%","location='index.php?Page=Search'");
					mm_menu_search.addMenuItem("%%LNG_SearchStatsEngine%%","location='index.php?Page=Engines'");

					window.mm_menu_referrers = new Menu("root",100,20,"Tahoma, Verdana, Arial, Helvetica, sans-serif",11,"#000000","#FFFFFF","#FFFFD6","#0D8655","left","middle",3,0,200,-5,7,true,true,true,0,true,true);
			
					mm_menu_referrers.hideOnMouseOut=true;
					mm_menu_referrers.bgColor='#0D8655';
					mm_menu_referrers.menuBorder=1;
					mm_menu_referrers.menuLiteBgColor='#0D8655';
					mm_menu_referrers.menuBorderBgColor='';
					mm_menu_referrers.addMenuItem("%%LNG_ReferrersStats%%","location='index.php?Page=Referrers'");

					window.mm_menu_view_conversions = new Menu("root",110,20,"Tahoma, Verdana, Arial, Helvetica, sans-serif",11,"#000000","#FFFFFF","#FFFFD6","#0D8655","left","middle",3,0,200,-5,7,true,true,true,0,true,true);
			
					mm_menu_view_conversions.hideOnMouseOut=true;
					mm_menu_view_conversions.bgColor='#0D8655';
					mm_menu_view_conversions.menuBorder=1;
					mm_menu_view_conversions.menuLiteBgColor='#0D8655';
					mm_menu_view_conversions.menuBorderBgColor='';
					mm_menu_view_conversions.addMenuItem("%%LNG_ConversionStats%%","location='index.php?Page=View_Conversions'");
					
					writeMenus();
				}
				mmLoadMenus();
</script>
<TABLE id="MenuTable" cellSpacing="0" cellPadding="0" width="100%" border="0">
	<TR>
		<TD vAlign="top" align="right" width="100%" bgColor="#ffffff"></TD>
		<TD vAlign="top" align="right" class="appColor"><A title="%%LNG_Help_Campaigns%%" href="index.php?Page=Campaigns"><IMG src="images/mnucampaigns.gif" border="0" width="111" height="30" onmouseover="MM_showMenu(window.mm_menu_campaigns,18,28,null,'link1')" onmouseout="MM_startTimeout();" id="link1"></A><A title="%%LNG_Help_PPC%%" href="index.php?Page=ppc"><IMG src="images/mnuppc.gif" border="0" width="122" height="30" onmouseover="MM_showMenu(window.mm_menu_ppc,18,28,null,'link2')" onmouseout="MM_startTimeout();" id="link2"></A><A title="%%LNG_Help_Search%%" href="index.php?Page=Search"><IMG src="images/mnusearchresults.gif" border="0" width="134" height="30" onmouseover="MM_showMenu(window.mm_menu_search,18,28,null,'link3')" onmouseout="MM_startTimeout();" id="link3"></A><A title="%%LNG_Help_Referrers%%" href="index.php?Page=Referrers"><IMG src="images/mnureferrers.gif" border="0" width="100" height="30" onmouseover="MM_showMenu(window.mm_menu_referrers,18,28,null,'link4')" onmouseout="MM_startTimeout();" id="link4"></A><A title="%%LNG_Help_Conversions%%" href="index.php?Page=View_Conversions"><IMG src="images/mnuconversions.gif" border="0" width="122" height="30" onmouseover="MM_showMenu(window.mm_menu_view_conversions,18,28,null,'link5')" onmouseout="MM_startTimeout();" id="link5"></A></TD>
		<td bgColor="#ffffff">&nbsp;&nbsp;&nbsp; </td></TR>
	<tr>
		<td colSpan="3" height="10"></td>
	</tr>
</TABLE>
<table><tr><td width="25"><img src="images/blank.gif" width="25" height="10"></td><td width="100%">