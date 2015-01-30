
<p><B><?=$Lang['CodeDescription']?></B></p>

<textarea style="width:100%" rows=10 readonly  onclick="this.select();">
<?=$ResultCode?>
</textarea>


<br><br>
<p align=center style="font-size:13px;font-weight:bold;">
<span class=GlobalMsg><?=$Lang['InstallFinished']?></span><br>
<?=str_replace("{LINK}", GetUrl("default", "admin"), $Lang['ClickHere'])?>

</p>