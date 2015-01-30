<?php
/**
* Language file variables.
*
* @see GetLang
*
* @version     $Id: language.php,v 1.32 2005/12/12 00:20:22 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @subpackage Language
* @filesource
*/

/**
* Here are all of the variables... Please backup before you start!
*/
define('LNG_ConfirmCancel', 'Are you sure you want to cancel?');

define('LNG_CurrencySymbol', "$");

define('LNG_CampaignStats', "Campaign Statistics");
define('LNG_PPCStats', "Pay Per Click Statistics");
define('LNG_ReferrersStats', "Referrer Statistics");
define('LNG_SearchStatsKeyword', "Search Statistics (By Keyword)");
define('LNG_SearchStatsKeyword_Specific', "Search Statistics (By Keyword: %s)");
define('LNG_SearchStatsEngine', "Search Statistics (By Engine)");
define('LNG_SearchStatsEngine_Specific', "Search Statistics (By Engine: %s)");
define('LNG_Campaign_Specific', 'Campaign Statistics (By Site: %s)');
define('LNG_PPC_Specific', 'Pay Per Click Statistics (By Search Engine: %s)');

define('LNG_SearchEngine', "Search Engine");

define('LNG_ControlPanel', "Control Panel");
define('LNG_GetTrackingCode', "Get Tracking Code");
define('LNG_GetConversionCode', "Get Conversion Code");
define('LNG_Users', "Users");
define('LNG_Settings', "Settings");
define('LNG_Logout', "Logout");
define('LNG_Go', 'Go');
define('LNG_To', 'To');

define('LNG_Page', 'Page');
define('LNG_Of', 'of');
define('LNG_For', 'for');
define('LNG_GrandTotal','Grand Total');

define('LNG_GoToFirst', 'Go To First Page');
define('LNG_GoToLast', 'Go To Last Page');

define('LNG_NumberFormat_Dec', '.');
define('LNG_NumberFormat_Thousands', ',');

define('LNG_DateFormat', 'd M Y');
define('LNG_TimeFormat', 'd M Y, h:i A');

define('LNG_NA', 'N/A');

define('LNG_ViewingResultsFor', 'Viewing results for');

define('LNG_Home', "Home");
define('LNG_Help_Campaigns', "View website visits and conversions that come from marketing campaigns");
define('LNG_Help_PPC', "View website visits and conversions that come from search engine pay per click results");
define('LNG_Help_Search', "View website visits and conversions that come from organic search results");
define('LNG_Help_Referrers', "View website visits and conversions that come from referrers");
define('LNG_Help_Conversions', 'View a list of all of your conversions');

define('LNG_FirstVisit', 'Tracking started on %s');

define('LNG_Total', "Total");
define('LNG_TotalTrafficSummary', "Total Traffic Summary");
define('LNG_TotalVisits', "Total Visits");
define('LNG_TotalConversions', "Total Conversions");
define('LNG_Conversions', "Conversions");
define('LNG_Revenue', "Revenue");
define('LNG_ROI', "ROI");
define('LNG_Visits', "Visits");

define('LNG_Visits_helpText', "Number of <b>unique</b> visits for the specified date range. <br><br>Unique visitors are counted only once no matter how many times they visit the site");

define('LNG_Visits_percent_helpText', "Percentage of visits from this particular source (eg. Search results), relative to the total number of visits for the specified date range.");

define('LNG_Conv_helpText', "Number of conversions for the specified date range.");

define('LNG_Conv_percent_helpText', "Conversion ratio -- number of conversions per visit for the specified date range.");
define('LNG_Revenue_helpText', "Amount of revenue generated for the specified date range.");
define('LNG_ROI_helpText', "Return On Investment -- Percentage of revenue generated in proportion to cost of campaign or PPC");



define('LNG_ToDo', 'To Do');

define('LNG_Mon', 'Mon');
define('LNG_Tue', 'Tue');
define('LNG_Wed', 'Wed');
define('LNG_Thu', 'Thu');
define('LNG_Fri', 'Fri');
define('LNG_Sat', 'Sat');
define('LNG_Sun', 'Sun');

define('LNG_Jan', 'Jan');
define('LNG_Feb', 'Feb');
define('LNG_Mar', 'Mar');
define('LNG_Apr', 'Apr');
define('LNG_May', 'May');
define('LNG_Jun', 'Jun');
define('LNG_Jul', 'Jul');
define('LNG_Aug', 'Aug');
define('LNG_Sep', 'Sep');
define('LNG_Oct', 'Oct');
define('LNG_Nov', 'Nov');
define('LNG_Dec', 'Dec');

define('LNG_DateRange', "Date Range");
define('LNG_Today', "Today");
define('LNG_Yesterday', "Yesterday");
define('LNG_Last24Hours', 'Last 24 Hours');
define('LNG_Last7Days', "Last 7 Days");
define('LNG_Last30Days', "Last 30 Days");
define('LNG_ThisMonth', "This Month");
define('LNG_LastMonth', "Last Month");
define('LNG_AllTime', "All Time");
define('LNG_Custom', "Custom");

define('LNG_StartDate', "Start Date");
define('LNG_Days', "Days");
define('LNG_Day', "Day");
define('LNG_Week', "Week");
define('LNG_Month', "Month");
define('LNG_3Months', "3 Months");
define('LNG_6Months', "6 Months");
define('LNG_12Months', "12 Months");

define('LNG_Conv', "Conv");
define('LNG_Action', "Action");
define('LNG_View', "View");

/**
* Create Campaign
*/
define('LNG_Campaigns', "Campaigns");
define('LNG_CreateCampaignSite', "Web Site");
define('LNG_CreateCampaignName', "Name");
define('LNG_CampaignInformation', "Campaign Information");
define('LNG_CampaignLink', "Campaign Link");
define('LNG_PPCLink', 'Pay-per-click Link');
define('LNG_LandingPageURL', "Landing Page URL");
define('LNG_AllLandingPages', 'All landing pages');
define('LNG_CampaignCost', "Cost ($)");
define('LNG_CostType', "Cost Type");
define('LNG_Recurring', "Recurring");
define('LNG_Period', "Period");
define('LNG_CampaignStartDate', "Start Date");
define('LNG_Help_CreateCampaign', 'Enter the required information and click the \'generate\' button to create a campaign tracking URL. Copy and use this URL as the landing page URL for your campaign link.');

define('LNG_CampaignSiteError', "Please enter the web site name this campaign will be running on.");
define('LNG_CampaignNameError', "Please enter a name for this campaign.");
define('LNG_CampaignURLError', "Please enter a landing page URL for this campaign.");
define('LNG_CampaignCostError', "Please enter a cost for this campaign.");
define('LNG_CampaignCostError2', "Please enter a valid numeric cost for this campaign.");
define('LNG_CampaignPeriodError', "Please enter a period (days) for this campaign.");
define('LNG_CampaignPeriodError2', "Please enter a valid numberic number of days for this campaign.");

define('LNG_HLP_CreateCampaignSite', "The web site this marketing campaign will be displayed on. eg. If placing a banner on Yahoo, then enter in Yahoo here.<br><br>Campaign stats will be grouped by web site so you can see which sites are returning the best results.");
define('LNG_HLP_CreateCampaignName',"The name of this marketing campaign. Used to display in the campaign stats. eg. Yahoo Banner Ad Campaign");
define('LNG_HLP_LandingPageURL', "The URL the visitor will end up on after clicking this link. eg. http://www.domain.com/product/");
define('LNG_HLP_CampaignCost', "The cost of this marketing campaign. If the campaign runs for a fixed period of time, then enter in the total cost of the campaign. <br><br>If the campaign is recurring, for example once per month, then enter in the cost per period, eg cost per month.");
define('LNG_HLP_CostType', "Is this a recurring cost? eg. If you are billed once per month for this particular campaign, then it is a recurring campaign");
define('LNG_HLP_Period', "The recurring cost period. eg. If you are billed once per month for this particular campaign, then you would select \'Month\'");
define('LNG_HLP_CampaignStartDate', "The date this campaign starts");

/**
* These are used by campaigns and ppc's.
*/
define('LNG_EncodeInfo', 'Encode Information?');
define('LNG_EncodeInfoYes', 'Yes, encode information.');
define('LNG_HLP_EncodeInfo', 'This encodes important information (eg cost) so visitors to your site can\\\'t see it.'); // need 3 \'s for the helptip.

/**
* Create PPC
*/
define('LNG_PayPerClick', 'Pay Per Click');
define('LNG_PPCName', "Pay Per Click Name");
define('LNG_PPCSummary', "Total Pay Per Click Results");
define('LNG_PPCSummary_helpText', "Results for the total visits and conversions that have come from pay per clicks. <br><br>You can use this to compare with more specific campaign statistics above.");
define('LNG_PPCInformation',"Pay Per Click Information");
define('LNG_CreatePPCEngine',"Search Engine");
define('LNG_CreatePPCName',"Name");
define('LNG_CreatePPCCost',"Cost Per Click ($)");

define('LNG_Help_CreatePPC', "Enter the required information and click the 'generate' button to create ppc tracking URL's. Copy and use this URL as the landing page URL for your ppc link.");
define('LNG_HLP_CreatePPCEngine',"The search engine this pay per click link will be displayed on. eg. If placing a ppc on Google, then enter in Google here.<br><br>Pay Per Click stats will be grouped by search engine so you can see which engines are returning the best results.");
define('LNG_HLP_CreatePPCName',"The name of this pay per click link. Used to display in the Pay Per Click stats. eg. Product Adwords Link #1. <br><br>You can also use the name field to specify the keywords used eg. Product (My Keywords Here)");
define('LNG_HLP_CreatePPCCost',"The cost per click for this Pay Per Click link");


define('LNG_SearchResults', "Search Results");
define('LNG_Referrers', "Referrers");
define('LNG_GenerateExcel', "Export Report");
define('LNG_PrintReport', 'Print Report');
define('LNG_GenerateExcel_disabled', 'Exporting and printing are not enabled as you do not have any statistics to report.');

define('LNG_CreateUser', "Create User");
define('LNG_CreatePPC', "Create Pay Per Click Link");
define('LNG_CreateCampaign', "Create Campaign Link");

define('LNG_PPCEngineError', "Please enter the search engine this ppc link will be running on.");
define('LNG_PPCNameError', "Please enter a name for this ppc link.");
define('LNG_PPCURLError', "Please enter a landing page URL for this ppc link.");
define('LNG_PPCCostError', "Please enter a valid numeric cost for this ppc link.");


define('LNG_HoldMouseOver', "Hold mouse over underlined text for more information");
define('LNG_LaunchQuickStart', "Launch Quick Start Popup");

define('LNG_BySearchKeywords', "By Search Keywords");
define('LNG_BySearchEngine', "By Search Engine");

define('LNG_Next', "Next");
define('LNG_Back', "Back");
define('LNG_ResultsPerPage',"Results per page");

define('LNG_SearchKeywords', "Search Keywords");
define('LNG_Keyword', "Search Keywords");
define('LNG_LandingPages', "Landing Pages");

// view all stuff..
define('LNG_HelpLandingPage_Engines', 'View landing pages from search engine results so you can see which pages turn into conversions.');
define('LNG_HelpLandingPage_Keywords', 'View landing pages from search engine keywords so you can see which pages turn into conversions.');
define('LNG_HelpLandingPage_Referrers', 'View landing pages from general referrers so you can see where your traffic comes from and where it hits your site.');

define('LNG_SearchResultsSummary', "Total Search Results");
define('LNG_SearchResultsSummary_helpText', "Results for the total visits and conversions that have come from organic search results. <br><br>You can use this to compare with more specific search engine statistics above.");
define('LNG_SearchEngines', "Search Engines");
define('LNG_ViewByEngines', "View By Search Engine");
define('LNG_ViewByKeywords', "View By Keywords");

define('LNG_ReferrerResultsSummary', "Total Referrer Results");
define('LNG_ReferrerResultsSummary_helpText', "Results for the total traffic and conversions that have come from referrers.");

define('LNG_ReferrerURL', "Referrer URL");
define('LNG_DirectVisit', "Direct Visit / Bookmark");
define('LNG_ViewAll', "View All");
define('LNG_ReferrersSummary', "Referrers Summary");

define('LNG_WebSite', "Web Site");
define('LNG_CampaignName', "Campaign Name");
define('LNG_CampaignSummary', "Total Campaign Results");
define('LNG_CampaignSummary_helpText', "Results for the total visits and conversions that have come from campaigns. <br><br>You can use this to compare with more specific campaign statistics above.");
define('LNG_TotalCost', "Total Cost");
define('LNG_TotalCostHelp', "Total cost of campaign, irrespective of the selected date range");
define('LNG_Cost', "Cost");

define('LNG_TrackingCode', "Tracking Code");
define('LNG_Help_TrackingCode', "Copy and paste the following Tracking Code to every page of your website. You should place this code  in the &lt;head> of your pages.");

define('LNG_ConversionCode', "Conversion Code");
define('LNG_ConversionInformation', "Conversion Information");

define('LNG_Help_ConversionCode', "Enter the required information and copy the generated code to your conversion page.<br>For example, if this was an 'order' conversion, then the code would be placed on the final page of the order process on your site.<br/>You can substitute the real order amount (if any) in your website by using PHP, ASP or other code.");

/**
* Create Conversion
*/
define('LNG_ConversionType', "Type");
define('LNG_Sale', "Sale");
define('LNG_Other', "Other");
define('LNG_ConversionName', "Name");
define('LNG_ConversionAmount', "Amount");
define('LNG_GenerateCode', "Generate Code");

define('LNG_ConversionNameError', "Please enter a name for this conversion");
define('LNG_ConversionAmountError', "Please enter a conversion amount");
define('LNG_ConversionAmountError2', "Please enter a valid numeric conversion amount");

define('LNG_HLP_ConversionType', "Select the type of conversion. Select \'Sale\' to indicate a conversion that contains an amount, such as a product purchase or paid sign up.<br><br>Select \'Other\' for leads such as newsletter signups, contact form submission etc.");
define('LNG_HLP_ConversionName', "Enter the name of this conversion. eg. Product Sale or Newsletter Signup");
define('LNG_HLP_ConversionAmount', "Enter the amount of revenue generated for this conversion.");

/**
* Additional' language vars.
*/
define('LNG_ErrCouldntLoadTemplate', 'Unable to load template: %s');
define('LNG_PageTitle', 'Control Panel');
define('LNG_Status', 'Status');
define('LNG_Edit', 'Edit');
define('LNG_Delete', 'Delete');
define('LNG_Save', 'Save');
define('LNG_Cancel', 'Cancel');

define('LNG_EmailAddress', 'Email Address');
define('LNG_Password', 'Password');
define('LNG_PasswordConfirm', 'Password (Confirm)');
define('LNG_PasswordConfirmAlert', 'Please confirm your new password');
define('LNG_PasswordsDontMatch', 'Your passwords don\'t match. Please try again.');

define('LNG_GoBack', 'Go Back');
define('LNG_NoAccess', 'You don\'t have access to this area.');

/**
* Login Page
*/
define('LNG_LoginTitle', 'Control Panel Login');
define('LNG_Help_Login', 'Log in with your username and password below.');
define('LNG_LoginDetails', 'Login Details');
define('LNG_Login', 'Login');
define('LNG_NoUsername', 'Please enter your username');
define('LNG_NoPassword', 'Please enter a password');
define('LNG_BadLogin', 'Your username or password are incorrect. Please try again.');
define('LNG_LogoutSuccessful', 'You have been logged out successfully.');
define('LNG_RememberMe', 'Remember my login details');
define('LNG_ForgotPasswordReminder', 'If you have forgotten your password, click <a href="index.php?Page=Login&Action=ForgotPass">here</a>');

/**
* Forgot password page.
*/
define('LNG_ForgotPasswordTitle', 'Forgot your password?');
define('LNG_ForgotPasswordDetails', 'Enter your details below.');
define('LNG_Help_ForgotPassword', 'Enter your details below to reset your password.');
define('LNG_NewPassword', 'New Password');
define('LNG_SendPassword', 'Send Password');
define('LNG_BadLogin_Forgot', 'Your username is incorrect. Please try again.');
define('LNG_ChangePasswordSubject', 'Your password has been changed.');
define('LNG_ChangePasswordEmail', 'You have recently chosen to change your control panel password. To confirm this, please click on the following link: %s');
define('LNG_ChangePassword_Emailed', 'You have been sent a link to click to activate your new password.');
define('LNG_PasswordUpdated', 'Your password hase been updated successfully. Please login below.');

/**
* Settings Page
*/
define('LNG_Help_Settings', 'Update the settings in the form below and click "Save", or click "Cancel" to keep the current settings.');

define('LNG_SettingsSaved', 'The modified settings have been saved successfully.');
define('LNG_SettingsNotSaved', 'The modified settings have been not been saved.');

define('LNG_DatabaseIntro', 'Database Details');
define('LNG_DatabaseType', 'Database Type');
define('LNG_DatabaseUser', 'Database User');
define('LNG_HLP_DatabaseUser','Username used to login to the Database');
define('LNG_DatabasePassword', 'Database Password');
define('LNG_HLP_DatabasePassword','Password used to login to the Database');
define('LNG_DatabasePasswordConfirm', 'Database Password (confirm)');
define('LNG_HLP_DatabasePasswordConfirm','Re-type password to confirm it is correct');
define('LNG_DatabaseHost', 'Database Hostname');
define('LNG_HLP_DatabaseHost', 'Hostname or IP address of the database server. eg. localhost');
define('LNG_DatabaseName', 'Database Name');
define('LNG_HLP_DatabaseName', 'The name of the database being used.');
define('LNG_DatabaseTablePrefix', 'Database Table Prefix');
define('LNG_HLP_DatabaseTablePrefix', 'Optional text to be prepended to tables. This is recommended if you are using a database that is not empty.');
define('LNG_DatabasePasswordsDontMatch', 'Your database passwords don\'t match. Please try again.');

define('LNG_Miscellaneous', 'Miscellaneous Settings');

define('LNG_ApplicationURL', 'Application URL');
define('LNG_HLP_ApplicationURL', 'Full URL path to the installation. eg. http://www.domain.com/track');

define('LNG_CookieTime', 'Cookie Expiry Time');
define('LNG_HLP_CookieTime', 'How long should the visit cookie stay on the visitors machine? (Hours)');

define('LNG_DelCookieOnPurchase', 'Delete old cookie?');
define('LNG_DelCookieIntro', 'Yes, delete old cookie');
define('LNG_HLP_DelCookieOnPurchase', 'When a purchase is made through your site, should the cookie tracking their usage be deleted?');

 define('LNG_ApplicationEmail', 'Contact Email Address'); 	 
 define('LNG_HLP_ApplicationEmail', 'Email address that emails are sent from when a user requests a \\\'forgotten password\\\'.');

define('LNG_LicenseKeyIntro', 'License Key Details');
define('LNG_LicenseKey', 'License Key');
define('LNG_LicenseKeyUpdated', 'Your license key has been updated. You will be logged out completely and you will need to log in again for the change to take effect.');
define('LNG_HLP_LicenseKey', 'The application license key provided upon your product purchase');

/**
* Users Area
*/
define('LNG_UserDetails', 'User Details');
define('LNG_UserAdd', 'Create User');
define('LNG_UserName', 'Username');
define('LNG_FullName', 'Full Name');

define('LNG_Help_Users', 'Manage your control panel users. Users created here can login to the control panel and track their own website traffic.');

define('LNG_Help_CreateUser', 'Enter the details of the new user in the form below and click "Save".');

define('LNG_EditUser', 'Edit User');
define('LNG_Help_EditUser', 'Modify the details of the user below and click "Save".');

define('LNG_Active', 'Active');
define('LNG_Admin', 'Administrator');
define('LNG_YesIsActive','Yes, this user is active');
define('LNG_YesIsAdmin','Yes, this user is an administrator');

define('LNG_SupplyUserPassword', 'Please supply a password');
define('LNG_SupplyUserUsername', 'Please supply a username');

define('LNG_SupplyUserEmailAddress', 'Please supply an email address');

define('LNG_UserUpdated', 'User details updated successfully.');
define('LNG_UserNotUpdated', 'User details NOT updated successfully.');

define('LNG_UserCreated', 'User has been created successfully.');
define('LNG_UserNotCreated', 'An Error occured. User has not been created.');
define('LNG_UserAlreadyExists', 'A user with that username already exists. Please enter a different username.');

define('LNG_DeleteUserPrompt', 'Are you sure you want to delete this user?\nIt will also delete their statistics.');
define('LNG_User_CantDeleteOwn', 'You cannnot delete your own user account.');
define('LNG_UserDeleted', 'User deleted successfully.');

define('LNG_MyAccount','My Account');
define('LNG_Help_MyAccount','Update your account details and click "Save" to continue.');

define('LNG_HLP_Active', 'An in-active user will still exist in the system but will not be able to login. This can be used to temporarily suspend access to a particular user.<br/>This does not disable tracking, this only stops the user from logging in to the control panel.');
define('LNG_HLP_Admin', 'A non-administrator has access to statistics only. They cannot access the users or settings panel.');

define('LNG_ClearStatistics', 'Clear Statistics');
define('LNG_ConfirmDeleteStatistics', '** Warning **\nYou are about to permanently delete all of this users statistics\nand they cannot be retrieved.\nAre you sure you want to delete all of these statistics?');
define('LNG_ReallyConfirmDeleteStatistics', 'Are you really sure you want to delete all of these statistics?\nThey are permanently deleted and not retrievable.');

define('LNG_StatsNotDeleted', 'Statistics were not deleted for this user.');
define('LNG_StatsDeleted', 'Statistics were deleted for this user.');
define('LNG_UnableToDeleteStats', 'Unable to delete statistics for this user');

define('LNG_ClearingStats_InProgress_campaigns', 'Clearing statistics for campaigns..<br/>&nbsp; Have removed %s/%s records.');
define('LNG_ClearingStats_InProgress_referrers', 'Clearing statistics for referrers..<br/>&nbsp; Have removed %s/%s records.');
define('LNG_ClearingStats_InProgress_payperclicks', 'Clearing statistics for pay per clicks..<br/>&nbsp; Have removed %s/%s records.');
define('LNG_ClearingStats_InProgress_search', 'Clearing statistics for search engines..<br/>&nbsp; Have removed %s/%s records.');
define('LNG_ClearingStats_InProgress_conversions', 'Clearing statistics for conversions..<br/>&nbsp; Have removed %s/%s records.');

define('LNG_ClearingStats_Todo_campaigns', 'Campaign statistics are in the queue.');
define('LNG_ClearingStats_Todo_referrers', 'Referrer statistics are in the queue.');
define('LNG_ClearingStats_Todo_payperclicks', 'Pay per click statistics are in the queue.');
define('LNG_ClearingStats_Todo_search', 'Search engine statistics are in the queue.');
define('LNG_ClearingStats_Todo_conversions', 'Conversion statistics are in the queue.');

define('LNG_ClearingStats_Done_campaigns', '&nbsp;Statistics cleared for campaigns. Removed %s records.');
define('LNG_ClearingStats_Done_referrers', '&nbsp;Statistics cleared for referrers. Removed %s records.');
define('LNG_ClearingStats_Done_payperclicks', '&nbsp;Statistics cleared for pay per clicks. Removed %s records.');
define('LNG_ClearingStats_Done_search', '&nbsp;Statistics cleared for search engines. Removed %s records.');
define('LNG_ClearingStats_Done_conversions', '&nbsp;Statistics cleared for conversions. Removed %s records.');

define('LNG_RemovedRecord_campaigns', 'Removed 1 campaign statistic');
define('LNG_RemovedRecords_campaigns', 'Removed %s campaign statistics');
define('LNG_RemovedRecord_referrers', 'Removed 1 referrer statistic');
define('LNG_RemovedRecords_referrers', 'Removed %s referrer statistics');
define('LNG_RemovedRecord_payperclicks', 'Removed 1 pay per click statistic');
define('LNG_RemovedRecords_payperclicks', 'Removed %s pay per click statistics');
define('LNG_RemovedRecord_search', 'Removed 1 search engine statistic');
define('LNG_RemovedRecords_search', 'Removed %s search engine statistics');
define('LNG_RemovedRecord_conversions', 'Removed 1 conversion statistic');
define('LNG_RemovedRecords_conversions', 'Removed %s conversion statistics');

define('LNG_UserLimitReached', 'You have reached your user limit and cannot create any more users.');

/**
* View All Campaigns
*/
define('LNG_CampaignResultsSummary', "Total Campaign Results");
define('LNG_CampaignResultsSummary_helpText', "Results for the total traffic and conversions that have come from all of your campaigns. <br><br>You can use this to compare with more specific campaign statistics above.");

/**
* View All PPCs
*/
define('LNG_PPCResultsSummary', "Total Pay Per Click Results");
define('LNG_PPCResultsSummary_helpText', "Results for the total traffic and conversions that have come from all of your pay per click campaigns. <br><br>You can use this to compare with more specific pay per click statistics above.");

/**
* Export Info.
*/
define('LNG_Export_ClickButton', 'Click the "Generate" button below to start generating an export of your statistics.');
define('LNG_ExportStart', 'Generate');
define('LNG_Export_NoStatsFound', 'No statistics found for that date range. Please try again.');
define('LNG_Export_ChooseType', 'Please choose a report to export.'); 

define('LNG_Export_DateRange', 'Date Range'); 
define('LNG_HLP_Export_DateRange', 'Select the date range you would like to export data for.'); 

define('LNG_Export_ChooseReports', 'Choose Report Types');
define('LNG_Export_Heading', 'Export Statistics');
define('LNG_Export_Help', 'Use the following form to choose what type of statistics to include in your report');
define('LNG_Export_Include', 'Export type');
define('LNG_HLP_Export_Include', 'Select the type of data you would like to include in your exported report');
define('LNG_Export_Finished', 'Your export has finished.');
define('LNG_Export_Click_Download', 'Click here to download your statistics.');
define('LNG_Export_Stats_Confirm', 'You will generate a report for the following areas:');

define('LNG_Export_Searchenginename', 'Search Engine');
define('LNG_Export_Keywords', 'Keywords');
define('LNG_Export_Landingpage', 'Landing Page');
define('LNG_Export_Revenue', 'Revenue (' . LNG_CurrencySymbol . ')');
define('LNG_Export_Cost', 'Cost (' . LNG_CurrencySymbol . ')');
define('LNG_Export_Ppcname', 'Link Name');
define('LNG_Export_Campaignsite', 'Site');
define('LNG_Export_Campaignname', 'Campaign Name');
define('LNG_Export_Domain', 'Domain Name');
define('LNG_Export_Url', 'Referrer URL');
define('LNG_Export_Roi', 'ROI (%)');
define('LNG_Export_Visits', 'Visits');
define('LNG_Export_Conv', 'Conversions');
define('LNG_Export_Percent', 'Conversion (%)');

define('LNG_Export_Header_Ppc', 'Pay Per Click');
define('LNG_Export_Header_Campaign', 'Campaigns');
define('LNG_Export_Header_Search', 'Search Results');
define('LNG_Export_Header_Referrer', 'Referrers');

define('LNG_Export_Ppc_Finished', 'Pay per click statistics have been exported.');
define('LNG_Export_Campaign_Finished', 'Campaign statistics have been exported.');
define('LNG_Export_Search_Finished', 'Search statistics have been exported.');
define('LNG_Export_Referrer_Finished', 'Referrer statistics have been exported.');

define('LNG_Export_Ppc_Todo', 'Pay per click statistics are waiting to be exported.');
define('LNG_Export_Campaign_Todo', 'Campaign statistics are waiting to be exported.');
define('LNG_Export_Search_Todo', 'Search statistics are waiting to be exported.');
define('LNG_Export_Referrer_Todo', 'Referrer statistics are waiting to be exported.');

define('LNG_Export_Ppc_InProgress', 'Pay per click statistics are being exported..<br/>&nbsp; - Currently exporting %s/%s pay per click campaigns.');
define('LNG_Export_Campaign_InProgress', 'Campaign statistics are being exported..<br/>&nbsp; - Currently exporting %s/%s campaigns.');
define('LNG_Export_Search_InProgress', 'Search statistics are being exported..<br/>&nbsp; - Currently exporting %s/%s search engines.');
define('LNG_Export_Referrer_InProgress', 'Referrer statistics are being exported..<br/>&nbsp; - Currently exporting %s/%s referrers.');

define('LNG_Export_Ppc_SubProgress', 'Exporting \'%s\' pay per click statistics..<br/>&nbsp; - Currently exporting %s/%s records.');
define('LNG_Export_Campaign_SubProgress', 'Exporting \'%s\' campaign statistics..<br/>&nbsp; - Currently exporting %s/%s records.');
define('LNG_Export_Search_SubProgress', 'Exporting \'%s\' search engine statistics..<br/>&nbsp; - Currently exporting %s/%s records.');
define('LNG_Export_Referrer_SubProgress', 'Exporting \'%s\' referrer statistics..<br/>&nbsp; - Currently exporting %s/%s records.');

/**
* PrintReport Info.
*/
define('LNG_Print_ClickButton', 'Click the "Generate" button below to start generating a report of your statistics.');
define('LNG_PrintStart', 'Generate');
define('LNG_Print_NoStatsFound', 'No statistics found for that date range. Please try again.');
define('LNG_Print_ChooseType', 'Please choose a report to print.'); 
define('LNG_Print_ChooseReports', 'Choose Report Types');
define('LNG_Print_Heading', 'Print Statistics');
define('LNG_Print_Help', 'Use the following form to choose what type of statistics to include in your report');
define('LNG_Print_Include', 'Print Type');
define('LNG_HLP_Print_Include', 'Select the type of data you would like to include in your printed report');

define('LNG_Print_DateRange', 'Date Range'); 
define('LNG_HLP_Print_DateRange', 'Select the date range you would like to print data for.'); 

define('LNG_Print_Finished', 'Your report has finished generating.');
define('LNG_Print_Click_View', 'Click here to view your statistics.');

define('LNG_Print_Stats_Confirm', 'You will generate a report for the following areas:');

define('LNG_Print_Stats_ReportTitle', 'The following report is for the following areas:');

define('LNG_Print_Searchenginename', 'Search Engine');
define('LNG_Print_Keywords', 'Search Keywords');
define('LNG_Print_Landingpage', 'Landing Pages');
define('LNG_Print_Revenue', 'Revenue');
define('LNG_Print_Cost', 'Cost');
define('LNG_Print_Ppcname', 'Name');
define('LNG_Print_Campaignsite', 'Web Site');
define('LNG_Print_Campaignname', 'Name');
define('LNG_Print_Domain', 'Domain Name');
define('LNG_Print_Url', 'Referrer URL');
define('LNG_Print_Roi', '(%) ROI');
define('LNG_Print_Visits', 'Visits');
define('LNG_Print_Conv', 'Conv');
define('LNG_Print_Percent', '(%) Conv');
define('LNG_Print_Name', 'Name');

define('LNG_Print_Header_Ppc', 'Pay Per Click');
define('LNG_Print_Header_Campaign', 'Campaigns');
define('LNG_Print_Header_Search', 'Search Results');
define('LNG_Print_Header_Referrer', 'Referrers');

define('LNG_Print_Ppc_Finished', 'Pay per click statistics have been generated.');
define('LNG_Print_Campaign_Finished', 'Campaign statistics have been generated.');
define('LNG_Print_Search_Finished', 'Search statistics have been generated.');
define('LNG_Print_Referrer_Finished', 'Referrer statistics have been generated.');

define('LNG_Print_Ppc_Todo', 'Pay per click statistics are waiting to be generated.');
define('LNG_Print_Campaign_Todo', 'Campaign statistics are waiting to be generated.');
define('LNG_Print_Search_Todo', 'Search statistics are waiting to be generated.');
define('LNG_Print_Referrer_Todo', 'Referrer statistics are waiting to be generated.');

define('LNG_Print_Ppc_InProgress', 'Pay per click statistics are being generated..<br/>&nbsp; - Currently printing %s/%s pay per click campaigns.');
define('LNG_Print_Campaign_InProgress', 'Campaign statistics are being generated..<br/>&nbsp; - Currently printing %s/%s campaigns.');
define('LNG_Print_Search_InProgress', 'Search statistics are being generated..<br/>&nbsp; - Currently printing %s/%s search engines.');
define('LNG_Print_Referrer_InProgress', 'Referrer statistics are being generated..<br/>&nbsp; - Currently printing %s/%s referrers.');

define('LNG_Print_Ppc_SubProgress', 'Generating \'%s\' pay per click statistics..<br/>&nbsp; Have generated %s/%s records.');
define('LNG_Print_Campaign_SubProgress', 'Generating \'%s\' campaign statistics..<br/>&nbsp; Have generated %s/%s records.');
define('LNG_Print_Search_SubProgress', 'Generating \'%s\' search engine statistics..<br/>&nbsp; Have generated %s/%s records.');
define('LNG_Print_Referrer_SubProgress', 'Generating \'%s\' referrer statistics..<br/>&nbsp; Have generated %s/%s records.');

define('LNG_PrintStatistics_Report', 'Statistics Report');

/**
* Main Index Page.
*/
define('LNG_Print_Totals', 'Total Numbers');
define('LNG_Print_TotalVisits', 'Total Visits');
define('LNG_Print_TotalConv', 'Total Conversions');
define('LNG_Print_TotalPercent', 'Conversions (%)');
define('LNG_Print_TotalCost', 'Total Cost (' . LNG_CurrencySymbol . ')');
define('LNG_Print_TotalRoi', 'Total ROI');
define('LNG_Print_TotalRevenue', 'Total Revenue (' . LNG_CurrencySymbol . ')');

/**
* Chart Items.
*/
define('LNG_ppc', 'Pay Per Click');
define('LNG_campaign', 'Campaigns');
define('LNG_search', 'Search Results');
define('LNG_referrer', 'Referrers');
define('LNG_Chart_Title', 'Revenue');

/**
* Quickstart items.
*/
define('LNG_Quickstart_TrackingCode', 'Create tracking code.');
define('LNG_HLP_Quickstart_TrackingCode', 'This enables the application to track when a visitor comes to your website.');

define('LNG_Quickstart_ConversionCode', 'Create conversion tracking code.');
define('LNG_HLP_Quickstart_ConversionCode', 'This enables the application to track when a visitor makes a purchase on your website.');

define('LNG_Quickstart_CreatePPC', 'Create pay per click campaign tracking.');
define('LNG_HLP_Quickstart_CreatePPC', 'This enables the applicataion to track a specific pay per click campaign from a search engine.');

define('LNG_Quickstart_CreateCampaign', 'Create campaign tracking.');
define('LNG_HLP_Quickstart_CreateCampaign', 'This enables the application to track a specific advertising campaign on another website.');

define('LNG_Quickstart_CloseWindow', '[x] Close');
define('LNG_Quickstart_DontShowWindow', 'Don\'t show this window anymore &raquo;');
define('LNG_Quickstart_4Steps', '4 Quick steps to get you started...');

define('LNG_Copyright', '<a href="http://www.interspire.com/trackpoint/" target="_new" class="FooterLink"><b>Interspire TrackPoint NX</b></a> Copyright <a href="http://www.interspire.com" target="_new" class="FooterLink">Interspire</a>');


/**
* Conversions listing.
*/
define('LNG_ConversionStats', 'Conversion Statistics');
define('LNG_Help_ConversionStats', 'View all of your conversion statistics. See what type of conversions you have so you can quickly compare to your own website statistics.');
define('LNG_ConversionTime', 'Conversion Time');
define('LNG_ConversionOrigin', 'Origin');
define('LNG_ConversionDetails', 'Details');
define('LNG_ViewConversions_ppc', 'Pay per click');
define('LNG_ViewConversions_campaign', 'Campaign');
define('LNG_ViewConversions_search', 'Search Result');
define('LNG_ViewConversions_referrer', 'Referrer');

/**
* Trackpoint NX Update
*/
define('LNG_Generate', 'Generate');
define('LNG_All', 'All');
define('LNG_CreatePPCBulk_Button', 'Create Bulk PPC');
define('LNG_CreatePPC_Button', 'Create Single PPC');

define('LNG_ChartType', 'Chart Type');
define('LNG_Chart_Title_Visits', 'Visits');
define('LNG_Chart_Title_Conversions', 'Conversions');

define('LNG_HLP_EmailAddress', 'This email address is used if you forget your password.');
define('LNG_IgnoreSites', 'Ignore Domain Names');
define('LNG_HLP_IgnoreSites', 'A comma separated list of sites to ignore. This can be used if you are tracking a 3rd party site and don\\\'t want the clients site to show up as a referrer, or if you have multiple sites interlinking with each other. Using a * will work as a wildcard and match any domain or subdomain. Do not include http:// or https://.<br/>For example, www.domain.com will only block www.domain.com - it will not block sub.domain.com. To block both, use *.domain.com.');

define('LNG_IgnoreIPs', 'Ignore IP Addresses');
define('LNG_HLP_IgnoreIPs', 'A comma separated list of ip addresses to ignore. This can be used to keep your own traffic from being tracked.<br/>You can use specific ip addresses (127.0.0.1) or network addresses (127.*) to block any part of that network.<br/>For example, 127.0.0.1 will only block that ip address, 192.168.0.* will block 192.168.0.1 to 192.168.0.254.');

define('LNG_IgnoreKeywords', 'Ignore Keywords');
define('LNG_HLP_IgnoreKeywords', 'A comma separated list of search keywords to ignore.<br/>This only blocks natural search results, not pay-per-click results.');

define('LNG_IPAddress', 'IP Address');

define('LNG_CreatePPCBulk', 'Create Pay Per Click Link (Bulk)');
define('LNG_Help_CreatePPCBulk', 'Enter the required information and click the \'generate\' button to create ppc tracking URL\'s. A new file will be generated for you to download containing the pay per click links.');
define('LNG_ChooseUserToCreatePPCFor', 'Select user');
define('LNG_HLP_ChooseUserToCreatePPCFor', 'Pay per click links are user specific. Choose the user you are creating a pay-per-click link for so it can be tracked correctly.');
define('LNG_ChooseFile_BulkPPC', 'Choose a file to upload');
define('LNG_HLP_ChooseFile_BulkPPC', 'Choose a file from your computer containing the pay-per-click data.<br/>The file you upload must contain all information regarding pay per click links.<br/>This includes Search Engine, Pay-per-click Name, Landing Page and Cost Per Click.<br/>For example:<br/>Google,MyTrackingSoftware,http://www.domain.com,0');

define('LNG_FieldSeparator', 'Field Separator');
define('LNG_HLP_FieldSeparator', 'What character separates the fields in the file you are uploading?<br/>This character must not appear in any data in the fields.<br/>To use a tab character, enter the word \\\'TAB\\\' as the separator.');

define('LNG_ChooseFileToUpload', 'Please choose a file to upload from your computer.');
define('LNG_UnableToReadFile', 'Unable to read the file you uploaded. Please try again.');

define('LNG_Bulk_Conversion_PPC_Finished', 'Bulk creation of pay-per-click links has finished. You can download your new file from <a href="%s">here</a>');
define('LNG_Bulk_PPC_RowsInvalid', 'The following lines in the file you uploaded are invalid:');

define('LNG_SwitchTo', 'Switch To');
define('LNG_SwitchTo_Title', 'Switch to this user to see their statistics.');
define('LNG_SwitchTo_Disabled_Title', 'You are already viewing statistics as this user.');
define('LNG_SwitchUser_Success', 'Switched to viewing statistics for user \'%s\'');
define('LNG_ViewingStatsAs', 'Viewing statistics for \'%s\'');

define('LNG_TrackingCodeForUser', '<br/><br/><b>You are creating tracking code for user \'%s\'</b>');
define('LNG_ConversionCodeForUser', '<br/><br/><b>You are creating conversion code for user \'%s\'</b>');
define('LNG_CampaignCodeForUser', '<br/><br/><b>You are creating a campaign link for user \'%s\'</b>');
define('LNG_PPCCodeForUser', '<br/><br/><b>You are creating a pay-per-click link for user \'%s\'</b>');

define('LNG_TrackingLogs', 'Keep Tracking Logs');
define('LNG_TrackingLogsIntro', 'Yes, keep tracking logs');
define('LNG_HLP_TrackingLogs', 'If this option is on, trackpoint will keep a log history for 3 months of all actions taken whilst tracking.<br/>This will help us track down any problems you have with conversions or general tracking, however it may take up a lot of space depending on how much traffic your website(s) receive.<br/>');

define('LNG_ConversionSummary', 'Conversion Summary');
define('LNG_ConversionSummary_helpText', 'This shows all conversion results regardless of origin.');

define('LNG_ServerTimeZone', 'Server Timezone');
define('LNG_HLP_ServerTimeZone', 'This is the timezone your server is in.');

define('LNG_UserTimeZone', 'User Timezone');
define('LNG_HLP_UserTimeZone', 'This is the timezone your user is in.');

/**
* If you do add to or remove from this list, you will need to edit the list below as well.
*/
define('LNG_GMT-12:00', '(-12:00) Eniwetok, Kwajalein');
define('LNG_GMT-11:00', '(-11:00) Midway Island, Samoa');
define('LNG_GMT-10:00', '(-10:00) Hawaii');
define('LNG_GMT-9:00',  '(-9:00)  Alaska');
define('LNG_GMT-8:00',  '(-8:00)  Pacific Time (US & Canada)');
define('LNG_GMT-7:00',  '(-7:00)  Mountain Time (US & Canada)');
define('LNG_GMT-6:00',  '(-6:00)  Central Time (US & Canada), Mexico City');
define('LNG_GMT-5:00',  '(-5:00)  Eastern Time (US & Canada), Bogota, Lima');
define('LNG_GMT-4:00',  '(-4:00)  Atlantic Time (Canada), Caracas, La Paz');
define('LNG_GMT-3:30',  '(-3:30)  Newfoundland');
define('LNG_GMT-3:00',  '(-3:00)  Brazil, Buenos Aires, Georgetown');
define('LNG_GMT-2:00',  '(-2:00)  Mid-Atlantic');
define('LNG_GMT-1:00',  '(-1:00)  Azores, Cape Verde Islands');
define('LNG_GMT',       '(GMT)    Western Europe Time, London, Lisbon, Casablanca');
define('LNG_GMT+1:00',  '(+1:00)  Brussels, Copenhagen, Madrid, Paris');
define('LNG_GMT+2:00',  '(+2:00)  Kalinangrad, South Africa');
define('LNG_GMT+3:00',  '(+3:00)  Baghdad, Riyadh, Moscow, St. Petersburg');
define('LNG_GMT+3:30',  '(+3:30)  Tehran');
define('LNG_GMT+4:00',  '(+4:00)  Abu Dhabi, Muscat, Baku, Tbilisi');
define('LNG_GMT+4:30',  '(+4:30)  Kabul');
define('LNG_GMT+5:00',  '(+5:00)  Ekaterinburg, Islamabad, Karachi, Tashkent');
define('LNG_GMT+5:30',  '(+5:30)  Bombay, Calcutta, Madras, New Delhi');
define('LNG_GMT+6:00',  '(+6:00)  Almaty, Dhaka, Colombo');
define('LNG_GMT+7:00',  '(+7:00)  Bangkok, Hanoi, Jakarta');
define('LNG_GMT+8:00',  '(+8:00)  Beijing, Perth, Singapore, Hong Kong');
define('LNG_GMT+9:00',  '(+9:00)  Tokyo, Seoul, Osaka, Sapporo, Yakutsk');
define('LNG_GMT+9:30',  '(+9:30)  Adelaide, Darwin');
define('LNG_GMT+10:00', '(+10:00) Eastern Australia, Guam, Vladivostok');
define('LNG_GMT+11:00', '(+11:00) Magadan, Solomon Islands, New Caledonia');
define('LNG_GMT+12:00', '(+12:00) Auckland, Wellington, Fiji, Kamchatka');

/**
* These variables below are used to display a list of timezones.
* They correspond to the language variables above.
* If you do add to the list above, you will need to add it to the list below as well.
*/
$GLOBALS['TrackPointTimeZones'] = array(
	'GMT-12:00',
	'GMT-11:00',
	'GMT-10:00',
	'GMT-9:00',
	'GMT-8:00',
	'GMT-7:00',
	'GMT-6:00',
	'GMT-5:00',
	'GMT-4:00',
	'GMT-3:30',
	'GMT-3:00',
	'GMT-2:00',
	'GMT-1:00',
	'GMT',
	'GMT+1:00',
	'GMT+2:00',
	'GMT+3:00',
	'GMT+3:30',
	'GMT+4:00',
	'GMT+4:30',
	'GMT+5:00',
	'GMT+5:30',
	'GMT+6:00',
	'GMT+7:00',
	'GMT+8:00',
	'GMT+9:00',
	'GMT+9:30',
	'GMT+10:00',
	'GMT+11:00',
	'GMT+12:00'
);


/**
* Trackpoint NX 0.1 Update
*/
define('LNG_EnterUsername', 'Please enter your username and password');
?>
