{config_load file="lang/eng/language.conf" section="OrderPage"}

{if $mt_language != 'eng'}{config_load file="lang/$mt_language/language.conf" section="OrderPage"}{/if}

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>{#OP_PaymentPage#}</title>
<meta http-equiv="Content-Type" content="text/html; charset={#GL_Charset#}">
{literal}
<style>
  .navigation { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; font-weight:bolder; color:#000066 }
  .regular1 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; text-align:justify }
  .regular2 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:13px; font-weight:bolder }
  .tabletop { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; color:#FFFFFF; font-weight:bolder }
  .tableside { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:12px; color:#FFFFFF }
  .tableside2 { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:11px; color:#FFFFFF }
  .tabledigit { font-family:Arial,Verdana,Helvetica,sans-serif; font-size:11px }
  .titlelarge { font-family:Arial Black,Verdana,Helvetica,sans-serif; font-size:16px; color:#000000 }
  .formfield { height:20px; width:100px }
  .formfield2 { height:20px; width:226px }
 </style>
{/literal}
<script language="javascript" src="{$rootdir}/scripts/general.js"></script>
<script language="javascript" src="{$rootdir}/scripts/creditcard.js"></script>
<script language="javascript" src="{$rootdir}/scripts/formvalid.js"></script>
<script language="javascript" src="{$rootdir}/scripts/prototype.js"></script>
{literal}
<script language="JavaScript" type="text/JavaScript">
<!--

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}
//-->
</script>
{/literal}
</head>

<body bgcolor="#FFFFFF"  text="#000066" link="#006699" vlink="#006699" alink="#006699" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td background="images/order_01.gif"><table width="740" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="1"><img src="images/order_01.gif" width="1" height="94"></td>
          <td width="149"><img src="images/order_02.jpg" width="149" height="94"></td>
          <td width="148"><img src="images/order_03.gif" width="148" height="94"></td>
          <td width="417"><table width="417" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><img src="images/order_04.gif" width="417" height="39"></td>
              </tr>
              <tr>
                <td height="37" align="right" bgcolor="#99CCFF" class="tabledigit">NicheBill 
                  Web Payment Service is the designated internet payment processor 
                  and <br>
                  anti-fraud prevention system for &quot;<a href="#">Sitename 
                  Goes Here</a>&quot;</td>
              </tr>
              <tr>
                <td><img src="images/order_07.gif" width="417" height="18"></td>
              </tr>
            </table></td>
          <td width="25"><img src="images/order_05.gif" width="25" height="94"></td>
        </tr>
      </table></td>
  </tr>
</table>
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td><hr width="630" size="1"></td>
  </tr>
  <tr> 
    <td align="center"><span class="regular2">&nbsp;<br>
      Product Description:</span><span class="regular1"> You are asking to order 
      <a href="#">product name here</a> at a price of USD $0.00</span></td>
  </tr>
  <tr>
    <td align="center"><img src="images/spacer.gif" width="660" height="25"></td>
  </tr>
</table>
<table width="700" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#EEEEEE">
  <form>
    <tr> 
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
    <tr> 
      <td width="330" align="center" valign="top"> <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr align="left"> 
            <td colspan="3" class="regular2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User 
              Information</td>
          </tr>
          <tr align="center"> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">First Name:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield3" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Last Name:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield32" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Email Address:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield32" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Phone Number:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield32" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="15"></td>
          </tr>
        </table>
        <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr align="left"> 
            <td colspan="3" class="regular2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;User 
              Billing Address</td>
          </tr>
          <tr align="center"> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Address:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield33" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">City:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield322" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Zip:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield322" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Country:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <select name="country" style="formfield2">
                <option value="al"  >Albania</option>
                <option value="af"  >Afghanistan</option>
                <option value="dz"  >Algeria</option>
                <option value="as"  >American Samoa</option>
                <option value="ad"  >Andorra</option>
                <option value="ao"  >Angola</option>
                <option value="ai"  >Anguilla</option>
                <option value="aq"  >Antarctica</option>
                <option value="ag"  >Antigua and Barbuda</option>
                <option value="ar"  >Argentina</option>
                <option value="am"  >Armenia</option>
                <option value="aw"  >Aruba</option>
                <option value="au"  >Australia</option>
                <option value="at"  >Austria</option>
                <option value="az"  >Azerbaijan</option>
                <option value="bs"  >Bahamas</option>
                <option value="bh"  >Bahrain</option>
                <option value="bd"  >Bangladesh</option>
                <option value="bb"  >Barbados</option>
                <option value="by"  >Belarus</option>
                <option value="be"  >Belgium</option>
                <option value="bz"  >Belize</option>
                <option value="bj"  >Benin</option>
                <option value="bm"  >Bermuda</option>
                <option value="bt"  >Bhutan</option>
                <option value="bo"  >Bolivia</option>
                <option value="ba"  >Bosnia and Herzegovina</option>
                <option value="bw"  >Botswana</option>
                <option value="bv"  >Bouvet Island</option>
                <option value="br"  >Brazil</option>
                <option value="io"  >British Indian Ocean Territory</option>
                <option value="bn"  >Brunei Darussalam</option>
                <option value="bg"  >Bulgaria</option>
                <option value="bf"  >Burkina Faso</option>
                <option value="bi"  >Burundi</option>
                <option value="kh"  >Cambodia</option>
                <option value="cm"  >Cameroon</option>
                <option value="ca"  >Canada</option>
                <option value="cv"  >Cape Verde</option>
                <option value="ky"  >Cayman Islands</option>
                <option value="cf"  >Central African Republic</option>
                <option value="td"  >Chad</option>
                <option value="cl"  >Chile</option>
                <option value="cn"  >China</option>
                <option value="cx"  >Christmas Island (Australia)</option>
                <option value="cc"  >Cocos (Keeling) Islands</option>
                <option value="co"  >Colombia</option>
                <option value="km"  >Comoros</option>
                <option value="cg"  >Congo</option>
                <option value="ck"  >Cook Islands</option>
                <option value="cr"  >Costa Rica</option>
                <option value="ci"  >Cote D'Ivoire (Ivory Coast)</option>
                <option value="hr"  >Croatia (Hrvatska)</option>
                <option value="cu"  >Cuba</option>
                <option value="cy"  >Cyprus</option>
                <option value="cz"  >Czech Republic</option>
                <option value="dk"  >Denmark</option>
                <option value="dj"  >Djibouti</option>
                <option value="dm"  >Dominica</option>
                <option value="do"  >Dominican Republic</option>
                <option value="tp"  >East Timor</option>
                <option value="ec"  >Ecuador</option>
                <option value="eg"  >Egypt</option>
                <option value="sv"  >El Salvador</option>
                <option value="gq"  >Equatorial Guinea</option>
                <option value="er"  >Eritrea</option>
                <option value="ee"  >Estonia</option>
                <option value="et"  >Ethiopia</option>
                <option value="fo"  >Faeroe Islands</option>
                <option value="fk"  >Falkland Islands (Malvinas)</option>
                <option value="fj"  >Fiji</option>
                <option value="fi"  >Finland</option>
                <option value="fr"  >France</option>
                <option value="gf"  >French Guiana</option>
                <option value="pf"  >French Polynesia</option>
                <option value="ga"  >Gabon</option>
                <option value="gm"  >Gambia</option>
                <option value="ge"  >Georgia</option>
                <option value="de"  >Germany</option>
                <option value="gh"  >Ghana</option>
                <option value="gi"  >Gibraltar</option>
                <option value="gr"  >Greece</option>
                <option value="gl"  >Greenland</option>
                <option value="gd"  >Grenada</option>
                <option value="gp"  >Guadeloupe</option>
                <option value="gu"  >Guam</option>
                <option value="gt"  >Guatemala</option>
                <option value="gn"  >Guinea</option>
                <option value="gw"  >Guinea Bissau</option>
                <option value="gy"  >Guyana</option>
                <option value="ht"  >Haiti</option>
                <option value="hn"  >Honduras</option>
                <option value="hk"  >Hong Kong</option>
                <option value="hu"  >Hungary</option>
                <option value="is"  >Iceland</option>
                <option value="in"  >India</option>
                <option value="id"  >Indonesia</option>
                <option value="ir"  >Iran</option>
                <option value="iq"  >Iraq</option>
                <option value="ie"  >Ireland</option>
                <option value="im"  >Isle of Man (U.K.)</option>
                <option value="il"  >Israel</option>
                <option value="it"  >Italy</option>
                <option value="jm"  >Jamaica</option>
                <option value="jp"  >Japan</option>
                <option value="jt"  >Johnston Island</option>
                <option value="jo"  >Jordan</option>
                <option value="kz"  >Kazakhstan</option>
                <option value="ke"  >Kenya</option>
                <option value="ki"  >Kiribati</option>
                <option value="kp"  >Korea (North)</option>
                <option value="kr"  >Korea (South)</option>
                <option value="kw"  >Kuwait</option>
                <option value="kg"  >Kyrgyzstan</option>
                <option value="la"  >Lao P.Dem.R.</option>
                <option value="lv"  >Latvia</option>
                <option value="lb"  >Lebanon</option>
                <option value="ls"  >Lesotho</option>
                <option value="lr"  >Liberia</option>
                <option value="ly"  >Libyan Arab Jamahiriya</option>
                <option value="li"  >Liechtenstein</option>
                <option value="lt"  >Lithuania</option>
                <option value="lu"  >Luxembourg</option>
                <option value="mo"  >Macau</option>
                <option value="mk"  >Macedonia</option>
                <option value="mg"  >Madagascar</option>
                <option value="mw"  >Malawi</option>
                <option value="my"  >Malaysia</option>
                <option value="mv"  >Maldives</option>
                <option value="ml"  >Mali</option>
                <option value="mt"  >Malta</option>
                <option value="mh"  >Marshall Islands</option>
                <option value="mq"  >Martinique</option>
                <option value="mr"  >Mauritania</option>
                <option value="mu"  >Mauritius</option>
                <option value="mx"  >Mexico</option>
                <option value="fm"  >Micronesia</option>
                <option value="mi"  >Midway Islands</option>
                <option value="md"  >Moldova</option>
                <option value="mc"  >Monaco</option>
                <option value="mn"  >Mongolia</option>
                <option value="ms"  >Montserrat</option>
                <option value="ma"  >Morocco</option>
                <option value="mz"  >Mozambique</option>
                <option value="mm"  >Myanmar</option>
                <option value="na"  >Namibia</option>
                <option value="nr"  >Nauru</option>
                <option value="np"  >Nepal</option>
                <option value="nl"  >Netherlands</option>
                <option value="an"  >Netherlands Antilles</option>
                <option value="nc"  >New Caledonia</option>
                <option value="nz"  >New Zealand</option>
                <option value="ni"  >Nicaragua</option>
                <option value="ne"  >Niger</option>
                <option value="ng"  >Nigeria</option>
                <option value="nu"  >Niue</option>
                <option value="nf"  >Norfolk Island</option>
                <option value="mp"  >Northern Mariana Islands</option>
                <option value="no"  >Norway</option>
                <option value="pk"  >Pakistan</option>
                <option value="pw"  >Palau</option>
                <option value="pa"  >Panama</option>
                <option value="pg"  >Papua New Guinea</option>
                <option value="py"  >Paraguay</option>
                <option value="pe"  >Peru</option>
                <option value="ph"  >Philippines</option>
                <option value="pn"  >Pitcairn</option>
                <option value="pl"  >Poland</option>
                <option value="pt"  >Portugal</option>
                <option value="pr"  >Puerto Rico</option>
                <option value="qa"  >Qatar</option>
                <option value="re"  >Reunion</option>
                <option value="ro"  >Romania</option>
                <option value="ru"  >Russian Federation</option>
                <option value="rw"  >Rwanda</option>
                <option value="sh"  >Saint Helena</option>
                <option value="kn"  >Saint Kitts and Nevis</option>
                <option value="lc"  >Saint Lucia</option>
                <option value="pm"  >Saint Pierre and Miquelon</option>
                <option value="vc"  >Saint Vincent and The Grenadines</option>
                <option value="ws"  >Samoa</option>
                <option value="sm"  >San Marino</option>
                <option value="st"  >Sao Tome and Principe</option>
                <option value="sa"  >Saudi Arabia</option>
                <option value="sn"  >Senegal</option>
                <option value="sc"  >Seychelles</option>
                <option value="sl"  >Sierra Leone</option>
                <option value="sg"  >Singapore</option>
                <option value="sk"  >Slovakia</option>
                <option value="si"  >Slovenia</option>
                <option value="sb"  >Solomon Islands</option>
                <option value="so"  >Somalia</option>
                <option value="za"  >South Africa</option>
                <option value="es"  >Spain</option>
                <option value="lk"  >Sri Lanka</option>
                <option value="sd"  >Sudan</option>
                <option value="sr"  >Suriname</option>
                <option value="sj"  >Svalbard and Jan Mayen Islands</option>
                <option value="sz"  >Swaziland</option>
                <option value="se"  >Sweden</option>
                <option value="ch"  >Switzerland</option>
                <option value="sy"  >Syrian Arab Rep.</option>
                <option value="tw"  >Taiwan</option>
                <option value="tj"  >Tajikistan</option>
                <option value="tz"  >Tanzania</option>
                <option value="th"  >Thailand</option>
                <option value="tg"  >Togo</option>
                <option value="tk"  >Tokelau</option>
                <option value="to"  >Tonga</option>
                <option value="tt"  >Trinidad and Tobago</option>
                <option value="tn"  >Tunisia</option>
                <option value="tr"  >Turkey</option>
                <option value="tm"  >Turkmenistan</option>
                <option value="tc"  >Turks and Caicos Islands</option>
                <option value="tv"  >Tuvalu</option>
                <option value="ug"  >Uganda</option>
                <option value="ua"  >Ukraine</option>
                <option value="ae"  >United Arab Emirates</option>
                <option value="gb"  >United Kingdom</option>
                <option value="us"  >United States</option>
                <option value="uy"  >Uruguay</option>
                <option value="uz"  >Uzbekistan</option>
                <option value="vu"  >Vanuatu</option>
                <option value="va"  >Vatican City State (Holy See)</option>
                <option value="ve"  >Venezuela</option>
                <option value="vn"  >Viet Nam</option>
                <option value="vg"  >Virgin Islands (British)</option>
                <option value="vi"  >Virgin Islands (US)</option>
                <option value="wk"  >Wake Island</option>
                <option value="wf"  >Wallis and Futuna Islands</option>
                <option value="eh"  >Western Sahara</option>
                <option value="ye"  >Yemen</option>
                <option value="yu"  >Yugoslavia</option>
                <option value="zr"  >Zaire</option>
                <option value="zm"  >Zambia</option>
              </select> </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">State:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <select name="state" style="formfield2">
                <option value="xx" >Outside US and Canada</option>
                <option value="ak" >Alaska</option>
                <option value="al" >Alabama</option>
                <option value="ar" >Arkansas</option>
                <option value="as" >American Samoa</option>
                <option value="az" >Arizona</option>
                <option value="ca" >California</option>
                <option value="co" >Colorado</option>
                <option value="ct" >Connecticut</option>
                <option value="dc" >District Of Columbia</option>
                <option value="de" >Delaware</option>
                <option value="fl" >Florida</option>
                <option value="ga" >Georgia</option>
                <option value="gu" >Guam</option>
                <option value="hi" >Hawaii</option>
                <option value="ia" >Iowa</option>
                <option value="id" >Idaho</option>
                <option value="il" >Illinois</option>
                <option value="in" >Indiana</option>
                <option value="ks" >Kansas</option>
                <option value="ky" >Kentucky</option>
                <option value="la" >Louisiana</option>
                <option value="ma" >Massachusetts</option>
                <option value="md" >Maryland</option>
                <option value="me" >Maine</option>
                <option value="mi" >Michigan</option>
                <option value="mn" >Minnesota</option>
                <option value="mo" >Missouri</option>
                <option value="mp" >Northern Mariana Is</option>
                <option value="ms" >Mississippi</option>
                <option value="mt" >Montana</option>
                <option value="nc" >North Carolina</option>
                <option value="nd" >North Dakota</option>
                <option value="ne" >Nebraska</option>
                <option value="nh" >New Hampshire</option>
                <option value="nj" >New Jersey</option>
                <option value="nm" >New Mexico</option>
                <option value="nv" >Nevada</option>
                <option value="ny" >New York</option>
                <option value="oh" >Ohio</option>
                <option value="ok" >Oklahoma</option>
                <option value="on" >Ontario</option>
                <option value="or" >Oregon</option>
                <option value="pa" >Pennsylvania</option>
                <option value="pr" >Puerto Rico</option>
                <option value="pw" >Palau</option>
                <option value="ri" >Rhode Island</option>
                <option value="sc" >South Carolina</option>
                <option value="sd" >South Dakota</option>
                <option value="tn" >Tennessee</option>
                <option value="tx" >Texas</option>
                <option value="ut" >Utah</option>
                <option value="va" >Virginia</option>
                <option value="vi" >Virgin Islands</option>
                <option value="vt" >Vermont</option>
                <option value="wa" >Washington</option>
                <option value="wi" >Wisconsin</option>
                <option value="wv" >West Virginia</option>
                <option value="wy" >Wyoming</option>
                <option value="aa" >Armed Forces Americas</option>
                <option value="ae" >Armed Forces Europe</option>
                <option value="ap" >Armed Forces Pacific</option>
                <option value="ab" >Alberta</option>
                <option value="bc" >British Columbia</option>
                <option value="mb" >Manitoba</option>
                <option value="nb" >New Brunswick</option>
                <option value="nf" >Newfoundland & Labrador</option>
                <option value="ns" >Nova Scotia</option>
                <option value="nt" >Northwest Territories</option>
                <option value="nu" >Nunavut</option>
                <option value="ot" >Ontario</option>
                <option value="pe" >Prince Edward Island</option>
                <option value="qc" >Quebec</option>
                <option value="sk" >Saskatchewan</option>
                <option value="yt" >Yukon Territory</option>
                <option value="fm" >Caroline Islands</option>
                <option value="mh" >Marshall Islands</option>
              </select> </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="30"></td>
          </tr>
        </table></td>
      <td width="330" align="center" valign="top"> <table width="300" border="0" cellspacing="0" cellpadding="0">
          <tr align="left"> 
            <td colspan="3" class="regular2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Credit 
              Card Details</td>
          </tr>
          <tr align="center"> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Credit Card No:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield34" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Expiry:</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <input name="textfield323" type="text" class="formfield2">
            </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td width="90" align="right" class="tabledigit">Card ID (CCV2):</td>
            <td width="10"><img src="images/spacer.gif" width="10" height="10"></td>
            <td width="200"> <select name="expire_month" style="formfield2">
                <option value="01" >01</option>
                <option value="02" >02</option>
                <option value="03" >03</option>
                <option value="04" >04</option>
                <option value="05" >05</option>
                <option value="06" >06</option>
                <option value="07" >07</option>
                <option value="08" >08</option>
                <option value="09" >09</option>
                <option value="10" >10</option>
                <option value="11" >11</option>
                <option value="12" >12</option>
              </select> &nbsp;&nbsp; <select name="expire_year" style="formfield2">
                <option value="05"  >2005</option>
                <option value="06"  >2006</option>
                <option value="07"  >2007</option>
                <option value="08"  >2008</option>
                <option value="09"  >2009</option>
                <option value="10"  >2010</option>
                <option value="11"  >2011</option>
                <option value="12"  >2012</option>
                <option value="13"  >2013</option>
                <option value="14"  >2014</option>
                <option value="15"  >2015</option>
              </select> &nbsp;&nbsp; </td>
          </tr>
          <tr> 
            <td colspan="3"><img src="images/spacer.gif" width="300" height="7"></td>
          </tr>
          <tr> 
            <td colspan="3"><table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr> 
                  <td width="190" align="center"><img src="images/cvv2.gif" width="175" height="110"></td>
                  <td width="110"> <table width="100" border="0" align="center" cellpadding="5" cellspacing="0">
                      <tr> 
                        <td align="center" class="tabledigit">Your Card ID or 
                          <strong>CVV2</strong> (credit verification value) is 
                          a 3-digit number found on the back of your card, used 
                          to help prevent fraudulent internet transactions.</td>
                      </tr>
                    </table></td>
                </tr>
              </table></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td colspan="2" align="center"><table width="580" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td class="tabledigit"><li>A transaction fee of USD $0.00 will be 
                added to the cost of your purchase. In total, you will be charged 
                $0.00
              <li>Your purchase will appear on your bill as being charged by 
              <li>You can cancel your subscription anytime at the <a href="#">NicheBill.com</a> 
                Customer Center. 
              <li>All fraudulent transactions will be investigated, as enforced 
                by the law.</td>
          </tr>
          <tr> 
            <td><img src="images/spacer.gif" width="580" height="9"></td>
          </tr>
          <tr> 
            <td align="center" class="tabledigit">By clicking the 'Secure purchase' 
              button, you agree to our <a href="#">Terms and Conditions</a>. Your 
              complete <a href="#">privacy</a> is assured.<br>
              To avoid multiple charges, press 'Secure purchase' only once. Authorization 
              may take a moment.</td>
          </tr>
          <tr> 
            <td><img src="images/spacer.gif" width="580" height="20"></td>
          </tr>
          <tr>
            <td align="center"><input type="submit" name="Submit" value="Secure Purchase"></td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td align="center">&nbsp;</td>
      <td align="center">&nbsp;</td>
    </tr>
  </form>
</table>
<table width="660" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center"><img src="images/spacer.gif" width="660" height="25"></td>
  </tr>
  <tr> 
    <td><hr width="630" size="1"></td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
  <tr> 
    <td align="center">&nbsp;</td>
  </tr>
</table>
</body>
</html>
