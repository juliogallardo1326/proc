<?php
	$allowBank=true;
	if(!$headerInclude) $headerInclude="ledgers";
	include("includes/sessioncheck.php");
	include("includes/header.php");
?>
<iframe src="../ev/login.php" width="1000" height="800" frameborder="0" > </iframe>

<?php
	include("includes/footer.php");
?>