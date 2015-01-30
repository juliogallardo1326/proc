/*
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN"><HTML><HEAD><SCRIPT LANGUAGE=JavaScript>
*/

function addElementNotes(obj,notes)
{
	var txt='';
	if(!obj.getAttribute('notes_added') && !notes)
		txt = ' '+getCurDateTime()+'\r\n<'+etel_full_name+'> ';
	
	
	if(notes)
		txt = ' '+getCurDateTime()+'\r\n<'+etel_full_name+'> '+notes;
	
	if(txt)
	{
		if(obj.value) txt = '\r\n\r\n'+txt;
		obj.value += txt;
		obj.setAttribute('notes_added',true);
		obj.scrollTop = obj.scrollHeight;
	}
}

function toggleSectionView(obj,force)
{
	name = obj.id;
	if(!$(name+'0')) return;
	
	var newvis = 'table-row';
	if(force=='Closed') newvis = 'none';
	else if(force=='Open') newvis = 'table-row';
	else newvis = ($(name+'0').style.display=='none'?'table-row':'none');
	
	var icon = (newvis=='none'?'plusbox.png':'minusbox.png');
	obj.setAttribute('alt',(newvis=='none'?'+':'-'));
	
	//var newimg = new Image();
	obj.src=rootdir+'/images/'+icon;
	
	//obj.src=newimg.src;
	for(var i=0;i<30;i++)
	{
		if(!$(name+i)) return;
		$(name+i).style.display = newvis;
	}
}

function getCurDateTime()
{
	var d=new Date()
	var s = '';
	var weekday=new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday")
	var monthname=new Array("Jan","Feb","Mar","Apr","May","June","July","Aug","Sep","Oct","Nov","Dec")
	s += weekday[d.getDay()] + " "+d.getDate() + ", "+monthname[d.getMonth()] + " "+d.getFullYear()+" ";
	s += d.getHours() + ":";
	s += (d.getMinutes()<10?"0"+d.getMinutes():d.getMinutes()) + ":";
	s += (d.getSeconds()<10?"0"+d.getSeconds():d.getSeconds());
	return(s);
}

function view_toggleView(varname)
{
	if(document.getElementById(varname+"0").style.display == 'none') mode = 'table-row';
	else mode = 'none';
	var i=0;
	while(document.getElementById(varname+i))
	{
		document.getElementById(varname+i).style.display=mode;
		i++;
	}

}

function checkAllowedChars(strToCheck, allowedChars)
{
     var acLen     = allowedChars.length;
     var stcLen     = strToCheck.length;
     strToCheck     = strToCheck.toLowerCase();
     var i;
     var j;
     var rightCount = 0;
     for(i = 0; i < acLen; i++)
     {
          switch(allowedChars.charAt(i))
          {
          case 'A':
               for(j = 0; j< stcLen; j++)
               {
                    rightCount += strToCheck.charAt(j) >= 'a' && strToCheck.charAt(j) <= 'z';
               }
               break;
          case 'N':
               for(j = 0; j< stcLen; j++)
               {
                    rightCount += strToCheck.charAt(j) >= '0' && strToCheck.charAt(j) <= '9';
               }
               break;
		  case 'D':
               for(j = 0; j< stcLen; j++)
               {
                    rightCount += ((strToCheck.charAt(j) >= '0' && strToCheck.charAt(j) <= '9')|| strToCheck.charAt(j) == '.' );
               }
               break;   
          default:
               for(j = -1; -1 != (j = strToCheck.indexOf(allowedChars.charAt(i), j+1)); rightCount++);
               break;
          }
     }
     if(rightCount == stcLen)
     {
          return true;
     }
     return false;
}

//**********************************************************************************
//       Check wether the characters specified are not there in the string
// Accepts the string and the character to be checked (it is a string)
//                                        A     -> for all alphabets (no case specification)
//                                        N     -> for all numbers
//                                        a - z     -> for all the lower case chars
//                                        other just specify it
//**********************************************************************************
function checkNotAllowedChars(strToCheck, unAllowedChars)
{
     var acLen     = unAllowedChars.length;
     var stcLen     = strToCheck.length;
     strToCheck     = strToCheck.toLowerCase();
     var i;
     var j;
     var rightCount = 0;
     for(i = 0; i < acLen; i++)
     {
          switch(unAllowedChars.charAt(i))
          {
          case 'A':
               for(j = 0; j< stcLen; j++)
               {
                    if(strToCheck.charAt(j) >= 'a' && strToCheck.charAt(j) <= 'z')
                    {
                         return false;
                    }
               }
               break;

          case 'N':
               for(j = 0; j< stcLen; j++)
               {
                    if(strToCheck.charAt(j) >= '0' && strToCheck.charAt(j) <= '9')
                    {
                         return false;
                    }
               }
               break;

          default:
               if(strToCheck.indexOf(unAllowedChars.charAt(i)) != -1)
               {
                    return false;
               }
               break;
          }
     }
     return true;
}

function trimSpace(frmElement)
{
	if (!frmElement) return;
     var stringToTrim = eval(frmElement).value;
     var len = stringToTrim.length;
     var front;
     var back;
     for(front = 0; front < len && (stringToTrim.charAt(front) == ' ' || stringToTrim.charAt(front) == '\n' || stringToTrim.charAt(front) == '\r' || stringToTrim.charAt(front) == '\t'); front++);
     for(back = len; back > 0 && back > front && (stringToTrim.charAt(back - 1) == ' ' || stringToTrim.charAt(back - 1) == '\n' || stringToTrim.charAt(back - 1) == '\r' || stringToTrim.charAt(back - 1) == '\t'); back--);

     frmElement.value = stringToTrim.substring(front, back);
}

function noChkBoxSelected(frmElement)
{
     var i;
     if(frmElement[1])
     {
          var len = frmElement.length;
          for(i = 0; i < len; i++)
               if(frmElement[i].checked)
                    break;

          if(i < len)
               return false;
          else
               return true;
     }
     else
          return !(frmElement.checked);
}

function findSelectedButton(btns)
{
     if(!btns[1])
          return btns.checked? 0: -1;

     for(i = 0; i < btns.length; i++)
     {
          if(btns[i].checked)
               return i;
     }

     return -1;
}

function setButton(btns, idx, val)
{
     idx = parseInt(idx, 10);
     if(!isNaN(idx))
          if(!btns[1])
               btns.checked = val;
          else
               btns[idx].checked = val;
}

function checkRedundantValues(frmElement)
{
     if(frmElement[1])
     {
          var cpy = new Array();
          for(i = 0; i < (frmElement.length - 1); i++)
               if(frmElement[i].value != '')
                    for(j = i+1; j < frmElement.length; j++)
                         if(frmElement[i].value == frmElement[j].value)
                              cpy[cpy.length] = i;
          if(cpy.length)
               return cpy;
     }
     return null;
}

//**********************************************************************************//
//                      Counts the Number of Occurance of a character                    //
// Accepts the string and the character                                                            //
//**********************************************************************************//
function countOccurance(str, charecter)
{
     var j;
     var count;
     for(j = -1, count = 0; -1 != (j = str.indexOf(charecter, j+1)); count++);
     return count;
}

function checkEmail(email, mandatory)
{
     if(mandatory && !(email.length))
          return false;

     if(!(email.length))
          return true;

     if(!(checkAllowedChars(email, 'AN@-_.<>')
          && countOccurance(email, '@') == 1
          && email.indexOf('@') != 0
          && email.lastIndexOf('@') != (email.length - 1)
          && countOccurance(email, '<') <= 1
          && countOccurance(email, '>') <= 1
          && ((email.lastIndexOf('>') == (email.length - 1) && email.indexOf('<') != -1)
               || (email.indexOf('>') == -1 && email.indexOf('<') == -1))
          && countOccurance(email, '.') >= 1
          && email.indexOf('..') == -1
          && email.indexOf('.') != 0
          && email.lastIndexOf('.') != (email.length - 1)))
     {
          return false;
     }

     afterAt = email.substring(email.indexOf('@')+1);
     if(!(afterAt.indexOf('.') != 0 && afterAt.lastIndexOf('.') != (afterAt.length - 1)))
          return false;

     beforeAt = email.substring(0, email.indexOf('@'));
     if(!(beforeAt.indexOf('_') != 0
      && beforeAt.indexOf('-') != 0
      && beforeAt.indexOf('.') != 0
      && beforeAt.lastIndexOf('.') != (beforeAt.length - 1)))
     {
          return false;
     }
     return true;
}

/*
 * checkDateString(dateString, dateFormat, seperator)
 *
 * dateString     (string)     The string that is to validated.
 * dateFormat     (string)     The format in which the date is expected to be present in dateString. {dmy for ddmmyyyy, ymd for yyyymmdd}
 * seperator     (string)     The seperator that seperates the day, month & year from each other. Its possible values are - and /
 *
 * Returns Value:
 *  true if the date that you give is correct. Else it returns false.
 */

function checkDateString(dateString, dateFormat, seperator)
{
     var dmy = new Array();
     var day, month, year;

     dateFormat.toLowerCase();
     if(!checkAllowedChars(dateFormat, 'dmy'))
     {
          alert('checkDateString: Function usage error.\n\nInvalid date format.');
          return false;
     }

     if(seperator.length != 1 || (!checkAllowedChars(seperator, '/-')))
     {
          alert('checkDateString: Function usage error.\n\nInvalid seperator.');
          return false;
     }


     if(!checkAllowedChars(dateString, 'N' + seperator))
          return false;

     dmy = dateString.split(seperator);
     if(dmy.length == 3)
     {
          i = 0;
          while(dateFormat.length > 0)
          {
               fmtLen = countOccurance(dateFormat, dateFormat.charAt(0));

               switch(dateFormat.charAt(0))
               {
               case 'd':
                    day = dmy[i];
                    break

               case 'm':
                    month = dmy[i];
                    break

               case 'y':
                    year = dmy[i];
                    break
               }
               dateFormat = dateFormat.substring(fmtLen);
               i++;
          }

          if(!(day.length > 0 && month.length > 0 && year.length > 0))
               return false;

          return _checkDate(day, month, year);
     }
     return false;
}
/*
 * checkDate(day, month, year)
 *
 * As you expect day, month and year are the strings that contains the corresponding values.
 *
 * Returns Value:
 *  true if the date that you give is correct. Else it returns false.
 */

function checkDate(day, month, year)
{
     if(!checkAllowedChars(day + month + year, 'N'))
          return false;

     if((day.length <= 0) || (month.length <= 0) || (year.length <= 0))
          return false;

     return _checkDate(day, month, year);
}

function _checkDate(day, month, year)
{
     year *= 1;
     if(year <= 0)
          return false;

     month *= 1;
     if(!((month > 0) && (month < 13)))
          return false;

     var daysInMonth = new Array();
     daysInMonth[ 0] = 31;                         //Jan
     daysInMonth[ 1] = isLeap(year) == true? 29: 28;     //Feb
     daysInMonth[ 2] = 31;                         //Mar
     daysInMonth[ 3] = 30;                         //Apr
     daysInMonth[ 4] = 31;                         //May
     daysInMonth[ 5] = 30;                         //Jun
     daysInMonth[ 6] = 31;                         //Jul
     daysInMonth[ 7] = 31;                         //Aug
     daysInMonth[ 8] = 30;                         //Sep
     daysInMonth[ 9] = 31;                         //Oct
     daysInMonth[10] = 30;                         //Nov
     daysInMonth[11] = 31;                         //Dec

     day *= 1;
     if(!((day > 0) && (day <= daysInMonth[month - 1])))
          return false;

     return true;
}

function isLeap(year)
{
     if((year % 4) == 0)
     {
          if((year % 100) == 0)
          {
               if((year % 400) == 0)
                    return true;
               else
                    return false;
          }
          return true;
     }
     return false;
}


/*
 * checkDropDown(dropDown, alertMsg, moveNext)
 *
 * dropDown          (object)     The reference to the dropdown object.
 * alertMsg          (string)     The message to be alerted on finding error. If it is null('') then the message will not be displayed in case of an error.
 * moveNext          (boolean)     Says whether to move to the next option on error.
 *
 * Returns Value:
 *  true if there was no error. Else it returns false.
 *
 * Remark
 *  The options that are not to be allowed to select by the user should be given the value null ('').
 */

function checkDropDown(dropDown, alertMsg, moveNext)
{
     if(dropDown.options[dropDown.selectedIndex].value == '')
     {
          if(alertMsg != '')
               alert(alertMsg);

          if(moveNext)
               cddMoveForward(dropDown)

          return false;
     }
     return true;
}

function cddMoveBack(dropDown)
{
     var i;
     for(i = dropDown.selectedIndex - 1; i >= 0 && dropDown.options[i].value == ''; i--);
     if(i < 0)
          dropDown.options[dropDown.selectedIndex].selected = false;
     else
          dropDown.options[i].selected = true;
}

function cddMoveForward(dropDown)
{
     var i;
     for(i = dropDown.selectedIndex + 1; i < dropDown.options.length && dropDown.options[i].value == ''; i++);
     if(i >= dropDown.options.length)
          cddMoveBack(dropDown);
     else
          dropDown.options[i].selected = true;
}


/*
 * formFocus(frm)
 *
 * frm          (object)     The reference to the form object to be focused.
 *
 * Remark
 *  Passes the focus to the first element in the given form.
 */

function formFocus(frm)
{
     var fieldLen;
     if(frm != null && frm.elements)
     {
          fieldLen = frm.elements.length;
          var eleType;
          for(i = 0; i < fieldLen; i++)
          {
               eleType = frm.elements[i].type;
               if(eleType == 'select-multiple' || eleType == 'select-one' || eleType == 'text' || eleType == 'textarea' || eleType == 'checkbox' || eleType == 'radio')
               {
                    frm.elements[i].focus();
                    break;
               }
          }
     }
}

// Function to check whether an element is null or contain initial spaces.
function spaceCheck(formname, fieldname) {
        anyspacing = true;
        itemlength = eval("document." + formname + "." + fieldname + ".value.length");
        itemvalue = eval("document." + formname + "." + fieldname + ".value");
        for(i = 0; i < itemlength; i++) {
                if(itemvalue.charAt(i) != ' ') {
                        anyspacing = false;
                        break;
                }
        }
       eval("document." + formname + "." + fieldname + ".focus()");
        return anyspacing;
}

//---------------------------------------------------------------------------------------------------
//     to check space
//     parameters  (formname,fieldname)
//-----------------------------------------------------------------------------------------------------

function chkspace(formname,fieldname,msgname) {
     field = eval("document." + formname +"."+ fieldname);
        if (field.value.indexOf(' ') >= 0) {
             alert(msgname + " cannot contain space");
             field.focus();
             return false;
        }
        else {
             return true;
        }
}

function isEmpty(formname, fieldname) {
        itemvalue = eval('document.' + formname + '.' + fieldname + '.value');
        if(itemvalue.length <= 0) {
                return true;
        }
        icount = 0;
        for(i=0;i<itemvalue.length;++i) {
                if(itemvalue.charAt(i) != ' ') {
                        ++icount;
                }
        }
        if(icount > 0) {
                return false;
        }
        else {
                return true;
        }
}

function openWindow(type, page) {
     windowFeatures = "";
     if (type == 'news') {
          window_width = 500;
          window_height = 350;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=1"
     }
     if (type == 'showimage') {
          window_width = 400;
          window_height = 400;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=1"
     }
      if (type == 'joincom') {
          window_width = 320;
          window_height = 250;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=0"
     }
       if (type == 'eire') {
          window_width = 330;
          window_height = 170;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=0"
      }
      if (type == 'ett') {
          window_width = 330;
          window_height = 170;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=0"
      }

          if (type == 'global') {
          window_width = 350;
          window_height = 200;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=0"
     }
     
        if (type == 'global1') {
          window_width = 350;
          window_height = 400;
          window_top = (screen.availHeight-window_height)/2
          window_left = (screen.availWidth-window_width)/2
          windowFeatures += "width=" + window_width + ",height=" + window_height + ",top="
          windowFeatures += window_top
          windowFeatures += ",left="
          windowFeatures += window_left
          windowFeatures += ",scrollbars=1"
     }
     window.open(page,type,windowFeatures)
}

function func_is_date1_after_date2(str_year1,str_month1,str_day1,str_year2,str_month2,str_day2)
{
	var isAfter = false;
	var date1 = new Date(str_year1,str_month1-1,str_day1,0,0,0);
	var date2 = new Date(str_year2,str_month2-1,str_day2,0,0,0);
	if(date1.getTime()-date2.getTime() > 0)
	{
		isAfter = true;
	}
	return isAfter;
}

function ValidateDateBox(strLabel,obj,iDay,iMonth,iYear,bSelected,bFutureDate,bPastDate)
{
	var bcorrect=true;
	if(bSelected)
	{
		if(bcorrect && !isDate(iDay,iMonth,iYear))
		{
			bcorrect=false;
			alert("Please select a valid " + strLabel + ".");
			obj.focus();
		}
	}
	if(iDay!="" && iMonth!="" && iYear!="")
	{
		if(bcorrect && !isDate(iDay,iMonth,iYear))
		{
			bcorrect=false;
			alert("Please select a valid " + strLabel + ".");
			obj.focus();
		}
	}
	if (bFutureDate)
	{
		if(bcorrect)
		{
			var dtnew = new Date(iYear,iMonth-1,iDay);
			var dtnow = new Date();
			var dtnowdate = new Date(parseInt(dtnow.getFullYear()),parseInt(dtnow.getMonth()),parseInt(dtnow.getDate()));
			if(dtnew<dtnowdate)
			{
				bcorrect=false;
				alert("Please select a valid billing date.");
				obj.focus();
			}
		}
	}
	if(bPastDate)
	{
		if(bcorrect)
		{
			var dtnew = new Date(iYear,iMonth-1,iDay);
			var dtnow = new Date();
			
			if(dtnew>dtnow)
			{
				bcorrect=false;
				alert("Please select a valid past date.");
				obj.focus();
			}
		}
	}
	return bcorrect;
}

function isDate(date,month,year)
{ 
	var bflag=true;
	if (isNaN(year) || isNaN(month) || isNaN(date))
	{
		bflag=false;
	}
	if(bflag)
	{
		var dtnew = new Date(year,month-1,date);
		if ((parseInt(dtnew.getFullYear()) == year) && (parseInt(dtnew.getMonth()) == (month-1)) && (parseInt(dtnew.getDate()) == date))
		{
			bflag=true;
		}
		else
		{
			bflag=false;
		}
	}
	return bflag;
}


function func_isEmail(emailval)
{
	var tempStr,icount;
	var blnmail,blnperiod; 
	var lastoccofperiod,maxthree;
	var ampicount=0;
	var amppos;
	var servername = 1;
	var dots;
	icount=emailval.length;
	blnperiod = 1;
	maxthree = 1;
	specialchar = 0
	lastoccofperiod = 0;
	if (icount==0)
	{
		return true;
	}
	for(i=0;i<icount;i++)
	{
		tempStr = emailval.charAt(i);
		if ((tempStr >='a')&&(tempStr <='z'))
		{
			specialchar=specialchar+1;
		}
		else
		{
			if ((tempStr >='A')&&(tempStr <='Z'))
			{
				specialchar=specialchar+1;
			}
			else
			{
				if ((tempStr >= 0)&&(tempStr<=9))
				{
					specialchar=specialchar+1;
				}
				else
				{
					if ((tempStr=='_')||(tempStr=='-')||(tempStr=='.')||(tempStr=='@'))
					{
						specialchar=specialchar+1;
					}
					else
					{
						return false;
					}
				}
			}
		}
	}
	dots = emailval.indexOf('..');
	if (dots != -1)
	{
		return false;
	}
	espace = emailval.indexOf(' ');
	if (espace != -1)
	{
		return false;
	}
	lastoccofperiod = emailval.lastIndexOf('.');
	if (lastoccofperiod <= 0)
	{
		blnperiod = 0;
	}
	if (((icount - lastoccofperiod) > 5)||((icount - lastoccofperiod) < 3))
	{
		maxthree = 0;
	}
	 for(i=0;i<=icount;i++)
	{
		tempStr = emailval.charAt(i)
		if (tempStr=='@')
		ampicount=ampicount + 1;
	}
	amppos = emailval.indexOf('@');
	if (emailval.charAt(amppos+1) == '.')
	servername = 0;
	if(icount - emailval.charAt(amppos)< 5) 
	servername = 0;
	if ((ampicount==1)&&(blnperiod==1)&&(maxthree==1)&&(servername==1))
	{
		blnmail=1;
	}
	else
	{
		 blnmail=0;
	}
	 //return blnmail;
	if (blnmail==0)
	{
		//alert('Please enter a valid email address');
		return false;
	}
	else
	{
		return true;
	}
 }
 
function showBankNames()
{
	
}
 
 // JSON
 
 
 var JSON = function () {
    var m = {
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        s = {
            'boolean': function (x) {
                return String(x);
            },
            number: function (x) {
                return isFinite(x) ? String(x) : 'null';
            },
            string: function (x) {
                if (/["\\\x00-\x1f]/.test(x)) {
                    x = x.replace(/([\x00-\x1f\\"])/g, function(a, b) {
                        var c = m[b];
                        if (c) {
                            return c;
                        }
                        c = b.charCodeAt();
                        return '\\u00' +
                            Math.floor(c / 16).toString(16) +
                            (c % 16).toString(16);
                    });
                }
                return '"' + x + '"';
            },
            object: function (x) {
                if (x) {
                    var a = [], b, f, i, l, v;
                    if (x instanceof Array) {
                        a[0] = '[';
                        l = x.length;
                        for (i = 0; i < l; i += 1) {
                            v = x[i];
                            f = s[typeof v];
                            if (f) {
                                v = f(v);
                                if (typeof v == 'string') {
                                    if (b) {
                                        a[a.length] = ',';
                                    }
                                    a[a.length] = v;
                                    b = true;
                                }
                            }
                        }
                        a[a.length] = ']';
                    } else if (x instanceof Object) {
                        a[0] = '{';
                        for (i in x) {
                            v = x[i];
                            f = s[typeof v];
                            if (f) {
                                v = f(v);
                                if (typeof v == 'string') {
                                    if (b) {
                                        a[a.length] = ',';
                                    }
                                    a.push(s.string(i), ':', v);
                                    b = true;
                                }
                            }
                        }
                        a[a.length] = '}';
                    } else {
                        return;
                    }
                    return a.join('');
                }
                return 'null';
            }
        };
    return {
        copyright: '(c)2005 JSON.org',
        license: 'http://www.JSON.org/license.html',
/*
    Stringify a JavaScript value, producing a JSON text.
*/
        stringify: function (v) {
            var f = s[typeof v];
            if (f) {
                v = f(v);
                if (typeof v == 'string') {
                    return v;
                }
            }
            return null;
        },
/*
    Parse a JSON text, producing a JavaScript value.
    It returns false if there is a syntax error.
*/
        parse: function (text) {
            try {
                return !(/[^,:{}\[\]0-9.\-+Eaeflnr-u \n\r\t]/.test(
                        text.replace(/"(\\.|[^"\\])*"/g, ''))) &&
                    eval('(' + text + ')');
            } catch (e) {
                return false;
            }
        }
    };
}();
 
 function print_r(theObj){
  if(theObj.constructor == Array ||
     theObj.constructor == Object){
    document.write("<ul>")
    for(var p in theObj){
      if(theObj[p].constructor == Array||
         theObj[p].constructor == Object){
document.write("<li>["+p+"] => "+typeof(theObj)+"</li>");
        document.write("<ul>")
        print_r(theObj[p]);
        document.write("</ul>")
      } else {
document.write("<li>["+p+"] => "+theObj[p]+"</li>");
      }
    }
    document.write("</ul>")
  }
}
 
/*Please don't delete this line </SCRIPT></HEAD><BODY STYLE="background-color:black;color:gray">
<FORM name=form1 onSubmit="alert(checkDateString(document.form1.dateInput.value, document.form1.dateFormat.value, document.form1.seperator.options[document.form1.seperator.selectedIndex].value)); return false"><TABLE><TR><TD ALIGN=RIGHT>Date:</TD><TD ALIGN=LEFT><INPUT STYLE="background-color:black;color:gray;border:1 solid" TYPE=TEXT name=dateInput></TD></TR><TR><TD ALIGN=RIGHT>Date Format:</TD><TD ALIGN=LEFT><INPUT STYLE="background-color:black;color:gray;border:1 solid" TYPE=TEXT name=dateFormat></TD></TR><TR><TD ALIGN=RIGHT>Seperator:</TD><TD ALIGN=LEFT><SELECT STYLE="background-color:black;color:gray;border:1 solid" NAME=seperator SIZE=1>document.write('document.write('<option VALUE='/'>/</option>');');document.write('document.write('<option VALUE='-'>-</option>');');</SELECT></TD></TR><TR><TD ALIGN=CENTER COLSPAN=2><INPUT STYLE="background-color:black;color:gray;border:1 solid" TYPE=submit onMouseOver="this.style.backgroundColor='#555555';this.style.color='#bbbbbb'" onMouseOut="this.style.backgroundColor='black';this.style.color='gray'"></TD></TR></TABLE></FORM>
</BODY></HTML>*/
