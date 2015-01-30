

<div  style="padding-left:10px;">

<br>
<B><?=$Lang['License']?></B><br><br>
<?=$Lang['Type']?>: 
<?if (ValidVar($_REQUEST['Trial'])) {?>
<?=$Lang['TrialVersion']?>
<?}?>
<?if ($LcP==3&&!$Trial) {?>
<?=$Lang['AgencyVersion']?>
<?}?>
<?if ($LcP==2&&!$Trial) {?>
<?=$Lang['MerchantVersion']?>
<?}?>
<br>


<?if (!ValidVar($_REQUEST['Trial'])&&$_REQUEST['LcL']>0) {?>
<?=$Lang['NumOfSites']?>:<?=$_REQUEST['LcL']?><br>
<?}?>

<?if (!ValidVar($_REQUEST['Trial'])) {?>
<?=$Lang['RegisteredTo']?>: <?=$_REQUEST['LcCL']?><br>
<?}?>




<br>
<B><?=$Lang['DbSettings']?></B><br><br>
<?=$Lang['Host']?>: <?=$_REQUEST['DbHost']?><br>
<?=$Lang['Port']?>: <?=$_REQUEST['DbPort']?><br>
<?=$Lang['Db']?>: <?=$_REQUEST['DbName']?><br>
<?=$Lang['DbUsername']?>: <?=$_REQUEST['DbUser']?><br>
<?=$Lang['DbPass']?>: <?=$_REQUEST['DbPass']?><br>
<?=$Lang['TablePfx']?>: <?=$_REQUEST['DbPref']?><br>

<br>
<B><?=$Lang['User']?></B><br><br>
<?=$Lang['Login']?>: <?=$_REQUEST['RegLogin']?><br>
<?=$Lang['Pass']?>: <?=$_REQUEST['RegPass']?><br>
<?=$Lang['Name']?>: <?=$_REQUEST['RegName']?><br>
<?=$Lang['Email']?>: <?=$_REQUEST['RegEmail']?><br>

<br>
<B><?=$Lang['Company']?></B><br><br>
<?=$Lang['CompanyName']?>: <?=$_REQUEST['CompName']?><br>

<?if (ValidVar($_REQUEST['CompDescr'])) {?>
<span style="color:#aaaaaa"><?=$_REQUEST['CompDescr']?></span><br>
<?}?>

<?=$Lang['Site']?>: <?=$_REQUEST['SiteDomain']?><br>

<br>
<B><?=$Lang['More']?></B><br><br>
<?=$Lang['UseModR']?>: <?=(($_REQUEST['UseModR'])?$Lang['Yes']:$Lang['No'])?><br>
<?=$Lang['SendUseInfo']?>: <?=(($_REQUEST['SendUsage'])?$Lang['Yes']:$Lang['No'])?><br>

<br>

</div>