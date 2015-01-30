<?php

class ISpy
{

	var $DB=NULL;
	var	$siteInfo=NULL;
	var	$curSite=0;
	var $ftp=NULL;
	
	var $sp_log_verbose=0;
	var $sp_log_output=0;
	var $sp_log = "";	
	
	var $sp_WordList;
	var $sp_allowed_ext = "";
	
	var $page_count = 0;
	
	function ISpy($ADODB=NULL)
	{
		if($ADODB) $this->DB = $ADODB;
		else
		{
			$this->DB = NewADOConnection(DB_TYPE);
			$this->DB->Connect(DB_SERVER, DB_USER, DB_PASS, DB_DBNAME);
		}
 		$this->DB->SetFetchMode(ADODB_FETCH_ASSOC);
		$this->sp_log_init("Spider Log ".date("F j, Y, g:i:s a"));
		$this->get_wordlist();
		$this->sp_allowed_ext_ar = array(
		  '.?HTM.?',
		   'HTA',
		    'HTC',
			 'XHTML',
			  'STM',
			   'SSI',
			    'JS',
				 'AS',
				  'ASC', 'ASR', 'XML', 'XSL', 'XSD',
				  							   'DTD',
											    'XSLT',
												 'RSS',
												  'RDF',
												   'LBI',
												    'DWT',
													 'ASP',
													  'ASA',
													   'ASPX',
													    'ASCX',
														 'ASMX',
						    'CONFIG', 'CS', 'CSS', 'CFM', 'CFML',
							 'CFC',
							  'TLD',
							   'TXT',
								'TPL',
								 'LASSO',
							      'JSP',
								   'JSF',
									'VB',
									 'VBS', 
									  'VTM',
									   'VTML',
										'INC',
										 'JAVA',
		'EDML', 'WML',	'PHP.?', 'JST', 'MXI');
		$this->sp_allowed_ext = '/('.implode("|",$this->sp_allowed_ext_ar).')/i';
	}
		
	function loadSite($FLD_SITE_ID=NULL)
	{
		$st = $this->sp_time();
		if($FLD_SITE_ID) $this->curSite = $FLD_SITE_ID;
		if($this->curSite == $this->siteInfo[FLD_SITE_ID]) return;
		
		if(!$this->curSite) $this->sp_log(" Error: No Site Selected.",1);
		
		$sql = "select ts.*,
			(date_sub(NOW(), interval ts.".FLD_SITE_SEARCH_FREQUENCY." day) > ".FLD_SITE_FTP_LAST_CHECK.") as ftp_expired,
			sum(".FLD_LINKS_TYPE."='ftp') as ftp_count,
			sum(".FLD_LINKS_TYPE."='http') as http_count,
			count(*) as page_count
			from ".TBL_SITES." as ts
		    left join ".TBL_LINKS." as tl on tl.".FLD_LINKS_SITE_ID." = ts.".FLD_SITE_ID."
		    where ".$this->sp_sql($FLD_SITE_ID,FLD_SITE_ID)." Group by ".FLD_SITE_ID;
			
		$this->siteInfo = $this->DB->GetRow($sql);
		if($this->siteInfo) $this->sp_log(" Loaded Site information for ".$this->siteInfo[FLD_SITE_NAME].". (".$this->sp_dur($st).")",1);
		else $this->sp_log(" Failed to load Site information for Site ID=$FLD_SITE_ID. (".$this->sp_dur($st).")",1);
		return;
	}
		
	function updateSite($update_array)
	{
		if(!$this->curSite) $this->sp_log(" Error: No Site Selected.",1);
				
		$sql = "update ".TBL_SITES." as ts set ".implode(",\n", $update_array )." where ".$this->sp_sql($this->curSite,FLD_SITE_ID);

		$rs = $this->DB->Execute($sql);
		if($rs) $this->sp_log(" Updated Site information for ".$this->siteInfo[FLD_SITE_NAME].". (".$this->sp_dur($st).")",1);
		else $this->sp_log(" ERROR: Failed to Update Site information for ".$this->siteInfo[FLD_SITE_NAME]." ~ ".$rs->getMessage().". (".$this->sp_dur($st).")",1); 
		
		return 1;
	}
	
	function reportSite()
	{
		$sql = "SELECT avg(li.".FLD_LINKS_SCORE_DISALLOWED.") as disallowed,
			min(li.".FLD_LINKS_SCORE_REQUIRED.") as required,
			avg(li.".FLD_LINKS_SCORE_DISALLOWED.")+min(li.".FLD_LINKS_SCORE_REQUIRED.") as score,
			count(li.".FLD_LINKS_ID.") as linksfound,
			sum(li.".FLD_LINKS_TYPE."='http') as linksfound_http,
			sum(li.".FLD_LINKS_TYPE."='ftp') as linksfound_ftp,
			sum(li.".FLD_LINKS_EXTERNAL.") as linksfound_ext,
			sum(li.".FLD_LINKS_PAGE_HASH." is not null) as linksparsed
			
			FROM ".TBL_SITES." as si left join ".TBL_LINKS." as li on li.".FLD_LINKS_SITE_ID." = ".FLD_SITE_ID." 
			where (li.".FLD_LINKS_ID." is not null) and ".$this->sp_sql($this->curSite,FLD_SITE_ID)."
			group by ".FLD_SITE_ID;
		$report = $this->DB->GetRow($sql);
		if (PEAR::isError($report)) 
			{$this->sp_log("  Failed to Grab Site Information:".$report->getMessage(),1); return 0;}
			
			
		$review = 'Not Required';
		if($report['score']>60) $review = 'Recommended';
		$report['review']=$review;
			
		$report['summary']="Report Summary:\n";
		if($report['linksfound']) $report['summary'].=" (".$report['linksparsed'].") Links Parsed out of (".$report['linksfound'].") Links Found. \n";
		else $report['summary'].=" No Links Found. Please review site manually.\n";
		
		if($report['linksfound_http']) $report['summary'].=" (".$report['linksfound_http'].") HTTP Links Found. \n";
		else if($report['linksfound']) $report['summary'].=" No HTTP Links Found. Please review HTTP manually.\n";
		
		if($report['linksfound_ftp']) $report['summary'].=" (".$report['linksfound_ftp'].") FTP Links Found. \n";
		else if($report['linksfound']) $report['summary'].=" No FTP Links Found. Please review FTP manually.\n";
		
		
		
		$this->sp_log($report['summary'],1);
		return $report;
	}	
	
	

	function parseAllSites()
	{
		$sql = "select ".FLD_SITE_ID."
		 from ".TBL_SITES." 
		 ";	// Pull all pending site links.
		
		set_time_limit(3600);
		
		$Sites = $this->DB->GetAll($sql);	// Grab all site links
		foreach($Sites as $Site)
			$this->parseSite($Site[FLD_SITE_ID]);
		return $this->sp_log_get();
	}
	
	function parseSite($FLD_SITE_ID=NULL)
	{	// Look for all pending sites to parse, and parse them.
		$FLD_SITE_ID = intval($FLD_SITE_ID);
		$st = $this->sp_time();
		$this->loadSite($FLD_SITE_ID);	// Load Site Info
		
		if(!$this->siteInfo['cs_enable_spider']) return 0;
		
		$this->page_count = intval($this->siteInfo['page_count']);
		// HTTP
		if($this->siteInfo['http_count']==0) 
		{
			$rootlink = $this->get_link($this->siteInfo[FLD_SITE_URL],$this->siteInfo[FLD_SITE_NAME]);
			$this->store_link($rootlink);	// Start with the site's index page.
		}
		
		$this->sp_log(" -- Start Parsing Thread -- ",1);
		$sql = "select tl.*, ts.".FLD_SITE_FTP.", ts.".FLD_SITE_USERNAME.", ts.".FLD_SITE_PASSWORD."
		 from ".TBL_LINKS." as tl
		 left join ".TBL_SITES." as ts on tl.".FLD_LINKS_SITE_ID." = ts.".FLD_SITE_ID."
		 where (".FLD_LINKS_LAST_CHECKED." < date_sub(NOW(), interval ts.".FLD_SITE_SEARCH_FREQUENCY." day)
			   or ".FLD_LINKS_PAGE_HASH." is NULL) and ".FLD_LINKS_ATTEMPTS." < 3
		 and ts.".FLD_SITE_ID." = '".$this->curSite."' order by cs_spider_report_score asc
		 ";	// Pull all pending site links.
		 
		$LinksToProcess = $this->DB->GetAll($sql);	// Grab all site links
		//if($LinksToProcess) $this->sp_log("  Error grabbing site links. (".$this->sp_dur($st).")",1);
		//else
			if(sizeof($LinksToProcess))
			{
				foreach($LinksToProcess as $link)		// For each one, 
					$this->parseHTTP($link);			// Parse
			}
			else
				$this->sp_log("  - No HTML Pages to Parse - ",1);
				
			
		// FTP
		
													// Check to see if its time to parse ftp.
		if($this->siteInfo['ftp_expired'] || $this->siteInfo['ftp_count']==0) $this->parseFTP();
		else $this->sp_log("  - Not time to check FTP Pages yet - ",1);
		
		$report = $this->reportSite();
		$this->updateSite(array(
		FLD_SITE_REPORT."=".$this->sp_qs(serialize($report)),
		FLD_SITE_REPORT_SCORE."=".$this->sp_qs($report['score'])
		));	// Update Site Info
		
		$this->sp_log(" -- End Parsing Thread -- (".$this->sp_dur($st).")",1);
		$this->sp_log("",1);
	}
	
	function parseHTTP($pageInfo)
	{	// Grab all the links in the page, and generate the word report.
		$st = $this->sp_time();	

		
		$linkInfo = parse_url($pageInfo[FLD_LINKS_URL]);	// Get Information about the link
		
		$parseMode = 'new';
		if($pageInfo[FLD_LINKS_PAGE_HASH]) $parseMode = 'update';
		
		if($pageInfo[FLD_LINKS_TYPE]=='http')
		{
			$this->sp_log(" Parsing Page ".substr($pageInfo[FLD_LINKS_URL],0,40)."...",1);
		
			$HTML = $this->load_page($pageInfo[FLD_LINKS_URL]);				// Get HTML
			
			$newPageHash = strtoupper(md5($HTML.$this->siteInfo[FLD_SITE_ID]));		// Create a MD5 Hash of the page+site ID for comparison
			if($parseMode && $pageInfo[FLD_LINKS_PAGE_HASH] == $newPageHash)
			{
				$this->sp_log("  Skipping Page - No changes found. ",0);
			}
			else if ($HTML==-1)
			{
				$this->sp_log("  Page Timed Out - Will try again later. ",1);
				$pageInfo[FLD_LINKS_ATTEMPTS] = intval($pageInfo[FLD_LINKS_ATTEMPTS])+1;				
				$this->store_link($pageInfo);									// Update the link with this info.
			}
			else
			{
				$pageInfo[FLD_LINKS_PAGE_HASH] = $newPageHash;					// Set the page hash.
				
				$root=$this->build_root($linkInfo);								// Build Root information
				$path=substr($linkInfo['path'],0,strrpos($linkInfo['path'],"/"))."/";
																				// Find root path if necessary.
				
				$parse_links = (!$pageInfo[FLD_LINKS_EXTERNAL] && $this->siteInfo[FLD_SITE_SEARCH_DEPTH]>$pageInfo[FLD_LINKS_DEPTH]);
				
				if($parse_links) 
					$pageInfo['links'] = 			$this->get_links($HTML,$root,$path);	// Find all links in page.
				$pageInfo['wordreport'] = 		$this->get_word_report($HTML);	// Generate the word report
				
				$this->store_link($pageInfo);									// Update the link with this info.
				
				$this->sp_log(" Finished Parsing Page in (".$this->sp_dur($st).")",0);
				
				if($parse_links) 
					$this->parse_links($pageInfo);		// Should we search for more pages?, store the links in database.
				
			}
			
			
		}

	}	
	
	function parseFTP()
	{
		if($this->page_count>SP_PAGELIMIT) return 0;

		$linkInfo = parse_url($this->siteInfo[FLD_SITE_FTP]);
		$this->ftp = new Net_FTP($linkInfo['host'], $linkInfo['port'], SP_TIMEOUT);
		$result = $this->ftp->connect();
		if (PEAR::isError($result)) 
			{$this->sp_log("  ".$result->getMessage(),1); return 0;}
		else		
			$this->sp_log("  Connected Successfully",1);
		
		$result = $this->ftp->login($this->siteInfo[FLD_SITE_USERNAME],$this->siteInfo[FLD_SITE_PASSWORD]);
		
		if (PEAR::isError($result)) 
			{$this->sp_log("  Failed to Log in:".$result->getMessage(),1); return 0;}
		
		$this->ftp_recurse(&$pageInfo,0);
		$this->updateSite(array(FLD_SITE_FTP_LAST_CHECK."=NOW()"));	// Update Site Info
		return 1;
	}
	
	function ftp_recurse($root,$depth=0)
	{
		$skip_array = array('.','..');
		$list=$this->ftp->ls();
		if (PEAR::isError($list)) {
			$this->sp_log("  Failed to pull ftp directory: ".$list->getMessage(),1);
			return 0;
		}
		foreach($list as $dir)
		{
		
			if($this->page_count>SP_PAGELIMIT) return 0;

			if($dir['is_dir']=='d')
			{
				if(!in_array($dir['name'],$skip_array))
				{
					$this->ftp->cd($dir['name']);
					$this->ftp_recurse($root,$depth+1);
					$this->ftp->cd('..');
				}			
			}
			else
			{
				$pathInfo = pathinfo($dir['name']);
				if($this->sp_ext_allowed($pathInfo['extension'])) 
				{
					$curdir = $this->ftp->pwd();
					$curfile=$root[FLD_SITE_FTP].$curdir.$dir['name'];
					$tmp_file = SP_TEMP."/ftp_get.tmp";
					$ti=1;
					$result = $this->ftp->get($dir['name'],$tmp_file,true,FTP_ASCII);
					while (PEAR::isError($result)) {
						$this->sp_log(" $ti Failed to write temporary file at ".$tmp_file.": ".$result->getMessage(),1);
						$tmp_file = SP_TEMP."/ftp_get$ti.tmp";
						$result = $this->ftp->get($dir['name'],$tmp_file,true,FTP_ASCII);
						$ti++;
					}
					$HTML = @implode('', file($tmp_file));
					$pageInfo = array();
					$pageInfo[FLD_LINKS_SITE_ID]=	$root[FLD_LINKS_SITE_ID];
					$pageInfo['wordreport'] = 		$this->get_word_report($HTML);	// Generate the word report
					$pageInfo['links'] = 			$this->get_links($HTML);	// Find all links in page.
					$pageInfo[FLD_LINKS_PAGE_HASH] = strtoupper(md5($HTML.$this->siteInfo[FLD_SITE_ID]));		// Create a MD5 Hash of the page for comparison
					$pageInfo[FLD_LINKS_URL] = 		$curfile;	// Find all links in page.
					$pageInfo[FLD_LINKS_NAME] = 	$dir['name'];	// Find all links in page.
					$pageInfo[FLD_LINKS_HASH] = 	$this->sp_get_hash($curfile);
					$pageInfo[FLD_LINKS_TYPE] = 	'ftp';
					$pageInfo[FLD_LINKS_DEPTH] = 	$depth;
					$this->store_link($pageInfo);						// Update the link with this info.

				}
			}
		}
		return 1;
	}
	
	function load_page($link)
	{	// Load HTML from a page. 
		$st = $this->sp_time();
		
		$url_parsed = parse_url($link);
		$host = $url_parsed["host"];
		$port = $url_parsed["port"];
		if ($port==0)
		   $port = 80;
		$path = $url_parsed["path"];
		
		//if url is http://example.com without final "/"
		//I was getting a 400 error
		if (empty($path))
		$path="/";
		
		//redirection if url is in wrong format
		if (empty($host)) return "";
		
		if ($url_parsed["query"] != "")
		   $path .= "?".$url_parsed["query"];
		$out = "GET $path HTTP/1.0\r\nHost: $host\r\n\r\n";
		$fp = @fsockopen($host, $port, $errno, $errstr, SP_TIMEOUT);
		if (!$fp) 
		{
			$this->sp_log("  Failed to Load Page. (".$this->sp_dur($st).")",1); 
			return -1;
		}
		@fwrite($fp, $out);
		$body = false;
		while (!@feof($fp)) {
			$s = @fgets($fp, 1024);
			if ( $body)
			   $ret .= $s;
			if ( $s == "\r\n" )
			   $body = true;
		}
		fclose($fp);
		$this->sp_log("  Loaded page successfully. (".$this->sp_dur($st).")",0); 
		return $ret;
	}
	
	function parse_links($parent)
	{	// Grab all the links in the page, and store them in the database.
		$st = $this->sp_time();
		$added=0;
		$total=0;
		 
		foreach($parent['links'] as $key=>$link)
		{
			if($this->page_count>SP_PAGELIMIT) return 0;
			$link[FLD_LINKS_PARENT_ID]=$parent[FLD_LINKS_ID];
			$link[FLD_LINKS_TYPE]=$parent[FLD_LINKS_TYPE];
			$link[FLD_LINKS_DEPTH]=$parent[FLD_LINKS_DEPTH]+1;
			$total++;
			$link[FLD_LINKS_ID] = $this->store_link($link,1);
			if ($link[FLD_LINKS_ID])
			{
				$parent['links'][$key] = $link;
				$added++;
			}
			else
				$parent['links'][$key] = NULL;
		}
		$this->sp_log("  Found ($added/$total) new pages. (".$this->sp_dur($st).")",1);
		foreach($parent['links'] as $link)
			if($link && $this->page_count<=SP_PAGELIMIT) $this->parseHTTP($link);
	}

	function links_exist($FLD_LINKS_HASH)
	{
		$this->sp_log(" -- Start Parsing Thread -- ",1);
		$sql = "SELECT ".FLD_LINKS_ID.",".FLD_LINKS_HASH."
				FROM ".TBL_LINKS."
				WHERE ".FLD_LINKS_HASH." in ('".implode("','",$FLD_LINKS_HASH)."')
				AND ".FLD_LINKS_SITE_ID." = '".$this->curSite;
		 		// Check to see if a link exists.
		return $this->DB->GetAssoc($sql);
	}
	
	function store_link($pageInfo,$insert_only=0)
	{	// Store a link in the database.
		$st = $this->sp_time();
		if($this->page_count>SP_PAGELIMIT) return 0;
		$this->page_count++;
		
		$sql_ar = array();
		
		if($pageInfo[FLD_LINKS_ATTEMPTS]) $sql_ar[] = 	$this->sp_sql($pageInfo[FLD_LINKS_ATTEMPTS],FLD_LINKS_ATTEMPTS);
		if($pageInfo[FLD_LINKS_PAGE_HASH]) $sql_ar[] = 	$this->sp_sql($pageInfo[FLD_LINKS_PAGE_HASH],FLD_LINKS_PAGE_HASH);
		if(isset($pageInfo['wordreport']['required_score'])) $sql_ar[] = 	$this->sp_sql($pageInfo['wordreport']['required_score'],FLD_LINKS_SCORE_REQUIRED);
		if(isset($pageInfo['wordreport']['disallowed_score'])) $sql_ar[] = 	$this->sp_sql($pageInfo['wordreport']['disallowed_score'],FLD_LINKS_SCORE_DISALLOWED);
		if(isset($pageInfo['links'])) $sql_ar[] = 							$this->sp_sql(sizeof($pageInfo['links']),FLD_LINKS_LINKS_FOUND);
		if(isset($pageInfo['wordreport'])) $sql_ar[] = FLD_LINKS_LAST_CHECKED."=NOW()";
		
		$sql_update_data = 
		implode(",\n", $sql_ar );
		
		if($pageInfo[FLD_LINKS_PARENT_ID]) $sql_ar[] = 		$this->sp_sql($pageInfo[FLD_LINKS_PARENT_ID],FLD_LINKS_PARENT_ID);
		if($pageInfo[FLD_LINKS_URL]) $sql_ar[] = 		$this->sp_sql($pageInfo[FLD_LINKS_URL],FLD_LINKS_URL);
		if($pageInfo[FLD_LINKS_NAME]) $sql_ar[] = 		$this->sp_sql($pageInfo[FLD_LINKS_NAME],FLD_LINKS_NAME);
		if($pageInfo[FLD_LINKS_EXTERNAL]) $sql_ar[] = 	$this->sp_sql($pageInfo[FLD_LINKS_EXTERNAL],FLD_LINKS_EXTERNAL);
		if($pageInfo[FLD_LINKS_HASH]) $sql_ar[] = 		$this->sp_sql($pageInfo[FLD_LINKS_HASH],FLD_LINKS_HASH);
		if($pageInfo[FLD_LINKS_TYPE]) $sql_ar[] = 		$this->sp_sql($pageInfo[FLD_LINKS_TYPE],FLD_LINKS_TYPE);
		if($pageInfo[FLD_LINKS_DEPTH]) $sql_ar[] = 		$this->sp_sql($pageInfo[FLD_LINKS_DEPTH],FLD_LINKS_DEPTH);
		$sql_ar[] = 	$this->sp_sql($this->curSite,FLD_LINKS_SITE_ID);
		
		$sql_insert_data = 
		implode(",\n", $sql_ar );
		
		$sql = "insert into ".TBL_LINKS." set 
		$sql_insert_data";
		if($sql_update_data && !$insert_only) $sql.= " on DUPLICATE KEY UPDATE $sql_update_data";

		$rs = $this->DB->Execute($sql);
		
		if($rs) $this->sp_log("  Stored/Updated Page ".$pageInfo[FLD_LINKS_URL].". (".$this->sp_dur($st).")",0); 
		else $this->sp_log("  Failed to Store Page ".$pageInfo[FLD_LINKS_URL]." (Duplicate). (".$this->sp_dur($st).")",0); 
		return ($this->DB->Insert_Id());
	}
	
	function get_word_report($HTML)
	{	// Add the weight of each word to make the score. A score of 0 is perfect. High score is bad.
		// Required words will only be required in the 2257 page. We'll work on that later.
		$word_report = array();
		$word_report['score']=0;
		$word_report['disallowed_score']=0;
		$word_report['required_score']=0;
		$badwords = 0; $reqwords = 0;
		$st = $this->sp_time();
		foreach($this->sp_WordList as $word=>$info)
		{
			if(preg_match("/".$word."/i",$HTML)==1)
			{	// If a bad word was found, add to score, cause thats bad!!
				if($info[FLD_WORD_TYPE]=='disallowed')
				{
					$word_report['disallowed_list'][]=$word;
					$word_report['score']+=$info[FLD_WORD_WEIGHT];
					$word_report['disallowed_score']+=$info[FLD_WORD_WEIGHT];
					$badwords++;
				}
				else $reqwords++;
			}
			else
			{	// If a required word was not found, add to score, cause thats bad!!
				if($info[FLD_WORD_TYPE]=='required')
				{
					$word_report['required_list'][]=$word;
					$word_report['score']+=$info[FLD_WORD_WEIGHT];
					$word_report['required_score']+=$info[FLD_WORD_WEIGHT];
				}
			}
		}
		$this->sp_log("  Found ($badwords) bad words and ($reqwords) required words. (".$this->sp_dur($st).")",1); 
		return $word_report;
	}
	
	function get_wordlist()
	{	// Pull word list from database.
		$st = $this->sp_time();
		
		$rs = $this->DB->Execute("select * from ".TBL_WORD_LIST);
		if (!$rs) echo($this->DB->ErrorMsg());
		while ($word = $rs->FetchRow()) {
			$key = $word[FLD_WORD_WORD];
			$this->sp_WordList[$key] = $word;
		}
		$this->sp_log(" Loaded (".sizeof($this->sp_WordList).") words from database. (".$this->sp_dur($st).")\n"); 
	}
	
	
	function get_link($url,$name,$root="",$path="") 
	{	// Get Link information
		$st = $this->sp_time();
		$url = $this->sp_clean_link($url);
		$urlInfo = parse_url($url);
		$rootInfo = parse_url($root);
		$pathInfo = pathinfo($urlInfo['path']);
		
		if($urlInfo['host'])	// Is this page potentially external?
		{
			if(!$urlInfo['scheme'])
				$url.="http://";
				
			$urlInfo[FLD_LINKS_EXTERNAL] = 1;		// Yes it may be.. but...
			if(!$root || str_replace('www.','',$urlInfo['host'])==str_replace('www.','',$rootInfo['host'])) 
				$urlInfo[FLD_LINKS_EXTERNAL] = 0;	// No, its not external
		}
		else
		{
			$rootpath = $url[0]=='/';		// Its internal.
			if(!$path) $path = '/';
			$url = $root.($rootpath?"":$path).$url;
			$urlInfo[FLD_LINKS_EXTERNAL] = 0;
		
		
		}
		$urlInfo['valid']=0;				// This link isn't valid, wtf.
		if($this->sp_ext_allowed($pathInfo['extension'])) 
			$urlInfo['valid']=1;			// Oh wait, its a valid extention
			
		if(strpos($url,"mailto:")!==false)	$urlInfo['valid']=0;
											// WTF, mailto? rtfm bitch.
		
		$hash = $this->sp_get_hash($url);
		$urlInfo[FLD_LINKS_URL] = $url;
		$urlInfo[FLD_LINKS_NAME] = $name;
		$urlInfo[FLD_LINKS_HASH] = $hash;
 
		return $urlInfo;
	}
	
	function get_links($string,$root=NULL,$path=NULL) {

		$regexp = '/'."<a[\s]+[^>]*(?:href|src|url)[\\s]*=[\\s]*\"?([^ #\"'>]+)(?:(?=[ >#])|(?:['\"]))[^>]*>?(.*?)<\/a>".'/';
		preg_match_all ($regexp, $string, &$matches);	// Run star's awesome regexp.
		$ret = $matches;
		$urlInfo = array();
		$i = 0;
		for($i=0;$i<sizeof($matches[0]);$i++)
		{	// For every hyperlink match, get a hash, information about the link
			$name = $matches[1][$i];
			$name = preg_replace('/<[^<]*>/','',$name);
			$urlFound = $this->get_link($name,$matches[2][$i],$root,$path);
			if($urlFound['valid']) $urlInfo[$urlFound[FLD_LINKS_HASH]] = $urlFound;	// If the link is valid, store it.
		}
		
		return $urlInfo;
	}
	
	function sp_log_init($s,$verbose=0,$output=0)
	{	// Setup Log. $s = initial message. $verbose = should we output extra detail?
		$this->sp_log_verbose=$verbose;
		$this->sp_log_output=$output;
		$this->sp_log=($s?$s."\n":"");
	}
	
	function sp_log_get()
	{	// Grab the current log.
		return $this->sp_log;
	}
	
	function sp_log($s,$nonverbose=0)
	{	// Log to the current log global. If its verbose, and verbose global is not on, then ignore the variable.
		if($nonverbose || $this->sp_log_verbose) 
		{
			$this->sp_log.=$s."\n";
			if($this->sp_log_output)
			{
				echo $s."\n";
				flush();
			}
		}
	}
	
	function build_root($linkInfo)
	{	// Build root info out of parse_url info.
		return $linkInfo['scheme']."://".$linkInfo['username'].($linkInfo['password']?":".$linkInfo['password']:"").($linkInfo['username']?"@":"").$linkInfo['host'];
	}
	
	function sp_qs($value)
	{
	   // Stripslashes
	   if (get_magic_quotes_gpc()) {
		   $value = stripslashes($value);
	   }
	   // Quote if not integer
	   if (!is_numeric($value)) {
		   $value = "'" . mysql_real_escape_string($value) . "'";
	   }
	   return $value;
	}
	
	function sp_sql($value,$field,$cmd = "=")
	{
	   return $field.$cmd.$this->sp_qs($value);
	}
	
	
	function sp_time()
	{  	// Get the current micro time.
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	function sp_dur($stime)
	{	// Return the duration since $stime.
		if(!$stime) return "N/A";
		list($usec, $sec) = explode(" ", microtime());
		return round(((float)$usec + (float)$sec)-$stime,4)."s";
	}
	
	function sp_get_hash($link)
	{	// Get a hash of something.
		return strtoupper(md5($link.$this->siteInfo[FLD_SITE_ID]));
	}
	
	function sp_ext_allowed($ext)
	{
		if(!$ext) return 1;
		return(preg_match($this->sp_allowed_ext,$ext));
	}
	
	function sp_clean_link($link)
	{	// Clean up a link, remove session id info. Are there other variable names for session id?
		$sesMatch = "(PHPSESSID|SID)=(\w{32})";
		$find = array('/'."&$sesMatch".'/i', '/'."\?$sesMatch".'/i', '/'."\?$sesMatch&".'/i');
		$replace = array("","","?");
		return(preg_replace($find,$replace,$link));
	}

}
?>