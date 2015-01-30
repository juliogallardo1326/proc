<?include $nsTemplate->Inc("inc/header");?>




<div style="width:500">

<?PostFORM();?>
<input type="hidden" name="go_auth" value="go_auth">
<input type="hidden" name="xref" value="<?=$xref?>">

<table class=CaptionTable>
<tr><td class=CaptionTd>
<?=$TableCaption?>
</td></tr>
</table>

<table class=FormTable>


<tr><td class=FormLeftTd>
<p><b><?=$Lang['Login']?></b></p>
</td>
<td class=FormRightTd>
<input type=text  name="xlogin" value="<?=$xlogin?>" style="width:100%;">
</td></tr>

<tr><td class=FormLeftTd>
<p><b><?=$Lang['Pass']?></b></p>
</td>
<td class=FormRightTd>
<input type=password  name="xpwd" style="width:100%;">
</td></tr>

<tr><td class=FormLeftTd>
<p><b><?=$Lang['Remember']?></b></p>
</td>
<td class=FormRightTd>
<input type=checkbox value=1 name=recall>
</td></tr>

<tr><td class=FormLeftTd>
</td>
<td class=FormRightTd>
<p><a href="<?=getURL("remind", "", "pub")?>">&raquo;&nbsp;<?=$Lang['ForgotPass']?></a></p>
</td></tr>


</table>

<table class=SubmitTable>
<tr><td class=SubmitLeftTd>
</td><td class=SubmitRightTd>
<input type=submit value="<?=$Lang['Enter']?>">
</td></tr>
</table>

</form>

</div>

<?include $nsTemplate->Inc("inc/footer");?>