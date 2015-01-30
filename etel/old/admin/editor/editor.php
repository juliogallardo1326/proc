<?php
	include("../../includes/dbconnection.php");
	$ipage= (isset($HTTP_GET_VARS["page"])?quote_smart($HTTP_GET_VARS["page"]):"");
	if($ipage=="") {
		$iTemplateId = (isset($HTTP_GET_VARS["id"])?quote_smart($HTTP_GET_VARS["id"]):"");
		$strContent = "";
		$qrySelect = "select template_content from cs_mailtemplate where template_id=".$iTemplateId;
		$rstSelect = mysql_query($qrySelect,$cnn_cs);
		if(mysql_num_rows($rstSelect)>0){
			$strContent = mysql_result($rstSelect,0,0);
			$strContent = str_replace("\"", "\'", $strContent); 
			$strContent = str_replace("\r\n", "\\r\\n", $strContent); 
			
		}
	}
if($ipage=="color") {

	?>
		
<html>
<head>
 <title>Color</title>
 <style type='text/css'>
     body 
	 {
	  	margin-left:15; 
		font-family:Verdana; 
		font-size:12; 
		background:threedface
	}
     .colorCells
	 {
	 	cursor:hand
	 }
     </style>
     <script language='JavaScript'>
     function colorSelect(color){
      window.returnValue = color;
      window.close();
	  }
     </script>
     </head>
     <body topmargin='0' leftmargin='0'>
     <table align='center' border='1' cellspacing='2' cellpadding='3'>
      <tr class='colorCells'>
      <td bgcolor='black' onClick="colorSelect('black');">&nbsp;</td>
        <td bgcolor='white' onClick="colorSelect('white');">&nbsp;</td>
      <td bgcolor='green' onClick="colorSelect('green');">&nbsp;</td>
      <td bgcolor='maroon' onClick="colorSelect('maroon');">&nbsp;</td>
      <td bgcolor='olive' onClick="colorSelect('olive');">&nbsp;</td>
       <td bgcolor='navy' onClick="colorSelect('navy');">&nbsp;</td>
      <td bgcolor='gray' onClick="colorSelect('gray');">&nbsp;</td>
      </tr>
      <tr class='colorCells'>
      <td bgcolor='lime' onClick="colorSelect('lime');">&nbsp;</td>
     <td bgcolor='aqua' onClick="colorSelect('aqua');">&nbsp;</td>
      <td bgcolor='pink' onClick="colorSelect('pink');">&nbsp;</td>
      <td bgcolor='silver' onClick="colorSelect('silver');">&nbsp;</td>
     <td bgcolor='red' onClick="colorSelect('red');">&nbsp;</td>
      <td bgcolor='blue' onClick="colorSelect('blue');">&nbsp;</td>
       <td bgcolor='teal' onClick="colorSelect('teal');">&nbsp;</td>
      </tr>
     </table>
     </body>
     </html>
<?php
}
if($ipage=="table") {
?>    <html> 
	  <head>
      <style type='text/css'>
        body   {background:threedface}
      </style>
	  <script>
      function getInfoAndUpdate()
	  {
         if (isNaN(formNumRows.value))
		 {
            alert ("The rows field can only contain numbers");
			return;
		 }
         else if(isNaN(formNumCols.value))
		 {
            alert ("The colums field can only contain numbers");
			return;
		 }
         var callerWindowObj = dialogArguments;
         callerWindowObj.rtNumRows = formNumRows.value;
         callerWindowObj.rtNumCols = formNumCols.value;
         callerWindowObj.rtTblAlign = formTblAlign.value;
         callerWindowObj.rtTblWidth = formTblWidth.value;
         callerWindowObj.createTable();
         window.close();}

      </script>
	  
      <title>Table Properties</title>
      </head>
      <body>
      <table align='center' border='0' cellpadding='0'cellspacing='0'width='100%'>
        <tr>
          <td width='50%'>Number of rows:</td>
          <td width='50%'>
            <input id='formNumRows' size='2' value='1'>
          </td>
        </tr>
        <tr>
          <td width='50%'>Number of colums:</td>
          <td width='50%'>
            <input id='formNumCols' size='2' value='1'>
          </td>
        </tr>
        <tr>
          <td width='50%'>Table Alignment:</td>
          <td width='50%'>
            <select id='formTblAlign' size='1'>
              <option value='left'>left</option>
              <option value='center' selected>center</option>
              <option value='right'>right</option>
            </select>
          </td>
        </tr>
        <tr>
          <td width='50%'>Table Width:</td>
          <td width='50%'>
                <select id='formTblWidth' size='1'>
      	        <option value='25%'>25%</option>
      	        <option value='50%' >50%</option>
      	        <option value='75%'>75%</option>
      	        <option value='100%' selected>100%</option>
            </select>
          </td>
        </tr>
      </table>
      <br>
      <table border='0' cellpadding='0'cellspacing='0'width='100%'>
        <tr>
          <td width='50%' align='right'>
            <input value='Ok' type=button onclick='getInfoAndUpdate();'>&nbsp;
          </td>
          <td width='50%' align='left'>
            &nbsp;<input value='Cancel' type=button onclick='window.close();'>
          </td>
        </tr>
      </table>
      </body>
      </html>
<?
}if($ipage=="") {
?>

<html>
<head>
<style type='text/css'>
TABLE#window{ background-color:threedface; padding:1px; color:menutext; border-width:1px; border-style:solid; border-color:threedhighlight threedshadow threedshadow threedhighlight; }
TABLE#toolBar0{ background-color:threedface; padding:1px; color:menutext; border-width:1px; border-style:solid; border-color:threedhighlight threedshadow threedshadow threedhighlight; }
TABLE#toolBar1{ background-color:threedface; padding:1px; color:menutext; border-width:1px; border-style:solid; border-color:threedhighlight threedshadow threedshadow threedhighlight; }
.btnCtrl{ height:18; border-left: threedface 1px solid; border-right: threedface 1px solid; border-top: threedface 1px solid; border-bottom: threedface 1px solid; }
</style>

<script language='JavaScript'>
//----- Editor Initialization ------
window.onerror = handleErrors;
function handleErrors(){
   //----- Used For Browsers That Don't Want To Behave -----
   return true;}
var viewMode = 1;
function loadEditor(){
  //----- Modify User Controls -----
  showWYSIWYGCtrl.style.display = 'none';
  hr6Ctrl.style.display = 'none';
  editbox.document.designMode="On";
  var datVal = "<?= $strContent ?>"
  editbox.document.open();
  editbox.document.write(datVal);
  editbox.document.close();}
function eButton(cmdButton, buttonval){
   //----- Controls Button Behaviors ------
   if (buttonval == "over"){
      cmdButton.style.backgroundColor = "threedhighlight";
      cmdButton.style.borderColor = "threeddarkshadow threeddarkshadow threeddarkshadow threeddarkshadow";}
   else if (buttonval == "out"){
      cmdButton.style.backgroundColor = "threedface";
      cmdButton.style.borderColor = "threedface";}
   else if (buttonval == "down"){
      cmdButton.style.backgroundColor = "threedlightshadow";
      cmdButton.style.borderColor = "threedshadow threedshadow threedshadow threedshadow";}
   else if (buttonval == "up"){
      cmdButton.style.backgroundColor = "threedhighlight";
      cmdButton.style.borderColor = "threedshadow threedshadow threedshadow threedshadow";
      cmdButton = null};
   else{
      return;}}
function newDocument(){
   //----- Creates An Empty Workspace ------
   if (editbox.document.body.innerHTML == ""){
      editbox.document.execCommand('refresh', false, null);}
   else{
      if (confirm("Would you like to save your entry?")){
         var dataRep = null;
         dataRep = document.body.all.submitData;
         dataRep.value = editbox.document.body.innerHTML;
         document.editor.submit();
         window.location.reload();}
      else{
         editbox.document.execCommand('refresh', false, null);}}}
function saveDocument(){
   if (editbox.document.body.innerHTML == ""){
      return;}
   else{
      if (confirm("Would you like to save your entry?")){
         var dataRep = null;
         dataRep = document.body.all.submitData;
         dataRep.value = editbox.document.body.innerHTML;
         document.editor.submit();}
      else{
         return;}}}
function tableDialog(){
   var rtNumRows = null;
   var rtNumCols = null;
   var rtTblAlign = null;
   var rtTblWidth = null;
   showModalDialog("editor.php?page=table",window,"status:false;dialogWidth:700px;dialogHeight:12em");
  }
function createTable(){
   //----- Creates User Defined Tables -----
   var cursor = editbox.document.selection.createRange();
   if (rtNumRows == "" || rtNumRows == "0"){
      rtNumRows = "1";}
   if (rtNumCols == "" || rtNumCols == "0"){
      rtNumCols = "1";}
   var rttrnum=1
   var rttdnum=1
   var rtNewTable = "<table border='1' align='" + rtTblAlign + "' cellpadding='0' cellspacing='0' width='" + rtTblWidth + "'>"
   while (rttrnum <= rtNumRows){
      rttrnum=rttrnum+1
      rtNewTable = rtNewTable + "<tr>"
      while (rttdnum <= rtNumCols){
         rtNewTable = rtNewTable + "<td>&nbsp;</td>"
         rttdnum=rttdnum+1}
      rttdnum=1
      rtNewTable = rtNewTable + "</tr>"}
   rtNewTable = rtNewTable + "</table>"
   cursor.pasteHTML(rtNewTable);
   editbox.focus();}
function insertimage(){
window.open("pdtImageUploader.php","PictureUpload","width=450px height=150px; center: Yes; help: No; resizable: No; status: No; scroll: No;");}
function foreColor(){
   //----- Sets Foreground Color -----
   var fColor = showModalDialog("editor.php?page=color",window,"status:false;dialogWidth:140px; dialogHeight:120px" );
   if (fColor != null){
      editbox.document.execCommand("ForeColor", false, fColor);}
   editbox.focus();}
function backColor(){
   //----- Sets Background Color -----
   var bColor = showModalDialog("editor.php?page=color","","dialogWidth:140px; dialogHeight:120px" );
   if (bColor != null){
      editbox.document.execCommand("BackColor", false, bColor);}
   editbox.focus();}
function eStat(status){
   //----- Updates Status Bar With Information -----
   var editStat = document.getElementById("editorStatus");
   editStat.innerHTML = status;}
function modeSelect(){
   //----- Changes Editor Mode -----
   var HTMLtitle
   var WYSIWYGtitle
   var editorTitle
   if(viewMode == 1){
      //----- Convert WYSIWYG editor to HTML -----
      iHTML = editbox.document.body.innerHTML; editbox.document.body.innerText = iHTML;
      HTMLtitle ="IE HTML Editor"; editorTitle = document.getElementById("editorTitle");
      editorTitle.innerHTML = HTMLtitle; document.title = "IE HTML Editor";
      saveCtrl.style.display = 'none'; linkCtrl.style.display = 'none';
      lineCtrl.style.display = 'none'; tableCtrl.style.display = 'none';
      hr1Ctrl.style.display = 'none'; orderedCtrl.style.display = 'none';
      unorderedCtrl.style.display = 'none'; hr2Ctrl.style.display = 'none';
      strikeCtrl.style.display = 'none'; subCtrl.style.display = 'none';
      superCtrl.style.display = 'none'; hr3Ctrl.style.display = 'none';
      forecolorCtrl.style.display = 'none'; backcolorCtrl.style.display = 'none';
      hr4Ctrl.style.display = 'none'; indentCtrl.style.display = 'none';
      outdentCtrl.style.display = 'none'; hr5Ctrl.style.display = 'none';
      showWYSIWYGCtrl.style.display = 'inline'; hr6Ctrl.style.display = 'inline';
      toolBar1.style.display = 'none'; newCtrl.style.display = 'none';
      editbox.focus();
      viewMode = 2;}
   else{
      //----- Convert HTML editor to WYSIWYG -----
      iText = editbox.document.body.innerText; editbox.document.body.innerHTML = iText;
      WYSIWYGtitle ="IE WYSIWYG Editor" ; editorTitle = document.getElementById("editorTitle");
      editorTitle.innerHTML = WYSIWYGtitle; document.title = "IE WYSIWYG Editor";
      saveCtrl.style.display = 'inline'; linkCtrl.style.display = 'inline';
      lineCtrl.style.display = 'inline'; tableCtrl.style.display = 'inline';
      hr1Ctrl.style.display = 'inline'; orderedCtrl.style.display = 'inline';
      unorderedCtrl.style.display = 'inline'; hr2Ctrl.style.display = 'inline';
      strikeCtrl.style.display = 'inline'; subCtrl.style.display = 'inline';
      superCtrl.style.display = 'inline'; hr3Ctrl.style.display = 'inline';
      forecolorCtrl.style.display = 'inline'; backcolorCtrl.style.display = 'inline';
      hr4Ctrl.style.display = 'inline'; indentCtrl.style.display = 'inline';
      outdentCtrl.style.display = 'inline'; hr5Ctrl.style.display = 'inline';
      showWYSIWYGCtrl.style.display = 'none'; hr6Ctrl.style.display = 'none';
      toolBar1.style.display = 'inline'; newCtrl.style.display = 'inline';
      editbox.focus();
      viewMode = 1;}}
function func_insert_select(){
	var strTag = document.editor.cboValues.options[document.editor.cboValues.selectedIndex].value;
	var cursor = editbox.document.selection.createRange();
	cursor.pasteHTML(strTag);
}

</script>
<title>IE WYSIWYG Editor</title>
</head>
<body onload="loadEditor()" marginheight="0" marginwidth="0" bottommargin="0" leftmargin="0" topmargin="0" bgcolor="#CCCCCC">
<table id='window' align='center' border='1' cellpadding='0' cellspacing='0' style='border-collapse: collapse; border: 1px solid #C0C0C0' width="790">
  <tr>
    <td>
      <table id='toolBar' width='100%' cellpadding='0' cellspacing='0'>
        <tr>
          <td width='5%' valign='top' background="../../images/midbg.gif">
            <img width='23' height='22' hspace='1' vspace='1' align='top' src='../../images/spacer.gif' alt=''>
          </td>
          <td width='95%' valign='top' background="../../images/midbg.gif">
            <b><font color='#FFFFFF' face='Arial' size='2'>
            <div id='editorTitle' style='position:relative; top:4px;'> WYSIWG 
              Editor</div>
			</font></b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
  <form action='mailcontentsave.php' method='post' name='editor' onsubmit="submitForm();">
    <td>
      <table id='toolBar0' cellpadding='0' cellspacing='0' width="100%">
        <tr valign='middle'>
          <td id='saveCtrl' width='23'>
            <div class='btnCtrl' onClick="saveDocument();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Saves the current document.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/save.gif' alt='Save'>
            </div>
          </td>
         
          <td>
            <hr noshade size='17' width='1'>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('cut', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Cuts the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/cut.gif' alt='Cut'>
            </div>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('copy', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Copies the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/copy.gif' alt='Copy'>
            </div>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('paste', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Pastes a previously selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/paste.gif' alt='Paste'>
            </div>
          </td>
          <td>
            <hr noshade size='17' width='1'>
          </td>
         
          <td id='lineCtrl' width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('InsertHorizontalRule', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Inserts a horizontal line.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='2' vspace='1' align='absmiddle' src='images/line.gif' alt='Line'>
            </div>
          </td>
    	<td id='tableCtrl' width='23'>
      		<div class='btnCtrl' onClick="tableDialog();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Inserts a user defined table.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
      		<img width='23' height='22' hspace='2' vspace='1' align='absmiddle' src='images/table.gif' alt='Create Table'> 
		 	</div>
      	</td>
          <td id='hr1Ctrl'>
            <hr noshade size='17' width='1'>
          </td>
          <td id='orderedCtrl' width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('insertorderedlist', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Inserts a numbered list.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='2' vspace='1' align='absmiddle' src='images/numlist.gif' alt='Ordered List'>
            </div>
          </td>
          <td id='unorderedCtrl' width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('insertunorderedlist', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Inserts a bulleted list.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='2' vspace='1' align='absmiddle' src='images/bullist.gif' alt='Unordered List'>
            </div>
          </td>
          <td id='hr2Ctrl'>
            <hr noshade size='17' width='1'>
          </td>
          <td id='forecolorCtrl' width='23'>
            <div class='btnCtrl' onClick="foreColor();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Formats the current selections foreground color.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='2' vspace='1' align='absmiddle' src='images/fgcolor.gif' alt='Foreground Color'>
            </div>
          </td>
          <td id='backcolorCtrl' width='23'>
            <div class='btnCtrl' onClick="backColor();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Formats the current selections background color.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='2' vspace='1' align='absmiddle' src='images/bgcolor.gif' alt='Background Color'>
            </div>
          </td>
          <td id='hr4Ctrl'>
            <hr noshade size='17' width='1'>
          </td>
          
          <td id='showWYSIWYGCtrl'>
            <div class='btnCtrl' onClick="modeSelect();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Switches the editor to WYSIWYG Mode.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/mode.gif' alt='Show WYSIWYG Editor'>
            </div>
          </td>
          <td id='hr6Ctrl'>
            <hr noshade size='17' width='1'>
          </td>
		  
		   <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('bold', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Bolds the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/bold.gif' alt='Bold'>
            </div>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('italic', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Italicizes the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/italic.gif' alt='Italic'>
            </div>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('underline', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Underlines the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/under.gif' alt='Underline'>
            </div>
          </td>
          <td>
            <hr noshade size='17' width='1'>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('justifyleft', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Left aligns the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/left.gif' alt='Justify Left'>
            </div>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('justifycenter', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Centers the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/center.gif' alt='Center'>
            </div>
          </td>
          <td width='23'>
            <div class='btnCtrl' onClick="editbox.document.execCommand('justifyright', false, null); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Right aligns the current selection.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/right.gif' alt='Justify Right'>
            </div>
          </td>
		  <td width='23'>
            <div class='btnCtrl' onClick="insertimage(); editbox.focus();" onmousedown="eButton(this, 'down');" onmouseup="eButton(this, 'up');" onmouseover="eButton(this, 'over'); eStat('Upload the Image file.');" onmouseout="eButton(this, 'out'); eStat('&nbsp;');">
              <img width='23' height='22' hspace='1' vspace='1' align='absmiddle' src='images/Images_Icon.gif' alt='Upload Image.'>
            </div>
          </td>
        </tr>
      </table>
	  <table align="left" cellpadding="0" cellspacing="0">
	  	<tr>
			 <td>
            <select onchange="editbox.document.execCommand('formatBlock', false, this[this.selectedIndex].value); editbox.focus();" onmouseover="eStat('Changes the current selections heading type.');" onmouseout="eStat('&nbsp;');">
              <option selected>Style</option>
              <option value='Normal'>Normal</option>
              <option value='Heading 1'>Heading 1</option>
              <option value='Heading 2'>Heading 2</option>
              <option value='Heading 3'>Heading 3</option>
              <option value='Heading 4'>Heading 4</option>
              <option value='Heading 5'>Heading 5</option>
              <option value='Address'>Address</option>
              <option value='Formatted'>Formatted</option>
              <option value='Definition Term'>Definition Term</option>
            </select>
          </td>
          <td>&nbsp;
            <select onchange="editbox.document.execCommand('fontname', false, this[this.selectedIndex].value); editbox.focus();" onmouseover="eStat('Changes the current selections font type.');" onmouseout="eStat('&nbsp;');">
              <option selected>Font</option>
              <option value='Arial'>Arial</option>
              <option value='Arial Black'>Arial Black</option>
              <option value='Arial Narrow'>Arial Narrow</option>
              <option value='Comic Sans MS'>Comic Sans MS</option>
              <option value='Courier New'>Courier New</option>
              <option value='System'>System</option>
              <option value='Tahoma'>Tahoma</option>
              <option value='Times New Roman'>Times New Roman</option>
              <option value='Verdana'>Verdana</option>
              <option value='Wingdings'>Wingdings</option>
            </select>
          </td>
          <td>&nbsp;
            <select onchange="editbox.document.execCommand('fontsize', false, this[this.selectedIndex].value); editbox.focus();" onmouseover="eStat('Changes the current selections font size.');" onmouseout="eStat('&nbsp;');">
              <option selected>Size</option>
              <option value='1'>1</option>
              <option value='2'>2</option>
              <option value='3'>3</option>
              <option value='4'>4</option>
              <option value='5'>5</option>
              <option value='6'>6</option>
              <option value='7'>7</option>
              <option value='8'>8</option>
              <option value='10'>10</option>
              <option value='12'>12</option>
              <option value='14'>14</option>
            </select>&nbsp;&nbsp;
          </td>
		  <td>&nbsp;
            <select name="cboValues" onchange="editbox.focus();" onmouseover="eStat('Changes the current selections font type.');" onmouseout="eStat('&nbsp;');">
              <option value='[MerchantName]'>MerchantName</option>
              <option value='[MerchantTollFreeNumber]'>MerchantTollFreeNumber</option>
              <option value='[RetrievalNumber]'>RetrievalNumber</option>
              <option value='[SecurityCode]'>SecurityCode</option>
              <option value='[Processor]'>Processor</option>
              <option value='[Dateofsale]'>Dateofsale</option>
              <option value='[CustomerName]'>CustomerName</option>
              <option value='[ChargeType]'>ChargeType</option>
              <option value='[CustomerTelephoneNumber]'>CustomerTelephoneNumber</option>
              <option value='[Charge]'>Charge</option>
              <option value='[VoiceCode]'>VoiceCode</option>
            </select>&nbsp;
          <input type="button" name="Insert" value="Insert" onclick="func_insert_select();" style="font-size:10px;width:40px">
		  </td>
		</tr>
	  </table>
    </td>
  </tr>
  <tr>
    <td>
      <iframe width='100%' id='editbox' height='400'></iframe>
    </td>
  </tr>
  <tr>
    <td width ='571' height="19"> <b><font color='#000000' face='Arial' size='2'><div id='editorStatus'>&nbsp;</div></font></b>
    </td>
  </tr>
</table>

  <input name="submitData" id='submitData' type='hidden' name='form_submission'>
  <input type="hidden" name="hdId" value="<?= $iTemplateId ?>">
</form>
</body>
</html>
<?php
}
?>