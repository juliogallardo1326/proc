// Form Validation by Ari Asulin 2005.

function isEmail (p_sEmail)
{
 var regEmail = /^[\w-]+(?:\.[\w-]+)*@(?:[\w-]+\.)+[a-zA-Z]{2,7}$/;
 return regEmail.test (p_sEmail);
}

function validateForm(form) {
	var formElements = form.elements;
	var validated = true;
	for (i=0; i<formElements.length; i++) {
		curElm=formElements[i];
		if (!validate(curElm,true,"")) validated = false;
		if (!validated) 
		{

			if (!(curElm.type == 'hidden')) 
				curElm.focus();
			return false;
		}
	}
	return validated;
}

function setupForm(form) {
	var formElements = form.elements;
	for (i=0; i<formElements.length; i++) {
		curElm=formElements[i];
		if(!curElm.onfocus)curElm.onfocus=updatevalidthis;
		if(!curElm.onchange)curElm.onchange=updatevalidthis;
		if(!curElm.onkeyup)curElm.onkeyup=updatevalidthis;
		if(!curElm.onblur)curElm.onblur=updatevalidthis;
		if(!curElm.onkeydown)curElm.onkeydown=updatevalidthis;
		if(curElm.alt=='url' && !curElm.value) curElm.value = "http://";
		//if(!curElm.onblur)curElm.onmousemove=updatevalidthis;		 <- hehe
	}
	if(!form.onsubmit)form.onsubmit=submitformthis;
}

function updatevalidthis()
{
		updatevalid(this);
}

function updatevalid(objValue)
{
	if(validate(objValue)) objValue.style.backgroundColor  = '#E6E6E6';
	else objValue.style.backgroundColor  = '#EBB8BF';
}
	
function submitform(form)
{
    if (!validateForm(form)) return false;
	form.submit();
	return true;
}	
function submitformthis()
{
    return submitform(this);
}

creditcard_confirm_val="asd9f87sda0f98dsjfa0sd98fadsjiasd;lfj";
function validate(objValue,objAlert,cmdvalue)
{
	// or alt
	if(objValue.disabled == true) return true;
	var strError = "";
	var valid = '';
	if (!valid)	var valid = objValue.getAttribute("valid");
	if (!valid)	var valid = objValue.getAttribute("src");
	if (!valid)	var valid = objValue.getAttribute("alt");
	if (!valid)	var valid = objValue.getAttribute("title");
	if (!valid) return true;

	params = valid.split("|");
	command = params[0];
	var displayTitle;
	displayTitle = objValue.title;
	if(!displayTitle) displayTitle = objValue.name;
	displayTitle=displayTitle.replace(/[^a-zA-Z0-9#$]+/ig, " ");
	if (!command) return true;
	switch(command) 
    { 
        case "required": 
        case "req": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Required Field"; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "least1": 
         { 
           if(eval(objValue.value) < 1) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Please create at least one Entry here."; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "req1": 
         { 
           if(eval(objValue.value) == -2) 
           { 
              if(!strError || strError.length ==0) 
              {               
			  	return confirm("It appears as if you haven't touched section '"+displayTitle+"'. Are you sure everything is fine here? If you're not certain, please double check."); 
              }//if 
          }//if 
           break;             
         }//case required 
        case "reqmenu": 
         { 
           if(objValue.value == "select" || objValue.value == "") 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Please select a valid option"; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "filereq": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Please make sure to upload a file before submitting the form."; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required  
		case "Description":
        case "noeffort": 
         { 
           if(eval(objValue.value.length) == 0) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Description is required."; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           if(eval(objValue.value.length) < 25) 
           { 
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Please add more description."; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
        case "maxlength": 
        case "maxlen": 
          { 
             if(eval(objValue.value.length) >  eval(params[1])) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = displayTitle + " : "+cmdvalue+" characters maximum "; 
               }//if 
               if (objAlert) alert(strError + "\n[Current length = " + objValue.value.length + " ]"); 
               return false; 
             }//if 
             break; 
          }//case maxlen 
        case "minlength": 
        case "minlen": 
           { 
             if(eval(objValue.value.length) <  eval(params[1])) 
             { 
               if(!strError || strError.length ==0) 
               { 
                 strError = displayTitle + " : " + cmdvalue + " characters minimum is "+params[1]; 
               }//if               
               if (objAlert) alert(strError + "\n[Current length is " + objValue.value.length + " ]"); 
               return false;                 
             }//if 
             break; 
            }//case minlen 
        case "between": 
           { 
             if(eval(objValue.value) <  eval(params[1]) || eval(objValue.value) >  eval(params[2]) || (eval(objValue.value.length) == 0)) 
				 { 
				   if(!strError || strError.length ==0) 
				   { 
					 strError = displayTitle + " : " + cmdvalue + " must be between "+params[1]+" and "+params[2]; 
				   }//if               
				   if (objAlert) alert(strError + "\n[Current value is " + objValue.value + " ]"); 
				   return false;                 
				 }//if     
             break; 
            }//case minlen 
        case "alnum": 
        case "alphanumeric": 
           { 
			   if(eval(objValue.value.length) == 0) 
			   { 
				  if(!strError || strError.length ==0) 
				  { 
					strError = displayTitle + " : Required Field"; 
				  }//if 
				  if (objAlert) alert(strError); 
				  return false; 
			   }//if 
              var charpos = objValue.value.search("[^A-Za-z0-9]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = displayTitle+": Only alpha-numeric characters allowed "; 
                }//if 
                if (objAlert) alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
        case "zipcode": 
           { 
              if((objValue.value.length < 5)) 
              { 
               if(!strError || strError.length ==0) 
                { 
                  strError = displayTitle+": Please Enter a Valid Zipcode "; 
                }//if 
                if (objAlert) alert(strError); 
                return false; 
              }//if 
              break; 
           }//case alphanumeric 
		   
        case "num": 
        case "numeric": 
           { 
			   if(eval(objValue.value.length) == 0) 
			   { 
				  if(!strError || strError.length ==0) 
				  { 
					strError = displayTitle + " : Required Field"; 
				  }//if 
				  if (objAlert) alert(strError); 
				  return false; 
			   }//if 
              var charpos = objValue.value.search("[^0-9.]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                if(!strError || strError.length ==0) 
                { 
                  strError = displayTitle+": Only digits allowed "; 
                }//if               
                if (objAlert) alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break;               
           }//numeric 
        case "phone": 
           {  
			if(!objValue.value.match("^[0-9+()/ -]+$")) 
			{ 
				if(!strError || strError.length ==0) 
				strError = displayTitle+": Please Enter a valid US or International Phone Number (-,+,0-9,()). If US, Please include area code. "; 
				
				if (objAlert) alert(strError + "\n [Error character position " + eval(charpos+1)+"]");
				return false; 
			}//if  
			break;             
           }//numeric 
        case "alphabetic": 
        case "alpha": 
           { 
              var charpos = objValue.value.search("[^A-Za-z]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = displayTitle+": Only alphabetic characters allowed "; 
                }//if                             
                if (objAlert) alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 
              break; 
           }//alpha 
		case "alnumhyphen":
			{
              var charpos = objValue.value.search("[^A-Za-z0-9\-_]"); 
              if(objValue.value.length > 0 &&  charpos >= 0) 
              { 
                  if(!strError || strError.length ==0) 
                { 
                  strError = displayTitle+": characters allowed are A-Z,a-z,0-9,- and _"; 
                }//if                             
                if (objAlert) alert(strError + "\n [Error character position " + eval(charpos+1)+"]"); 
                return false; 
              }//if 			
			break;
			}
        case "creditcard": 
          { 
			 if(creditcard_confirm_val == objValue.value) return true;
			   if(!isValidCreditCard(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 

                 { 
                    strError = displayTitle+": Enter a valid CreditCard "; 
                 }//if                                               
                 if (objAlert) {
					 if (confirm(displayTitle+": This creditcard does not appear to be valid. Would you like to continue anyway?"))
					 {
						 creditcard_confirm_val=objValue.value;
						 return true;
					 }
				 }
				return false;
			   }//if 
           break; 
          }//case email 
        case "email": 
          { 
               if(!isEmail(objValue.value)) 
               { 
                 if(!strError || strError.length ==0) 

                 { 
                    strError = displayTitle+": Enter a valid Email address "; 
                 }//if                                               
                 if (objAlert) alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
        case "confirm": 
          { 
		  	if (!document.getElementById(params[1])) break;
               if((objValue.value!=document.getElementById(params[1]).value))
               { 
                 if(!strError || strError.length ==0) 

                 { 
                    strError = displayTitle+": Please Confirm that the "+displayTitle+" fields line up "; 
                 }//if                                               
                 if (objAlert) alert(strError); 
                 return false; 
               }//if 
           break; 
          }//case email 
        case "regexp": 
         { 
		 	if(objValue.value.length > 0)
			{
	            if(!objValue.value.match(cmdvalue)) 
	            { 
	              if(!strError || strError.length ==0) 
	              { 
	                strError = displayTitle+": Invalid characters found "; 
	              }//if                                                               
	              if (objAlert) alert(strError); 
	              return false;                   
	            }//if 
			}
           break; 
         }//case regexp 
        case "dontselect": 
         { 
            if(objValue.selectedIndex == null) 
            { 
             // if (objAlert) alert("BUG: dontselect command for non-select Item"); 
              return false; 
            } 
            if(objValue.selectedIndex == eval(cmdvalue)) 
            { 
             if(!strError || strError.length ==0) 
              { 
              strError = displayTitle+": Please Select one option "; 
              }//if                                                               
              if (objAlert) alert(strError); 
              return false;                                   
             } 
             break; 
         }//case dontselect 
        case "url": 
          { 
			if(objValue.value.match(/^((ht|f)tp(s?)):\/\/[\w-]+(\.[\w-]+)*\.(\w{2}|com|net|org|mil|int|edu|gov|info|biz|coop|aero|pro|name|museum)(\/[\w\-\.])*.*$/) == null)
			{
              if(!strError || strError.length ==0) 
              { 
                strError = displayTitle + " : Valid URL Required (Please ensure that http://, https:// or ftp:// is included in the URL)"; 
              }//if 
              if (objAlert) alert(strError); 
              return false; 
           }//if 
           break;             
         }//case required 
    }//switch 
    return true; 

}

