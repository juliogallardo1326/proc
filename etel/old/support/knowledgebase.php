<?php

require "orca/okb_lang_en.php";
include "orca/okb_head.php";
session_start();
$headerInclude = "service";
$rootdir = "../";
$usedir= "";
$display_stat_wait= "";
$printable_version= "";
include($rootdir."includes/header.php");
?>
  <style type="text/css">

body {
  background-color:#ffffff;
  font:normal 100% Arial,sans-serif;
}

  </style>

<div align="center">
<?php include "orca/okb_body.php"; ?>
</div>
</body>
</html>