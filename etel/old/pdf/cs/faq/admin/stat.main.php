<?php
/******************************************************************************
 * File:				stat.main.php
 * Description:			main page statistics
 * Authors:				Thorsten Rinne <thorsten@phpmyfaq.de>
 * Date:				2003-02-24
 * Last change:			2004-11-01
 * Copyright:           (c) 2001-2006 phpMyFAQ Team
 * 
* The contents of this file are subject to the Mozilla Public License
* Version 1.1 (the "License"); you may not use this file except in
* compliance with the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
* 
* Software distributed under the License is distributed on an "AS IS"
* basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
* License for the specific language governing rights and limitations
* under the License.
*/

if (!defined('IS_VALID_PHPMYFAQ_ADMIN')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

if ($permission["viewlog"]) {
?>
	<h2><?php print $PMF_LANG["ad_stat_sess"]; ?></h2>
	
    <form action="<?php print $_SERVER["PHP_SELF"].$linkext ?>" method="post" style="display: inline;">
	<input type="hidden" name="aktion" value="sessionbrowse" />
    
    <fieldset>
    <legend><?php print $PMF_LANG["ad_stat_sess"]; ?></legend>
    
        <label class="left"><?php print $PMF_LANG["ad_stat_days"]; ?>:</label>
<?php 
	$danz = 0;
	$fir = 9999999999999999999999999;
	$las = 0;		
	$dir = opendir(PMF_ROOT_DIR."/data");
    
	while($dat = readdir($dir)) {
		if ($dat != "." && $dat != "..") {
			$danz++;
		}
		if (FileToDate($dat) > $las) {
			$las = FileToDate($dat);
		}
		if (FileToDate($dat) < $fir && FileToDate($dat) > 0) {
			$fir = FileToDate($dat);
		}
	}
    
	closedir($dir);
	print $danz; ?><br />
    
        <label class="left"><?php print $PMF_LANG["ad_stat_vis"]; ?>:</label>
        <?php print $vanz = $db->num_rows($db->query("SELECT sid FROM ".SQLPREFIX."faqsessions")); ?><br />
    
        <label class="left"><?php print $PMF_LANG["ad_stat_vpd"]; ?>:</label>
        <?php print (($danz != 0) ? round(($vanz / $danz),2) : 0); ?><br />
        
        <label class="left"><?php print $PMF_LANG["ad_stat_fien"]; ?>:</label>
        <?php
	if (is_file(PMF_ROOT_DIR."/data/tracking".date("dmY", $fir))) {
		$fp = @fopen(PMF_ROOT_DIR."/data/tracking".date("dmY", $fir), "r");
		list($dummy, $dummy, $dummy, $dummy, $dummy, $dummy, $dummy, $qstamp) = fgetcsv($fp, 1024, ";");
		fclose($fp);	
		print date("d.m.Y H:i:s", $qstamp);
	} else {
		print $PMF_LANG["ad_sess_noentry"];
	} ?><br />
        
        <label class="left"><?php print $PMF_LANG["ad_stat_laen"]; ?>:</label>
<?php
	if (is_file(PMF_ROOT_DIR."/data/tracking".date("dmY", $las))) {
		$fp = fopen(PMF_ROOT_DIR."/data/tracking".date("dmY", $las), "r");
		
        while (list($dummy, $dummy, $dummy, $dummy, $dummy, $dummy, $dummy, $tstamp) = fgetcsv($fp, 1024, ";")) {
			$stamp = $tstamp;
		}
        
		fclose($fp);
        
		if (empty($stamp)) {
			$stamp = time();
		}
		print date("d.m.Y H:i:s", $stamp).'<br />';
	} else {
		print $PMF_LANG["ad_sess_noentry"].'<br />';
	}
    
	$dir = opendir(PMF_ROOT_DIR."/data");
    $trackingDates = array();
    
	while (false !== ($dat = readdir($dir))) {
		if ($dat != "." && $dat != ".." && strlen($dat) == 16 && !is_dir($dat)) {
			$trackingDates[] = FileToDate($dat);
		}
	}
    
	closedir($dir);
    sort($trackingDates);
?>
        <label class="left"><?php print $PMF_LANG["ad_stat_browse"]; ?>:</label>
		<select name="day" size="1">
<?php
    foreach ($trackingDates as $trackingDate) {
        printf('<option value="%d"', $trackingDate);
        if (date("Y-m-d", $trackingDate) == strftime('%Y-%m-%d', time())) {
			print ' selected="selected"';
		}
		print '>';
		print date('Y-m-d', $trackingDate);
		print "</option>\n";
    }
?>
		</select><br />
        
        <div align="center">
        <input class="submit" type="submit" value="<?php print $PMF_LANG["ad_stat_ok"]; ?>" />
        </div>
    
        <p align="center"><a href="<?php print $_SERVER["PHP_SELF"].$linkext; ?>&amp;aktion=sessionsuche&amp;statstart=<?php print $qstamp ?>&amp;statend=<?php print $stamp ?>"><?php print $PMF_LANG["ad_sess_search"]; ?></a></p>

    </fieldset>
	</form>
<?php
} else {
	print $PMF_LANG["err_NotAuth"];
}
