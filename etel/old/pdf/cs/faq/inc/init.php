<?php
/**
* $Id: init.php,v 1.1.2.17.2.6 2006/04/27 21:34:12 matteo Exp $
*
* Some functions
*
* @author       Johann-Peter Hartmann <hartmann@mayflower.de>
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Stefan Esser <sesser@php.net>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @since        2005-09-24
* @copyright    (c) 2005-2006 phpMyFAQ Team
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

//debug mode:
// - false	debug mode disabled
// - true	debug mode enabled
define("DEBUG", false);

if (DEBUG) {
    error_reporting(E_ALL);
}

// Needed for PMF_Init::isASupportedLanguage()
require_once('constants.php');

class PMF_Init
{
    /**
    * The accepted language of the user agent
    *
    * @var  string
    * @see
    */
    var $acceptedLanguage;

    /**
    * The current language
    *
    * @var  string
    * @see
    */
    var $language;



    /**
    * PMF_Init()
    *
    * Constructor
    */
    function PMF_Init()
    {
    }

	/**
	* cleanRequest
    *
	* Cleans the request environment from global variables, unescaped slashes and xss in the request string.
    *
	* @return   void
    * @access   public
    * @author   Johann-Peter Hartmann <hartmann@mayflower.de>
	*/
	function cleanRequest()
    {
        $_SERVER['PHP_SELF'] = str_replace('%2F', '/', rawurlencode($_SERVER['PHP_SELF']));
		// remove global registered variables to avoid injections
		if (ini_get('register_globals')) {
			PMF_Init::unregisterGlobalVariables();
		}
		// clean external variables
		$externals = array('_REQUEST', '_GET', '_POST', '_COOKIE');
		foreach ($externals as $external) {
			if (isset($GLOBALS[$external]) && is_array($GLOBALS[$external])) {

				// first clean XSS issues
				$newvalues = $GLOBALS[trim($external)];
				$newvalues = PMF_Init::removeXSSGPC($newvalues);

				// then apply magic quotes
				$newvalues = PMF_Init::removeMagicQuotesGPC($newvalues);

				// clean old array and insert cleaned data
				foreach (array_keys($GLOBALS[$external]) as $key) unset($GLOBALS[$external][$key]);
				foreach (array_keys($newvalues) as $key) $GLOBALS[$external][$key] = $newvalues[$key];
			}
		}
	}


    /**
    * getUserAgentLanguage()
    *
    * Gets the accepted language from the user agent
    *
    * @return   void
    * @access   private
    * @author   Thorsten Rinne <thorsten@phpmyfaq.de>
    * @author   Matteo Scaramuccia <matteo@scaramuccia.com>
    */
    function getUserAgentLanguage()
    {
        $this->acceptedLanguage = '';

        // $_SERVER['HTTP_ACCEPT_LANGUAGE'] could be like the text below:
        // it,pt-br;q=0.8,en-us;q=0.5,en;q=0.3
        // TODO: (ENH) get an array of accepted languages and cycle through it in PMF_Init::setLanguage
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            // ISO Language Codes, 2-letters: ISO 639-1, <Country tag>[-<Country subtag>]
            // Simplified language syntax detection: xx[-yy]
            preg_match("/([a-z\-]+)/i", trim($_SERVER['HTTP_ACCEPT_LANGUAGE']), $matches);
            if (isset($matches[1])) {
                $this->acceptedLanguage = $matches[1];
            }
        }
    }

    /**
    * isASupportedLanguage()
    *
    * True if the language is supported by the current phpMyFAQ installation
    *
    * @param    string  $langcode
    * @return   bool
    * @access   public
    * @author   Matteo scaramuccia <matteo@scaramuccia.com>
    */
    function isASupportedLanguage($langcode)
    {
        global $languageCodes;

        return isset($languageCodes[strtoupper($langcode)]);
    }

    /**
    * setLanguage()
    *
    * Sets the current language for phpMyFAQ user session
    *
    * @param    bool    $config_detection
    * @param    string  $config_language
    * @return   string  $language
    * @access   public
    * @author   Thorsten Rinne <rinne@mayflower.de>
    * @author   Matteo scaramuccia <matteo@scaramuccia.com>
    */
    function setLanguage($config_detection, $config_language)
    {
        $_lang = array();
        PMF_Init::getUserAgentLanguage();

        // Get language from: _POST, _GET, _COOKIE, phpMyFAQ configuration and
        //                    the automatic language detection
        if (isset($_POST['language']) && PMF_Init::isASupportedLanguage($_POST['language']) ) {
            $_lang['post'] = $_POST['language'];
        }
        // Get the user language
        if (isset($_GET['lang']) && PMF_Init::isASupportedLanguage($_GET['lang']) ) {
            $_lang['get'] = $_GET['lang'];
        }
        // Get the faq record language
        if (isset($_GET['artlang']) && PMF_Init::isASupportedLanguage($_GET['artlang']) ) {
            $_lang['get'] = $_GET['artlang'];
        }
        // Get the language from the cookie
        if (isset($_COOKIE['pmf_lang']) && PMF_Init::isASupportedLanguage($_COOKIE['pmf_lang']) ) {
            $_lang['cookie'] = $_COOKIE['pmf_lang'];
        }
        // Get the language from the config
        if (isset($config_language)) {
            $confLangCode = str_replace(array("language_", ".php"), "", $config_language);
            if (PMF_Init::isASupportedLanguage($confLangCode) ) {
                $_lang['config'] = $confLangCode;
            }
        }
        // Detect the browser's language
        if ((true === $config_detection) && PMF_Init::isASupportedLanguage($this->acceptedLanguage) ) {
            $_lang['detection'] = $this->acceptedLanguage;
        }

        // Select the language
        if (isset($_lang['post'])) {
            $this->language = $_lang['post'];
            unset($_lang);
            setcookie('pmf_lang', $this->language, time() + 3600);
        } elseif (isset($_lang['get'])) {
            $this->language = $_lang['get'];
        } elseif (isset($_lang['cookie'])) {
            $this->language = $_lang['cookie'];
            unset($_lang);
        } elseif (isset($_lang['config'])) {
            $this->language = $_lang['config'];
            unset($_lang);
            setcookie('pmf_lang', $this->language, time() + 3600);
        } elseif (isset($_lang['detection'])) {
            $this->language = $_lang['detection'];
            unset($_lang);
            setcookie('pmf_lang', $this->language, time() + 3600);
        } else {
            $this->language = 'en'; // just a fallback
        }

        return $this->language;
    }

    /**
    * unregisterGlobalVariables
    *
    * This function deregisters the global variables when register_globals = on
    *
    * @return   void
    * @access   private
    * @author   Stefan Esser <sesser@php.net>
    */
    function unregisterGlobalVariables()
    {
        if (!ini_get('register_globals')) {
            return;
        }

        if (isset($_REQUEST['GLOBALS']) || isset($_FILES['GLOBALS'])) {
            die('GLOBALS overwrite attempt detected' );
        }

        $noUnset = array('GLOBALS', '_GET', '_POST', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        $input = array_merge($_GET, $_POST, $_COOKIE, $_SERVER, $_ENV, $_FILES, isset($_SESSION) && is_array($_SESSION) ? $_SESSION : array());
        foreach (array_keys($input) as $k) {
            if (!in_array($k, $noUnset) && isset($GLOBALS[$k])) {
                unset($GLOBALS[$k]);
            }
        }
    }

    /**
    * removeMagicQuotesGPC
    *
    * This function removess the magic quotes if they enabled
    *
    * @param    array
    * @return   array
    * @access   private
    * @author   Thorsten Rinne <thorsten@phpmyfaq.de>
    */
    function removeMagicQuotesGPC($data)
    {
        if (get_magic_quotes_gpc()) {
            $addedData = array();
            foreach ($data as $key => $val) {
                $key = addslashes($key);
                if (is_array($val)) {
                    $addedData[$key] = PMF_Init::removeMagicQuotesGPC($val);
                } else {
                    $addedData[$key] = $val;
                }
            }
            return $addedData;
        }
        return $data;
    }

    /**
    * Cleans a html string from some xss issues
    *
    * @param       string  $string
    * @return      string
    * @access      private
    * @author      Christian Stocker <chregu@bitflux.ch>
    * @copyright   Bitflux (c) 2001-2005 Bitflux GmbH
    */
    function basicXSSClean($string)
    {
        if (strpos($string, '\0') !== false) {
            return null;
        }
        if (get_magic_quotes_gpc()) {
    		$string = stripslashes($string);
    	}
    	$string = str_replace(array("&amp;","&lt;","&gt;"),array("&amp;amp;","&amp;lt;","&amp;gt;",),$string);
    	// fix &entitiy\n;
    	$string = preg_replace('#(&\#*\w+)[\x00-\x20]+;#u',"$1;",$string);
    	$string = preg_replace('#(&\#x*)([0-9A-F]+);*#iu',"$1$2;",$string);
        $string = str_replace('\0', '', $string);
    	$string = html_entity_decode(trim($string), ENT_COMPAT);
    	// remove any attribute starting with "on" or xmlns
    	$string = preg_replace('#(<[^>]+[\x00-\x20\"\'])(on|xmlns)[^>]*>#iUu',"$1>",$string);
    	// remove javascript: and vbscript: protocol
    	$string = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*)[\\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu','$1=$2nojavascript...',$string);
    	$string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'\"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iUu','$1=$2novbscript...',$string);
    	//<span style="width: expression(alert('Ping!'));"></span>
    	// only works in ie...
    	$string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*expression[\x00-\x20]*\([^>]*>#iU',"$1>",$string);
    	$string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*behaviour[\x00-\x20]*\([^>]*>#iU',"$1>",$string);
    	$string = preg_replace('#(<[^>]+)style[\x00-\x20]*=[\x00-\x20]*([\`\'\"]*).*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*>#iUu',"$1>",$string);
    	//remove namespaced elements (we do not need them...)
    	$string = preg_replace('#</*\w+:\w[^>]*>#i',"",$string);
    	//remove really unwanted tags
    	do {
    		$oldstring = $string;
    		$string = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i',"",$string);
    	} while ($oldstring != $string);
    	return $string;
    }

    /**
    * removeXSSGPC
    *
    * Removes xss from an array
    *
    * @param    array   $data
    * @return   array
    * @acces    private
    * @author   Thorsten Rinne <thorsten@phpmyfaq.de>
    * @author   Johann-Peter Hartmann <hartmann@mayflower.de>
    */
    function removeXSSGPC($data) {
        $cleanData = array();
        foreach ($data as $key => $val) {
            $key = PMF_Init::basicXSSClean($key);
            if (is_array($val)) {
            	$cleanData[$key] = PMF_Init::removeXSSGPC($val);
            } else {
            	$cleanData[$key] = PMF_Init::basicXSSClean($val);
            }
        }
        return $cleanData;
    }

}
?>
