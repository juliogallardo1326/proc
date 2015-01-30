<?php

include 'includes/sessioncheckuser.php';
require_once("includes/dbconnection.php");
require_once('includes/function.php');
include 'includes/header.php';

$gw_title = $_SESSION['gw_title'];
?>



<div align="center">
<table  width="75%">
  <tr><td>
<p>&nbsp; </p>
<p>&nbsp; </p>
<p>&nbsp; </p>
<ul>
  <li><strong> Log in </strong></li>
  <li><strong> Profile </strong></li>
  <ul>
    <li>Change Password</li>
    <li>View Profile</li>
    <li>Promotional Tools</li>
  </ul>
  <li><strong> Tools </strong></li>
  <ul>
    <li>Web Sites</li>
    <li>Pricing Setup</li>
    <li>Integration Guide</li>
  </ul>
  <li><strong> Ledgers </strong></li>
  <ul>
    <li>Quick Stats</li>
    <li>Ledger </li>
    <li>Projected Settlement</li>
  </ul>
  <li><strong> Transactions </strong></li>
  <li><strong>Support</strong></li>
</ul>
<p>&nbsp; </p>
<p>&nbsp; </p>
<p>&nbsp; </p>
<p>&nbsp; </p>
<ul>
  <li><strong> Log in </strong></li>
</ul>
<p> -Please remember to select both your user type and to enter the security code in all Caps. </p>
<p> -Once logged into the site you will be sent to the LEDGERS/Quick Stats page </p>
<ul>
  <li><strong> Profile </strong></li>
  <ul>
    <li>Change Password</li>
  </ul>
</ul>
<p> -Changing your password has never been easier. Just enter in your old password and then choose and enter in your new password twice. </p>
<ul>
  <ul>
    <li>View Profile</li>
  </ul>
</ul>
<p> -You can easily view your company information, website info and information on your rates and fees. </p>
<ul>
  <ul>
    <li>Payment Details</li>
  </ul>
</ul>
<p> -Bank Account information may be updated or changed using this page, It is imperative that you enter the requested information into each black field, as inaccurate information or a lack of information could cause delays and problems for your payments and processing. </p>
<ul>
  <li><strong> Tools </strong></li>
  <ul>
    <li>Websites</li>
  </ul>
</ul>
<p> -Prior to integrating a site with <?=$gw_title?> you must fill out this form and tell us what payment methods you wish to accept at this site. </p>
<p> -If you have existing web sites whose payment methods need to be changed, please click on the link. At the top of this form there is a hyper link titled, &ldquo;View/Edit Current Websites&rdquo;. You will now see a separate page which details all of your active sites with <?=$gw_title?> and allows you to select them individually and change the payment methods accepted. </p>
<ul>
  <ul>
    <li>Pricing Setup</li>
  </ul>
</ul>
<p> -Create new One-Time or Recurring Price Point Sub Account(s) </p>
<p> +This page will allow you to set up a new price point to charge your customers. There are two options, if you want to charge your customers a one time fee then fill out the top form. However if you want to charge your customer a recurring fee then fill out the bottom form. </p>
<p> +Set up One Time Payment. Enter the amount you wish to charge for your service or product. The description field is optional. If you choose to enter in a description it will be displayed to your customer at the time of purchase on the product payment form </p>
<p> +Set up Recurring Transaction. If you choose to have a trial, you will need to check the trial box and list the number of days the trial should last as well as the amount to be charged during that trial period. Enter the amount to be charged to your customers either after the trial or if there is no trial period into the field titled &ldquo;Recurring Amount&rdquo;. Be sure to specify the interval for charging your customer in either days or months and the number of days or months. If you choose to enter in a description it will be displayed to your customer at the time of purchase on the product payment form </p>
<p> -View/Add Sub Account, you will notice this is a link at the top of the form. If you click on this link it will take you to a page which allows you to view all of your sub accounts. </p>
<p> +Add a sub account: You will notice that at the top of this form there is a hyper link titled &ldquo;add a sub Account&rdquo; this link will take you back to the first page. </p>
<p>&nbsp; </p>
<p>&nbsp; </p>
<ul>
  <ul>
    <li>Integration guide</li>
  </ul>
</ul>
<p> -The integration guide will guide you through a step by step process for integrating your website into the <?=$gw_title?> system. </p>
<p> -Download the integration guide: If you right click this first link and click &ldquo;Save link as&rdquo; your browser will prompt you to pick a folder, to save this adobe acrobat file to. If you don&rsquo;t have adobe acrobat you can download it at http://www.adobe.com/products/acrobat/readstep2.html </p>
<p> -at the bottom of this page you will see the <?=$gw_title?> reference numbers for your web sites. These reference numbers are unique to each of your domain names. That is to say that for each domain you have registered with us, it will have its own unique reference number which will only work with that site and no others. This both enhances security for you and for <?=$gw_title?> </p>
<ul>
  <li><strong> Ledgers </strong></li>
  <ul>
    <li>Quick Stats</li>
  </ul>
</ul>
<p> - Quick stats gives you an overview of your aggregate processing with <?=$gw_title?>. There are multiple snapshots allowing you to view your aggregate totals from one day all the way up to one year back. </p>
<p> -Net column. You will notice that the totals under the &ldquo;Net&rdquo; column are hyperlinked, by clicking the hyperlink on a total you will be able to see a more detailed summary of that total. </p>
<ul>
  <ul>
    <li>Ledger </li>
  </ul>
</ul>
<p> -If there is a specific period you would like to see which is not covered by the Quick Stats &ldquo;Net&rdquo; page, then use this page to select the appropriate dates. This option allows you to select your own dates and view the totals and details, where as Quick Stats will only give you a select few options to choose from. </p>
<ul>
  <ul>
    <li>Projected Settlement</li>
  </ul>
</ul>
<p> -Here you can view your aggregate Net. </p>
<p> -First select the month and year that you would like to view and hit the &ldquo;SUBMIT&rdquo; button. </p>
<p> -$500 is the minimum that <?=$gw_title?> will pay in a given pay period. So if your projected Net is less than $500 that amount will be rolled over to the next pay period. </p>
<p> -You may click here on the totals to view wire transfer status </p>
<ul>
  <li><strong> Transactions </strong></li>
</ul>
<p> -This page allows you many options to find and narrow down your search for transactions. </p>
<p> -Dates: you can search specific dates by selecting the appropriate date. The &ldquo;&hellip;&rdquo; button will show you a calendar upon which you can click and make your appropriate date selection. </p>
<p> -Payment type: you can search based on payment type. Select the payment type you wish to search for by clicking it on the drop down menu. </p>
<p> -Status: you may search based on the status of your transaction, just check the boxes that you want to search, (Pending Check Transactions, Declined, Refunded, Approved, Recurring Billing, Charge Back, Display Test Transactions). </p>
<p> -Customer: You may search for a specific customer&rsquo;s transaction by entering in their first and last names, e-mail or reference number. </p>
<p> -After selecting all your search criteria click on the &ldquo;VIEW&rdquo; button to perform the search. </p>
<p> +Search results page: </p>
<p> -The Search results page will show you the total for all the listed transactions as well as their status, amount, payment type, and what customer that transaction was for. </p>
<p>&nbsp;</p>
<ul>
  <li><strong> Support </strong></li>
</ul>
<p><strong></strong>If you encounter errors or problems please use this section to report a bug or ask a question. This will take you to a page with a box to type your problem into. Please be as specific as possible when detailing the type of problem you were having and what you were doing or trying to access.</p>
</td>
  </tr></table></div>
<?php
include("includes/footer.php");
	?>