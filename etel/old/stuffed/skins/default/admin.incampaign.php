<?include $nsTemplate->Inc("inc/header");?>
<?include $nsTemplate->Inc("inc/submenu");?>
<?
if (ValidArr($InCampArr)&&count($InCampArr)>0) include $nsTemplate->Inc("admin.sub_camp_list");
else include $nsTemplate->Inc("inc/no_records");
?>
<?include $nsTemplate->Inc("inc/footer");?>