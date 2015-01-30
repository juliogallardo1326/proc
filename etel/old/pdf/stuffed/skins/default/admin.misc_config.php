<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>

<SCRIPT LANGUAGE="JavaScript">
<!--

function CheckSSL()
{
	var Obj=GetObj("SSLink");
	var SLink=Obj.value;
	var Modr=<?=((MOD_R)?"1":"0");?>;
	window.open(SLink+"/"+((Modr)?"":"index.php"), "_blank");
}

function SetTrackingMode(Mode) 
{
	if (Mode!="NONE" && Mode!="PAID" && Mode!="NATURAL") return false;
	if (Mode=="NONE") {
		GetObj('NoneEntryPrior').style.display='';
		GetObj('PaidEntryPrior').style.display='none';
		GetObj('NaturalEntryPrior').style.display='none';
	}
	else {
		GetObj('NoneEntryPrior').style.display='none';
		GetObj('PaidEntryPrior').style.display='';
		GetObj('NaturalEntryPrior').style.display='';
	}
}

//-->
</SCRIPT>


<table  class=FormTable>
<form action="<?=$nsProduct->SelfAction();?>" method=post>


<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['SectionGeneral']?></span>
</td></tr>

<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['SSLink']?><br>
<span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['CheckSSL']?></span>
</td><td class=FormRightTd>
<input type=text ID="SSLink" name="EditArr[SSLink]" value="<?=$EditArr['SSLink']?>" style="width:50%;">
<input type=button value="<?=$Lang['CheckUrl']?>" onclick="CheckSSL();">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['FromEmail']?><br>
</td><td class=FormRightTd>
<input type=text ID="FromEmail" name="EditArr[FromEmail]" value="<?=$EditArr['FromEmail']?>" style="width:50%;">
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['OnlinePeriod']?><br>
</td><td class=FormRightTd>
<input type=text ID="OnlinePeriod" name="EditArr[OnlinePeriod]" value="<?=$EditArr['OnlinePeriod']?>" style="width:40px;">
</td></tr>


<?if (count($SkinsArr)>1) {?>
<tr><td class=FormLeftTd>
<?=$Lang['Skin']?>
</td><td class=FormRightTd>
<select name="EditArr[DefSkin]">
<?for($i=0;$i<count($SkinsArr);$i++) {?>
	<option value="<?=$SkinsArr[$i]?>" <?=(($SkinsArr[$i]==$nsProduct->DEFAULT_SKIN)?"selected":"")?>><?=$SkinsArr[$i]?></option>
<?}?>
</select>
</td></tr>
<?}?>


<?if (count($LangsArr)>1) {?>
<tr><td class=FormLeftTd>
<?=$Lang['Lang']?>
</td><td class=FormRightTd>
<select name="EditArr[DefLang]">
<?for($i=0;$i<count($LangsArr);$i++) {?>
	<option value="<?=$LangsArr[$i]['name']?>" <?=(($LangsArr[$i]['name']==$nsProduct->DEFAULT_LANG)?"selected":"")?>><?=$LangsArr[$i]['caption']?></option>
<?}?>
</select>
</td></tr>
<?}?>



<tr><td class=FormLeftTd>
<?=$Lang['AllowSent']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[SendUsage]" value=1 <?=((ValidVar($EditArr['SendUsage'])=='1')?"checked":"")?>>
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['UseStore']?>
<br>
<span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['StoreDescr']?></span>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[UseStore]" value=1 <?=((ValidVar($EditArr['UseStore'])=='1')?"checked":"")?>>
</td></tr>



<!-- TRACKING MODE -->
<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['TrackingMode']?></span>
</td></tr>

<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['TrafficPrior']?>
<br><span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['TrafficPriorDescr']?></span>
</td><td class=FormRightTd>

<input type=radio id="pr1" name="EditArr[TrafficPrior]" <?=(($EditArr['TrafficPrior']=="PAID")?"checked":"")?> value=PAID onclick="SetTrackingMode(this.value);"><label for=pr1><?=$Lang['TrafficPrior1']?></label>&nbsp;&nbsp;

<input type=radio id="pr2" name="EditArr[TrafficPrior]" <?=(($EditArr['TrafficPrior']=="NATURAL")?"checked":"")?> value=NATURAL onclick="SetTrackingMode(this.value);"><label for=pr2><?=$Lang['TrafficPrior2']?></label>&nbsp;&nbsp;

<input type=radio id="pr3" name="EditArr[TrafficPrior]" <?=(($EditArr['TrafficPrior']=="NONE")?"checked":"")?> value=NONE onclick="SetTrackingMode(this.value);"><label for=pr3><?=$Lang['TrafficPrior3']?></label>

</td></tr>



<tr id="NoneEntryPrior" style="display:none"><td class=FormLeftTd>
<?=$Lang['NoneEntryPrior']?>
<br><span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['NoneEntryPriorDescr']?></span>
</td><td class=FormRightTd>
<input type=radio id="pr4" name="EditArr[NoneEntryPrior]" <?=(($EditArr['NoneEntryPrior']=="FIRST")?"checked":"")?> value=FIRST><label for=pr4><?=$Lang['EntryPrior1']?></label>&nbsp;&nbsp;
<input type=radio id="pr5" name="EditArr[NoneEntryPrior]" <?=(($EditArr['NoneEntryPrior']=="LAST")?"checked":"")?> value=LAST><label for=pr5><?=$Lang['EntryPrior2']?></label>
</td></tr>

<tr id="PaidEntryPrior" style="display:none"><td class=FormLeftTd>
<?=$Lang['PaidEntryPrior']?>
<br><span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['PaidEntryPriorDescr']?></span>
</td><td class=FormRightTd>
<input type=radio id="pr6" name="EditArr[PaidEntryPrior]" <?=(($EditArr['PaidEntryPrior']=="FIRST")?"checked":"")?> value=FIRST><label for=pr6><?=$Lang['EntryPrior1']?></label>&nbsp;&nbsp;
<input type=radio id="pr7" name="EditArr[PaidEntryPrior]" <?=(($EditArr['PaidEntryPrior']=="LAST")?"checked":"")?> value=LAST><label for=pr7><?=$Lang['EntryPrior2']?></label>
</td></tr>

<tr id="NaturalEntryPrior" style="display:none"><td class=FormLeftTd>
<?=$Lang['NaturalEntryPrior']?>
<br><span style="font-weight:normal;font-size:10px;color:#666666"><?=$Lang['NaturalEntryPriorDescr']?></span>
</td><td class=FormRightTd>
<input type=radio id="pr8" name="EditArr[NaturalEntryPrior]" <?=(($EditArr['NaturalEntryPrior']=="FIRST")?"checked":"")?> value=FIRST><label for=pr8><?=$Lang['EntryPrior1']?></label>&nbsp;&nbsp;
<input type=radio id="pr9" name="EditArr[NaturalEntryPrior]" <?=(($EditArr['NaturalEntryPrior']=="LAST")?"checked":"")?> value=LAST><label for=pr9><?=$Lang['EntryPrior2']?></label>
</td></tr>

<SCRIPT LANGUAGE="JavaScript">
SetTrackingMode('<?=$EditArr['TrafficPrior']?>');
</SCRIPT>

<!--// TRACKING MODE -->




<!-- IP TRACKING -->
<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['SectionIp']?></span>
</td></tr>

<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['UseIpTracking']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[UseIp]" value=1 <?=((ValidVar($EditArr['UseIp'])=='1')?"checked":"")?>>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['IpNoCookie']?><br>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[IpNoCookie]" value=1 <?=((ValidVar($EditArr['IpNoCookie'])=='1')?"checked":"")?>>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['IpPeriod']?><br>
</td><td class=FormRightTd>
<input type=text ID="IpPeriod" name="EditArr[IpPeriod]" value="<?=$EditArr['IpPeriod']?>" style="width:40px;">
</td></tr>

<!--// IP TRACKING -->



<!-- Click FRAUD-->
<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['SectionFraud']?></span>
</td></tr>

<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['EnableClickFraud']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[EnableFraud]" value=1 <?=((ValidVar($EditArr['EnableFraud'])=='1')?"checked":"")?>>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['FraudCount']?><br>
</td><td class=FormRightTd>
<input type=text ID="IpPeriod" name="EditArr[FraudCount]" value="<?=$EditArr['FraudCount']?>" style="width:40px;">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['FraudPeriod']?><br>
</td><td class=FormRightTd>
<input type=text ID="IpPeriod" name="EditArr[FraudPeriod]" value="<?=$EditArr['FraudPeriod']?>" style="width:40px;">
</td></tr>
<!--// Click FRAUD-->





<!-- VAR NAMES -->
<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['SectionTrack']?></span>
</td></tr>

<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>



<tr><td class=FormLeftTd>
<?=$Lang['VarCamp']?><br>
</td><td class=FormRightTd>
<input type=text ID="VarCamp" name="EditArr[VarCamp]" value="<?=$EditArr['VarCamp']?>" style="width:100px;">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['VarCampSource']?><br>
</td><td class=FormRightTd>
<input type=text ID="VarCampSource" name="EditArr[VarCampSource]" value="<?=$EditArr['VarCampSource']?>" style="width:100px;">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['VarKw']?><br>
</td><td class=FormRightTd>
<input type=text ID="VarKw" name="EditArr[VarKw]" value="<?=$EditArr['VarKw']?>" style="width:100px;">
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['VarKeyword']?><br>
</td><td class=FormRightTd>
<input type=text ID="VarKeyword" name="EditArr[VarKeyword]" value="<?=$EditArr['VarKeyword']?>" style="width:100px;">
</td></tr>


<!--// VAR NAMES -->
<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['SectionP3P']?></span>
</td></tr>

<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['P3P']?>
</td><td class=FormRightTd>
<input type=text name="EditArr[P3P]" value="<?=$EditArr['P3P']?>">
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['P3P_ref']?>
</td><td class=FormRightTd>
<input type=text name="EditArr[P3P_REF]" value="<?=$EditArr['P3P_REF']?>">
</td></tr>





<!-- WHITE LABEL -->
<tr style="height:50px;"><td colspan=2 valign=bottom>
<span style="color:#77B60B; font-size:12px;font-weight:bold;padding-left:10px"><?=$Lang['SectionWhite']?></span>
</td></tr>
<tr><td colspan=2 valign=bottom>
<div style="width:100%;height:2px;background:#e1e1e1;"></div>
</td></tr>

<tr><td class=FormLeftTd>
<?=$Lang['UseWhiteLogo']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[UseWhiteLogo]" value=1 <?=((ValidVar($EditArr['UseWhiteLogo'])=='1')?"checked":"")?> <?=((!isset($nsProduct->WHITE_POSSIBLE) || !$nsProduct->WHITE_POSSIBLE)?"disabled":"")?>>
</td></tr>


<tr><td class=FormLeftTd>
<?=$Lang['UseWhiteCopy']?>
</td><td class=FormRightTd>
<input type=checkbox name="EditArr[UseWhiteCopy]" value=1 <?=((ValidVar($EditArr['UseWhiteCopy'])=='1')?"checked":"")?> <?=((!isset($nsProduct->WHITE_POSSIBLE) || !$nsProduct->WHITE_POSSIBLE)?"disabled":"")?>>
</td></tr>


<?if ($nsProduct->WHITE_POSSIBLE) {?>
<tr><td class=FormLeftTd>
<?=$Lang['WhiteLogo']?>
</td><td class=FormRightTd>
<input type=text name="EditArr[WhiteLogo]" value="<?=$EditArr['WhiteLogo']?>">
</td></tr>
<?}?>


</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Save']?>">
</td></tr>
</table>



<?include $nsTemplate->Inc("inc/footer");?>