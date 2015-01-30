<td valign="top" nowrap>
<p class=MenuPath>
<?if (ValidArr($ProgPath)&&count($ProgPath)>0) {?>
<a href="<?=$ProgPath[0]['Url']?>"><?=$ProgPath[0]['Name']?></a>
<?}
else {?>
<a href="<?=$nsProduct->SelfAction()?>"><?=$PageTitle?></a>
<?}?>
<?if (ValidArr($ProgPath)&&count($ProgPath)>1) {?>
<img src="<?=FileLink("images/0.gif");?>" width="7" height="1" border="0"><img src="<?=FileLink("images/arrow_02.gif");?>" width="3" height="10" border="0"><img src="<?=FileLink("images/0.gif");?>" width="7" height="1" border="0">
<?}?>
</p>
</td>


<?if (ValidArr($ProgPath)&&count($ProgPath)>1) {?>

<td width="100%" valign="top">
<p class=MenuPath>
<?for ($i=1;$i<count($ProgPath);$i++) {?>
<?if ($i>1) {?><nobr><img src="<?=FileLink("images/arrow_02.gif");?>" width="3" height="10" border="0"><img src="<?=FileLink("images/0.gif");?>" width="7" height="1" border="0"></nobr><?}?>
<a href="<?=$ProgPath[$i]['Url']?>"><?=$ProgPath[$i]['Name']?></a><img src="<?=FileLink("images/0.gif");?>" width="7" height="1" border="0"><?}?>
</p>
</td>

<?}?>