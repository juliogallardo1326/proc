<?

function MenuTab($Name, $Url, $Selected=false, $Img=false, $Width=false, $Height=false)
{
	if (!$Selected) {
		?>
		<td class=TabsBorder2 align=center>
		<p class=TabsMenu><a href="<?=$Url?>">
		<?if ($Img) {?>
		<IMG SRC="<?=FileLink("images/small_icon_".$Img.".gif");?>" WIDTH="<?=$Width?>" HEIGHT="<?=$Height?>" BORDER="0" title="<?=$Name?>">&nbsp;
		<?}?>

		<?=$Name?></a></p>
		</td>
		<?
	}
	
	if ($Selected) {?>

	<td align=left>
		<table width=100% cellpadding=0 cellspacing=0 border=0 height=27>
		<tr><td valign=top width=9>
		<p><img src="<?=FileLink("images/corn_02.gif");?>" width="9" height="27" border="0"></p>
		</td><td align=center style="border-top-color:#86c71d;border-top-style:solid;border-top-width:1px;border-bottom-color:#ffffff;border-bottom-style:solid;border-bottom-width:2px;">
		<p class=TabsMenuSel><a href="<?=$Url?>">
		<?if ($Img) {?>
		<IMG SRC="<?=FileLink("images/small_icon_".$Img."-a.gif");?>" WIDTH="<?=$Width?>" HEIGHT="<?=$Height?>" BORDER="0" title="<?=$Name?>">&nbsp;
		<?}?>
		<?=$Name?></a></p>
		</td><td valign=top width=9>
		<p><img src="<?=FileLink("images/corn_03.gif");?>" width="9" height="27" border="0"></p>
		</td></tr>
		</table>
	</td>

	<?}
}

?>
	
<table width=100% cellpadding=0 cellspacing=0 border=0 height=27>
<tr>


<?if (ValidId($CompId)) {

MenuTab(
	$Lang['MPaidAdv'], 
	getURL("paid_constructor", "CpId=$CompId", "report"),
	(($nsProduct->Action=="paid_constructor")?true:false),
	"stat", 8, 8
);

MenuTab(
	$Lang['MNatural'], 
	getURL("natural_constructor", "CpId=$CompId", "report"),
	(($nsProduct->Action=="natural_constructor")?true:false),
	"stat", 8, 8
);

MenuTab(
	$Lang['MLogs'], 
	getURL("reports", "CpId=$CompId", "admin"),
	((ValidVar($MenuSection)=="logs")?true:false),
	"logs", 11, 9
);

MenuTab(
	$Lang['MCampaign'], 
	getURL("campaign", "CpId=$CompId", "admin"),
	(($nsProduct->Action=="campaign"||$nsProduct->Action=="incampaign" ||$nsProduct->Action=="sub_camp"||$nsProduct->Action=="campaign_link")?true:false) ,
	"campaign", 8, 7
);


MenuTab(
	$Lang['MActions'], 
	getURL("actions", "CpId=$CompId", "admin"),
	(($nsProduct->Action=="actions")?true:false),
	"actions", 4, 8
);

MenuTab(
	$Lang['MSplits'], 
	getURL("split_list", "", "admin"),
	((ValidVar($MenuSection)=="split_test")?true:false),
	"split", 13, 8
);


if ($nsUser->ADMIN||$nsUser->SUPER_USER) {
	MenuTab(
		$Lang['MSettings'], 
		getURL("settings", "CpId=$CompId", "admin"),
		((ValidVar($MenuSection)=="settings")?true:false),
		"settings", 9, 9
	);
}

if (isset($AdditionalTabs)) {
	for($i=0;$i<count($AdditionalTabs);$i++) {
		$Row=$AdditionalTabs[$i];
		if (ValidVar($Row['Permit'])) {
			if ($Row['Permit']=="superuser" && !$nsUser->SUPER_USER) continue;
			if ($Row['Permit']=="admin" && !$nsUser->ADMIN) continue;
			if ($Row['Permit']=="superadmin" && !$nsUser->SUPER_ADMIN) continue;
		}
		if (ValidVar($Row['PMode'])) {
			if ($Row['PMode']==2 && $nsProduct->LICENSE!=2) continue;
			if ($Row['PMode']==3 && $nsProduct->LICENSE!=3) continue;
		}
		MenuTab (
			$Row['Name'],
			$Row['URL'],
			((isset($AdditionalSection)&&$AdditionalSection==$Row['id'])?true:false),
			ValidVar($Row['Img']),
			ValidVar($Row['ImgW']),
			ValidVar($Row['ImgH'])
		);
		unset($Row);
	}
}

} else {?>

<td width=100% class=TabsBorder><p><img src="<?=FileLink("images/0.gif");?>" width="1" height="27" border="0"></p></td>

<?}?>
</tr>
</table>