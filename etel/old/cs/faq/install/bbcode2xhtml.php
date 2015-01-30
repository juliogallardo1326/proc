<?php
/**
* $Id: bbcode2xhtml.php,v 1.2.2.3.2.1 2006/02/19 07:17:06 thorstenr Exp $
*
* This file replaces the BBCode from phpMyFAQ 1.3.x to the XHTML code for
* phpMyFAQ 1.5.x
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2002-07-16
* @copyright    (c) 2004-2006 phpMyFAQ Team
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

/******************************************************************************
 * Funktionen für den BB-Code-Parser
 ******************************************************************************/

/*
 * Funktion für Umwandlung der BB-Codes | @@ Thorsten, 2001-07-01
 * Contributors:    Meikel Katzengreis, 2003-02-21
 *                  Nick Georgakis, 2003-08-25
 * Last Update: @@ Thorsten, 2004-04-26
 */
function parseUBB($ret)
{
    $ret = stripslashes($ret);
    $pattern = array(   '{\[b\](.*)\[/b\]}smUi',
                        '{\[u\](.*)\[/u\]}smUi',
                        '{\[i\](.*)\[/i\]}smUi',
                        '{\[ul\](.*)\[/ul\]}smUi',
                        '{\[li\](.*)\[/li\]}smUi',
                        '{\[url=(.*)\](.*)\[/url\]}smUi',
                        '{\[url\](.*)\[/url\]}smUi',
                        '{\[surl=(.*)\](.*)\[/surl\]}smUi',
                        '{\[surl\](.*)\[/surl\]}smUi',
                        '{\[img\](.*)\[/img\]}smUi',
                        '{\[size=(.*)\](.*)\[/size\]}smUi',
                        '{\[color=(.*)\](.*)\[/color\]}smUi',
                        '{\[align=(.*)\](.*)\[/align\]}smUi',
                        '{\[\\\\\]}smUi',
                        '{\[center\](.*)\[/center\]}smUi',
                        '{\[(h[1-6]+)\](.*)\[/\\1\]}smUi',
                        '{\[email\](.*)\[/email\]}smUi',
                        '{\[fimg\](.*)\[/fimg\]}smUi',
                        '{\[fimg desc=(.*)\](.*)\[/fimg\]}smUi',
                        '{\[quote\](.*)\[/quote\]}smUi',
                        '{\[email\](.*)\[/email\]}smUi'
                        );
    
    $replace = array(   '<b>\\1</b>',
                        '<u>\\1</u>',
                        '<i>\\1</i>',
                        '<ul>\\1</ul>',
                        '<li>\\1</li>',
                        '<a href="http://\\1" target="_blank">\\2</a>',
                        '<a href="http://\\1" target="_blank">\\1</a>',
                        '<a href="https://\\1" target="_blank">\\2</a>',
                        '<a href="https://\\1" target="_blank">\\1</a>',
                        '<img src="\\1" border="0">',
                        '<span style="font-size: \\1px;">\\2</span>',
                        '<span style="color: \\1;">\\2</span>',
                        '<div align="\\1">\\2</div>',
                        '\\\\'.'\n',
                        '<div align="center">\\1</div>',
                        '<\\1>\\2</\\1>',
                        '<a href="mailto:\\1">\\1</a>',
                        '<img src="./images/\\1" border="0" />',
                        '<img src="./images/\\2" border="0" alt="\\1" onMouseOver="'."self.status='\\1'; return(true);".'" onMouseOut="'."self.status=''; return(true);".'" />',
                        '<table width="90%" border="0" cellspacing="2" cellpadding="2" align="center" style="border: 1px dashed black;"><tr><td>Quote:<br />\\1</td></tr></table>',
                        '<a href="http://mailto:\\1">\\1</a>'
                        );
    
    $ret = preg_replace($pattern,$replace,$ret);
    $ret = preg_replace_callback("#\[php\](.*)\[\/php\]#Usi","php_syntax", $ret);
    $ret = preg_replace_callback("#\[code\](.*)\[\/code\]#Usi","code_syntax", $ret);
    $ret = str_replace('http://http://','http://',$ret);
    $ret = str_replace('https://https://','http://',$ret);
    
    // Umwandlung fehlt!
    
    return $ret;
}

/*
 * Funktion für PHP-Syntax-Highlighting | @@ Meikel Katzengreis, 2003-03-31
 * Last Update: @@ Thorsten, 2003-07-23
 */
function php_syntax($output)
{
    $ret = $output[1];
    $ret = wordwrap($ret, 120, "\n", 0);
    $ret = highlight_syntax(unhtmlentities($ret));
    return "<pre>".$ret."</pre>";
}

/*
 * Funktion für PHP-Syntax-Highlighting | @@ Meikel Katzengreis, 2003-03-31
 * Last Update: @@ Thorsten, 2003-07-23
 */
function highlight_syntax($code)
{
    return ob_highlight_syntax ($code);
}

/*
 * Funktion für BB-Code | @@ Meikel Katzengreis, 2003-07-23
 * Last Update: @@ Thorsten, 2003-07-23
 */
function code_core ($text)
{
    $text = preg_replace("=<br(>|([\s/][^>]*)>)\r?\n?=i", "\n", $text[1]);
    return $text;
}

/*
 * Funktion für BB-Code | @@ Meikel Katzengreis, 2003-07-23
 * Last Update: @@ Thorsten, 2004-01-20
 */
function code_syntax($output)
{
    $ret = $output[1];
    $ret = wordwrap($ret, 120, "\n", 0);
    $ret = unhtmlentities($ret);
    return "<pre>".$ret."</pre>";
}



/*
 * Funktion für BB-Code | @@ Meikel Katzengreis, 2003-07-23
 * Last Update: @@ Thorsten, 2003-07-23
 */
function ob_highlight_syntax ($code)
{
    ob_start();
    @highlight_string($code);
    $code = ob_get_contents();
    ob_end_clean();
    return $code;
}

if (isset($_GET["step"]) && $_GET["step"] != "") {
    $step = $_GET["step"];
    }
else {
    $step = 1;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>phpMyFAQ 1.5 BBCode Converter</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style type="text/css"><!--
    body {
	    margin: 0px;
	    padding: 0px;
	    font-size: 12px;
	    font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	    background-color: #B0B0B0;
    }
    #header {
	    margin: auto;
	    padding: 35px;
	    background-color: #6A88B1;
        text-align: center;
    }
    #header h1 {
	    font: bold 36px Garamond, times, serif;
	    margin: auto;
	    color: #f5f5f5;
        text-align: center;
    }
    .center {
        text-align: center;
    }
    fieldset.installation {
        margin: auto;
        border: 1px solid black;
        width: 550px;
        margin-top: 10px;
    }
    legend.installation {
        border: 1px solid black;
        background-color: #FCE397;
        padding: 4px 4px 4px 4px;
    }
    .input {
        width: 200px;
        background-color: #f5f5f5;
        border: 1px solid black;
    }
    span.text {
        width: 250px;
        float: left;
        padding-right: 10px;
        line-height: 20px;
    }
    #admin {
        line-height: 20px;
        font-weight: bold;
    }
    .help {
        cursor: help;
        border-bottom: 1px dotted Black;
        font-size: 14px;
        font-weight: bold;
        padding-left: 5px;
    }
    .button {
        background-color: #ff7f50;
        border: 1px solid #000000;
        color: #ffffff;
    }
    .error {
        margin: auto;
        margin-top: 20px;
        width: 600px;
        text-align: center;
        padding: 10px;
        line-height: 20px;
        background-color: #f5f5f5;
        border: 1px solid black;
    }
    --></style>
</head>
<body>

<h1 id="header">phpMyFAQ 1.4 BBCode Converter</h1>

<?php
/**************************** STEP 1 OF 2 ***************************/
if ($step == 1) {
?>
<form action="bbcode2xhtml.php?step=2" method="post">
<fieldset class="installation">
<legend class="installation"><strong>phpMyFAQ 1.5 BBCode Converter (Step 1 of 2)</strong></legend>
<p>This converter will work <strong>only</strong> after an update from phpMyFAQ 1.3.x to 1.5.x.</p>
<p>You don't need this convert script after an update from phpMyFAQ 1.4.x to 1.5.x.</p>
<p>This converter will <strong>not</strong> update your phpMyFAQ version!</p>
<p><strong>Please make a backup of your SQL tables before running this update.</strong></p>
<p class="center"><input type="submit" value="Go to step 2 of 2" class="button" /></p>
</fieldset>
</form>
<?php
}

/**************************** STEP 2 OF 2 ***************************/
if ($step == 2) {
    require_once ("../inc/data.php");
    require_once ("../inc/config.php");
    require_once ("../inc/functions.php");
    require_once ("../inc/mysql.php");
    define("SQLPREFIX", $DB["prefix"]);
    $db = new db_mysql();
    $db->connect($DB["server"], $DB["user"], $DB["password"], $DB["db"]);
    
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[b]', '<strong>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/b]', '</strong>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[u]', '<span style=\"text-decoration: underline;\">')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/u]', '</span>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[i]', '<em>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/i]', '</em>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[ul]', '<ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/ul]', '</ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[list]', '<ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/list]', '</ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[li]', '<li>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/li]', '</li>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[center]', '<div style=\"text-align: center;\">')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/center]', '</div>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[php]', '<pre class=\"php\">')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/php]', '</pre>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[code]', '<pre>')";
    $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = REPLACE(content, '[/code]', '</pre>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[b]', '<strong>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/b]', '</strong>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[u]', '<span style=\"text-decoration: underline;\">')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/u]', '</span>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[i]', '<em>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/i]', '</em>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[ul]', '<ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/ul]', '</ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[list]', '<ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/list]', '</ul>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[li]', '<li>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/li]', '</li>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[center]', '<div style=\"text-align: center;\">')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/center]', '</div>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[php]', '<pre class=\"php\">')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/php]', '</pre>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[code]', '<pre>')";
    $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = REPLACE(artikel, '[/code]', '</pre>')";
    
    $res = $db->query("SELECT id, lang, content FROM ".SQLPREFIX."faqdata ORDER BY id, lang");
    if ($db->num_rows($res) > 0) {
        while ($row = $db->fetch_object($res)) {
            $updates[] = array("id" => $row->id, "lang" => $row->lang, "content" => nl2br(parseUBB($row->content)));
        }
        foreach ($updates as $value) {
            $query[] = "UPDATE ".SQLPREFIX."faqdata SET content = '".addslashes($value["content"])."' WHERE id = ".$value["id"]." AND lang = '".$value["lang"]."'";
        }
    }
    unset($res);
    
    $res = $db->query("SELECT id, artikel FROM ".SQLPREFIX."faqnews ORDER BY id, lang");
    if ($db->num_rows($res) > 0) {
        while ($row = $db->fetch_object($res)) {
            $updates[] = array("id" => $row->id, "artikel" => nl2br(parseUBB($row->artikel)));
        }
        foreach ($updates as $value) {
            $query[] = "UPDATE ".SQLPREFIX."faqnews SET artikel = '".addslashes($value["artikel"])."' WHERE id = ".$value["id"];
        }
    }
    
	$query[] = "OPTIMIZE TABLE ".SQLPREFIX."faqadminlog, ".SQLPREFIX."faqadminsessions, ".SQLPREFIX."faqcategories, ".SQLPREFIX."faqchanges, ".SQLPREFIX."faqcomments, ".SQLPREFIX."faqdata, ".SQLPREFIX."faqfragen, ".SQLPREFIX."faqnews, ".SQLPREFIX."faqsessions, ".SQLPREFIX."faquser, ".SQLPREFIX."faqvisits, ".SQLPREFIX."faqvoting";
	
	print "<p class=\"center\">";
    while ($each_query = each($query)) {
		$result = $db->query($each_query[1]);
        print "<!-- ".$each_query[1]." -->\n";
		print "|&nbsp;\n";
		flush();
		if (!$result) {
			print "<p class=\"error\"><strong>Error:</strong> ".mysql_error()."</p>";
			die();
	    }
        wait(250);
	}
    print "</p>\n";
    print "<p class=\"center\">The BBCode was converted successfully in XHTML.</p>";
    print "<p class=\"center\"><a href=\"../index.php\">phpMyFAQ</a></p>";
    if (@unlink(basename($_SERVER["PHP_SELF"]))) {
        print "<p class=\"center\">This file was deleted automatically.</p>\n";
    } else {
        print "<p class=\"center\">Please delete this file manually.</p>\n";
    }
}
?>
<p class="center">&copy; 2001-2006 <a href="http://www.phpmyfaq.de/" target="_blank">phpMyFAQ-Team</a> | All rights reserved.</p>
</body>
</html>
