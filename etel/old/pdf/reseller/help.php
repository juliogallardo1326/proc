<?php
include 'includes/sessioncheck.php';
include 'includes/header.php';
?>

<div align="center">
<table  width="75%">
  <tr><td>
<ul>
  <li><strong> Log in </strong></li>
  <li><strong> Profile </strong></li>
  <ul>
    <li>Change Password</li>
    <li>Edit Profile</li>
    </ul>
  <li><strong> Promotional Tools</strong></li>
  <li><strong> Portfolio </strong></li>
  <ul>
    <li>Add New Merchant</li>
    <li>View Pending Application Status</li>
  </ul>
  <li><strong> Ledgers </strong></li>
  <ul>
    <li>Quick Stats</li>
    <li>Ledger </li>
    <li>Projected Settlement</li>
  </ul>
  <li><strong> Support </strong></li>
  
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
    <li>Edit Profile</li>
  </ul>
</ul>
<p> -You will notice that you cannot change your user name, company name, or contact name using this page, if you would however like to make changes to those fields please send an e-mail request to <a href="mailto:Partners@<?=$_SESSION["gw_title"]?>"> Partners@<?=$_SESSION["gw_title"]?></a> and we will promptly help you with your needs. </p>
<p> -E-mail address Changes can be made here, Just enter in your new e-mail twice. </p>
<ul>
  <ul>
    <li>Payment Details</li>
  </ul>
</ul>
<p> -Bank Account information may be updated or changed using this page, It is imperative that you enter the requested information into each black field, as inaccurate information or a lack of information could cause delays and problems for your payments and processing. </p>

    <ul>
      <li><strong> Promotional Tools</strong></li>
    </ul>
    <p> -Use this page to acquire links banners and banner code for promoting <?=$_SESSION["gw_title"]?> </p>
<p> -You will notice that your user name is at the end of the link. Please use this link to direct prospects and clients to <?=$_SESSION["gw_title"]?> so that we can keep track of your merchants and properly compensate you for their business. </p>
<p> -You can use the banner code with any of the banners listed below. </p>
<p> -Just save the banner picture and then put it into a web site linking that picture with the banner code. </p>
<ul>
  <li><strong> Portfolio </strong></li>
  <ul>
    <li>Add New Merchant</li>
  </ul>
</ul>
<p> -Adding a new merchant is easy! Just enter in some basic information into this page&rsquo;s table to get the process started. </p>
<p> -You will notice that at the bottom of the page, below the &ldquo;SUBMIT&rdquo; button there is a link entitled &ldquo;Click to View the Example Letter&rdquo;. </p>
<p> -If you click this link a new window will open showing you the template which will be populated with the information you fill into the forms and e-mailed to the prospective merchant. </p>
<ul>
  <ul>
    <li>View Pending Application Status</li>
  </ul>
</ul>
<p> -To view the pending application status of your various merchants, just select one of the two options. </p>
<p> -By selecting &ldquo;Merchant Type&rdquo; you will be able to view all your pending merchants that fall under that type of business. </p>
<p> -If instead you are interested in viewing just a single specific merchant click instead on the &ldquo;Select Companies&rdquo; and scroll down and select the merchant of your choosing </p>
<ul>
  <li><strong> Ledgers </strong></li>
  <ul>
    <li>Quick Stats</li>
  </ul>
</ul>
<p> - Quick stats gives you an overview of your aggregate business with <?=$_SESSION["gw_title"]?>. There are multiple snapshots allowing you to view your aggregate totals from one day all the way up to one year back. </p>
<p> -Net Earned column. You will notice that the totals under the &ldquo;Net Earned&rdquo; column are hyperlinked, by clicking the hyperlink on a total you will be able to see a more detailed summary of that total. </p>
<ul>
  <ul>
    <li>Ledger </li>
  </ul>
</ul>
<p> -If there is a specific period you would like to see which is not covered by the Quick Stats &ldquo;Net Earned&rdquo; page, then use this page to select the appropriate dates. This option allows you to select your own dates and view the totals and details, where as Quick Stats will only give you a select few options to choose from. </p>
<ul>
  <ul>
    <li>Projected Settlement</li>
  </ul>
</ul>
<p> -Here you can view your aggregate Net Earned from all of your merchants. </p>
<p> -First select the month and year that you would like to view and hit the &ldquo;SUBMIT&rdquo; button. </p>
<p> -$500 is the minimum that <?=$_SESSION["gw_title"]?> will pay in a given pay period. So if your projected Net Earned is less than $500 that amount will be rolled over to the next pay period. </p>
<p> -You may click here on the totals to view wire transfer status </p>
<ul>
  <li><strong> Support </strong></li>
  </ul>
<p><strong></strong>If you encounter errors or problems please use this section. This will take you to a page with a box to type your problem into. Please be as specific as possible when detailing the type of problem you were having and what you were doing or trying to access.</p></td>
  </tr></table></div>
<?php
include("includes/footer.php");
	?>