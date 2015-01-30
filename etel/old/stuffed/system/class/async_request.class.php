<?

class nsAsyncRequest{
  var $FieldId;
  var $WithField;
  var $onLoadJsCode;
  var $loading_phrase;
  
  //-------------------------------------------------------------------------------
  function nsAsyncRequest($onLoadJsCode='', $FieldId=false, $lp=false){
    $this->WithField = true;
    $this->loadingPhrase($lp);
    $this->DumpXmlRequestCode();
    $this->Reset($onLoadJsCode, $FieldId);
    
  }
  //-------------------------------------------------------------------------------
  function loadingPhrase($p=false){ 
      $this->loading_phrase = $p===false ? '<h1>Loading...</h1>' : $p; 
  }
  //-------------------------------------------------------------------------------
  /**
  * @return void
  * @param string $field_type
  * @desc prints the field for async-recieved HTML
  */
  function printField($field_type='SPAN'){
    // writing jacascript code for recieving the xml-http-request
    // and showing it as innerHtml:
    ?><?
    // show the field itself:
    ?><<?=$field_type?> ID="<?=$this->FieldId?>" 
    style='margin-top: 0px'></<?=$field_type?>> 
    <?
  }
  //-------------------------------------------------------------------------------
  /**
  * @return string
  * @desc returns the JavaScript link for request operation 
  * for field recieved by "$this->printField"
  */
  function getJsLink($RequestURL, $loadingp=false, $addit_jscode=''){
    $this->loadingPhrase($loadingp);
    return 'do_xml_http_request(\''.$this->FieldId.'\', \''
            .$RequestURL.'\''.$addit_jscode.', \''
            .addslashes($this->onLoadJsCode).'\', \''.$this->loading_phrase.'\',
            '.($this->WithField?'true':'false').')';
  }
  //-------------------------------------------------------------------------------
  function getJsRequestURL(){?>xml_request_url<?}
  //-------------------------------------------------------------------------------
  function Reset($onLoadJsCode='', $FieldId=false){
    if($FieldId) $this->FieldId = $FieldId; 
    else $this->FieldId = uniqid('_xr_id_');
    $this->onLoadJsCode = $onLoadJsCode;
  }
  //-------------------------------------------------------------------------------
  function DumpXmlRequestCode(){
    if(!isset($GLOBALS['DumpXmlRequestCode_done'])){
      $GLOBALS['DumpXmlRequestCode_done']=true;?>
<script language="JavaScript">

  var fields_ids=new Array(); 
  var fields_shown=new Array(); 
  var fields_texts=new Array(); 
  var field_text_place = 0;
 
  function is_in_shown_fields(fid){
     var i=0;
     for(i=0;i<fields_ids.length;++i){
       if(fields_ids[i]==fid){
         if(fields_texts[i] != 'empty'){                  
              return i;
         }
         field_text_place = i;
         fields_shown[i] = false;
         return 'shown';
       }
     }
     fields_ids[i] =  fid;          
     fields_shown[i] = true;
     fields_texts[i] =  'empty';          
     
         return 'not';
  }

  var xmlhttp=false;
  
  /*@cc_on @*/
  /*@if (@_jscript_version >= 5)
   try {
    xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
   } catch (e) {
    try {
     xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    } catch (E) {
     xmlhttp = false;
    }
   }
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
    xmlhttp = new XMLHttpRequest();
  }
  // the event function:
  var xml_request_url = '';
  function do_xml_http_request(field_id, request_url, onReadyLoadCode, lphrase, with_field){        
    if(with_field)  what_is_shown = is_in_shown_fields(field_id);
    else what_is_shown = 'not';
    if (what_is_shown == 'not'){
      xml_request_url = request_url;
      if(with_field)  getId(field_id).style.display = '';
      if(with_field)  getId(field_id).innerHTML = lphrase;

      xmlhttp.open("GET", xml_request_url, true);
      xmlhttp.onreadystatechange=function() {
        if (xmlhttp.readyState != 4) return;
        if(with_field)  getId(field_id).innerHTML = xmlhttp.responseText;
        eval(onReadyLoadCode);
        // gotta grab all the "<script></s cript>" and evaluate it:::::::::::::::::::
        re = /<script>(.*)<\/script>/g;
        rez = ':\n';
        do{
          found = re.exec(xmlhttp.responseText);
          if(re.lastIndex) eval(found[1]);
        }while(re.lastIndex);
        // done grabbing :::::::::::::::::::
      }
      
      xmlhttp.send(null);           
    } else if (what_is_shown == 'shown'){
      fields_texts[field_text_place] = 'set';
      if(with_field)  getId(field_id).style.display = 'none';
      eval(onReadyLoadCode);
    } else {                
      if(with_field)  getId(field_id).style.display = '';
      fields_texts[what_is_shown] = 'empty';
      eval(onReadyLoadCode);
    }                
  }   
</script>
  <?}
  }
  //-------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------
  //-------------------------------------------------------------------------------
};





?>