<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<?require_once SELF."/lib/calendar.func.php"?>

<SCRIPT LANGUAGE="JavaScript">


function ShowOnlinePeriod()
{
	var oCheck=GetObj("OnlineOnly");
	var oInput=GetObj("OnlinePeriod");
	var oText=GetObj("OPText");
	if (!oCheck.checked) {
		oInput.style.display="none";
		oText.style.display="none";
	}
	else {
		oInput.disabled=false;
		oInput.style.display="";
		oText.style.display="";
	}
}


var HGObj=false;
var HGSel=false;
var HGShowed=false;

var LastSelName=false;

function CloseHostGrp()
{
	if (!HGObj) return false;
	if (HGShowed) {
		HGShowed=false;
		return false;
	}
	ObjRef(LastSelName).selectedIndex=-1;
	HGObj.CurrentPos=false;
	HGObj.CurrentRef=false;
	HGObj.CurrentAgent=false;
	HGObj.Close();
}

function ShowMenu(SubId, ImgId, GrpId, DivName, SelName, VisId)
{
	if (HGObj && HGObj.ObjType!=DivName) {
		HGObj.Close();
		HGObj=false;
	}
	if (!HGObj) {
		HGObj= new DynamicObj(DivName);
		HGObj.el.onclick=function () {
			HGShowed=true;
		}
		HGObj.CurrentPos=false;
		HGObj.ObjType=DivName;
	}
	HGShowed=true;
	HGObj.VisId=VisId;
	if (HGObj.CurrentPos==ImgId) {
		HGObj.CurrentPos=false;
		HGObj.CurrentRef=false;
		HGObj.CurrentAgent=false;
		HGObj.Close();
		ObjRef(SelName).selectedIndex=-1;
		return;
	}

	LeftOffset=(SelName=="HostGrpList")?0:12;
	HGObj.SetRelativePosition(ImgId, LeftOffset, 2, 2);
	HGObj.CurrentPos=ImgId;
	if (SelName=="HostGrpList") HGObj.CurrentRef=SubId;
	if (SelName=="AgentGrpList") HGObj.CurrentAgent=SubId;
	LastSelName=SelName;
	if (GrpId) {
		HGSel = ObjRef(SelName);
		for (var i=0; i < HGSel.options.length; i++) {
			if (HGSel.options[i].value == GrpId) {
				HGSel.selectedIndex=i;
				break;
			}
		}
	}
	HGObj.Show();

	document.body.onclick=CloseHostGrp;
}

function ShowHostGrpMenu(RefId, ImgId, GrpId, VisId) {
	ShowMenu(RefId, ImgId, GrpId, "HostGrpMenu", "HostGrpList", VisId);
}

function ShowAgentGrpMenu(AgId, ImgId, GrpId, VisId) {
	ShowMenu(AgId, ImgId, GrpId, "AgentGrpMenu", "AgentGrpList", VisId);
}

function MoveToGrp() {
	if (HGObj.CurrentRef) {
		ObjRef("MoveReferer").value=HGObj.CurrentRef;
		ObjRef("MoveToGrp").value=ObjRef("HostGrpList").options[ObjRef("HostGrpList").selectedIndex].value;
	}
	if (HGObj.CurrentAgent) {
		ObjRef("MoveAgent").value=HGObj.CurrentAgent;
		ObjRef("MoveToGrp").value=ObjRef("AgentGrpList").options[ObjRef("AgentGrpList").selectedIndex].value;
	}
	ObjRef("AName").value=HGObj.VisId;
	ObjRef("VisForm").submit();
}


</SCRIPT>


<div id="HostGrpMenu" style="display:none;position:absolute; background:#ffffff; padding:8px; border:2px solid #77B60B;">
<select id="HostGrpList">
<option value=-1></option>
<?for ($i=0;$i<count($HostGrpArr);$i++) {?>
	<option value=<?=$HostGrpArr[$i]->ID?>><?=$HostGrpArr[$i]->NAME?></option>
<?}?>
</select>&nbsp;
<input type=button onclick="MoveToGrp();" value="<?=$Lang['MoveToGrp']?>">
</div>


<div id="AgentGrpMenu" style="display:none;position:absolute; background:#ffffff; padding:8px; border:2px solid #77B60B;">
<select id="AgentGrpList">
<option value=-1></option>
<?for ($i=0;$i<count($AgentGrpArr);$i++) {?>
	<option value=<?=$AgentGrpArr[$i]->ID?>><?=$AgentGrpArr[$i]->NAME?></option>
<?}?>
</select>&nbsp;
<input type=button onclick="MoveToGrp();" value="<?=$Lang['MoveToGrp']?>">
</div>


<table width=100% cellpadding=0 cellspacing=0 border=0>
<tr><td height=24 width=100% >
<!-- табы -->

<table width=100% height=26 cellpadding=0 cellspacing=0 border=0>
<tr height=2>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<?if ($Settings['All']->FRAUD_ENABLE) {?><td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td><?}?>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#ffffff"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td bgcolor="#85C71D"><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
<td><p><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="2" BORDER="0" ALT=""></p></td>
</tr>
<tr height=24>
<td class=LogTab3 nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Hits$Get", "report");?>"><?=$Lang['MenuHits']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Clicks$Get", "report");?>"><?=$Lang['MenuClicks']?></a></p></td>
<?if ($Settings['All']->FRAUD_ENABLE) {?><td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Fraud$Get", "report");?>"><?=$Lang['MenuClickFraud']?></a></p></td><?}?>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Actions$Get", "report");?>"><?=$Lang['MenuActions']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Sales$Get", "report");?>"><?=$Lang['MenuSales']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Split$Get", "report");?>"><?=$Lang['MenuSplits']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Ref$Get", "report");?>"><?=$Lang['MenuRefs']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Key$Get", "report");?>"><?=$Lang['MenuKeys']?></a></p></td>
<td class=LogTab nowrap><p class=TabsMenu><a href="<?=getURL("logs", "Mode=Undef$Get", "report");?>"><?=$Lang['MenuUndef']?></a></p></td>
<td class=LogTab2 nowrap><p class=TabsMenu><a href="<?=getURL("visitor_path", $Get, "report");?>"><?=$Lang['Paths']?></a></p></td>
<td width=100%	style="border-left-style:solid;	border-left-width:1px;	border-left-color:e5e5e5;	border-bottom-style:solid;
	border-bottom-width:1px;	border-bottom-color:#C8C8C8;">&nbsp;</td>
</tr></table>

</td></tr><tr><td width=100% style="border-left-style:solid;border-left-width:1px;border-left-color:#C7C7C7; border-bottom-style:solid;border-bottom-width:1px;border-bottom-color:#C7C7C7; border-right-style:solid;border-right-width:1px;border-right-color:#C7C7C7;">
<!-- фильтры -->
<?GetFORM(false, false, false, "ID=\"VisForm\"");?>
<input type=hidden name="CpId" value="<?=$CpId?>">
<?if (count($SitesArr)<2) {?><input type=hidden name="SiteId" value="<?=$SiteId?>"><?}?>
<input type=hidden name="GrpId" value="<?=$GrpId?>">
<input type=hidden name="AllSites" value="<?=$AllSites?>">

<input type=hidden name="MoveAgent" id="MoveAgent" value="">
<input type=hidden name="MoveReferer" id="MoveReferer" value="">
<input type=hidden name="MoveToGrp" id="MoveToGrp" value="">
<input type=hidden name="AName" id="AName" value="">

<table width=100% cellpadding=8 cellspacing=0 border=0>

	<tr><td width=80 nowrap style="padding-left:10px;padding-bottom:4px;">
	<?=$Lang['ChooseSite']?>:
	</td><td width=220 style="padding-bottom:4px;">
	<select name=SiteId style="width:220px">
	<option value=0><?=$Lang['AllSites']?></option>
	<?for($i=0;$i<Count($SitesArr);$i++) {?>
		<option value="<?=$SitesArr[$i]->ID?>" <?=(($SitesArr[$i]->ID==$SiteId)?"selected":"")?>>
		<?=$SitesArr[$i]->HOST?>
		</option>
	<?}?>
	</select>
	</td>
	<td width=80 style="padding-bottom:4px;">
	<?=$Lang['Date']?>:
	</td>
	<td nowrap width=100 style="padding-bottom:4px;">
	<input type=text size=5 class=DateFld  id="ViewDate" name="ViewDate" ondblclick="this.value=''" value="<?=$ViewDate?>">&nbsp;
	<a href="javascript:;" onclick="ShowCalendar('ViewDate');"><IMG SRC="<?=FileLink("images/icon_date.gif");?>" WIDTH="18" HEIGHT="18" BORDER="0" ALT="" align=middle align=absmiddle></a>
	</td>
	<td style="padding-bottom:4px;">
	<?if (!ValidId($VisId)) {?>
	<table cellpadding=0 cellspacing=0 border=0><tr>
	<td><input type=checkbox id="OnlineOnly" name="OnlineOnly" value=1 <?=(($OnlineOnly)?"checked":"")?> onclick="ShowOnlinePeriod();"></td>
	<td style="padding-left:5px;"><label for="OnlineOnly"><?=$Lang['OnlineOnly']?></label></td>
	</tr></table>
	<?}?>

	</td>
	</tr>

	<tr><td width=80 nowrap style="padding-left:10px;padding-top:4px;padding-bottom:4px;">
	<?=$Lang['Filter']?>
	</td><td width=220 style="padding-top:3px;padding-bottom:4px;">
	<input type=text style="width:107px;padding-left:3px;" name=Filter ondblclick="this.value=''"  value="<?=$Filter?>">&nbsp;
	<select name=FilterFor style="width:107px;">
	<option value="IP" <?=(($FilterFor=="IP")?"selected":"")?>><?=$Lang['ByIp']?></option>
	<option value="Action" <?=(($FilterFor=="Action")?"selected":"")?>><?=$Lang['ByAction']?></option>
	<option value="ActionItem" <?=(($FilterFor=="ActionItem")?"selected":"")?>><?=$Lang['ByActionItem']?></option>
	<option value="Path" <?=(($FilterFor=="Path")?"selected":"")?>><?=$Lang['ByPath']?></option>
	<option value="Sale" <?=(($FilterFor=="Sale")?"selected":"")?>><?=$Lang['BySale']?></option>
	<option value="SaleItem" <?=(($FilterFor=="SaleItem")?"selected":"")?>><?=$Lang['BySaleItem']?></option>
	<option value="Agent" <?=(($FilterFor=="Agent")?"selected":"")?>><?=$Lang['ByAgent']?></option>
	<option value="Ref" <?=(($FilterFor=="Ref")?"selected":"")?>><?=$Lang['ByRef']?></option>
	<option value="Key" <?=(($FilterFor=="Key")?"selected":"")?>><?=$Lang['ByKey']?></option>
	</select>
	</td>
	<td width=80 style="padding-top:3px;padding-bottom:4px;">
	<?=$Lang['Vis']?>:
	</td>
	<td width=100 style="padding-top:3px;padding-bottom:4px;">
	<input type=text size=5 class=DateFld name=VisId ondblclick="this.value=''" value="<?=$VisId?>">
	</td>

	<td style="padding-top:4px;padding-bottom:4px;"><input type=submit value="<?=$Lang['Refresh']?>"></td>
	</tr>
	
	<tr>
	<td style="padding-top:3px;"></td>
	<td style="padding-top:3px;" colspan=2>
	<input type=text style="width:50px;padding-left:3px;" name="OnPage" value="<?=$OnPage?>">&nbsp;&nbsp;<?=$Lang['VisitorsOnPage']?>
	&nbsp;&nbsp;
	
	</td>
	<td colspan=3 style="padding-top:3px;">
	<?if (!ValidId($VisId)) {?>
	<table cellpadding=0 cellspacing=0 border=0><tr><td>
	<input type=text id="OnlinePeriod" name=OnlinePeriod size=5 style="width:65px;padding-left:3px;"value="<?=$OnlineTime?>"></td>
	<td style="padding-left:5px;"><div id="OPText"><?=$Lang['OnlinePeriod']?></div></td>
	</tr></table>
	<?}?>
	</td>

	</tr>
	
	<tr><td style="padding-top:0px;"></td>
	<td style="padding-top:0px;">
	<select name="UserOrder" style="width:100%;">
	<option value="ASC" <?=(($UserOrder=="ASC")?"selected":"")?>><?=$Lang['AscOrder']?></option>
	<option value="DESC" <?=(($UserOrder=="DESC")?"selected":"")?>><?=$Lang['DescOrder']?></option>
	</select>
	</td>
	<td colspan=3 style="padding-top:0px;"></td></tr>

</table>


</form>
</td></tr>
</table>
<?if (!ValidId($VisId)) {?>
<SCRIPT LANGUAGE="JavaScript">
<!--
ShowOnlinePeriod();
//-->
</SCRIPT>
<?}?>



<?$Pages->Dump();?>

</td></tr></table>
<table width=100% cellpadding=0 cellspacing=0 border=0><tr>
<td style="padding-left:41px;padding-right:20px;">
<!--// -->



<span style="line-height:16px;">

<?
	foreach ($VPath as $vi=>$RowArr) {
		for($i=0;$i<count($RowArr);$i++) {
		$Row=$RowArr[$i];?>

	<?if ($Row->NewDay&&$IpCnt<2&&(!ValidId($VisId)||ValidVar($ViewDate))) {?>
		<br><B>
		<?if (ValidVar($PrevDate)) {?>
		<a href="<?=$nsProduct->SelfAction("SiteId=$SiteId&AllSites=$AllSites&GrpId=$GrpId&CpId=".$Row->COMPANY_ID."&VisId=$VisId&ViewDate=$PrevDate")?>"><IMG SRC="<?=FileLink("images/page_prev.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT=""></a>&nbsp;
		<?}?>
		<?=$Row->Date?>.<?=$Row->Year?>
		<?if (ValidVar($NextDate)) {?>
		&nbsp;<a href="<?=$nsProduct->SelfAction("SiteId=$SiteId&AllSites=$AllSites&GrpId=$GrpId&CpId=".$Row->COMPANY_ID."&VisId=$VisId&ViewDate=$NextDate")?>"><IMG SRC="<?=FileLink("images/page_next.gif");?>" WIDTH="11" HEIGHT="11" BORDER="0" ALT=""></a>
		<?}?>
		</B>
		<?if ($Row->TotalTime&&$VisId) {
			echo "<span style=\"color:#8C8C8C\">[";
			echo $Lang['VisitLength'].":&nbsp;";
			echo $Row->Hours.$Lang['Hour'].":".$Row->Minutes.$Lang['Minute'];
			echo "]</span>";
		}?>
		<br>
	<?}?>

	<?if ($Row->NewVis) {?>
		<br><B>
		<?if (!ValidId($VisId)) {?><a href="<?=$nsProduct->SelfAction("SiteId=$SiteId&GrpId=$GrpId&CpId=".$Row->COMPANY_ID."&VisId=".$Row->VISITOR_ID); ?>" name="Vis<?=$Row->VISITOR_ID?>">	<?}?>
		<IMG SRC="<?=FileLink("images/icon_visitor".(($Row->ONLINE)?"_online":"").".gif");?>" WIDTH="9" HEIGHT="9" BORDER="0" ALT="">&nbsp;
		<?if (!$Row->VISITOR_NAME) {?><?=$Lang['Vis']?> <?=$Row->VISITOR_ID?><?}?>
		<?if ($Row->VISITOR_NAME) { echo $Row->VISITOR_NAME; }?>
		<?if (!ValidId($VisId)) {?></a><?}?>
		</B>
		 [<?=$Row->LAST_IP?><?=(($Row->COUNTRY_ID)?", ".$Row->COUNTRY_NAME:"")?>]
		 <?if ($VisId&&$AllowVisInfo) {?>
		 / <a href="<?=getURL("visitor", "ViewId=$VisId&CpId=".$CurrentCompany->ID, "admin")?>"  style="color:#77B60B"><?=$Lang['AddInfo']?></a>
		 <?}?>
		 <?if (ValidId($VisId)&&$VisitorAgent) {?><br><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="9" HEIGHT="1" BORDER="0" ALT="" id="Agn_<?=$Visitor->ID?>">&nbsp;<a href="javascript:;" onclick="ShowAgentGrpMenu(<?=$Visitor->AGENT_ID?>, 'Agn_<?=$Visitor->ID?>', <?=$Visitor->GRP_ID?>, <?=$Row->VISITOR_ID?>);"><?=$VisitorAgent?></a>
		 <?if ($Visitor->GRP_ID) {?>
		 &nbsp;<span class="LogRefGrp"><B>[<a href="<?=getURL("natural_constructor", "CpId=$CpId&GroupBy=AgentGrp&ShowAll=1", "report")?>"><?=$Visitor->GRP_NAME?></a>&nbsp;]</B></span>
		 <?}?>
		 <?}?>
		 <?if (ValidVar($Row->USER_AGENT)) {?><br><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="9" HEIGHT="1" BORDER="0" ALT="">&nbsp;<?=$Row->USER_AGENT?>
		 <?} else {?>
			<?if ($Row->FIRST_STAMP) {
				echo "<span style=\"color:#8C8C8C\">[".$Lang['FirstTime'];
				echo $Row->FIRST_STAMP_NAME;
				echo "]</span>";
			}?>
		 <?}?>
		<?if ($Row->TotalTime&&!$VisId) {
			echo "<span style=\"color:#8C8C8C\">[";
			echo $Lang['VisitLength'].":&nbsp;";
			echo $Row->Hours.$Lang['Hour'].":".$Row->Minutes.$Lang['Minute'];
			echo "]</span>";
		}?>
	<br>
	<?}?>


	<?if (($Row->NewDay&&!$ViewDate&&!$GrpId)||($IpCnt>1&&$Row->NewVis&&!$GrpId)) {?>
		<br>
		<IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="10" HEIGHT="12" BORDER="0" ALT=""><B>
		<?=$Row->Date?>.<?=$Row->Year?>
		</B>
		<?if ($Row->TotalTime) {
			echo "<span style=\"color:#8C8C8C\">[";
			echo $Lang['VisitLength'].":&nbsp;";
			echo $Row->Hours.$Lang['Hour'].":".$Row->Minutes.$Lang['Minute'];
			echo "]</span>";
		}?>
		<br>
	<?}?>


	<?if ($Row->NewRef) {?>
		<?if ($Row->REFERER) {?>
		
		<div style="padding-left:10px;"><nobr>
			<a href="javascript:;" onclick="ShowHostGrpMenu(<?=$Row->HOST_ID?>, 'Ref_<?=$Row->ID?>', <?=$Row->HOST_GRP_ID?>, <?=$Row->VISITOR_ID?>);">
			<IMG SRC="<?=FileLink("images/icon_ref.gif");?>" WIDTH="12" HEIGHT="9" BORDER="0" ALT="" ID="Ref_<?=$Row->ID?>"></a>
		<span style="font-size:10px;">
		&nbsp;&nbsp;<?=$Row->Time?>&nbsp;
		</span>
		<a href="<?=$Row->REFERER?>" target=_blank style="color:#77B60B">
		<?=htmlspecialchars(urldecode(urldecode($Row->REFERER)))?>
		</a>
		<?if (ValidId($Row->HOST_GRP_ID)&&$Row->HOST_GRP_ID>0) {?>
		&nbsp;<span class="LogRefGrp"><B>[<a href="<?=getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&HostGrpId=".$Row->HOST_GRP_ID, "report")?>" title="<?=$Lang['SourceStat']?>"><?=$Row->HOST_GRP?>&nbsp;<IMG SRC="<?=FileLink("images/icon_ref_stat.gif");?>" WIDTH="8" HEIGHT="8" BORDER="0" ALT="">&nbsp;</a>]</B></span>
		<?}?>
		<?if (ValidVar($Row->KEYWORD)) {?>
		&nbsp;<span class="LogRefGrp">[<a href="<?=getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&KeyId=".$Row->NATURAL_KEY, "report")?>"  title="<?=$Lang['KeyStat']?>"><?=$Row->KEYWORD?>&nbsp;<IMG SRC="<?=FileLink("images/icon_ref_stat.gif");?>" WIDTH="8" HEIGHT="8" BORDER="0" ALT="">&nbsp;</a>]</span>
		<?}?>
		</nobr></div>
		<?}?>
	<?}?>

	<div style="padding-left:10px;"><nobr><IMG SRC="<?=FileLink("images/icon_doc.gif");?>" WIDTH="12" HEIGHT="9" BORDER="0" ALT="">
	<span style="font-size:10px;">
	&nbsp;	<?=$Row->Time?>&nbsp;
	</span>
	<a href="<?=$Row->LINK?>" target=_blank>
	<?=$Row->PATH?><?if ($Row->QUERY) echo $Row->QUERY;?>
	</a>
	<?if ($Row->NAME) echo " <B>- ".$Row->NAME."</B>";?>
	<?if ($Row->ACTION_ID>0) {
		echo " <span class=LogRefGrp><B>[";
		echo "<a href=\"".getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&WhereArr[0][Mode]=Site&WhereArr[0][Id]=".$Row->SITE_ID."&GroupBy=Action", "report")."\">";
		echo $Row->ACTION;
		if ($Row->ACTION_ITEM) {
			echo " : ".stripslashes($Row->ACTION_ITEM);
		}
		echo "&nbsp;<IMG SRC=\"".FileLink("images/small_icon_actions-a.gif")."\" WIDTH=4 HEIGHT=8 BORDER=0>&nbsp;";
		echo "</a>]</B></span>";

	}?>
	<?if ($Row->ORDER_ID) {
		echo " <span class=LogRefGrp><B>[";
		echo "<a href=\"".getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&WhereArr[0][Mode]=Order&WhereArr[0][Id]=".$Row->ORDER_ID."&GroupBy=Sale", "report")."\">";
		echo $Lang['OrderNo']." ".$Row->ORDER_ID;
		if ($Row->ITEM_NAME) echo " : ".stripslashes($Row->ITEM_NAME);
		if ($Row->ORDER_COST&&ValidVar($CurrentCompany->CUR[0])) echo ", ".ShowCost($Row->ORDER_COST);
		echo "</a>]</B></span>";
	}?>

	<?if ($Row->CLICK_ID>0) {
			echo "  <span class=LogRefGrp><B>[";
			echo "<a href=\"".getURL("paid_constructor", "CpId=".$Row->COMPANY_ID."&CampId=".$Row->CAMP_ID, "report")."\">";
			echo $Row->CAMP_NAME;
			echo "&nbsp;<IMG SRC=\"".FileLink("images/icon_ref_stat.gif")."\" WIDTH=8 HEIGHT=8 BORDER=0>&nbsp;</a>]</B></span> ";
			
			if ($Row->CAMP_KEYWORD) {
				echo " <span class=LogRefGrp>[";
				echo "<a href=\"".getURL("paid_constructor", "CpId=".$Row->COMPANY_ID."&WhereArr[0][Mode]=Camp&WhereArr[0][Id]=".$Row->CAMP_ID."&GroupBy=CampKey", "report")."\">";
				echo $Row->CAMP_KEYWORD."</a>]</span> ";
			}
	}?>
	<?if ($Row->SPLIT_ID>0) {
			echo " <span class=LogRefGrp><B>[";
			echo "<a href=\"".getURL("natural_constructor", "CpId=".$Row->COMPANY_ID."&WhereArr[0][Mode]=Split&WhereArr[0][Id]=".$Row->SPLIT_CAMP_ID."&GroupBy=Page", "report")."\">";
			echo $Row->SPLIT_NAME."&nbsp;<IMG SRC=\"".FileLink("images/small_icon_split-a.gif")."\" WIDTH=13 HEIGHT=8 BORDER=0>&nbsp;</a>]</B></span>";
	}?>

	<?if ($ViewNode) {?>
	<span style="font-size:10px;">[Node: <a <?=(($Row->NODE_START)?"name=\"Node".$Row->COOKIE_LOG."\"":"")?> href="#Node<?=$Row->COOKIE_LOG?>"><?=(($Row->NODE_START)?"<b>":"")?><?=$Row->COOKIE_LOG?></b></a>]</span>
	<?}?>
	</nobr></div>

<?}}


if (count($VPath)==0) include $nsTemplate->Inc("inc/no_records");?>



</td></tr></table>
<table width=100% cellpadding=0 cellspacing=0 border=0>
<td style="padding-left:41px;padding-right:20px;">

<?$Pages->Dump();?>

<!--// -->


<?include $nsTemplate->Inc("inc/footer");?>