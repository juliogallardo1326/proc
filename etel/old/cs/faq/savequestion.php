<?php
/**
* $Id: savequestion.php,v 1.11.2.9.2.8 2006/04/25 12:07:24 matteo Exp $
*
* @author           Thorsten Rinne <thorsten@phpmyfaq.de>
* @author           David Saez Padros <david@ols.es>
* @since            2002-09-17
* @copyright        (c) 2001-2006 phpMyFAQ Team
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

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

$captcha = new PMF_Captcha($db, $sids, $pmf->language, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);

if (    isset($_POST['username']) && $_POST['username'] != ''
     && isset($_POST['usermail']) && checkEmail($_POST['usermail'])
     && isset($_POST['content']) && $_POST['content'] != ''
     && IPCheck($_SERVER['REMOTE_ADDR'])
     && checkBannedWord(htmlspecialchars(strip_tags($_POST['content'])))
     && checkCaptchaCode() ) {
    if (isset($_POST['try_search'])) {
        $suchbegriff = strip_tags($_POST['content']);
        $printResult = searchEngine($suchbegriff, $numr);
        echo $numr;
    } else {
        $numr = 0;
    }

    if ($numr == 0) {

        $cat = new category;
        $categories = $cat->getAllCategories();
        $usermail = $db->escape_string($IDN->encode($_POST['usermail']));
        $username = $db->escape_string(strip_tags($_POST['username']));
        $selected_category = intval($_POST['rubrik']);

        list($user, $host) = explode("@", $usermail);
        if (checkEmail($usermail)) {
            $datum   = date("YmdHis");
            $content = strip_tags($_POST['content']);

            $result = $db->query("INSERT INTO ".SQLPREFIX."faqfragen (id, ask_username, ask_usermail, ask_rubrik, ask_content, ask_date) VALUES (".$db->nextID(SQLPREFIX."faqfragen", "id").", '".$db->escape_string($username)."', '".$db->escape_string($usermail)."', ".$selected_category.", '".$db->escape_string($content)."', '".$datum."')");

            $questionMail = "User: ".$username.", mailto:".$usermail."\n".$PMF_LANG["msgCategory"].":  ".$categories[$selected_category]["name"]."\n\n".wordwrap($content, 72);

            $additional_header = array();
            $additional_header[] = 'MIME-Version: 1.0';
            $additional_header[] = 'Content-Type: text/plain; charset='. $PMF_LANG['metaCharset'];
            if (strtolower($PMF_LANG['metaCharset']) == 'utf-8') {
                $additional_header[] = 'Content-Transfer-Encoding: 8bit';
            }
            $additional_header[] = 'From: '.'<'.$IDN->encode($usermail).'>';
            $body = strip_tags($questionMail);
            $body = str_replace(array("\r\n", "\r", "\n"), "\n", $body);
            $body = str_replace(array("\r\n", "\r", "\n"), "\n", $body);
            if (strstr(PHP_OS, 'WIN') !== NULL) {
                // if windows, cr must "\r\n". if other must "\n".
                $body = str_replace("\n", "\r\n", $body);
            }
            mail($IDN->encode($PMF_CONF['adminmail']), $PMF_CONF['title'], $body, implode("\r\n", $additional_header));

            $tpl->processTemplate ("writeContent", array(
                    "msgQuestion" => $PMF_LANG["msgQuestion"],
                    "Message" => $PMF_LANG["msgAskThx4Mail"],
                    ));
        } else {
            $tpl->processTemplate ("writeContent", array(
                    "msgQuestion" => $PMF_LANG["msgQuestion"],
                    "Message" => $PMF_LANG["err_noMailAdress"],
                    ));
        }

    } else {

        $tpl->templates['writeContent'] = $tpl->readTemplate('template/asksearch.tpl');

		$tpl->processTemplate ('writeContent', array(
			'msgQuestion' => $PMF_LANG["msgQuestion"],
            'printResult' => $printResult,
            'msgAskYourQuestion' => $PMF_LANG['msgAskYourQuestion'],
            'msgContent' => $_POST['content'],
            'postUsername' => urlencode($_REQUEST['username']),
            'postUsermail' => urlencode($_REQUEST['usermail']),
            'postRubrik' => urlencode($_REQUEST['rubrik']),
            'postContent' => urlencode($_REQUEST['content']),
            'writeSendAdress' => $_SERVER['PHP_SELF'].'?'.$sids.'action=savequestion',
			));
    }
} else {
	if (IPCheck($_SERVER["REMOTE_ADDR"]) == FALSE) {
		$tpl->processTemplate ("writeContent", array(
				"msgQuestion" => $PMF_LANG["msgQuestion"],
				"Message" => $PMF_LANG["err_bannedIP"],
				));
	} else {
		$tpl->processTemplate ("writeContent", array(
				"msgQuestion" => $PMF_LANG["msgQuestion"],
				"Message" => $PMF_LANG["err_SaveQuestion"],
				));
    }
}

$tpl->includeTemplate("writeContent", "index");
?>
