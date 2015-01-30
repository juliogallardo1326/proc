<?
$etel_debug_mode = 0;
$etel_disable_https = 1;
$gateway_db_select = 3;
set_time_limit(0);
//chdir("..");
$mins = 15;
require_once("../includes/dbconnection.php");
require_once("../admin/includes/mailbody_replytemplate.php");
require_once("../includes/function.php");
require_once("../includes/integration.php");
require_once("../includes/transaction.class.php");

$test = (isset($_GET['test']) ? TRUE : FALSE);

$cnt = 0;
$trans_class= new transaction_class(false);

function clean_html($text)
{
	if(is_array($text))
	{
		foreach($text as $key=>$value)
			$text[$key] = clean_html($value);
		return $text;
	}
	return str_replace(">","&gt;",str_replace("<","&lt;",$text));
}
	

function process_expired(&$trans_class,$mins,$cntLimit=0)
{
	echo "<p>processing expired subscriptions</p>";
	$timeLimit = time() + ($mins * 60);
	$cnt=1;
	
	echo "
	<table>
			<tr>
			<td>Subscription ID</td><td>&nbsp;</td>
			<td>Count ID</td><td>&nbsp;</td>
			<td>Response</td><td>&nbsp;</td>
			</tr>
	";
	
	while((time() < $timeLimit || $mins==0) && ($cnt < $cntLimit || $cntLimit==0) && $id = $trans_class->get_next_expired_rebill())
	{
		$trans_class->pull_subscription($id);
		$res = $trans_class->update_account_status();
		echo "
			<tr>
			<td>$id</td><td></td>
			<td>" . $cnt . "</td><td></td>
			<td>" . clean_html($res[1]['response']['body']) . "</td><td></td>
			</tr>
		";
		flush();
		$cnt++;
	}
	echo "
	</table>
	";
}

function process_rebills(&$trans_class,$cntLimit=0,$settings=NULL)
{
	$mins=0;
	$logged = "<p>processing subscriptions</p>";
	
	
	if($settings['bank_limit']) $bank_html .= "<tr><td colspan='10'> Banks: ".implode(', ',$settings['bank_limit'])." </td></tr>";
	if($cntLimit) $bank_html .= "<tr><td colspan='10'> Processing $cntLimit Transactions...</td></tr>";
	
	$logged .= "
	<table>
			$bank_html
			<tr>
			<td>Subscription ID</td><td>&nbsp;</td>
			<td>Attempt</td><td>&nbsp;</td>
			<td>Success</td><td>&nbsp;</td>
			<td>Error Message</td><td>&nbsp;</td>
			<td>Status</td><td>&nbsp;</td>
			<td>Amount</td><td>&nbsp;</td>
			<td>Rebill Status</td><td>&nbsp;</td>
			<td>Should Rebill On</td><td>&nbsp;</td>
			<td>Next Rebill Date</td><td>&nbsp;</td>
			<td>Transaction ID</td><td>&nbsp;</td>
			<td>Rebills Left</td><td>&nbsp;</td>
			</tr>
	";
	echo $logged;
	flush();
	$timeLimit = time() + ($mins * 60);
	$cnt=0;
	
	while(($cnt < $cntLimit || $cntLimit==0) && $rebill = $trans_class->get_next_rebill($settings))
	{
		$id = $rebill['ss_ID'];
		if(!isset($count[$id]))
			$count[$id] = 1;
		else
		{
			$count[$id]++;
			toLog('erroralert','rebill','Subscription ID: $id, attempted to rebill more than once in one session',$id);
		}
		if(!$id) break;
		$trans_class->pull_subscription($id);
		
		$old_rebill_date = $trans_class->row['subscriptionTable']['ss_rebill_next_date'];
		
		if($count[$id] == 1)			
			$res = $trans_class->processRebill();

		$logged = "
			<tr>
			<td>$id</td><td></td>
			<td>" . $count[$id] . "</td><td></td>
			<td>" . $res['success'] . "</td><td></td>
			<td>" . $res['errormsg'] . "</td><td></td>
			<td>" . $res['status'] . "</td><td></td>
			<td>" . $trans_class->amount . "</td><td></td>
			<td>" . $trans_class->row['subscriptionTable']['ss_rebill_status'] . "</td><td></td>
			<td>" . $old_rebill_date . "</td><td></td>
			<td>" . $trans_class->row['subscriptionTable']['ss_rebill_next_date'] . "</td><td></td>
			<td>" . $res['transactionId'] . "</td><td></td>
			<td>" . $rebill['num_rows'] . "</td><td></td>
			</tr>
		";
		if($res['status'] != 'D') $totalamount += $trans_class->amount;
		echo $logged;		
		$log .=  $count[$id]." Result:" . $res['errormsg'] . " Status:" . $res['status'] . " ID:" . $res['transactionId'] . "\n";
	
		$cnt++;
		flush();
		//if($mins==0) sleep(rand(0,10));
	}
	echo "</table>";
	echo "Total Amount Processed: $totalamount<BR>";
	$logged = $cnt.($testTrans ? " TEST" : " LIVE")." transactions attempted to rebill on ".date("m/d/Y")." at ".date("g:i a").". Settings = ".print_r($settings,true);

	echo $logged;
	$log .= $logged;
		
	toLog('misc','system', $log, -1);

}
$sql = "

SELECT *
FROM 
	(
		SELECT 
			ss_bank_id, sum( ss_rebill_amount ) AS totalamount, count( * ) AS totalcnt
		FROM 
			cs_subscription
			left join cs_companydetails as cd on userId = ss_user_ID
			left join cs_company_sites as cs on cs_ID = ss_site_ID
			left join cs_rebillingdetails on ss_rebill_id = rd_subaccount 
		WHERE
				ss_rebill_status = 'active'
			AND ss_rebill_frozen = 'no'
			AND activeuser = '1'
			AND rd_enabled = 'Yes'
			AND cs_verified in ('approved','non-compliant')
		GROUP BY 
			ss_bank_id
	) AS t2 
left join
	(
		SELECT 
			bank_id, sum( amount ) AS amt, count( * ) AS cnt
		FROM 
			`cs_transactiondetails`
		WHERE 
			transactionDate > subdate( now( ) , 1 )
			AND td_is_a_rebill =1
			AND STATUS != 'D'
		GROUP BY 
			bank_id
	) AS t1
ON 
	ss_bank_id = bank_id
";
$result = sql_query_read($sql) or dieLog($sql);
while($dayinfo_fetch = mysql_fetch_assoc($result))
	$dayinfo[$dayinfo_fetch['bank_id']] = $dayinfo_fetch;

$bankList = array(
	18 => NULL,
	31 => array('cnt_limit'=>30),
	34 => array('cnt_limit'=>30),
	
	//41 => array('cnt_limit'=>30,'amt_limit'=>1000,'bill_cnt'=>10,'bank_where'=>"ss_user_ID in (114466,121470,1328,124796,139440,116529) && ss_billing_cvv2 is not null"),
	//42 => array('cnt_limit'=>15,'amt_limit'=>500,'bill_cnt'=>5,'bank_where'=>"ss_user_ID in (114466,121470,1328,124796,139440,116529) && ss_billing_cvv2 is not null")//,124796,139807

	41 => array('cnt_limit'=>1800,'amt_limit'=>18000,'bill_cnt'=>120,'bank_where'=>"ss_billing_cvv2 is not null"),
	42 => array('cnt_limit'=>1000,'amt_limit'=>10000,'bill_cnt'=>60,'bank_where'=>"ss_billing_cvv2 is not null")
	//39 => array('cnt_limit'=>1000,'amt_limit'=>10000,'bill_cnt'=>120),
	//40 => array('cnt_limit'=>1000,'amt_limit'=>10000,'bill_cnt'=>60)
);


foreach($bankList as $bankid => $bankItem)
{
	$bankItem = array_merge($dayinfo[$bankid],$bankItem);
	$bankItem['skip'] = false;
	
	if(!isset($bankItem['cnt_limit']))
		$bankItem['cnt_limit'] = intval($dayinfo[$bankid]['totalcnt']/30);
	if(!isset($bankItem['amt_limit']))
		$bankItem['amt_limit'] = intval($dayinfo[$bankid]['totalamount']/30);
	
	if(!isset($bankItem['bill_cnt']))
		$bankItem['bill_cnt'] = intval($bankItem['cnt_limit']/12);
	if($bankItem['bill_cnt']<1) $bankItem['skip'] = true;
	
	if($bankItem['cnt_limit'] && $bankItem['cnt']>$bankItem['cnt_limit'])
		$bankItem['skip'] = true;
	if($bankItem['amt_limit'] && $bankItem['amt']>$bankItem['amt_limit'])
		$bankItem['skip'] = true;
		
	if(!$bankItem['skip'])
		if(!$test)	process_rebills($trans_class,$bankItem['bill_cnt'],array('bank_limit'=>array($bankid),'bank_where'=>$bankItem['bank_where']));
		
	else echo 'Skipping Bank '.$bankid.'...<br>'.print_r($bankItem,true);
	if($test) etelPrint($bankItem);
}
	
if(!$test)	process_expired($trans_class,0,250);



?>
