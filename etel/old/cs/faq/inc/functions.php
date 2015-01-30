<?php
/**
* $Id: functions.php,v 1.65.2.58.2.37 2006/05/10 20:05:32 thorstenr Exp $
*
* This is the main functions file!
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Matthias Sommerfeld <phlymail@phlylabs.de>
* @author       Bastian Pöttner <bastian@poettner.net>
* @author       Meikel Katzengreis <meikel@katzengreis.com>
* @author       Robin Wood <robin@digininja.org>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @since        2001-02-18
* @copyright    (c) 2001-2006 phpMyFAQ Team
*
* Portions created by Matthias Sommerfeld are Copyright (c) 2001-2004 blue
* birdy, Berlin (http://bluebirdy.de). All Rights Reserved.
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
******************************************************************************/

/**
* Function to get a pretty formatted output of a variable
*
* NOTE: Just for debugging!
*
* @param    object
* @return   void
* @access   public
* @since    2004-11-27
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function pmf_dump($var)
{
    print '<pre>';
    print_r($var);
    print '</pre>';
}

//
// GENERAL FUNCTIONS
//

/**
 * getAvailableLanguages()
 *
 * This function returns the available languages
 *
 * @return  array
 * @access  public
 * @since   2006-03-21
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 * @author  Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function getAvailableLanguages()
{
    global $languageCodes;
    $search = array("language_", ".php");
    $languages = array();
    if ($dir = @opendir(dirname(dirname(__FILE__)).'/lang/')) {
        while (FALSE !== ($file = @readdir($dir))) {
		
            if (($file != ".") && ($file != "..") && (!is_dir($file))) {
                $languageFiles[] = strtoupper(str_replace($search, "", trim($file)));
            }
        }
        closedir($dir);
        foreach ($languageFiles as $lang) {
            // Check if the file is related to a (real) language before using it
            if (array_key_exists($lang, $languageCodes)) {
                $languages[strtolower($lang)] = $languageCodes[$lang];
            }
        }
        // Sort the languages list
        asort($languages);
        reset($languages);
    }

    return $languages;
}

/**
 * selectLanguages()
 *
 * This function displays the <select> box for the available languages
 *
 * @param   string
 * @return  string
 * @access  public
 * @since   2003-12-12
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function selectLanguages($default)
{
    global $languageCodes;

    $output = "<select class=\"language\" name=\"language\" id=\"language\" size=\"1\">\n";
    $languages = getAvailableLanguages();

    if (count($languages) > 0) {
        foreach ($languages as $lang => $value) {
            $output .= "\t<option value=\"".$lang."\"";
            if ($lang == $default) {
                $output .= " selected=\"selected\"";
            }
            $output .=  ">".$value."</option>\n";
        }
    } else {
        $output .= "\t<option value=\"en\">".$languageCodes["EN"]."</option>";
    }
    $output .= "</select>\n";

    return $output;
}

/**
 * languageOptions()
 *
 * Function for displaying all languages in <option>
 *
 * @param   string  the languange to be selected
 * @param   bool    print only the passed language?
 * @param   bool    print the <language file> instead of the <language code> as value?
 * @return  string
 * @access  public
 * @since   2004-06-01
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 * @author  Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function languageOptions($lang = "", $onlyThisLang = false, $fileLanguageValue = false)
{
    $output = "";

    foreach (getAvailableLanguages() as $key => $value) {
        if ($onlyThisLang) {
            if (strtolower($key) == $lang) {
                if ($fileLanguageValue) {
                    $output .= "\t<option value=\"language_".strtolower($lang).".php\"";
                } else {
                    $output .= "\t<option value=\"".strtolower($lang)."\"";
                }
                $output .= " selected=\"selected\"";
                $output .= ">".$value."</option>\n";
                break;
            }
        }
        else {
            if ($fileLanguageValue) {
                $output .= "\t<option value=\"language_".strtolower($key).".php\"";
            } else {
                $output .= "\t<option value=\"".strtolower($key)."\"";
            }
            if (strtolower($key) == $lang) {
                $output .= " selected=\"selected\"";
            }
            $output .= ">".$value."</option>\n";
        }
    }

    return $output;
}

/**
* Returns the FAQ record title from the ID and language
*
* @param    integer     record id
* @param    string      language
* @return   string
* @since    2002-08-28
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function getThema($id, $lang)
{
	global $db, $PMF_LANG;
    $result = $db->query(sprintf("SELECT thema FROM %sfaqdata WHERE id = %d AND lang = '%s'", SQLPREFIX, $id, $lang));
    if ($db->num_rows($result) > 0) {
        while ($row = $db->fetch_object($result)) {
            $output = PMF_htmlentities($row->thema, ENT_NOQUOTES, $PMF_LANG['metaCharset']);
        }
    } else {
        $output = $PMF_LANG["no_cats"];
    }
    return $output;
}

/**
* Returns the keywords of a FAQ record from the ID and language
*
* @param    integer     record id
* @param    string      language
* @return   string
* @since    2005-11-30
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function getKeywords($id, $lang)
{
	global $db, $PMF_LANG;
    $result = $db->query(sprintf("SELECT keywords FROM %sfaqdata WHERE id = %d AND lang = '%s'", SQLPREFIX, $id, $lang));
    if ($db->num_rows($result) > 0) {
        $row = $db->fetch_object($result);
        return PMF_htmlentities($row->keywords, ENT_NOQUOTES, $PMF_LANG['metaCharset']);
    } else {
        return '';
    }
}

/**
* Converts the phpMyFAQ date format to the ISO 8601 format
*
* @param    string
* @return   date
* @since    2001-04-30
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function makeDate($date)
{
    $offset = (60 * 60) * (PMF_DATETIME_TIMEZONE / 100);
    $current = strtotime(substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2)." ".substr($date,8,2).":".substr($date,10,2));
    $timestamp = $current + $offset;
    return date("Y-m-d H:i", $timestamp);
}

/**
* Converts the phpMyFAQ/Unix date format to the RFC822 format
*
* @param    string date
* @param    boolean true if the passed date is in phpMyFAQ format, false if in Unix timestamp format
* @return   string RFC 822 date
* @since    2005-10-03
* @author   Matteo Scaramuccia <matteo@scaramuccia.com>
*/
function makeRFC822Date($date, $phpmyfaq = true)
{
     $offset = (60 * 60) * (PMF_DATETIME_TIMEZONE / 100);
     if ($phpmyfaq) {
        $current = strtotime(substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2)." ".substr($date,8,2).":".substr($date,10,2).":".substr($date,12,2));
     } else {
        $current = $date;
     }
     $timestamp = $current + $offset;
     return gmdate("D, d M Y H:i:s", $timestamp)." ".(PMF_DATETIME_TIMEZONE == '0' ? 'GMT':PMF_DATETIME_TIMEZONE);
}

/**
* Returns the number of activated records
*
* @param    string
* @return   int
* @access   public
* @since    2002-08-23
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function generateNumberOfArticles($lang = '')
{
    global $db;

    $query = "SELECT id FROM ".SQLPREFIX."faqdata WHERE active = 'yes'";
    if (PMF_Init::isASupportedLanguage($lang)) {
        $query .= " AND lang = '".$lang."'";
    }
    $num = $db->num_rows($db->query($query));
    if ($num > 0) {
        return $num;
    } else {
        return 0;
    }
}

/**
* This function returns all records from one category
*
* @param    int     category id
* @return   string
* @access   public
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2002-08-27
*/
function printThemes($category)
{
	global $db, $sids, $PMF_LANG, $PMF_CONF;
	$seite = 1;
	$output = "";

	if (isset($_REQUEST["seite"])) {
		$seite = $_REQUEST["seite"];
	}

	$result = $db->query('SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqvisits.visits AS visits FROM '.SQLPREFIX.'faqdata
LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang LEFT JOIN '.SQLPREFIX.'faqvisits ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqvisits.id AND '.SQLPREFIX.'faqvisits.lang = '.SQLPREFIX.'faqdata.lang WHERE '.SQLPREFIX.'faqdata.active = \'yes\' AND '.SQLPREFIX.'faqcategoryrelations.category_id ='.$category.' ORDER BY '.SQLPREFIX.'faqdata.id');

	$num = $db->num_rows($result);
	$pages = ceil($num / $PMF_CONF["numRecordsPage"]);

	if ($seite == 1) {
		$first = 0;
	} else {
		$first = ($seite * $PMF_CONF["numRecordsPage"]) - $PMF_CONF["numRecordsPage"];
	}

	if ($num > 0) {
		if ($pages > 1) {
			$output .= "<p><strong>".$PMF_LANG["msgPage"].$seite." ".$PMF_LANG["msgVoteFrom"]." ".$pages." ".$PMF_LANG["msgPages"]."</strong></p>";
		}
		$output .= "<ul class=\"phpmyfaq_ul\">\n";
		$counter = 0;
		$displayedCounter = 0;
		while (($row = $db->fetch_object($result)) && $displayedCounter < $PMF_CONF['numRecordsPage']) {
			$counter ++;
			if ($counter <= $first) {
				continue;
			}
			$displayedCounter++;

            if (empty($row->visits)) {

				$visits = "0";
            } else {

				$visits = $row->visits;
            }

            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {

                $output .= "\t<li><a href=\"".$row->category_id."_".$row->id."_".$row->lang.".html\">".PMF_htmlentities($row->thema, ENT_NOQUOTES, $PMF_LANG['metaCharset'])."</a> [".$row->lang."]<br /><div class=\"little\">(".$visits." ".$PMF_LANG["msgViews"].")</div></li>\n";
            } else {

                $output .= "\t<li><a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=artikel&amp;cat=".$row->category_id."&amp;id=".$row->id."&amp;artlang=".$row->lang."\">".PMF_htmlentities($row->thema,ENT_NOQUOTES, $PMF_LANG['metaCharset'])."</a><br /><div class=\"little\">(".$visits." ".$PMF_LANG["msgViews"].")</div></li>\n";
            }
        }
        $output .= "</ul>\n";
    } else {
		return false;
	}

    if ($pages > 1) {
        $output .= "<p align=\"center\"><strong>";
        $previous = $seite - 1;
        $next = $seite + 1;

        if ($previous != 0) {
            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
                $output .= "[ <a href=\"category".$category."_".$previous.".html\">".$PMF_LANG["msgPrevious"]."</a> ]";
            } else {
                $output .= "[ <a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=show&amp;cat=".$category."&amp;seite=".$previous."\">".$PMF_LANG["msgPrevious"]."</a> ]";
            }
        }

        $output .= " ";

        for ($i = 1; $i <= $pages; $i++) {
            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
                $output .= "[ <a href=\"category".$category."_".$i.".html\">".$i."</a> ]";
            } else {
                $output .= "[ <a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=show&amp;cat=".$category."&amp;seite=".$i."\">".$i."</a> ] ";
            }
        }

        if ($next <= $pages) {
            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
                $output .= "[ <a href=\"category".$category."_".$next.".html\">".$PMF_LANG["msgNext"]."</a> ]";
            } else {
                $output .= "[ <a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=show&amp;cat=".$category."&amp;seite=".$next."\">".$PMF_LANG["msgNext"]."</a> ]";
            }
        }

        $output .= "</strong></p>";
    }
	return $output;
}

/**
* Returns an array of country codes for a specific FAQ record ID
*
* @param    integer
* @return   array
* @since    2003-03-17
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function check4Language($id)
{
	global $db;
	$output = array();
	$result = $db->query("SELECT lang FROM ".SQLPREFIX."faqdata WHERE id = ".$id);
	if ($db->num_rows($result) > 0) {
		while ($row = $db->fetch_object($result)) {
			$output[] = $row->lang;
			}
		}
	return $output;
}

/**
 * safeEmail()
 *
 * If the email spam protection has been activated from the general PMF configuration
 * this function converts an email address e.g. from "user@example.org" to "user_AT_example_DOT_org"
 * Otherwise it will return the plain email address.
 *
 * @param    string
 * @return   string
 * @since    2003-04-17
 * @author   Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function safeEmail($email)
{
    global $PMF_CONF;

    if (isset($PMF_CONF['spamEnableSafeEmail']) && ($PMF_CONF['spamEnableSafeEmail'] == 'TRUE')) {
        return str_replace(array('@', '.'), array('_AT_', '_DOT_'), $email);
    } else {
        return $email;
    }
}

/**
* Reload locking for user votings
*
* @param    integer     FAQ record id
* @param    string      IP
* @return   boolean
* @since    2003-05-15
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function votingCheck($id, $ip)
{
    global $db;
    $timeNow = (time() - 300);
    if ($db->num_rows($db->query("SELECT id FROM ".SQLPREFIX."faqvoting WHERE artikel = ".$id." AND (ip = '".$ip."' AND datum > ".$timeNow.")"))) {
        return false;
    } else {
	    return true;
    }
}

/**
 * check4AddrMatch()
 *
 * Checks for an address match (IP or Network)
 *
 * @param    string  IP Address
 * @param    string  Network Address (e.g.: a.b.c.d/255.255.255.0 or a.b.c.d/24) or IP Address
 * @return   boolean
 * @since    2006-01-23
 * @author   Matteo Scaramuccia <matteo@scaramuccia.com>
 * @author   Kenneth Shaw <ken@expitrans.com>
 */
function check4AddrMatch($ip, $network)
{
    // See also ip2long PHP online manual: Kenneth Shaw
    // coded a network matching function called net_match.
    // We use here his way of doing bit-by-bit network comparison
    $matched = false;

    // Start applying the discovering of the network mask
    $ip_arr = explode('/', $network);

    $network_long = ip2long($ip_arr[0]);
    $ip_long      = ip2long($ip);

    if (!isset($ip_arr[1])) {
        // $network seems to be a simple ip address, instead of a network address
        $matched = ($network_long == $ip_long);
    } else {
        // $network seems to be a real network address
        $x = ip2long($ip_arr[1]);
        // Evaluate the netmask: <Network Mask> or <CIDR>
        $mask = ( long2ip($x) == $ip_arr[1] ? $x : 0xffffffff << (32 - $ip_arr[1]));
        $matched = ( ($ip_long & $mask) == ($network_long & $mask) );
    }

    return $matched;
}

/**
 * Checks if a IP is banned
 *
 * @param    string  IP
 * @return   boolean
 * @since    2003-06-06
 * @author   Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function IPCheck($ip)
{
	global $PMF_CONF;
	$arrBannedIPs = explode(" ", $PMF_CONF["bannedIP"]);
	foreach ($arrBannedIPs as $oneIPorNetwork) {
        if (check4AddrMatch($ip, $oneIPorNetwork)) {
            return false;
        }
    }
	return true;
}

/**
 * getBannedWords()
 *
 * This function returns the banned words dictionary as an array.
 *
 * @return  array
 * @access  public
 * @author  Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function getBannedWords()
{
    $bannedTrimmedWords = array();
    $bannedWordsFile = dirname(__FILE__).'/blockedwords.txt';
    $bannedWords     = array();

    // Read the dictionary
    if (file_exists($bannedWordsFile) && is_readable($bannedWordsFile)) {
        $bannedWords = @file($bannedWordsFile);
    }
    // Trim it
    foreach ($bannedWords as $word) {
        $bannedTrimmedWords[] = trim($word);
    }

    return $bannedTrimmedWords;
}

/**
 * checkBannedWord()
 *
 * This function checks the content against a dab word list
 * if the banned word spam protection has been activated from the general PMF configuration.
 *
 * @param   string  $content
 * @return  bool
 * @access  public
 * @author  Katherine A. Bouton
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 * @author  Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function checkBannedWord($content)
{
    global $PMF_CONF;

    // Sanity checks
    if (    ('' == $content)
         || (!isset($PMF_CONF['spamCheckBannedWords']))
         || (isset($PMF_CONF['spamCheckBannedWords']) && ($PMF_CONF['spamCheckBannedWords'] != 'TRUE')) ) {
        return true;
    }

    $bannedWords = getBannedWords();
    // Start evaluating each word in $content
    $checkWords = explode(' ', strip_tags($content));
    // Each item of $checkWords array will be checked against the $bannedWords array
    // for a match. We cannot directly use in_array function because
    // in_array needle is allowed to be an array only in PHP4.2.0+
    if (is_array($bannedWords) && (count($bannedWords) > 0)) {
        // TODO: Find out why in_array fro PHP 4.2.0+ never return true even in case of a matching. In the mean time downgrade to the use of array_search for each word despite the PHP version.
        // if (version_compare(PHP_VERSION, '4.2.0', '<')) {
        if (true) {
            foreach ($checkWords as $checkWord) {
                // Check a not blank word against the banned ones
                if ( $checkWord != '') {
                    $result = array_search($checkWord, $bannedWords); // NULL if PHP < 4.2.0, FALSE otherwise
                    $find = (version_compare(PHP_VERSION, '4.2.0', '<') ? !(null === $result) : !(false === $result));
                    if ($find) {
                        return false;
                    }
                }
            }
        }
        else {
            if (in_array($checkWords, $bannedWords)) {
                return false;
            }
        }
    }

    return true;
}

/**
 * printCaptchaFieldset
 *
 * Get out the HTML code for the fieldset that insert the captcha code in a (public) form
 *
 * @param    string  Text of the HTML Legend element
 * @param    string  HTML code for the Captcha image
 * @param    string  Length of the Captcha code
 * @return   string
 * @since    2006-04-25
 * @author   Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function printCaptchaFieldset($legend, $img, $length)
{
    global $PMF_CONF;

    $html = '';

    if (isset($PMF_CONF['spamEnableCatpchaCode']) && ($PMF_CONF['spamEnableCatpchaCode'] == 'TRUE')) {
        $html .= '<fieldset><legend>'.$legend.'</legend>';
        $html .= '<div style="text-align:center;">'.$img;
        $html .= '<input class="inputfield" style="vertical-align: top;" type="text" name="captcha" id="captcha" value="" size="'.$length.'" />';
        $html .= '</div></fieldset>';
    }

    return $html;
}

/**
 * checkCaptchaCode()
 *
 * This function checks the provided captcha code
 * if the captcha code spam protection has been activated from the general PMF configuration.
 *
 * @return  bool
 * @access  public
 * @since   2006-04-25
 * @author  Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function checkCaptchaCode()
{
    global $PMF_CONF, $captcha;

    if (isset($PMF_CONF['spamEnableCatpchaCode']) && ($PMF_CONF['spamEnableCatpchaCode'] == 'TRUE')) {
        return (isset($_POST['captcha']) && ($captcha->validateCaptchaCode($_POST['captcha'])));
    } else {
        return true;
    }
}

/**
 * getHighlightedBannedWords()
 *
 * This function returns the passed content with HTML hilighted banned words.
 *
 * @param   string  $content
 * @return  string
 * @access  public
 * @author  Matteo Scaramuccia <matteo@scaramuccia.com>
 */
function getHighlightedBannedWords($content)
{
    $bannedHTMLHiliWords = array();
    $bannedWords = getBannedWords();

    // Build the RegExp array
    foreach ($bannedWords as $word) {
        $bannedHTMLHiliWords[] = "/(".quotemeta($word).")/ism";
    }
    // Use the CSS "highlight" class to highlight the banned words
    if (count($bannedHTMLHiliWords)>0) {
        return preg_replace($bannedHTMLHiliWords, "<span class=\"highlight\">\\1</span>", $content);
    }
    else {
        return $content;
    }
}

/**
* hilight()
*
* Adds PHP syntax highlighting to your pre tags
*
* @param    string
* @return   string
* @access   public
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2004-12-25
*/
function hilight($content)
{
    $string = $content[2];

    if (version_compare(PHP_VERSION, "4.2.0", ">=")) {
        // We're on 4.2.0 or later
        //   mixed highlight_string ( string str [, bool return] )
        //   The return parameter became available in PHP 4.2.0
        $string = str_replace("&lt;?php", " ", $string);
        $string = str_replace("?&gt;", " ", $string);

        if (!ereg('^<\\?', $string) || !ereg('^&lt;\\?', $string)) {
            $string = "<?php\n".$string."\n?>";
        }

        $string = implode("\n", explode("<br />", $string));
        $string = highlight_string($string, TRUE);
        $string = eregi_replace('^.*<pre>',  '', $string);
        $string = eregi_replace('</pre>.*$', '', $string);
        $string = str_replace("\n", "", $string);
        $string = str_replace("&nbsp;", " ", $string);

        // Making the PHP generated stuff XHTML compatible
        $string = preg_replace('/<FONT COLOR="/i', '<span style="color:', $string);
        $string = preg_replace('/<\/FONT>/i', '</span>', $string);
    } else {
        // We're under 4.2.0
        // Do nothing
    }

    return $string;
}

//
// USERTRACKING FUNCTIONS
//

/**
* Trackt den User und zeichnet die Bewegungen auf
*
* @param    string
* @param    integer
* @return   void
* @since    2001-02-18
* @since    Bastian Pöttner <bastian@poettner.net>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @author   Matteo Scaramuccia <matteo@scaramuccia.com>
*/
function Tracking($action, $id = 0)
{
    global $db, $PMF_CONF, $sid;
    if (isset($PMF_CONF["tracking"])) {
        if (isset($_GET["sid"])) {
            $sid = $_GET["sid"];
        }
        if (isset($_COOKIE['pmf_sid'])) {
            $sid = $_COOKIE['pmf_sid'];
        }
        if ($action == "old_session") {
            unset($sid);
        }
        if (!isset($sid)) {
            $sid = $db->nextID(SQLPREFIX."faqsessions", "sid");
            // HACK: be sure that pmf_sid cookie contains the current $sid
            if (isset($_COOKIE["pmf_sid"]) && ((int)$_COOKIE['pmf_sid'] != $sid)) {
                setcookie('pmf_sid', $sid, time() + 3600);
            }
            $db->query("INSERT INTO ".SQLPREFIX."faqsessions (sid, ip, time) VALUES (".$sid.", '".$_SERVER["REMOTE_ADDR"]."', ".time().")");
        }
        $fp = @fopen("./data/tracking".date("dmY"), "a+b");
        if ($fp) {
            $flanz = "0";
            while (!flock($fp, LOCK_EX) && $flanz < 6) {
                wait(500);
                $flanz++;
            }
            if ($flanz >= 6) {
                fclose($fp);
            } elseif ((!empty($_SERVER["HTTP_REFERER"])) || ($action == "new_session")) {
                if (!isset($_SERVER["HTTP_REFERER"])) {
                    $_SERVER["HTTP_REFERER"] = "";
                }
                if (!isset($_SERVER["QUERY_STRING"])) {
                    $_SERVER["QUERY_STRING"] = "";
                }
                fputs($fp, $sid.";".str_replace(";", ",",$action).";".$id.";".$_SERVER["REMOTE_ADDR"].";".str_replace(";", ",", $_SERVER["QUERY_STRING"]).";".str_replace(";", ",", $_SERVER["HTTP_REFERER"]).";".str_replace(";", ",", urldecode($_SERVER["HTTP_USER_AGENT"])).";".time().";\n");
                flock($fp, LOCK_UN);
                fclose($fp);
            }
        }
    }
}

/**
* An OS independent function like usleep
*
* @param    integer
* @return   void
* @since    2004-05-30
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function wait($usecs)
{
    $temp = gettimeofday();
    $start = (int)$temp["usec"];
    while(1) {
        $temp = gettimeofday();
        $stop = (int)$temp["usec"];
        if ($stop - $start >= $usecs) {
            break;
            }
        }
}

/*
 * Testet ob die SID zu der IP passt |  @@ Bastian, 2001-04-07
 * Last Update: @@ Thorsten, 2003-03-06
 * Last Update: @@ Matteo, 2006-03-13
 */
function CheckSID($sid, $ip)
{
    global $db;

    if ($db->num_rows($db->query("SELECT sid FROM ".SQLPREFIX."faqsessions WHERE sid = ".$sid." AND ip = '".$ip."' AND time > ".(time()-86400))) < 1) {
        // No sid found (maybe someone is refering to an old one): create a new one
        Tracking("old_session", $sid);
    }
    else {
        // Update the current sid in the db
        $db->query("UPDATE ".SQLPREFIX."faqsessions SET time = ".time()." WHERE sid = ".$sid." AND ip = '".$ip."'");
    }
}

/*
 * Funktion für Anzeige von Usern, die online sind | @@ Thorsten, 2001-05-02
 * Last Update: @@ Thorsten, 2004-07-17
 * Last Update: @@ Matteo, 2006-03-13
 */
function userOnline()
{
    global $db, $PMF_CONF;

    if (isset($PMF_CONF["tracking"])) {
        $timeNow = (time() - 300);
        // Count all sids within the last five minutes (300sec), that is to say
        // the number of unique users who have perfomed some activities within the last five minutes
        $result = $db->query("SELECT count(sid) AS activeusers FROM ".SQLPREFIX."faqsessions WHERE time > ".$timeNow);
        if (isset($result)) {
            $row = $db->fetch_object($result);
            return $row->activeusers;
        }
    }

    return 0;
}



/******************************************************************************
 * Funktionen für die Startseite
 ******************************************************************************/

/**
* generateNews
*
* Function for generating the FAQ news
*
* @return   string
* @access   public
* @since    2002-08-23
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function generateNews()
{
	global $db, $PMF_LANG, $PMF_CONF;
	$counter = 0;
	$result = $db->query("SELECT id, datum, header, artikel, link, linktitel, target FROM ".SQLPREFIX."faqnews ORDER BY datum desc");
	$output = '';
	if ($PMF_CONF['numNewsArticles'] > 0 && $db->num_rows($result) > 0) {
		while (($row = $db->fetch_object($result)) && $counter < $PMF_CONF['numNewsArticles']) {
			$counter++;
			$output .= sprintf('<h3><a name="news_%d">%s</a></h3><div class="block">%s', $row->id, $row->header, $row->artikel);
            if ($row->link != '') {
    		    $output .= sprintf('<br />Info: <a href="http://%s" target="_%s">%s</a>', $row->link, $row->target, $row->linktitel);
    		}
		    $output .= sprintf('</div><div class="date">%s</div>', makeDate($row->datum));
		}
        return $output;
	} else {
        return $PMF_LANG['msgNoNews'];
    }
}



/**
* generateTopTenData
*
* This function generates the Top Ten data with the mosted viewed records
*
* @param    string, int
* @return   string
* @access   public
* @author   Robin Wood <robin@digininja.org>
* @since    2005-03-06
*/
function generateTopTenData($language = '', $count = PMF_NUMBER_RECORDS_TOPTEN)
{
	global $db, $sids, $PMF_LANG, $PMF_CONF;
	$query = 'SELECT DISTINCT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqvisits.visits AS visits FROM '.SQLPREFIX.'faqvisits, '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang WHERE ';

    if (isset($language) && PMF_Init::isASupportedLanguage($language)) {
        $query .= SQLPREFIX.'faqdata.lang = \''.$language.'\' AND ';
    }

    $query .= SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqvisits.id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqvisits.lang AND '.SQLPREFIX.'faqdata.active = \'yes\' ORDER BY '.SQLPREFIX.'faqvisits.visits DESC';

    $result = $db->query($query);
	$returnArray = array();

	$i = 1;
    $oldId = 0;
	while (($row = $db->fetch_object($result)) && $i <= $count) {
	    if ($oldId != $row->id) {
	        $data['visits'] =  $row->visits;
	        $data['thema'] = $row->thema;
	        if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
	            $data['url'] = $row->category_id."_".$row->id."_".$row->lang.".html";
	        } else {
	            $data['url'] = $_SERVER["PHP_SELF"]."?".$sids."action=artikel&amp;cat=".$row->category_id."&amp;id=".$row->id."&amp;artlang=".$row->lang;
	        }
	        $returnArray[] = $data;
	        $i++;
	    }
	    $oldId = $row->id;
	}

	return $returnArray;
}



/**
* This function generates the Top Ten with the mosted viewed records
*
* @param    string
* @return   string
* @access   public
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2002-05-07
*/
function generateTopTen($language = '')
{
	global $PMF_LANG, $PMF_CONF;

    $result = generateTopTenData($language, PMF_NUMBER_RECORDS_TOPTEN);
	if (count($result) > 0) {
	    $output = "<ol>\n";
		foreach ($result as $row) {
            $output .= "\t<li><strong>".$row['visits']." ".$PMF_LANG["msgViews"].":</strong><br />";
			$output .= '<a href="'.$row['url'] . '">'.makeShorterText(PMF_htmlentities($row['thema'], ENT_NOQUOTES, $PMF_LANG['metaCharset']), 8)."</a></li>\n";
		}
        $output .= "</ol>\n";
	} else {
		$output = $PMF_LANG["err_noTopTen"];
	}
	return $output;
}



/**
* This function generates the list with the five latest published records
*
* @param    string
* @return   string
* @access   public
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2002-05-07
*/
function generateFiveNewest($language = '')
{
	global $PMF_LANG;
	$result = generateNewestData($language, PMF_NUMBER_RECORDS_LATEST);

	if (count ($result) > 0) {
		$output = '<ol>';
		foreach ($result as $row) {
			$output .= '<li><a href="'.$row['url'].'">'.makeShorterText(PMF_htmlentities($row['thema'], ENT_NOQUOTES, $PMF_LANG['metaCharset']), 8).'</a> ('.makeDate($row['datum']).')</li>';
        }
        $output .= '</ol>';
    } else {
		$output = $PMF_LANG["err_noArticles"];
    }
	return $output;
}



/**
* This function generates an array with a specified number of most recent published records
*
* @param    string, int
* @return   array
* @access   public
* @author   Robin Wood <robin@digininja.org>
* @since    2005-03-06
*/
function generateNewestData($language = '', $count = PMF_NUMBER_RECORDS_LATEST)
{
	global $db, $sids, $PMF_LANG, $PMF_CONF;
	$query = 'SELECT DISTINCT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqdata.datum AS datum, '.SQLPREFIX.'faqvisits.visits AS visits FROM '.SQLPREFIX.'faqvisits, '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang WHERE ';

    if (isset($language) && PMF_Init::isASupportedLanguage($language)) {
        $query .= SQLPREFIX.'faqdata.lang = \''.$language.'\' AND ';
    }

    $query .= SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqvisits.id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqvisits.lang AND '.SQLPREFIX.'faqdata.active = \'yes\' ORDER BY '.SQLPREFIX.'faqdata.datum DESC';
    $result = $db->query($query);

	$newestArray = array();
	$data = array();

	$i = 0;
	$oldId = 0;
	while (($row = $db->fetch_object($result)) && $i < $count ) {
		if ($oldId != $row->id) {
		    $data['datum'] = $row->datum;
		    $data['thema'] = $row->thema;
		    $data['visits'] = $row->visits;
		    if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
		        $data['url'] = $row->category_id."_".$row->id."_".$row->lang.".html";
		    } else {
		        $data['url'] = $_SERVER["PHP_SELF"]."?".$sids."action=artikel&amp;cat=".$row->category_id."&amp;id=".$row->id."&amp;artlang=".$row->lang;
		    }
		    $newestArray[] = $data;
		    $i++;
		}
		$oldId = $row->id;
	}
	return ($newestArray);
}



/*
 * Kürzt einen String auf eine bestimmte Anzahl von Wörtern | @@ Thorsten - 2002-08-26
 * Last Update: @@ Thorsten, 2004-06-09
 */
function makeShorterText($str, $char)
{
	$str = ereg_replace("[[:space:]]+", " ", $str);
	$arrStr = explode(" ", $str);
	$shortStr = "";
    $num = count($arrStr);
	if ($num > $char) {
		for ($j = 0; $j <= $char; $j++) {
			$shortStr .= $arrStr[$j]." ";
			}
		$shortStr .= "...";
		}
	else {
		$shortStr = $str;
		}
	return $shortStr;
}



/******************************************************************************
 * Funktionen für Artikelseiten
 ******************************************************************************/

/**
* logViews()
*
* Counting the views of a FAQ record
*
* @param    integer     id
* @param    string      lang
* @return   void
* @access   public
* @since    2001-02-15
* @auhtor   Bastian Pöttner <bastian@poettner.net>
* @author   Thorsten Rinne
*/
function logViews($myid, $lang)
{
    global $db;
    $nVisits = 0;
    $heute = time();
    if ($result = $db->query("SELECT visits FROM ".SQLPREFIX."faqvisits WHERE id = ".$myid." AND lang = '".$lang."'")) {
        $row = $db->fetch_object($result);
        $nVisits = $row->visits;
    }
    if ($nVisits == "0" || $nVisits == "") {
        $db->query("INSERT INTO ".SQLPREFIX."faqvisits (id, lang, visits, last_visit) VALUES (".$myid.", '".$lang."', 1, ".$heute.")");
    } else {
        $db->query("UPDATE ".SQLPREFIX."faqvisits SET visits = visits+1, last_visit = ".$heute." WHERE id = ".$myid." AND lang = '".$lang."'");
    }
}

/*
 * Macht an den String nen / dran, falls keiner da ist
 * @@ Bastian, 2002-01-06
 */
function EndSlash($string)
{
	if (substr($string, strlen($string)-1, 1) != "/" ) {
		$string .= "/";
	}
	return $string;
}

/**
* generateVoting()
*
* Calculates the rating of the user votings
*
* @param    integer     record id
* @param    string
* @access   public
* @since    2002-08-29
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function generateVoting($id)
{
	global $db, $PMF_LANG;
 	$result = $db->query(sprintf('SELECT (vote/usr) as voting, usr FROM %sfaqvoting WHERE artikel = %d', SQLPREFIX, $id));
	if ($db->num_rows($result) > 0) {
        $row = $db->fetch_object($result);
        return " ".round($row->voting, 2)." ".$PMF_LANG["msgVoteFrom"]." 5 (".$row->usr." ".$PMF_LANG["msgVotings"].")";
	} else {
		return " 0 ".$PMF_LANG["msgVoteFrom"]." 5 (0 ".$PMF_LANG["msgVotings"].")";
	}
}

/*
 * Gibt die Kommentare zum Artikel aus | @@ Thorsten - 2002-08-29
 * Last Update: @@ Thorsten, 2004-10-22
 */
function generateComments($id)
{
	global $db, $PMF_LANG;

	$result = $db->query("SELECT usr, email, comment, datum FROM ".SQLPREFIX."faqcomments WHERE id = ".$id);
	$output = "";
	if ($db->num_rows($result) > 0) {
		while ($row = $db->fetch_object($result)) {
			$output .= "<p class=\"comment\">\n";;
			$output .= "<strong>".$PMF_LANG["msgCommentBy"]."<a href=\"mailto:".safeEmail($row->email)."\">".$row->usr."</a> (".date('Y-m-d H:i:s', $row->datum)."):</strong>\n";
			$output .= "<br />".safeHTML($row->comment)."\n</p>";
		}
	}
	return $output;
}

/**
 * safeHTML()
 *
 * Function to remove all HTML tags but not <strong>, <em>, <u>, und <a>
 *
 * @param   string  $html
 * @return  string  $html
 * @access  public
 * @since   2003-02-23
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function safeHTML($html)
{
    $html = strip_tags($html, "<strong><em><i><u><a><br>");
    $html = str_replace('<a ', '<a rel="nofollow" ', $html);
    return $html;
}

/*
 * Funktion wandelt HTML Entities zurück | @@ Thorsten, 2003-03-27
 * Last Update: @@ Thorsten, 2003-03-27
 */
function unhtmlentities($html)
{
	$translation_table = get_html_translation_table (HTML_ENTITIES);
	$translation_table = array_flip ($translation_table);
	$content = strtr ($html, $translation_table);
	return preg_replace('/\&\#([0-9]+)\;/me', "chr('\\1')", $content);
}

/*
 * Syntax check for e-mails | @@ Thorsten, 2004-01-19
 * Last Update: @@ Thorsten, 2004-06-27
 */
function checkEmail($sender)
{
    global $IDN;
    $sender = $IDN->decode($sender);
    $pattern =  "#^[-!\#$%&\"*+\\./\d=?A-Z^_|'a-z{|}~]+".
                "@".
                "[-!\#$%&\"*+\\./\d=?A-Z^_|'a-z{|}~]+\.".
                "[-!\#$%&\"*+\\./\d=?A-Z^_|'a-z{|}~]+$#";
    if (isset($sender) && preg_match($pattern, $sender)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/*
 * Decode MIME header elements in e-mails | @@ Matthias Sommerfeld
 * (c) 2001-2004 blue birdy, Berlin (http://bluebirdy.de)
 * used with permission
 * Last Update: @@ Thorsten, 2004-07-17
 */
function encode_iso88591($coded = "", $cmode = "g")
{
    if ("g" == $cmode) {
        $coded = str_replace(" ", "_", quoted_printable_encode($coded));
        $zeilen = explode("\r\n", $coded);
        $coded = "";
        foreach ($zeilen as $key => $value) {
            if (!$value) {
                continue;
                }
            if ($key > 0) {
                $coded .= "\t";
                }
            $coded .= '=?iso-8859-1?Q?'.$value.'?='."\r\n";
            }
        return rtrim($coded);
        }
    elseif ("@" == $cmode) {
        $zeilen = explode("\r\n", $coded);
        $coded = "";
        foreach ($zeilen as $key => $value) {
            if (!$value) {
                continue;
                }
            if ($key > 0) {
                $coded .= "\t";
                }
            unset ($words);
            if (function_exists('mb_encode_mimeheader')) {
                $coded .= mb_encode_mimeheader($value)."\r\n";
            } else {
                $words = explode(" ", $value, 2);
                foreach ($words as $k => $word) {
                    if (preg_match("/[\x80-\xff]/", $word) && preg_match("/\(|\)/", $word)) {
                        $words[$k] = preg_replace (
                            "/^(\()?([^\)]+)(\))?$/ie",
                            "'(=?iso-8859-1?Q?'.rtrim(quoted_printable_encode(str_replace(' ', '_', '\\2'))).'?=)'",
                            $word);
                    }
                }
                $coded .= join(" ", $words)."\r\n";
            }
        }
        return rtrim($coded);
    } else {
        $zeilen = explode("\r\n", $coded);
        $coded = '';
        foreach ($zeilen as $key => $value) {
            if (!$value) {
                continue;
                }
            if ($key > 0) {
                $coded .= "\t";
                }
            unset ($words);
            if (function_exists('mb_encode_mimeheader')) {
                $coded .= mb_encode_mimeheader( $value)."\r\n";
            } else {
                unset ($words);
                $words = explode(" ", $value);
                foreach ($words as $k => $word) {
                    if (preg_match('/[\x80-\xff]/', $word)) {
                        $words[$k] = '=?iso-8859-1?Q?'.rtrim(quoted_printable_encode($word)).'?=';
                    }
                }
            }
            $coded .= join(' ', $words)."\r\n";
        }
        return rtrim($coded);
    }
}

/*
 * Decode MIME header elements in e-mails | @@ Matthias Sommerfeld
 * (c) 2001-2004 blue birdy, Berlin (http://bluebirdy.de)
 * used with permission
 * Last Update: @@ Thorsten, 2004-07-17
 */
function quoted_printable_encode($return = '')
{
    // Ersetzen der lt. RFC 1521 nötigen Zeichen
    $return = preg_replace('/([^\t\x20\x2E\041-\074\076-\176])/ie', "sprintf('=%2X',ord('\\1'))", $return);
    $return = preg_replace('!=\ ([A-F0-9])!', '=0\\1', $return);
    // Einfügen von QP-Breaks (=\r\n)
    if (strlen($return) > 75) {
        $length = strlen($return); $offset = 0;
        do {
            $step = 76;
            $add_mode = (($offset+$step) < $length) ? 1 : 0;
            $auszug = substr($return, $offset, $step);
            if (preg_match('!\=$!', $auszug))   $step = 75;
            if (preg_match('!\=.$!', $auszug))  $step = 74;
            if (preg_match('!\=..$!', $auszug)) $step = 73;
            $auszug = substr($return, $offset, $step);
            $offset += $step;
            $schachtel .= $auszug;
            if (1 == $add_mode) $schachtel.= '='."\r\n";
            } while ($offset < $length);
        $return = $schachtel;
        }
    $return = preg_replace('!\.$!', '. ', $return);
    return preg_replace('!(\r\n|\r|\n)$!', '', $return)."\r\n";
}


/******************************************************************************
 * Funktionen für den XML-Export
 ******************************************************************************/

/*
 * Gibt die XML-Datei zum Artikel aus | @@ Thorsten - 2002-08-29
 * Last Update: @@ Thorsten, 2004-08-11
 */
function generateXMLExport($id, $lang = "")
{
	global $db, $tree, $PMF_LANG, $PMF_CONF;
	$result = $db->query('SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqdata.solution_id AS solution_id, '.SQLPREFIX.'faqdata.revision_id AS revision_id, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.keywords AS keywords, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqdata.content AS content, '.SQLPREFIX.'faqdata.author AS author, '.SQLPREFIX.'faqdata.datum AS datum FROM '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang WHERE id = '.$id.' AND lang = \''.$lang.'\' AND active = \'yes\'');

	if ($db->num_rows($result) > 0) {
		while ($row = $db->fetch_object($result)) {
            $xml_content = $row->content;
			$xml_rubrik = $tree->getPath($row->category_id);
			$xml_thema = wordwrap($row->thema, 60);
			$xml_keywords = $row->keywords;
			$xml_content = trim(htmlspecialchars(wordwrap($xml_content, 60), ENT_NOQUOTES, $PMF_LANG['metaCharset']));
			if (is_writeable("./xml/")) {
				$xml_fp = @fopen("./xml/article_".$row->id."_".$row->lang.".xml","wb");
				$my_xml_output = "<?xml version=\"1.0\" encoding=\"".$PMF_LANG["metaCharset"]."\" standalone=\"yes\" ?>\n";
				$my_xml_output .= "<!-- XML-Output by phpMyFAQ ".$PMF_CONF["version"]." | Date: ".makeDate(date("YmdHis"))." -->\n";
				$my_xml_output .= "<phpmyfaq xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:NamespaceSchemaLocation=\"http://www.phpmyfaq.de/xml/faqschema.xsd\">\n";
				$my_xml_output .= "\t<article id=\"".$row->id."\" solution_id=\"".$row->solution_id."\" revision_id=\"1.".$row->revision_id."\">\n";
				$my_xml_output .= "\t<language>".$row->lang."</language>\n";
				$my_xml_output .= "\t<category>".PMF_htmlentities(strip_tags($xml_rubrik), ENT_NOQUOTES, $PMF_LANG['metaCharset'])."</category>\n";
				if ($xml_keywords) {
					$my_xml_output .= "\t<keywords>".$xml_keywords."</keywords>\n";
					}
				else {
					$my_xml_output .= "\t<keywords />\n";
					}
				$my_xml_output .= "\t<theme>".PMF_htmlentities(strip_tags($xml_thema), ENT_NOQUOTES, $PMF_LANG['metaCharset'])."</theme>\n";
				$my_xml_output .= "\t<content xmlns=\"http://www.w3.org/TR/REC-html40\">".strip_tags($xml_content)."</content>\n";
				if ($row->author) {
					$my_xml_output .= "\t<author>".$row->author."</author>\n";
					}
				else {
					$my_xml_output .= "\t<author />\n";
					}
				$my_xml_output .= "\t<date>".makeDate($row->datum)."</date>\n";
				$my_xml_output .= "\t</article>\n";
				$my_xml_output .= "</phpmyfaq>";
                fputs($xml_fp, $my_xml_output);
                fclose($xml_fp);
                }
            }
        return true;
        }
    return false;
}

/**
* generateXHTMLFile()
*
* This function generates a plain XHTML file with all entries.
*
* @return   boolean
* @access   public
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2004-11-13
*/
function generateXHTMLFile()
{
	global $db, $PMF_CONF, $PMF_LANG;

    $tree = new Category();
    $tree->transform(0);
    $old = 0;

	$result = $db->query('SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqdata.solution_id AS solution_id, '.SQLPREFIX.'faqdata.revision_id AS revision_id, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.keywords AS keywords, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqdata.content AS content, '.SQLPREFIX.'faqdata.author AS author, '.SQLPREFIX.'faqdata.datum AS datum FROM '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang ORDER BY '.SQLPREFIX.'faqcategoryrelations.category_id, '.SQLPREFIX.'faqdata.id');

    $xhtml = '<?xml version="1.0" encoding="'.$PMF_LANG['metaCharset'].'" ?>';
    $xhtml .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $xhtml .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="'.$PMF_LANG['metaLanguage'].'" lang="'.$PMF_LANG['metaLanguage'].'">';
    $xhtml .= '<head>';
    $xhtml .= '    <title>'.$PMF_CONF['title'].'</title>';
    $xhtml .= '    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset='.$PMF_LANG['metaCharset'].'" />';
    $xhtml .= '    <meta name="title" content="'.$PMF_CONF['title'].'" />';
    $xhtml .= '</head>';
    $xhtml .= '<body dir="'.$PMF_LANG['dir'].'">';

    if ($db->num_rows($result) > 0) {
        while ($row = $db->fetch_object($result)) {
            if ($row->category_id != $old) {
                $xhtml .= '<h1>'.$tree->getPath($row->category_id).'</h1>';
            }
            $xhtml .= '<h2>'.$row->thema.'</h2>';
            $xhtml .= '<p>'.$row->content.'</p>';
            $xhtml .= '<p>'.$PMF_LANG["msgAuthor"].$row->author.'<br />';
            $xhtml .= 'ID: '.$row->solution_id.'<br />';
            $xhtml .= $PMF_LANG['ad_entry_revision'].': 1.'.$row->revision_id.'<br />';
            $xhtml .= $PMF_LANG["msgLastUpdateArticle"].makeDate($row->datum).'</p>';
            $xhtml .= '<hr style="width: 90%;" />';
            $old = $row->category_id;
        }
    }
    $xhtml .= '</body>';
    $xhtml .= '</html>';

    if ($fp = fopen("../xml/phpmyfaq.html","w")) {

        fputs($fp, $xhtml);
		fclose($fp);
    }
}

/*
 * Funktion zum Exportieren aller Artikel in eine XML-Datei | @@ Thorsten, 2003-03-03
 * Last Update: @@ Thorsten, 2004-05-07
 */
function generateXMLFile()
{
	global $db, $tree, $PMF_CONF, $PMF_LANG;

	$result = $db->query('SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqdata.solution_id AS solution_id, '.SQLPREFIX.'faqdata.revision_id AS revision_id, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqdata.content AS content, '.SQLPREFIX.'faqdata.keywords AS keywords, '.SQLPREFIX.'faqdata.author AS author, '.SQLPREFIX.'faqdata.datum AS datum FROM '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang ORDER BY '.SQLPREFIX.'faqcategoryrelations.category_id, '.SQLPREFIX.'faqdata.id');

	if ($db->num_rows($result) > 0) {
		$my_xml_output = "<?xml version=\"1.0\" encoding=\"".$PMF_LANG["metaCharset"]."\" standalone=\"yes\" ?>\n";
		$my_xml_output .= "<!-- XML-Output by phpMyFAQ ".$PMF_CONF["version"]." | Date: ".makeDate(date("YmdHis"))." -->\n";
		$my_xml_output .= "<phpmyfaq xmlns=\"http://www.phpmyfaq.de/phpmyfaq\">\n";
		$xml_fp = fopen("../xml/phpmyfaq.xml","w");
		while ($row = $db->fetch_object($result)) {
            $xml_content = wordwrap($row->content);
			$xml_rubrik = $tree->categoryName[$row->category_id]['name'];
			$xml_thema = wordwrap($row->thema, 60);
			$xml_content = trim(htmlspecialchars(wordwrap($xml_content, 60)));

			if (is_writeable("../xml/")) {
				$my_xml_output .= "\t<article id=\"".$row->id."\" solution_id=\"".$row->solution_id."\" revision_id=\"1.".$row->revision_id."\">\n";
				$my_xml_output .= "\t<language>".$row->lang."</language>\n";
				$my_xml_output .= "\t<category>".PMF_htmlentities(strip_tags($xml_rubrik), ENT_NOQUOTES, $PMF_LANG['metaCharset'])."</category>\n";
				if (isset($row->keywords) && $row->keywords != '') {
					$my_xml_output .= "\t<keywords>".$row->keywords."</keywords>\n";
					}
				else {
					$my_xml_output .= "\t<keywords />\n";
					}
				$my_xml_output .= "\t<theme>".PMF_htmlentities(strip_tags($xml_thema), ENT_NOQUOTES, $PMF_LANG['metaCharset'])."</theme>\n";
				$my_xml_output .= "\t<content xmlns=\"http://www.w3.org/TR/REC-html40\">".strip_tags($xml_content)."</content>\n";
				if ($row->author) {
					$my_xml_output .= "\t<author>".$row->author."</author>\n";
					}
				else {
					$my_xml_output .= "\t<author />\n";
					}
				$my_xml_output .= "\t<date>".makeDate($row->datum)."</date>\n";
				$my_xml_output .= "\t</article>\n\n";
				}
			}
		$my_xml_output .= "</phpmyfaq>";
		fputs($xml_fp, $my_xml_output);
		fclose($xml_fp);
		}
}

/**
* searchEngine()
*
* The main search function for the full text search
*
* @param    string
* @param    string
* @return   string
* @access   public
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2002-09-16
*/
function searchEngine($begriff, $category = '%')
{
    global $db, $sids, $tree, $PMF_LANG, $PMF_CONF;

    $_begriff    = $begriff;
    $seite       = '';
    $output      = '';
    $searchItems = array();

    if (isset($_REQUEST["seite"])) {
        $seite = $_REQUEST["seite"];
    } else {
        $seite = 1;
    }

    if ('%' == $category) {
        $cond = array(SQLPREFIX."faqdata.active" => "'yes'");
    } else {
        $cond = array(SQLPREFIX."faqdata.active" => "'yes'",
                      SQLPREFIX."faqcategoryrelations.category_id" => $category);
    }

    if (is_numeric($begriff)) {
        // search for the solution_id
        $result = $db->search(SQLPREFIX.'faqdata',
                        array(SQLPREFIX.'faqdata.id AS id',
                              SQLPREFIX.'faqdata.lang AS lang',
                              SQLPREFIX.'faqdata.solution_id AS solution_id',
                              SQLPREFIX.'faqcategoryrelations.category_id AS category_id',
                              SQLPREFIX.'faqdata.thema AS thema',
                              SQLPREFIX.'faqdata.content AS content'),
                        SQLPREFIX.'faqcategoryrelations',
                        array(SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id',
                              SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang'),
                        array(SQLPREFIX.'faqdata.solution_id'),
                        $begriff,
                        $cond);
    } else {
        $result = $db->search(SQLPREFIX."faqdata",
                        array(SQLPREFIX."faqdata.id AS id",
                              SQLPREFIX."faqdata.lang AS lang",
                              SQLPREFIX."faqcategoryrelations.category_id AS category_id",
                              SQLPREFIX."faqdata.thema AS thema",
                              SQLPREFIX."faqdata.content AS content"),
                        SQLPREFIX."faqcategoryrelations",
                        array(SQLPREFIX."faqdata.id = ".SQLPREFIX."faqcategoryrelations.record_id",
                              SQLPREFIX."faqdata.lang = ".SQLPREFIX."faqcategoryrelations.record_lang"),
                        array(SQLPREFIX."faqdata.thema",
                              SQLPREFIX."faqdata.content",
                              SQLPREFIX."faqdata.keywords"),
                        $begriff,
                        $cond);
    }

    if (false === $result) {
        $output = $PMF_LANG['err_noArticles'];
        $num = 0;
    } else {
        $num = $db->num_rows($result);
    }
    // Show the record with the solution ID directly
    // Sanity checks: if a valid Solution ID has been provided the result set
    //                will measure 1: this is true ONLY if the faq is not
    //                classified among more than 1 category
    if (is_numeric($begriff) && ($begriff > PMF_SOLUTION_ID_START_VALUE) && ($num > 0)) {
        if (isset($PMF_CONF['mod_rewrite']) && $PMF_CONF['mod_rewrite'] == 'TRUE') {
            header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'solution_id_'.$begriff.'.html');
        } else {
            header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']).'?solution_id='.$begriff);
        }
        exit();
    }

    if (0 == $num) {
        $keys = preg_split("/\s+/", $begriff);
        $numKeys = count($keys);
        $where = '';
        for ($i = 0; $i < $numKeys; $i++) {
            if (strlen($where) != 0 ) {
                $where = $where." OR ";
            }
            $where = $where.'('.SQLPREFIX."faqdata.thema LIKE '%".$keys[$i]."%' OR ".SQLPREFIX."faqdata.content LIKE '%".$keys[$i]."%' OR ".SQLPREFIX."faqdata.keywords LIKE '%".$keys[$i]."%') AND ".SQLPREFIX.'faqdata.active = \'yes\'';
            if (is_numeric($category)) {
                $where .= ' AND '.SQLPREFIX.'faqcategoryrelations.category_id = '.$category;
            }
        }

        $where = " WHERE (".$where.") AND active = 'yes'";
        $query = 'SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema, '.SQLPREFIX.'faqdata.content AS content FROM '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang '.$where;
        $result = $db->query($query);
        $num = $db->num_rows($result);
    }

    $pages = ceil($num / $PMF_CONF["numRecordsPage"]);
    $last = $seite * $PMF_CONF["numRecordsPage"];
    $first = $last - $PMF_CONF["numRecordsPage"];
    if ($last > $num) {
        $last = $num;
    }

    if ($num > 0) {
        if ($num == "1") {
            $output .= "<p>".$num.$PMF_LANG["msgSearchAmount"]."</p>\n";
        } else {
            $output .= "<p>".$num.$PMF_LANG["msgSearchAmounts"]."</p>\n";
        }
        if ($pages > 1) {
            $output .= "<p><strong>".$PMF_LANG["msgPage"].$seite." ".$PMF_LANG["msgVoteFrom"]." ".$pages." ".$PMF_LANG["msgPages"]."</strong></p>";
        }
        $output .= "<ul class=\"phpmyfaq_ul\">\n";

        $counter = 0;
        $displayedCounter = 0;
        while (($row = $db->fetch_object($result)) && $displayedCounter < $PMF_CONF['numRecordsPage']) {
            $counter ++;
            if ($counter <= $first) {
                continue;
            }
            $displayedCounter++;

            $rubriktext = $tree->getPath($row->category_id);
            $thema = PMF_htmlentities(chopString($row->thema, 15),ENT_NOQUOTES, $PMF_LANG['metaCharset']);
            $content = chopString(strip_tags($row->content), 25);
            $begriff = str_replace(array('^', '.', '?', '*', '+', '{', '}', '(', ')', '[', ']'), '', $begriff);
            $begriff = preg_quote($begriff, '/');
            $searchItems = explode(' ', $begriff);

            if (strlen($searchItems[0]) > 1) {
                foreach ($searchItems as $item) {
                    $thema   = preg_replace_callback(
                        '/('.$item.
                        '="[^"]*")|((href|src|title|alt|class|style|id|name)="[^"]*'.$item.
                        '[^"]*")|('.$item.
                        ')/mis', "highlight_no_links", $thema);
                    $content = preg_replace_callback(
                        '/('.$item.
                        '="[^"]*")|((href|src|title|alt|class|style|id|name)="[^"]*'.$item.
                        '[^"]*")|('.$item.
                        ')/mis', "highlight_no_links", $content);
                }
            }

            // Print the link to the faq record
            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
                $output .= "<li><strong>".$rubriktext."</strong>: <a href=\"".$row->category_id."_".$row->id."_".$row->lang.".html?highlight=".$begriff."\">".$thema."...</a><br /><div class=\"searchpreview\"><strong>".$PMF_LANG["msgSearchContent"]."</strong> ".$content."...</div><br /></li>\n";
            } else {
                $output .= "<li><strong>".$rubriktext."</strong>: <a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=artikel&amp;cat=".$row->category_id."&amp;id=".$row->id."&amp;artlang=".$row->lang."&amp;highlight=".$begriff."\">".$thema."...</a><br /><div class=\"searchpreview\"><strong>".$PMF_LANG["msgSearchContent"]."</strong> ".$content."...</div><br /></li>\n";
            }
        }
        $output .= "</ul>\n";
    } else {
        $output = $PMF_LANG["err_noArticles"];
    }

    if ($num > $PMF_CONF["numRecordsPage"]) {
        $output .= "<p align=\"center\"><strong>";
        $vor = $seite - 1;
        $next = $seite + 1;
        if ($vor != 0) {
            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
                $output .= "[ <a href=\"search.html?search=".urlencode($_begriff)."&amp;seite=".$vor."\">".$PMF_LANG["msgPrevious"]."</a> ]";
            } else {
                $output .= "[ <a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=search&amp;search=".urlencode($_begriff)."&amp;seite=".$vor."\">".$PMF_LANG["msgPrevious"]."</a> ]";
            }
        }
        $output .= " ";
        if ($next <= $pages) {
            if (isset($PMF_CONF["mod_rewrite"]) && $PMF_CONF["mod_rewrite"] == "TRUE") {
                $output .= "[ <a href=\"search.html?search=".urlencode($_begriff)."&amp;seite=".$next."\">".$PMF_LANG["msgNext"]."</a> ]";
            } else {
                $output .= "[ <a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=search&amp;search=".urlencode($_begriff)."&amp;seite=".$next."\">".$PMF_LANG["msgNext"]."</a> ]";
            }
        }
        $output .= "</strong></p>";
    }

    return $output;
}



/**
* Callback function for filtering HTML from URLs and images
*
* @param    array
* @access   public
* @return   string
* @author   Matthias Sommerfeld <phlymail@phlylabs.de>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2003-07-14
*/
function highlight_no_links($string = '')
{
    foreach ($string as $str) {
        if ('href='  == substr(ltrim($str), 0, 5) || 'src='   == substr(ltrim($str), 0, 4) || 'title=' == substr(ltrim($str), 0, 6) || 'alt='   == substr(ltrim($str), 0, 4) || 'class=' == substr(ltrim($str), 0, 6) || 'style=' == substr(ltrim($str), 0, 6) || 'id=' == substr(ltrim($str), 0, 3) || 'name='  == substr(ltrim($str), 0, 5) ) {
            return $str;
        } elseif ('' == $str) {
            return '';
        } else {
            return '<span class="highlight">'.$str.'</span>';
        }
    }
}



/*
 * This functions chops a string | @@ Thorsten, 2003-12-16
 * Last Update: @@ Thorsten, 2003-12-16
 */
function chopString($string, $words)
{
    $str = "";
    $pieces = explode(" ", $string);
    $num = count($pieces);
    if ($words > $num) {
        $words = $num;
    }
    for ($i = 0; $i < $words; $i++) {
        $str .= $pieces[$i]." ";
    }
    return $str;
}

/******************************************************************************
 * Funktionen für die Offenen Fragen
 ******************************************************************************/

/**
 * printOpenQuestions()
 *
 * Gives the HTML output code for the Open Questions
 *
 * @return  string
 * @access  public
 * @since   2002-09-17
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function printOpenQuestions()
{
    global $db, $sids, $tree, $PMF_LANG;

    $query = "SELECT id, ask_username, ask_usermail, ask_rubrik, ask_content, ask_date FROM ".SQLPREFIX."faqfragen ORDER BY ask_date ASC";
    $result = $db->query($query);
    $output = "";
    if ($db->num_rows($result) > 0) {
        while ($row = $db->fetch_object($result)) {
            $output .= "\t<tr class=\"openquestions\">\n";
            $output .= "\t\t<td valign=\"top\" nowrap=\"nowrap\"><a name=\"openq_".$row->id."\">".makeDate($row->ask_date)."</a><br /><a href=\"mailto:".safeEmail($row->ask_usermail)."\">".$row->ask_username."</a></td>\n";
            $output .= "\t\t<td valign=\"top\"><strong>".$tree->categoryName[$row->ask_rubrik]["name"].":</strong><br />".strip_tags($row->ask_content)."</td>\n";
            $output .= "\t\t<td valign=\"top\"><a href=\"".$_SERVER["PHP_SELF"]."?".$sids."action=add&amp;question=".rawurlencode($row->ask_content)."&amp;cat=".$row->ask_rubrik."\">".$PMF_LANG["msg2answer"]."</a></td>\n";
            $output .= "\t</tr>\n";
            }
        }
    else {
        $output = "\t<tr>\n\t\t<td colspan=\"3\">".$PMF_LANG["msgNoQuestionsAvailable"]."</td>\n\t</tr>\n";
        }

    return $output;
}

//
// Various functions
//

/**
* PMF_htmlentities
*
* This is a wrapper for htmlentities() with a check on valid charsets.
*
* @param    string
* @param    string
* @param    string
* @return   return
* @access   private
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function PMF_htmlentities($string, $quote_style = ENT_COMPAT, $charset = 'iso-8859-1')
{
    $acceptedCharsets = array('iso-8859-1', 'iso-8859-15', 'utf-8', 'cp866', 'ibm866', '866', 'cp1251', 'windows-1251', 'win-1251', '1251', 'cp1252', 'windows-1252', '1252', 'koi8-r', 'koi8-ru', 'koi8r', 'big5', '950', 'gb2312', '936', 'big5-hkscs', 'shift_jis', 'sjis', '932', 'euc-jp', 'eucjp');

    if (in_array(strtolower($charset), $acceptedCharsets)) {
        return htmlspecialchars($string, ENT_NOQUOTES, $charset);
    } else {
        return htmlspecialchars($string);
    }
}
//*/


/******************************************************************************
 * Funktionen für die Benutzerauthentifizierung und Rechtevergabe
 ******************************************************************************/

/**
* Administrator logging
*
* @param    string
* @return   void
* @access   public
* @since    2001-02-18
* @author   Bastian Poettner <bastian@poettner.net>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function adminlog($text)
{
    global $db, $PMF_CONF, $auth_id;
    if (isset($PMF_CONF["enableadminlog"]) && is_numeric($auth_id)) {
        $db->query('INSERT INTO '.SQLPREFIX.'faqadminlog (id, time, usr, text, ip) VALUES ('.$db->nextID(SQLPREFIX.'faqadminlog', 'id').', '.time().', '.$auth_id.', \''.nl2br($text).'\', \''.$_SERVER["REMOTE_ADDR"].'\')');
    }
}

/*
 * Checkt, ob eine SQL-Tabelle leer ist | @@ Thorsten 2002-01-10
 * Last Update: @@ Thorsten, 2003-03-24
 */
function emptyTable($table)
{
	global $db;
	if ($db->num_rows($db->query("SELECT * FROM ".$table)) < 1) {
		return TRUE;
    } else {
		return FALSE;
	}
}



/******************************************************************************
 * Funktionen für den Adminbereich
 ******************************************************************************/

/*
 * Funktion zum generieren vom "Umblättern" | @@ Bastian, 2002-01-03
 * Last Update: @@ Thorsten, 2004-05-07
 */
function PageSpan($code, $start, $end, $akt)
{
	global $PMF_LANG;
	if ($akt > $start) {
		$out = str_replace("<NUM>", $akt-1, $code).$PMF_LANG["msgPreviusPage"]."</a> | ";
	} else {
		$out = "";
	}
	for ($h = $start; $h<=$end; $h++) {
		if ($h > $start) {
			$out .= ", ";
		}
		if ($h != $akt) {
			$out .= str_replace("<NUM>", $h, $code).$h."</a>";
		} else {
			$out .= $h;
		}
	}
	if ($akt < $end) {
		$out .= " | ".str_replace("<NUM>", $akt+1, $code).$PMF_LANG["msgNextPage"]."</a>";
	}
	$out = $PMF_LANG["msgPageDoublePoint"].$out;
	return $out;
}

/*
 * Bastelt aus den Dateinamen des Tracking einen Timestamp | @@ Bastian, 2002-01-05
 * Last Update: @@ Thorsten, 2002-09-19
 */
function FileToDate($file)
{
	if (strlen($file) >= 16) {
		$tag = substr($file, 8, 2);
		$mon = substr($file, 10, 2);
		$yea = substr($file, 12, 4);
		$tim = mktime(1, 1, 1, $mon, $tag, $yea);
		return $tim;
	} else {
		return -1;
	}
}

/*
 * Bastelt nen Timestamp ausm Datum | @@ Bastian, 2001-04-09
 * Last Update: @@ Thorsten - 2002-09-27
 */
function mkts($datum,$zeit)
{
	if (strlen($datum) > 0) {
		$tag = substr($datum,0,strpos($datum,"."));
		$datum = substr($datum,(strpos($datum,".")+1),strlen($datum));
		$monat = substr($datum,0,strpos($datum,"."));
		$datum = substr($datum,(strpos($datum,".")+1),strlen($datum));
		$jahr = $datum;
	} else {
		$tag = date("d");
		$monat = date("m");
		$jahr = date("Y");
	} if (strlen($zeit) > 0) {
		$stunde = substr($zeit,0,strpos($zeit,":"));
		$zeit = substr($zeit,(strpos($zeit,":")+1),strlen($zeit));
		$minute = substr($zeit,0,strpos($zeit,":"));
		$zeit = substr($zeit,(strpos($zeit,":")+1),strlen($zeit));
		$sekunde = $zeit;
	} else {
		$stunde = date("H");
		$minute = date("i");
		$sekunde = date("s");
	}
	return mktime($stunde, $minute, $sekunde, $monat, $tag, $jahr);
}

/**
 * getSolutionId()
 *
 * Gets the latest solution id for a FAQ record
 *
 * @return  integer
 * @access  public
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function getSolutionId()
{
    global $db;

    $latest_id = 0;
    $next_solution_id = 0;

    $query = sprintf('SELECT MAX(solution_id) AS solution_id FROM %sfaqdata', SQLPREFIX);
    $result = $db->query($query);

    if ($result && $row = $db->fetch_object($result)) {
        $latest_id = $row->solution_id;
    }

    if ($latest_id < PMF_SOLUTION_ID_START_VALUE) {
        $next_solution_id = PMF_SOLUTION_ID_START_VALUE;
    } else {
        $next_solution_id = $latest_id + PMF_SOLUTION_ID_INCREMENT_VALUE;
    }

    return $next_solution_id;
}

/**
 * getIdFromSolutionId()
 *
 * Gets the record ID from a given solution ID
 *
 * @param   integer
 * @return  array
 * @access  public
 * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function getIdFromSolutionId($solution_id)
{
    global $db;

    $query = sprintf("SELECT id, lang FROM %sfaqdata WHERE solution_id = %s", SQLPREFIX, $solution_id);
    $result = $db->query($query);

    if ($row = $db->fetch_object($result)) {
    	return array('id' => $row->id, 'lang' => $row->lang);
    }

    return null;
}


//
// Add re-built PHP functions, which we need and not included in PHP 4.1.0
//

/**
* Creates an array by using one array for keys and another for its values
* NOTE: This function is included in PHP 5.0.0 and later
*
* @param    array
* @param    array
* @return   array
* @access   public
* @since    2004-07-23
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
if (!function_exists("array_combine")) {
    function array_combine($keys, $values)
    {
        $keys = array_values($keys);
        $values  = array_values($values);
        $combined = array ();
        $num = count($values);
        for ($i = 0; $i < $num; $i++) {
            $combined[$keys[$i]] = $values[$i];
        }
        return $combined;
    }
}

/**
* Convert all HTML entities to their applicable characters
* NOTE: This function is included since PHP 4.3.0 and later
*
* @param    string
* @param    string
* @param    string
* @return   string
* @access   public
* @since    2005-09-25
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
if (!function_exists('html_entity_decode')) {
    function html_entity_decode($given_html, $quote_style = ENT_QUOTES, $charset = 'UTF-8')
    {
        $trans_table = array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style));
        $trans_table['&#39;'] = "'";
        return (strtr($given_html, $trans_table));
    }
}

/**
* Returns an array with all string keys lowercased or uppercased
* NOTE: This function is included since PHP 4.2.0 and later
*
* @param    array
* @param    const
* @return   array
* @access   public
* @since    2005-10-03
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
if (!function_exists('array_change_key_case')) {
    if (!defined('CASE_LOWER')) {
        define('CASE_LOWER', 0);
    }
    if (!defined('CASE_UPPER')) {
        define('CASE_UPPER', 1);
    }
    function array_change_key_case($input, $case = CASE_LOWER)
    {
        $output = array();
        $inputKeys = array_keys($input);
        foreach ($inputKeys as $key) {
            if (CASE_LOWER == $case) {
                $output[strtolower($key)] = $input[$key];
            } else {
                $output[strtoupper($key)] = $input[$key];
            }
        }
        return $output;
    }
}


//
// Functions for backup and SQL security
//

/**
* build_insert()

* This function builds the the queries for the backup
*
* @param    string      query
* @param    string      table name
* @return   array
* @access   public
* @author   Meikel Katzengreis <meikel@katzengreis.com>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
* @since    2003-03-24
*/
function build_insert($query, $table)
{
	global $db;
	if (!$result = $db->query($query)) {
		return;
	}
	$ret = array();
	$ret[] = "\n-- Table: ".$table;
	while ($row = $db->fetch_assoc ($result)) {
		$p1 = array();
		$p2 = array();
		foreach ($row as $key => $val) {
			$p1[] = $key;
            $val = trim($val);
            if ('rights' != $key && is_numeric($val)) {
                $p2[] = $val;
            } else {
                $p2[] = "'".$db->escape_string($val)."'";
            }
		}
		$ret[] = "INSERT INTO ".$table." (".implode(",", $p1).") VALUES (".implode(",", $p2).");";
	}
	return $ret;
}

/**
* alignTablePrefixByPattern()
*
* Align the prefix of the table name used in the PMF backup file,
* from the (old) value of the system upon which the backup was performed
* to the (new) prefix of the system upon which the backup will be restored.
* This alignment will be perfomed ONLY upon those given SQL queries starting
* with the given pattern.
*
* @param    $query                  string
* @param    $start_pattern      string
* @param    $oldvalue               string
* @param    $newvalue              string
* @return   string
* @access   public
* @author   Matteo Scaramuccia <matteo@scaramuccia.com>
*/
function alignTablePrefixByPattern($query, $start_pattern, $oldvalue, $newvalue)
{
    $ret = $query;

    preg_match_all("/^".$start_pattern."\s+(\w+)(\s+|$)/i", $query, $matches);
    if (isset($matches[1][0])) {
        $oldtablefullname = $matches[1][0];
        $newtablefullname = $newvalue.substr($oldtablefullname, strlen($oldvalue));
        $ret = str_replace($oldtablefullname, $newtablefullname, $query);
    }

    return $ret;
}

/**
* alignTablePrefix()
*
* Align the prefix of the table name used in the PMF backup file,
* from the (old) value of the system upon which the backup was performed
* to the (new) prefix of the system upon which the backup will be restored
* This alignment will be performed upon all of the SQL query "patterns"
* provided within the PMF backup file.
*
* @param    $query                  string
* @param    $oldvalue               string
* @param    $newvalue              string
* @return   string
* @access   public
* @author   Matteo Scaramuccia <matteo@scaramuccia.com>
*/
function alignTablePrefix($query, $oldvalue, $newvalue)
{
    // Align DELETE FROM <prefix.tablename>
    $query = alignTablePrefixByPattern($query, "DELETE FROM", $oldvalue, $newvalue);
    // Align INSERT INTO <prefix.tablename>
    $query = alignTablePrefixByPattern($query, "INSERT INTO", $oldvalue, $newvalue);

    return $query;
}

/**
* fixslashes
*
* This function takes care of safely removing slashes
* not really needed for escaping characters on PMF 1.5.3 and above
* (see instead PMF DB population on 1.5.2 and below)
*
* @param    string
* @return   string
* @access   public
* @since    2005-11-28
* @author   Matteo Scaramuccia <matteo@scaramuccia.com>
*/
function fixslashes($text)
{
    $fixedtext = $text;
    // Check if the line must be fixed...
    if (!(false === strpos($fixedtext, "\\"))) {
        // ... and fix it!
        $fixedtext = preg_replace("/(\\\\)([^\w\s\\\\])/", "$2", $fixedtext);
    }

    return $fixedtext;
}


//
// LDAP FUNCTIONS
//

/**
* Returns the user name from REMOTE_USER
*
* @return   string
* @access   public
* @author   Adam Greene <phpmyfaq@skippy.fastmail.fm>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function getShortUserName()
{
    if (isset($_ENV['REMOTE_USER'])) {
        return $_ENV['REMOTE_USER'];
    } elseif (isset($_SERVER['REMOTE_USER'])) {
        return $_SERVER['REMOTE_USER'];
    } else {
        return '';
    }
}

/**
* Returns the full user name from LDAP if available
*
* @return   string
* @access   public
* @author   Adam Greene <phpmyfaq@skippy.fastmail.fm>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function getFullUserName()
{
    global $PMF_CONF;
    if (isset($PMF_CONF['ldap_support']) && $PMF_CONF['ldap_support'] == 'TRUE') {
        global $ldap;
        return $ldap->ldap_getCompleteName(getShortUserName());
    } else {
        return getShortUserName();
    }
}

/**
* Returns the full user name from LDAP if available
*
* @return   string
* @access   public
* @author   Adam Greene <phpmyfaq@skippy.fastmail.fm>
* @author   Thorsten Rinne <thorsten@phpmyfaq.de>
*/
function getEmailAddress()
{
    global $PMF_CONF;
    if (isset($PMF_CONF['ldap_support']) && $PMF_CONF['ldap_support'] == TRUE) {
        global $ldap;
        return $ldap->ldap_getMail(getShortUserName());
    } else {
        return '';
    }
}
