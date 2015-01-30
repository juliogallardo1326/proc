<?php
// Set up Config Info

	// Header/Footer - You can define your own
	define("SP_HEADER",'../admin/includes/header.php');
	define("SP_FOOTER",'../admin/includes/footer.php');

	// Database
	define("DB_TYPE","mysql");
	define("DB_SERVER","localhost");
	define("DB_USER","etel_root");
	define("DB_PASS","WSD%780=");
	define("DB_DBNAME","etel_dbscompanysetup");
	
	// Other
	
	define("SP_PAGELIMIT",500);
	define("SP_TIMEOUT",4);
	define("SP_TEMP","temp");
	
  // Define Tables and Fields

	define("TBL_WORD_LIST","etel_dbscompanysetup.sp_word_list");
		define("FLD_WORD_ID","wl_ID");
		define("FLD_WORD_WORD","wl_word");
		define("FLD_WORD_WEIGHT","wl_weight");
		define("FLD_WORD_TYPE","wl_type");
		
	define("TBL_WORD_CATEGORY","etel_dbscompanysetup.sp_word_category");
		define("FLD_WORD_CATEGORY_ID","wc_ID");
		define("FLD_WORD_CATEGORY_CATEGORY","wc_category");
		
	define("TBL_WL_WC","etel_dbscompanysetup.sp_wl_wc");
		// Fields Defined in TBL_WORD_CATEGORY and TBL_WORD_LIST
		// These fields must have the same field name.
	
	
	define("TBL_SITES","cs_company_sites");
		define("FLD_SITE_ID","cs_ID");
		define("FLD_SITE_NAME","cs_name");
		define("FLD_SITE_URL","cs_URL");
		define("FLD_SITE_FTP","cs_ftp");  
		define("FLD_SITE_FTP_LAST_CHECK","cs_ftp_last_check");  
		define("FLD_SITE_USERNAME","cs_ftp_user");  
		define("FLD_SITE_PASSWORD","cs_ftp_pass");  	
		define("FLD_SITE_SEARCH_FREQUENCY","cs_search_frequency");  
		define("FLD_SITE_SEARCH_DEPTH","cs_search_depth");   
		define("FLD_SITE_ENABLE_SPIDER","cs_enable_spider");   
		define("FLD_SITE_REPORT_TYPE","cs_spider_report_type");   
		define("FLD_SITE_REPORT","cs_spider_report");   
		define("FLD_SITE_REPORT_SCORE","cs_spider_report_score");  
		
	
	define("TBL_LINKS","etel_dbscompanysetup.sp_links");
		define("FLD_LINKS_ID","li_ID");
		define("FLD_LINKS_PARENT_ID","li_parent_ID");
		define("FLD_LINKS_HASH","li_hash");
		define("FLD_LINKS_SITE_ID","li_si_ID");
		define("FLD_LINKS_URL","li_url");
		define("FLD_LINKS_NAME","li_name");
		define("FLD_LINKS_TYPE","li_type");
		define("FLD_LINKS_PAGE_HASH","li_page_hash");  
		define("FLD_LINKS_ATTEMPTS","li_attempts");  	
		define("FLD_LINKS_LAST_CHECKED","li_last_checked");  
		define("FLD_LINKS_DEPTH","li_depth");   
		define("FLD_LINKS_EXTERNAL","li_external");   
		define("FLD_LINKS_SCORE_REQUIRED","li_score_required");   
		define("FLD_LINKS_SCORE_DISALLOWED","li_score_disallowed");   
		define("FLD_LINKS_LINKS_FOUND","li_links_found");  

							

?>