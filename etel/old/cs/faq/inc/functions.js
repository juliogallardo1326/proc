/**
* $Id: functions.js,v 1.2.2.5.2.1 2006/03/04 10:45:41 thorstenr Exp $
*
* Some JavaScript functions used in the admin backend
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Periklis Tsirakidis <tsirakidis@phpdevel.de>
* @author       Matteo Scaramuccia <matteo@scaramuccia.com>
* @since        2003-11-13
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

function Picture(pic,title,width,height)
{
    popup = window.open(pic, title, 'width='+width+', height='+height+', toolbar=no, directories=no, status=no, scrollbars=no, resizable=yes, menubar=no');
    popup.focus();
}

function checkAll(checkBox)
{
    var v = checkBox.checked;
    var f = checkBox.form;
    for (var i = 0; i < f.elements.length; i++) {
        if (f.elements[i].type == "checkbox") {
            f.elements[i].checked = v;
            }
        }
}

function addEngine(uri, name, ext, cat)
{
    if ((typeof window.sidebar == "object") && (typeof window.sidebar.addSearchEngine == "function")) {
        window.sidebar.addSearchEngine(uri+"/"+name+".src", uri+"/images/"+name+"."+ext, name, cat);
    } else {
        alert('Mozilla Firefox, Mozilla or Netscape 6 or later is needed to install the search plugin!');
    }
}

function focusOnUsernameField()
{
    if (document.forms[0] && document.forms[0].elements["faqusername"]) {
        document.forms[0].elements["faqusername"].focus();
    }
}

/**
 * showhideCategory()
 *
 * Displays or hides a div block
 *
 * @param    string
 * @return   void
 * @access   public
 * @since    2006-03-04
 * @author   Thorsten Rinne <thorsten@phpmyfaq.de>
 */
function showhideCategory(id)
{
    if (document.getElementById(id).style.display == 'none') {
        document.getElementById(id).style.display = 'block';
    } else {
        document.getElementById(id).style.display = 'none';
    }
}
