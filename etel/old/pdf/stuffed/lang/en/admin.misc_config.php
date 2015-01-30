<?
$Lang['Title']="General settings";
$Lang['P3P']="P3P compact privacy policy";
$Lang['P3P_ref']="P3P compact privacy policy location";
$Lang['Skin']="Skin";
$Lang['Lang']="Language";
$Lang['WhiteLogo']="Tracker logo URL (for White Label license)";
$Lang['AllowSent']="Send anonymous usage statistics to developers to help improve this product";

$Lang['CheckUrl']="Open URL to see if it is correct";
$Lang['SSLink']="SSL-protected URL of the tracker";
$Lang['CheckSSL']="Please specify an URL of the directory where the tracker can 
be accessed on your SSL-protected site (if you have one). For example, \"https://www.site.com/tracker\". If
you will specify this URL you will be able to generate tracking code suited for
placing on SSL-protected pages (for example, order process pages). This setting
is optional.";

$Lang['UseStore']="Use fallback file based storage for redirect URLs";
$Lang['StoreDescr']="This is a safety measure. If enabled, URLs for redirect based actions and split test
will be additionally saved as files in directory \"store\" in the main
tracker's directory. If the database would be unavailable during a redirect,
then a visitor would still be redirected to a proper location.";

$Lang['WriteErr']="Error! It is not possible to save files in directory 'store'. Please make sure that this directory exists inside the main tracker's directory and that it has proper access rights set.";

$Lang['FromEmail']="\"From\" email address for all outgoing emails";
$Lang['OnlinePeriod']="Maximum number of seconds since the last activity to consider the visitor to be online";

# 11/11/2005

$Lang['UseIpTracking']="Use IP tracking";
$Lang['IpPeriod']="Number of days since the last visit with the same IP";
$Lang['IpNoCookie']="Track by IP only if cookies are disabled";

$Lang['EnableClickFraud']="Enable click fraud detection";
$Lang['FraudCount']="Number of clicks from the same visitor to consider them fraudulent";
$Lang['FraudPeriod']="Number of minutes in which the number of clicks specified above should be performed by the same visitor";

$Lang['VarCamp']="Numerical campaign parameter name (default &mdash; &amp;c)";
$Lang['VarCampSource']="Text-based campaign parameter name (no default, if not specified here, then this feature is disabled)";
$Lang['VarKeyword']="Numerical keyword parameter name (default &mdash; &amp;k)";
$Lang['VarKw']="Text-based keyword parameter name (default &mdash; &amp;kw)";

$Lang['SectionGeneral']="General settings";
$Lang['SectionIp']="IP tracking settings";
$Lang['SectionFraud']="Click fraud settings";
$Lang['SectionTrack']="Tracking parameters";
$Lang['SectionP3P']="P3P compact private policy settings";
$Lang['SectionWhite']="White label settings";

$Lang['UseWhite']="Enable white label mode";

$Lang['UseWhiteLogo']="Hide logo";
$Lang['UseWhiteCopy']="Hide copyright notice";

#11/01/2006

$Lang['TrackingMode']="Tracking mode";
$Lang['TrafficPrior']="Traffic priority";
$Lang['TrafficPrior1']="Paid traffic";
$Lang['TrafficPrior2']="Natural traffic";
$Lang['TrafficPrior3']="Equal priority";

$Lang['PaidEntryPrior']="Paid traffic entry points priority";
$Lang['NaturalEntryPrior']="Natural traffic entry points priority";
$Lang['NoneEntryPrior']="Entry points priority";

$Lang['EntryPrior1']="First entry point";
$Lang['EntryPrior2']="Last entry point";

$Lang['TrafficPriorDescr']="This setting specifies which type of traffic
(if any) will have the highest priority during tracking. A sale or an action 
will be attributed to the type of traffic which is chosen here (if entry 
points from that type of traffic would be found in the visitor's path).";
$Lang['PaidEntryPriorDescr']="
This settings specifies to which PAID traffic entry point a sale or an 
action should be attributed to.";
$Lang['NaturalEntryPriorDescr']="
This settings specifies to which NATURAL traffic entry point a sale or an 
action should be attributed to.
";
$Lang['NoneEntryPriorDescr']="
This settings specifies to which entry point a sale or an action should be 
attributed to.
";



?>