<?
$Lang['CreateNewAction']="Create new action";
$Lang['Actions']="actions";
$Lang['CreateAction']="Create action";

$Lang['MustFillName']="Please specify the name of the action.";
$Lang['WhatIsAction']="Please specify the URL of the page.";
$Lang['UnableParseTemplate']="Unable to parse the template.";
$Lang['InvalidDomain']="Invalid domain specified.";
$Lang['UnableCreatePage']="Unable to create a new page.";
$Lang['WriteErr']="Warning! Unable to save a copy of the action's data in a file. Please, make sure that directory 'store' and file 'store/redir_action.nodb' are writable by the tracker.";
$Lang['LangType']="Action type";
$Lang['CatchPage']="Catch loading of a page";
$Lang['CatchRedir']="Use redirect";
$Lang['Template']="URL of the page to catch";
$Lang['Template2']="URL of the page that would be associated with this action";
$Lang['ActionItemVar']="Action target variable (optional)";
$Lang['RedirTo']="Redirect to the following URL";
$Lang['ActionActive']="Gather statistics for this action";
$Lang['NotActive']="Disabled";
$Lang['RedirOnly']="Redirect type";

/////////////////////////////////////

$Lang['RedirUrlRequired']="Redirect url required";
$Lang['DynamicUrl']="Use dynamic URLs for redirect";

$Lang['ActionHelpOnPage']="You can use <b>wildcards</b> in the \"URL of the page to catch\" 
field to catch loading of several pages with similar, but slightly different URLs.<br><br>
Example of using a wildcard: <span style=\"color: #777;\">http://www.your-site.com/*/download.html</span><br><br>

You can also track <b>action targets</b> by specifying a special characters sequence 
\"<span style=\"color: #777;\">{a}</span>\" in any part of the page’s URL, like this:

<blockquote style=\"color: #777\">
http://www.your-site.com/download.php?file={a}
</blockquote>

If a visitor will open a page with the following URL:

<blockquote style=\"color: #777\">
http://www.your-site.com/download.php?file=myfile.zip
</blockquote>

then an action will be triggered and logged and also \"myfile.zip\" will be 
logged as an action target.";

$Lang['ActionHelpRedir']="To pass a <b>dynamic redirect URL</b> to the action, you 
would need to add \"<span style=\"color: #777;\">&rurl=your_url</span>\" 
parameter to the action’s URL.<br><br>

You can also specify an <b>action target</b> in the same manner by adding 
\"<span style=\"color: #777;\">&itm=action_target</span>\" to the actions’s URL.<br><br>

Action's URL is displayed at the top of the action's page after the action is 
created.";

$Lang['NoRedirWithPageTemp']="It's not possible to create a redirect action with the specified URL";
$Lang['OnlyOneActionTarget']="Only one action target could be specified in the URL";

$Lang['UrlName']="Action's url:&nbsp;&nbsp;";

$Lang['ChooseSiteFirst']="Choose site!";

# 11/12/2005

$Lang['CatchCode']="Place tracking code inside a page";
$Lang['ActionHelpCode']="
After this action will be created you will be able to get a special tracking
code for it by clicking on the \"Get tracking code\" link that will appear 
in the top menu.<br><br>

You should place this tracking code inside a page on your site. Every time this 
page will be loaded by a visitor the action will be triggered.  
";

$Lang['GenerateCode']="<nobr>Get tracking code</nobr>";

?>