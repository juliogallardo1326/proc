<?
if (isset($SubMenu)&&ValidArr($SubMenu)&&count($SubMenu)>0) {?>
	<table cellpadding=5 cellspacing=0 border=0 class=SubMenuTable><tr>
	<?
	foreach ($SubMenu as $i=>$MenuRow) {?>
	<td class=SubMenuTd onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background=''">
	<a href="<?=$SubMenu[$i]['Link']?>" <?=(isset($SubMenu[$i]['Onclick']))?"onclick=\"".$SubMenu[$i]['Onclick']."\"":""?>><IMG SRC="<?=FileLink("images/icon_menu.gif");?>" WIDTH="7" HEIGHT="4" BORDER="0" ALT="" style="margin-bottom:2px;">&nbsp;
	<?=$SubMenu[$i]['Name']?></a>
	</td>
<?}?>
	</tr></table><IMG SRC="<?=FileLink("images/0.gif");?>" WIDTH="1" HEIGHT="10" BORDER="0" ALT="">
<?}?>