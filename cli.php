<?php
require_once('Processor/SiteMap.php');
\Processor\SiteMap::route() ||
\CPath\Route\CPathMap::route();
