<?php
/**
* $Id: contact.php,v 1.3.2.4.2.2 2006/04/25 12:07:24 matteo Exp $
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2002-09-16
* @copyright    (c) 2001-2006 phpMyFAQ Team
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

Tracking('contact', 0);

$captcha = new PMF_Captcha($db, $sids, $pmf->language, $_SERVER['HTTP_USER_AGENT'], $_SERVER['REMOTE_ADDR']);

if (isset($_GET['gen'])) {
    $captcha->showCaptchaImg();
    exit;
}

$tpl->processTemplate ('writeContent', array(
                        'msgContact' => $PMF_LANG['msgContact'],
                        'msgContactOwnText' => unhtmlentities($PMF_CONF['msgContactOwnText']),
                        'msgContactEMail' => $PMF_LANG['msgContactEMail'],
                        'writeSendAdress' => $_SERVER['PHP_SELF'].'?'.$sids.'action=sendmail',
                        'msgNewContentName' => $PMF_LANG['msgNewContentName'],
                        'msgNewContentMail' => $PMF_LANG['msgNewContentMail'],
                        'defaultContentMail' => getEmailAddress(),
                        'defaultContentName' => getFullUserName(),
                        'msgMessage' => $PMF_LANG['msgMessage'],
                        'msgS2FButton' => $PMF_LANG['msgS2FButton'],
                        'version' => $PMF_CONF['version'],
                        'captchaFieldset' => printCaptchaFieldset($PMF_LANG['msgCaptcha'], $captcha->printCaptcha('contact'), $captcha->caplength)
                        ));

$tpl->includeTemplate('writeContent', 'index');
?>
