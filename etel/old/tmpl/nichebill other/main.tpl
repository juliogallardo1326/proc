{include file='main_header.tpl'}
  <!-- Begin Main -->
<div align="center" style="height:100%;">
 <div class="FrontTable">
   <div class="CallUsPhone">Contact Us at<br /> 1-800-123-4567</div>
   <div class="FrontLogin">
    <form method="post" {if $login_fails} class="Failed" {/if}>
     User: <input type="text" name="username" class="TextField" />
     Pass: <input type="password" name="password" class="TextField" />  &nbsp;
     <input type="submit" name="Submit" value="Login" />
    </form>
   </div>
   <div class="Row1x1">
    <img id="FadeImageBlank" class="PaymentTypes" src="{$tempdir}/images/frontpage/FrontPage_payment_types.png"></img>
    <img id="FadeImageBlank" class="FadeImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_blank.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_apply.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_contact.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_demo.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_faq.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_home.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_rates.png"></img>
    <img class="LoadImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_services.png"></img>
    <img id="FadeImage2" class="FadeImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_blank.png"></img>
    <img id="FadeImage1" class="FadeImage" src="{$tempdir}/images/frontpage/FrontPage_fadeimage_blank.png"></img>
    <div class="Button" onmousemove="ShowFadeImage(this,'home')">Home</div>
    <div class="Button" onmousemove="ShowFadeImage(this,'services')">Services</div>
    <div class="Button" onmousemove="ShowFadeImage(this,'rates')">Rates</div>
    <div class="Button" onmousemove="ShowFadeImage(this,'apply')">Apply Now</div>
    <div class="Button" onmousemove="ShowFadeImage(this,'contact')">Contact Us</div>
    <div class="Button" onmousemove="ShowFadeImage(this,'faq')">FAQ</div>
    <div class="Button" onmousemove="ShowFadeImage(this,'demo')">Demo</div>
    <div class="Services" id="flashcontent" style="padding:40px;">Flash
	 <script type="text/javascript">
	var so = new SWFObject("{$tempdir}images/frontpage/services.swf", "Flashloader", "320", "320", "8", "");
	so.addParam("quality", "high");
	so.addParam("wmode", "transparent");
	so.addParam("menu", "false");
	so.write("flashcontent");
</script>
	</div>
   </div>
   <div class="Row2x1">
   
    <div class="PageTitle">Welcome to NicheBill.com</div>
   As truly one of the most unique international payment processors,
NicheBill.com is a worldwide leader in “High Risk” Online Payment
Processing. We specialize in issuing offshore merchant accounts and
processing online transactions for both Internet and traditional
businesses around the globe.
   </div>
   <div class="Row3x1"></div>
 </div>
</div>
<script>
ShowFadeImage(null,'apply');
</script>
  <!-- End Main -->
{include file='main_footer.tpl'}