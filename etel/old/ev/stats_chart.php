<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003, 2004, 2005, 2006 MySQL AB                        |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Authors: Jo�o Prado Maia <jpm@mysql.com>                             |
// +----------------------------------------------------------------------+
//
// @(#) $Id: s.stats_chart.php 1.11 03/10/01 17:20:27-00:00 jpradomaia $
//
include_once("config.inc.php");
include_once(APP_INC_PATH . "db_access.php");
include_once(APP_INC_PATH . "class.auth.php");
include_once(APP_INC_PATH . "class.stats.php");

ini_set('memory_limit', '64M');
error_reporting(0);

@include_once(APP_JPGRAPH_PATH . "jpgraph.php");
@include_once(APP_JPGRAPH_PATH . "jpgraph_pie.php");

Auth::checkAuthentication(APP_COOKIE);

// check to see if the TTF file is available or not
$ttf_font = TTF_DIR . "verdana.ttf";
if (!@file_exists($ttf_font)) {
    $font = FF_FONT1;
} else {
    $font = FF_VERDANA;
}

// Some data
$scale = floatval($HTTP_GET_VARS["scale"]);
if($scale>3)$scale = 3; if($scale<1)$scale=1;
if ($HTTP_GET_VARS["plot"] == "status") {
    $data = Stats::getAssocStatus();
    $graph_title = "Issues by Status";
} elseif ($HTTP_GET_VARS["plot"] == "release") {
    $data = Stats::getAssocRelease();
    $graph_title = "Issues by Release";
} elseif ($HTTP_GET_VARS["plot"] == "priority") {
    $data = Stats::getAssocPriority();
    $graph_title = "Issues by Priority";
} elseif ($HTTP_GET_VARS["plot"] == "user") {
    $data = Stats::getAssocUser();
    $graph_title = "Issues by Assignment";
} elseif ($HTTP_GET_VARS["plot"] == "category") {
    $data = Stats::getAssocCategory();
    $graph_title = "Issues by Category";
}
$labels = array_keys($data);
$data = array_values($data);

// check the values coming from the database and if they are all empty, then
// output a pre-generated 'No Data Available' picture
if (!Stats::hasData($data)) {
    readfile(APP_PATH . "images/no_data.gif");
    exit;
}

// A new graph
$graph = new PieGraph(360*$scale,200*$scale,"auto");

// Setup title
$graph->title->Set($graph_title);
$graph->title->SetFont($font, FS_BOLD, 12);

// The pie plot
$p1 = new PiePlot($data);
$p1->SetTheme('pastel');

// Move center of pie to the left to make better room
// for the legend
$p1->SetCenter(0.30,0.55);

// Label font and color setup
$p1->SetFont($font, FS_BOLD);
$p1->SetFontColor("black");

// Use absolute values (type==1)
$p1->SetLabelType(1);

// Label format
$p1->SetLabelFormat("%d");

// Size of pie in fraction of the width of the graph
$p1->SetSize(0.3);

// Legends
$p1->SetLegends($labels);
$graph->legend->SetFont($font);
$graph->legend->Pos(0.04,0.12);

$graph->Add($p1);
$graph->Stroke();
?>