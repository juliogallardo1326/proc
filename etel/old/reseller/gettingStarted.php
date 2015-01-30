<?php
	include("includes/sessioncheck.php");
	$headerInclude="startHere";
	include("includes/header.php");
	require_once("../includes/updateAccess.php");
	
if($companyInfo['en_info']['Reseller']['Completion']<1)
	etel_update_serialized_field('cs_entities','en_info'," en_ID = '".$companyInfo['en_ID']."'",
	array('Reseller'=>array('Completion'=>1)) );

?><br><br>
<table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tr>
   		 <td width="100%" valign="top" align="center">
       <table border="0" cellpadding="0" cellspacing="0" width="85%" class="disbd">
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#999999" height="20">
              <img border="0" src="../images/spacer.gif" width="1" height="1">
              </td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center" bgcolor="#2F5F68" height="5"><img border="0" src="../images/spacer.gif" width="1" height="1"></td>
            </tr>
            <tr>
              <td width="100%" valign="top" align="center">
            <table  border="0" cellspacing="0" cellpadding="0" width="795" height="61">
              <tr>
                <td  class="bentx">
                 <p>Dear new re-seller,</p>

				 <p>Welcome to <?=$_SESSION["gw_title"]?>'s partner program. You have made the right choice
				 when choosing <?=$_SESSION["gw_title"]?> as your global partner for referring your medium to
				 high risk merchants for Offshore Credit Card, ACH, and WEB900 Billing. As a
				 preferred reseller of <?=$_SESSION["gw_title"]?>, you will be entitled to refer new merchants
				 to our company and then receive an ongoing residual commission for your
				 involvement.</p>

				 <p>Below are complete instructions on how to work with us as a preferred
				 reseller:</p>

				 <p>Step 1- Click on “Start Here” and change your password to something you can easily
				 remember.<span style="mso-spacerun: yes">  </span>Next, you will then need to
				 complete the online partner application form so that we have all of the details
				 about your company and banking details to deposit your commissions into. Please
				 note that your commissions can be transferred to any bank in the world, as we
				 are an international processor. Once you have completed the partner application
				 form, you will need to accept the terms and conditions of the reseller contract
				 to become a new partner with us. Once complete, you are a new reseller and can
				 begin signing up new merchants and start making money!</p>

				 <p>Step 2-Congradulations, you are now ready to begin promoting <?=$_SESSION["gw_title"]?>! First,
				 click on <b style='mso-bidi-font-weight:normal'><span style='color:red'>“Profile”</span></b>.<span
				 style="mso-spacerun: yes">  </span>Here you will find your link code to promote
				 <?=$_SESSION["gw_title"]?>.<span style="mso-spacerun: yes">  </span>There are you can
				 promote <?=$_SESSION["gw_title"]?> on the web.<span style="mso-spacerun: yes">
				 </span>Either place the text link on your website or use our pre-made high
				 converting static or animated banners and link your partner code to earn credit
				 for your merchant signups.<span style="mso-spacerun: yes">  </span>Now you can
				 successfully begin receiving credit for all new merchant signups through our
				 text or banner ads.<span style="mso-spacerun: yes">  </span></p>

				 <p>Step 3-If you want to directly sign up new merchants by entering in
				 preliminary details for them, click on <strong><span style='color:red'>&quot;Portfolio&quot;,
				 </span></strong><strong><span style='font-weight:normal'>then</span><span
				 style='color:red'> </span></strong>click <strong><span style='color:red'>&quot;Add
				 Merchant&quot;.</span></strong><span style="mso-spacerun: yes">  </span>You
				 will input their company name and email address and a welcome letter will be
				 sent to the merchant (which you can view this letter) stating your company has
				 registered them in the <?=$_SESSION['gw_title']?> system, and they are now ready to login and
				 complete the application process.<span style="mso-spacerun: yes">  </span>Each
				 new merchant you ADD will be checked in our database by company name and email
				 address to ensure that it is not already in the system or processing with
				 <?=$_SESSION['gw_title']?>. Once you add a new merchant, this now becomes your company’s
				 merchant and cannot be registered by any other reseller who promotes <?=$_SESSION['gw_title']?></p>

				 <p>All of your registered merchants will fill out an online fully automated
				 merchant application; they are also prompted to upload required documents. They
				 are as follows: </p>

				 <ol start=1 type=1>
				  <li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
				      mso-list:l1 level1 lfo2;tab-stops:list .5in'>Drivers License/Passport
				      Photo </li>
				  <li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
				      mso-list:l1 level1 lfo2;tab-stops:list .5in'>Articles of Incorporation </li>
				  <li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
				      mso-list:l1 level1 lfo2;tab-stops:list .5in'>Last 3 months previous
				      processing history (business plan if start-up) </li>
				 </ol>

				 <p>As your registered merchants upload these scanned items in our system and
				 fill out the automated online merchant application, you are able to view the
				 status of how many documents they have uploaded, if they have filled out the
				 merchant application online completely, etc, giving you complete control so
				 that you can stay on your merchants and know there status with <?=$_SESSION["gw_title"]?>.
				 This way, if you have a merchant contact you asking the status of their
				 account, you can easily login to the system and advise them where their account
				 is in the approval process. </p>

				 <p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'>This
				 concludes the steps for promoting <?=$_SESSION["gw_title"]?> and how the merchant application
				 process takes place.<span style="mso-spacerun: yes">  </span>Now you will learn
				 your responsibilities as a reseller after your merchant has signed up for an
				 offshore merchant account:</p>

				 <p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'>After
				 your referred merchants complete the online application and upload required
				 documents necessary for obtaining an offshore merchant account, your company
				 will be emailed the merchants buy rates and fees within 24 hours or completion.<span
				 style="mso-spacerun: yes">  </span>Rates and fees are based on a case by case
				 basis depending on merchant type, volume, risk, etc. Please understand that <?=$_SESSION['gw_title']?>
				 is only an international processor so social security number or credit is not a
				 factor when applying for a merchant account. International banks base credit by
				 business model and volume, the rates and fee structure changes depending on
				 what risk the merchant is to the bank.</p>

				 <p class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto'>You
				 are able to mark up the buy rates and keep 100% above the rates and fees, so
				 what ever you can sell your merchants at, you the reseller determine how much
				 money you can make!<span style="mso-spacerun: yes">  </span>Each month on the
				 10th, you will be sent a wire transfer for the commissions earned of your
				 processing merchants. Please note that residuals are calculated on a net basis,
				 so if you introduce a negative merchant who causes charge backs, its negative
				 balance will carry over into your processing portfolio. </p>

				 <p>When you refer a new merchant, you are able to make money 3 ways: </p>

				 <ol start=1 type=1>
				  <li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
				      mso-list:l0 level1 lfo1;tab-stops:list .5in'>Se-Up Fee </li>
				  <li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
				      mso-list:l0 level1 lfo1;tab-stops:list .5in'>Discount Rate </li>
				  <li class=MsoNormal style='mso-margin-top-alt:auto;mso-margin-bottom-alt:auto;
				      mso-list:l0 level1 lfo1;tab-stops:list .5in'>Transaction fee</li>
				 </ol>

				 <p>This is the general overview from start to finish of how to successfully
				 promote and service your referred merchants.<span style="mso-spacerun: yes">
				 </span>Some of <?=$_SESSION['gw_title']?>’s top resellers are making in excess or $50, 000+ or
				 more each month and so can you.<span style="mso-spacerun: yes">  </span>If you
				 have any questions, support, or just want ideas on how to promote <?=$_SESSION["gw_title"]?>
				 to potential web sites needing credit card processing, call or email us
				 24/7/365 and we are always here to help you.<span style="mso-spacerun: yes">
				 </span>Good luck and welcome aboard!</p>

				 <p>Sincerely, </p>

<p><a href="<?=$_SESSION["gw_domain"]?>"><?=$_SESSION["gw_title"]?></a></p>
				  <p></p>
				  <p></p>
				  <p></p>
				  <p></p>
				  <p></p>
				</td>
              </tr>
              <tr>
                <td align="center" valign="middle" width="793" height="40"><a href="javascript:window.history.back();"><img border="0" src="../images/back.jpg"></a>&nbsp;&nbsp;<a href="resellerApplication.php"><img border="0" src="../images/continue.gif"></a></td>
              </tr>
            </table>
              </td>
            </tr>
          </table>
	 </td>
  	</tr>
	</table><br>
	<?php

	include("../includes/footer.php");
	
?>