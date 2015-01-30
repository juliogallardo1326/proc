<?php /* ***** Orca Knowledgebase - English Language File ***** */

/* ***************************************************************
* Orca Knowledgebase v2.1b
*  A small and efficient knowledgebase system
* Copyright (C) 2004 GreyWyvern
*
* This program may be distributed under the terms of the GPL
*   - http://www.gnu.org/licenses/gpl.txt
* 
* See the readme.txt file for installation instructions.
*
* If you translate this file into your native language, please
* send me a copy so I can include it in the forum package.  Your
* name will appear in the header of the file you translate :)
*************************************************************** */

$lang['charset'] = "ISO-8859-1";
setlocale(LC_TIME, array("en_EN", "enc"));
$pageEncoding = 2;  // Final Page Encoding
                    //  1 - UTF-8
                    //  2 - ISO-8859-1
                    //  3 - Other

$sData['dateformat'] = "%b %d, %Y  %X";  // see http://www.php.net/strftime


/* ************************************************************ */
/* ***** User GUI ********************************************* */
/* ************************************************************ */

$lang['term1'] = "Search";
$lang['term2'] = "Clear";
$lang['term3'] = "Go to Question ID";
$lang['term4'] = "All Categories";
$lang['term5'] = "All Subcategories";
$lang['term6'] = "Go";
$lang['term7'] = "Updated";
$lang['term8'] = "Category";
$lang['term9'] = "Subcategory";
$lang['terma'] = "Question ID %s does not exist";
$lang['termb'] = "Back";
$lang['termc'] = "<strong>%d</strong> Result(s)";
$lang['termd'] = "Searching in: %s";
$lang['terme'] = "Displaying: %s";
$lang['termf'] = "Question";
$lang['termg'] = "No results found";
$lang['termh'] = "Previous";
$lang['termi'] = "Showing Questions %1\$d to %2\$d";
$lang['termj'] = "Next";
$lang['termk'] = "Previous page";
$lang['terml'] = "Next page";
$lang['termm'] = "Couldn't find what you were looking for?";
$lang['termn'] = "Send a question to the knowledgebase administrator";
$lang['termo'] = "Your question";
$lang['termp'] = "Your email address";
$lang['termq'] = "Thank you!";
$lang['termr'] = "Your question has been sent";
$lang['terms'] = "Solution";
$lang['termt'] = "Filter";


/* ***** Email ************************************************ */
$lang['email1']['subject'] = "%s - Submitted question";
$lang['email1']['message'] = <<<ORCA
<%1\$s> asked the following question via %2\$s:

%3\$s

____________________________________________________________
%2\$s
ORCA;


/* ************************************************************ */
/* ***** Control Panel **************************************** */
/* ************************************************************ */
$lang['misc1'] = "Order list by %s";
$lang['misc2'] = " - Control Panel";
$lang['misc3'] = "Knowledgebase Control Panel";
$lang['misc4'] = "You are not logged in";
$lang['misc5'] = "Login";
$lang['misc6'] = "You are logged in";
$lang['misc8'] = "Logout";
$lang['misc9'] = "Continue Editing the Knowledgebase";
$lang['misca'] = "Return to Editing Mode";
$lang['miscb'] = "File Upload Manager";
$lang['miscc'] = "Upload Accessory Files";
$lang['miscd'] = "Errors Were Encountered";
$lang['misce'] = "The previous action resulted in the following error(s):";
$lang['miscf'] = "Refresh this page";
$lang['miscg'] = "Clear Error";


/* ***** File Upload Manager ********************************** */
$lang['misch'] = "Cannot access file directory. please chmod directory %s with value 777 (xrw-xrw-xrw)";
$lang['misci'] = "Refresh";
$lang['miscj'] = "File:";
$lang['misck'] = "Storage Directory:";
$lang['miscl'] = "File types allowed:";
$lang['miscm'] = "File size limit:";
$lang['miscn'] = "Upload";
$lang['misco'] = "Clear";
$lang['miscp'] = "Filename";
$lang['miscq'] = "File Type";
$lang['miscr'] = "File Size";
$lang['miscs'] = "Delete";
$lang['misct'] = "Delete file";
$lang['miscu'] = "Are you sure you want to delete this file?";
$lang['miscv'] = "Back / Refresh";


/* ***** Main Controls **************************************** */
$lang['miscw'] = "Category Controls";
$lang['miscx'] = "Add Category";
$lang['miscy'] = "Add";
$lang['miscz'] = "Select Working Category";
$lang['mis_1'] = "Select a Category";
$lang['mis_2'] = "None";
$lang['mis_3'] = "Select";
$lang['mis_4'] = "Rename Category";
$lang['mis_5'] = "New Name";
$lang['mis_6'] = "Rename";
$lang['mis_7'] = "Delete Category";
$lang['mis_8'] = "Delete";
$lang['mis_9'] = "Subcategory Controls";
$lang['mis_a'] = "Add Subcategory";
$lang['mis_b'] = "Select Working Subcategory";
$lang['mis_c'] = "Select a Subcategory";
$lang['mis_d'] = "Rename Subcategory";
$lang['mis_e'] = "Delete Subcategory";
$lang['mis_f'] = "Question Controls";
$lang['mis_g'] = "You have changed this Question's Category";
$lang['mis_h'] = "To complete this edit, please select or create a new Subcategory for this Question";
$lang['mis_i'] = "Subcategory";
$lang['mis_j'] = "New Subcategory";
$lang['mis_k'] = "Cancel Question Edit";
$lang['mis_l'] = "Cancel";
$lang['mis_m'] = "Finish Editing";
$lang['mis_n'] = "Category";
$lang['mis_o'] = "Online";
$lang['mis_p'] = "Question";
$lang['mis_q'] = "Answer<br /><small><em>HTML Permitted</em></small>";
$lang['mis_r'] = "Select file";
$lang['mis_s'] = "Add Link";
$lang['mis_t'] = "Add Image";
$lang['mis_u'] = "Keywords<br /><small><em>Separated by spaces</em></small>";
$lang['mis_v'] = "Cancel Question Addition";
$lang['mis_w'] = "Question ID #";
$lang['mis_x'] = "Last Updated";
$lang['mis_y'] = "Hits";
$lang['mis_z'] = "Add Question in this Subcategory";
$lang['mi__1'] = "Add Question in this Category";
$lang['mi__2'] = "Delete Question by ID #";
$lang['mi__3'] = "Edit Question by ID #";
$lang['mi__4'] = "Edit";


/* ***** Database List Display ******************************** */
$lang['mi__5'] = "Question Database";
$lang['mi__6'] = "Displaying:";
$lang['mi__7'] = "All Questions";
$lang['mi__8'] = "Q ID";
$lang['mi__9'] = "E";	// Short form for Edit


/* ***** Pagination/Footer ************************************ */
$lang['mi__a'] = "Previous page";
$lang['mi__b'] = "Previous";
$lang['mi__c'] = "Showing Questions %1\$d to %2\$d";
$lang['mi__d'] = "Next page";
$lang['mi__e'] = "Next";
$lang['mi__f'] = "** There are no Questions in this Subcategory **";
$lang['mi__g'] = "** There are no Questions in this Category **";
$lang['mi__h'] = "** There are no questions in the database **";


/* ***** Error Messages *************************************** */
$lang['err1'] = "File type not allowed (%s), or no file to upload";
$lang['err2'] = "Files larger than %1\$d BYTES (%2\$d KB) not permitted";
$lang['err3'] = "Filename already exists";
$lang['err4'] = "Upload failed.  You must chmod the target dir to 777";
$lang['err5'] = "Invalid Characters in Category Name";
$lang['err6'] = "Category Already Exists";
$lang['err7'] = "Invalid Characters in Subcategory Name";
$lang['err8'] = "Subcategory Already Exists";
$lang['err9'] = "No Subcategory Name Entered";
$lang['erra'] = "Question Already Exists";
$lang['errb'] = "Question ID <strong>%s</strong> does not exist";
$lang['errc'] = "No Subcategory Name Entered";
$lang['errd'] = "No Question Entered";
$lang['erre'] = "No Answer Entered";


while (list($key, $value) = each($lang)) {
  if (!is_array($value) && $key != "charset") {
    if ($pageEncoding == 3) {
      $lang[$key] = htmlentities($value, ENT_COMPAT, "ISO-8859-1");
      $lang[$key] = str_replace("&gt;", ">", $lang[$key]);
      $lang[$key] = str_replace("&lt;", "<", $lang[$key]);
    } else if ($pageEncoding == 1) $lang[$key] = utf8_encode($value);
  }
}

?>