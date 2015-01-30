<?
	
	function get_merchant_quick_status($id)
	{
	
		$sql = "SELECT cd.userId, companyname,  
		sum( amount*(status='A') ) AS Sales, 
		sum( amount*(status='A' AND transactionDate > now() - interval 1 DAY)) AS Sales_Today, 
		sum( amount*(status='A' AND transactionDate > now() - interval cd_payperiod DAY)) AS Sales_ThisPeriod, 
		sum( amount*(status='A' AND transactionDate between now() - interval cd_payperiod*2 DAY AND now() - interval cd_payperiod DAY )) AS Sales_LastPeriod, 
		sum( amount*(status='A' AND transactionDate between now() - interval cd_payperiod*3 DAY AND now() - interval cd_payperiod*2 DAY )) AS Sales_Last2Period, 
		sum( amount ) AS Total, 
		count(*) AS Total_Cnt,
		sum( amount*(cancelstatus='Y') ) AS Refunds, 
		max( `transactionDate` ) AS last_transaction, 
		min( `transactionDate` ) AS first_transaction, 
		(UNIX_TIMESTAMP( max( `transactionDate` ) ) - UNIX_TIMESTAMP( min( `transactionDate` ) )) / ( 24 *60 *60 )+1 AS Days_Processing
		
		FROM `cs_companydetails` AS cd
		LEFT JOIN `cs_transactiondetails` AS td ON td.`userId` = cd.`userId`
		where cd.`userId` = '$id'
		GROUP BY cd.`userId`
		LIMIT 1";
		$result=mysql_query($sql) or dieLog(mysql_errno().": ".mysql_error()." ~ $sql");
		$list = mysql_fetch_assoc($result);
		
		
		$score = array();
		$score['actual'] = 0;
		$score['possible'] = 0;
		$score['summmary'] = "Score Summary:\n";
		// Is processing?
		if($list['Total_Cnt'] && $list['Days_Processing']>0)
		{
			$avg = $list['Total']/$list['Days_Processing'];
			if($avg>0)
			{
				$cur_score=($list['Sales_Today']/$avg);
				$score['summmary'] .= "Today Company is processing at %".formatMoney($cur_score*100)." of Daily Average.\n";
				if($cur_score>1) $cur_score = 1;
				$score['possible']+=30;
				$score['actual']+=intval(30*$cur_score);
			}
			
			$tot = $list['Sales_ThisPeriod']+$list['Sales_LastPeriod']+$list['Sales_Last2Period'];
			if ($tot)
			{
				$cur_score=(($list['Sales_ThisPeriod']*3)/($list['Sales_ThisPeriod']+$list['Sales_LastPeriod']+$list['Sales_Last2Period']));
				$score['summmary'] .= "For This Sales Period, Company is processing at %".formatMoney($cur_score*100)." of 3 Period Average.\n";
				if($cur_score>1)$cur_score=1;
				$score['possible']+=50;
				$score['actual']+=intval(50*$cur_score);
			}
		}
		$score['percent']=0;
		if($score['possible']) $score['percent']=100*(formatMoney($score['actual']/$score['possible']));
		return $score;
	}
?>