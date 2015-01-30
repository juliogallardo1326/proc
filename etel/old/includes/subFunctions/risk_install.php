<?

class risk_report_install
{
	function risk_report_install()
	{
		$report['Total Transactions']['desc'] = "The total number and value of all transactions";
		$report['Total Transactions']['source'] = '$sales_amount';
		$report['Total Transactions']['display'] = '$sales_count . " (\$" . number_format($sales_amount,2) . ")"';
		$report['Declined Transactions']['desc'] = "Alert when Declines are above Percentage";
		$report['Declined Transactions']['source'] = '$sales_amount > 0 ? $decline_amount*100/$sales_amount : 0';
		$report['Declined Transactions']['display'] = 'number_format($sales_amount > 0 ? $decline_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($decline_amount,2) . ")"';
		$report['Unique Declined Transactions']['desc'] = "Alert when Unique Declines are above Percentage";
		$report['Unique Declined Transactions']['source'] = '$sales_amount > 0 ? $uniquedecline_amount*100/$sales_amount : 0';
		$report['Unique Declined Transactions']['display'] = 'number_format($sales_amount > 0 ? $uniquedecline_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($uniquedecline_amount,2) . ")"';
		$report['Chargebacks - Total']['desc'] = "Alert when Transaction Chargebacks are over a limit";
		$report['Chargebacks - Total']['source'] = '$sales_amount > 0 ? $chargebacks_amount*100/$sales_amount : 0';
		$report['Chargebacks - Total']['display'] = 'number_format($sales_amount > 0 ? $chargebacks_amount*100/$sales_amount : 0,2)."% (\$" . number_format($chargebacks_amount,2) . ")"';
		$report['Chargebacks - Visa']['desc'] = "Alert when Visa Transaction Chargebacks are over a limit";
		$report['Chargebacks - Visa']['source'] = '$sales_amount > 0 ? $chargebacksVisa_amount*100/$sales_amount : 0';
		$report['Chargebacks - Visa']['display'] = 'number_format($sales_amount > 0 ? $chargebacksVisa_amount*100/$sales_amount : 0,2) . "% (\$" . number_format(chargebacksVisa_amount,2) . ")"';
		$report['Chargebacks - MC']['desc'] = "Alert when Mastercard Transaction Chargebacks are over a limit";
		$report['Chargebacks - MC']['source'] = '$sales_amount > 0 ? $chargebacksMastercard_amount*100/$sales_amount : 0';
		$report['Chargebacks - MC']['display'] = 'number_format($sales_amount > 0 ? $chargebacksMastercard_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($chargebacksMastercard_amount,2) . ")"';
		$report['Refunds']['desc'] = "Alert when Refunds are over a limit";
		$report['Refunds']['source'] = '$sales_amount > 0 ? $refund_amount*100/$sales_amount : 0';
		$report['Refunds']['display'] = 'number_format($sales_amount > 0 ? $refund_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($refund_amount,2) . ")"';
		$report['Non-Unique Transactions']['desc'] = "Alert when Non-Unique Transactions are over a limit";
		$report['Non-Unique Transactions']['source'] = '$sales_amount > 0 ? $nonunique_amount*100/$sales_amount : 0';
		$report['Non-Unique Transactions']['display'] = 'number_format($sales_amount > 0 ? $nonunique_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($nonunique_amount,2) . ")"';
//		$report['Unique Transactions']['desc'] = "Alert when Unique Approved Transactions are over a limit";
//		$report['Unique Transactions']['source'] = '$sales_amount > 0 ? $unique_amount*100/$sales_amount : 0';
//		$report['Unique Transactions']['display'] = 'number_format($sales_amount > 0 ? $unique_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($unique_amount,2) . ")"';
		$report['CreditCard Transactions']['desc'] = "Report on CreditCard Sales";
		$report['CreditCard Transactions']['source'] = '$sales_amount > 0 ? $creditcard_amount*100/$sales_amount : 0';
		$report['CreditCard Transactions']['display'] = 'number_format($sales_amount > 0 ? $creditcard_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($creditcard_amount,2) . ")"';
		$report['Check Transactions']['desc'] = "Report on Check Sales";
		$report['Check Transactions']['source'] = '$sales_amount > 0 ? $check_amount*100/$sales_amount : 0';
		$report['Check Transactions']['display'] = 'number_format($sales_amount > 0 ? $check_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($check_amount,2) . ")"';
		$report['Web900 Transactions']['desc'] = "Report on Web900 Sales";
		$report['Web900 Transactions']['source'] = '$sales_amount > 0 ? $web900_amount*100/$sales_amount : 0';
		$report['Web900 Transactions']['display'] = 'number_format($sales_amount > 0 ? $web900_amount*100/$sales_amount : 0,2) . "% (\$" . number_format($web900_amount,2) . ")"';
		$report['Spider Score']['desc'] = "Alert when website has a high Spider Score";
		$report['Spider Score']['source'] = 'number_format($spider,2)';
		$report['Customer Support']['desc'] = "Report on Customer Service";
		$report['Customer Support']['source'] = 'str_replace("\n,","<br>",$customerservice)';
	
		$report['Total Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>1000,"text"=>"Low","color"=>"CCFFCC","score"=>0,"plaintext"=>""),
											array("limit"=>5000,"text"=>"Medium","color"=>"99FF99","score"=>0,"plaintext"=>""),
											array("limit"=>10000,"text"=>"High","color"=>"66FF66","score"=>0,"plaintext"=>""),
											array("limit"=>100000,"text"=>"Severe","color"=>"00FF00","score"=>0,"plaintext"=>"")
										);
															
		$report['Declined Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>"The number of declined transactions is currently at a low level."),
											array("limit"=>25,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>"You should be aware of the number of declined transactions.  This number should be lower."),
											array("limit"=>35,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>"The number of declined transactions is approaching an unacceptible level.  Please take steps to correct this."),
											array("limit"=>45,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"The number of declined transactions is above the acceptible level.  You must correct this problem as soon as possible.")
										);
															
		$report['Unique Declined Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>17,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>"Some transactions are not being approved."),
											array("limit"=>18,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>"The number of transactions not being approved is getting high.  Please correct this."),
											array("limit"=>19,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>"The number of transactions that have not be approved is reaching an unacceptible level.  You should take steps to correct this."),
											array("limit"=>20,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"The number of transactions that failed to be approved is at an unacceptible level.  You must correct this.")
										);

		$report['Chargebacks - Total']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>0.5,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>"You have a low number of chargebacks."),
											array("limit"=>1.0,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>"The number of chargebacks is getting high.  Be aware of this."),
											array("limit"=>1.5,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>"The number of chargebacks is reaching an unacceptible level.  You should take steps to correct this."),
											array("limit"=>2.0,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"The number of chargebacks is at an unacceptible level.  You must correct this.")
										);

		$report['Chargebacks - Visa']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>0.5,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>"You have a very low occurance of chargebacks on Visa cards."),
											array("limit"=>1.0,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>"The number of chargebacks on Visa cards is getting high.  You need to watch this."),
											array("limit"=>1.5,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>"The number of chargebacks on Visa cards is getting too high.  You must correct this."),
											array("limit"=>2.0,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"The number of chargebacks on Visa cards is unacceptible.  This must be corrected immediately.")
										);

		$report['Chargebacks - MC']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>0.25,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>"You have a very low occurance of chargebacks on Mastercard."),
											array("limit"=>0.50,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>"The number of chargebacks on Mastercard is getting high.  You need to watch this."),
											array("limit"=>0.75,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>"The number of chargebacks on Mastercard is getting too high.  You must correct this."),
											array("limit"=>1.0,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"The number of chargebacks on Mastercard is unacceptible.  This must be corrected immediately.")
										);

		$report['Refunds']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>5,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>"The number of refunds is low but you should keep an eye on them."),
											array("limit"=>6,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>"The number of refunds is getting higher.  You should find ways to reduce refunds."),
											array("limit"=>7,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>"The number of refunds is high.  You need to fix this."),
											array("limit"=>8,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"The number of refunds is unacceptible.  This must be fixed.")
										);
	
		$report['Non-Unique Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>""),
											array("limit"=>20,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>""),
											array("limit"=>25,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>""),
											array("limit"=>30,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"")
										);
/*
		$report['Unique Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>""),
											array("limit"=>20,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>""),
											array("limit"=>25,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>""),
											array("limit"=>30,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"")
										);
*/
		$report['CreditCard Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>""),
											array("limit"=>20,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>""),
											array("limit"=>25,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>""),
											array("limit"=>30,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"")
										);
		
		$report['Check Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>""),
											array("limit"=>20,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>""),
											array("limit"=>25,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>""),
											array("limit"=>30,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"")
										);

		$report['Web900 Transactions']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>""),
											array("limit"=>20,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>""),
											array("limit"=>25,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>""),
											array("limit"=>30,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"")
										);

		$report['Spider Score']['label'] = array(
											array("limit"=>0,"text"=>"Very Low","color"=>"FFFFFF","score"=>0,"plaintext"=>""),
											array("limit"=>15,"text"=>"Low","color"=>"FFEEEE","score"=>10,"plaintext"=>""),
											array("limit"=>20,"text"=>"Medium","color"=>"FFAAAA","score"=>20,"plaintext"=>""),
											array("limit"=>25,"text"=>"High","color"=>"FF6666","score"=>30,"plaintext"=>""),
											array("limit"=>30,"text"=>"Severe","color"=>"FF0000","score"=>40,"plaintext"=>"")
										);

		$report_dates[] = array(
								"rrd_name"=>"month",
								"rrd_title"=>"This Month",
								"rrd_from_day"=>'1',
								"rrd_from_month"=>'$this_month',
								"rrd_from_year"=>'$this_year',
								"rrd_to_day"=>'0',
								"rrd_to_month"=>'$this_month+1',
								"rrd_to_year"=>'$this_year'
								);
		$report_dates[] = array(
								"rrd_name"=>"month",
								"rrd_title"=>"Last Month",
								"rrd_from_day"=>'1',
								"rrd_from_month"=>'$this_month-1',
								"rrd_from_year"=>'$this_year',
								"rrd_to_day"=>'0',
								"rrd_to_month"=>'$this_month',
								"rrd_to_year"=>'$this_year'
								);

		$report_dates[] = array(
								"rrd_name"=>"day",
								"rrd_title"=>"Today",
								"rrd_from_day"=>'$this_day',
								"rrd_from_month"=>'$this_month',
								"rrd_from_year"=>'$this_year',
								"rrd_to_day"=>'$this_day',
								"rrd_to_month"=>'$this_month',
								"rrd_to_year"=>'$this_year'
								);
		$report_dates[] = array(
								"rrd_name"=>"day",
								"rrd_title"=>"Yesterday",
								"rrd_from_day"=>'$this_day-1',
								"rrd_from_month"=>'$this_month',
								"rrd_from_year"=>'$this_year',
								"rrd_to_day"=>'$this_day-1',
								"rrd_to_month"=>'$this_month',
								"rrd_to_year"=>'$this_year'
								);
		$report_dates[] = array(
								"rrd_name"=>"week",
								"rrd_title"=>"Last Week",
								"rrd_from_day"=>'$this_day-13',
								"rrd_from_month"=>'$this_month',
								"rrd_from_year"=>'$this_year',
								"rrd_to_day"=>'$this_day-7',
								"rrd_to_month"=>'$this_month',
								"rrd_to_year"=>'$this_year'
								);
		$report_dates[] = array(
								"rrd_name"=>"week",
								"rrd_title"=>"This Week",
								"rrd_from_day"=>'$this_day-6',
								"rrd_from_month"=>'$this_month',
								"rrd_from_year"=>'$this_year',
								"rrd_to_day"=>'$this_day',
								"rrd_to_month"=>'$this_month',
								"rrd_to_year"=>'$this_year'
								);
								
		$report_proj[] = array(
						"rrp_name"=>'tomorrow',
						"rrp_title"=>'Tomorrow',
						"rrp_equation"=>'number_format($lastday != 0 ? $thisday+$thisday-$lastday : 0,2)'
							);
		$report_proj[] = array(
						"rrp_name"=>'nextweek',
						"rrp_title"=>'Next Week',
						"rrp_equation"=>'number_format($lastweek != 0 ? $thisweek+$thisweek-$lastweek : 0,2)'
							);
		$report_proj[] = array(
						"rrp_name"=>'nextmonth',
						"rrp_title"=>'Next Month',
						"rrp_equation"=>'number_format($lastmonth != 0 ? $thismonth+$thismonth-$lastmonth : 0,2)'
							);
		
		foreach($report as $title => $info)
		{
				$values = "rrc_title=\"" . $title . "\"";
				$values .= ", rrc_desc=\"" . $info['desc'] . "\"";
				$values .= ", rrc_equation='" . $info['source'] . "'";
				$values .= ", rrc_display='" . $info['display'] . "'";
				$values .= ", rrc_label='" . quote_smart(serialize($info['label'])) . "'";
				
				$sql = "INSERT INTO cs_risk_report_calc SET $values ON DUPLICATE KEY UPDATE $values;";
				$res = sql_query_write($sql) or dieLog(mysql_error());
		}

		$sql = "DELETE FROM cs_risk_report_dates";
		$res = sql_query_write($sql) or dieLog(mysql_error());
		
		foreach($report_dates as $index => $info)
		{
				$values = "";
				foreach($info as $name=>$value)
					$values .= ($values!="" ? ", " : "") . "$name=\"$value\"";
					
				$sql = "INSERT INTO cs_risk_report_dates SET $values;";
				$res = sql_query_write($sql) or dieLog(mysql_error());
		}

		foreach($report_proj as $index => $info)
		{
				$values = "";
				foreach($info as $name=>$value)
					$values .= ($values!="" ? ", " : "") . "$name=\"$value\"";

				$sql = "INSERT INTO cs_risk_report_projections SET $values ON DUPLICATE KEY UPDATE $values;";
				$res = sql_query_write($sql) or dieLog(mysql_error());
		}
	}
}
?>