<?php
/**
* $Id: footer.php,v 1.2.2.5.2.9 2006/05/09 05:42:30 thorstenr Exp $
*
* Footer of the admin area
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @since        2003-02-26
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

if (!defined('IS_VALID_PHPMYFAQ_ADMIN')) {
    header('Location: http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}
?>
</div>

<div class="clearing"></div>

<!-- Footer -->
<div>
    <div id="footer">
        <div>Etelegate.net'</div>
    </div>
</div>
<?php
if (isset($auth)) {
    $pmf_hash = base64_encode($auth_user.','.$auth_pass);
?>
<iframe id="keepPMFSessionAlive" src="session.keepalive.php<?php print $linkext; ?>&amp;lang=<?php print $LANGCODE; ?>&amp;hash=<?php print $pmf_hash; ?>" frameBorder="no" width="0" height="0"></iframe>
<?php
}
if (isset($_REQUEST["aktion"]) && ($_REQUEST["aktion"] == "editentry" || $_REQUEST["aktion"] == "news" || $_REQUEST["aktion"] == "editpreview" || $_REQUEST["aktion"] == "takequestion") && !emptyTable(SQLPREFIX."faqcategories")) {
?>
<style type="text/css"> @import url(editor/htmlarea.css); </style>
<script type="text/javascript">
//<![CDATA[
_editor_url = "editor";
_editor_lang = "en";
//]]>
</script>
<script type="text/javascript" src="editor/htmlarea.js"></script>
<script type="text/javascript" src="editor/plugins/ImageManager/image-manager.js"></script>
<script type="text/javascript">
//<![CDATA[
    HTMLArea.init();
    HTMLArea.loadPlugin('ImageManager');
    HTMLArea.onload = function() {
    var editor = new HTMLArea('content');
    var config = new HTMLArea.Config();
    var phpMyFAQLinks   = {
<?php
    $output = "'Include internal links' : '',\n";
    $result = $db->query('SELECT '.SQLPREFIX.'faqdata.id AS id, '.SQLPREFIX.'faqdata.lang AS lang, '.SQLPREFIX.'faqcategoryrelations.category_id AS category_id, '.SQLPREFIX.'faqdata.thema AS thema FROM '.SQLPREFIX.'faqdata LEFT JOIN '.SQLPREFIX.'faqcategoryrelations ON '.SQLPREFIX.'faqdata.id = '.SQLPREFIX.'faqcategoryrelations.record_id AND '.SQLPREFIX.'faqdata.lang = '.SQLPREFIX.'faqcategoryrelations.record_lang ORDER BY '.SQLPREFIX.'faqcategoryrelations.category_id, '.SQLPREFIX.'faqdata.id');
    while ($row = $db->fetch_object($result)) {
        $_title = makeShorterText(addslashes(PMF_htmlentities(str_replace(array("\n", "\r", "\r\n"), "", $row->thema), ENT_NOQUOTES, $PMF_LANG['metaCharset'])), 8);
        $output .= sprintf("'%s' : '<a href=\"index.php?action=artikel&amp;cat=%d&amp;id=%d&amp;artlang=%s\">%s<\/a>',\n", $_title, $row->category_id, $row->id, $row->lang, $_title);
    }
    $output = substr($output, 0, -2);
    print $output;
?>
        };

    var internalLinks = {
        id      :   'internalLinks',
        tooltip :   'internal Link',
        options :   phpMyFAQLinks,
        action  :   function(editor)
        {
            var elem = editor._toolbarObjects[this.id].element;
            editor.insertHTML(elem.value);
            elem.selectedIndex = 0;
            },
        refresh :   function(editor) { }
    };

    config.registerDropdown(internalLinks);

    config.toolbar = [ [ "fontname", "space", "fontsize", "space", "formatblock", "space", "bold", "italic", "underline", "strikethrough", "separator", "subscript", "superscript", "separator", "copy", "cut", "paste" ], [ "undo", "redo", "space", "justifyleft", "justifycenter", "justifyright", "justifyfull", "separator", "lefttoright", "righttoleft", "separator", "orderedlist", "unorderedlist", "outdent", "indent", "separator", "forecolor", "hilitecolor", "separator", "inserthorizontalrule", "createlink", "insertimage", "inserttable", "htmlmode", "space", "removeformat", "killword" ], [ "internalLinks" ] ];

    config.formatblock = {
        "Heading 3"     : "h3",
        "Heading 4"     : "h4",
        "Heading 5"     : "h5",
        "Heading 6"     : "h6",
        "Normal"        : "p",
        "Address"       : "address",
        "Formatted"     : "pre",
        "PHP code"      : "code"
        };

    config.fontname = {
        "&mdash; font &mdash;"  : '',
        "Bitsream Vera Sans"    : '"Bitstream Vera Sans", verdana, tahoma, sans-serif',
        "Trebuchet MS"          : '"Trebuchet MS", verdana, tahoma, sans-serif',
        "Verdana"               : 'verdana, arial, helvetica, sans-serif',
        "Tahoma"                : 'tahoma, arial, helvetica, sans-serif',
        "Arial"                 : 'arial, helvetica, sans-serif',
        "Courier New"           : 'courier new, courier, monospace',
        "Times New Roman"       : 'times new roman, times, serif',
        "Georgia"               : 'georgia, times new roman, times, serif',
        "Impact"                : 'impact',
        "WingDings"             : 'wingdings'
        };

    config.width        = '550px';
    config.height       = '400px';
    config.pageStyle    = 'html, body { font-family: "Bitstream Vera Sans", "Trebuchet MS", Verdana, Tahoma, sans-serif; font-size: 12px; }';
    HTMLArea.replace('content', config);
    }
//]]>
</script>
<?php
}
?>
</body>
</html>
