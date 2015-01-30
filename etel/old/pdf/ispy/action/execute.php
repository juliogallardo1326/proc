<?php
$spy = new ISPY();
echo "<PRE>";
$spy->sp_log_output=1;
error_reporting (0);
$spy->parseAllSites();

echo "</PRE>";
//echo nl2br($spy->parseAllSites());
?>