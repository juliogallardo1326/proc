<?
class ivr_class
{
	function ivr_class()
	{
		if(!$this->mysql_table_exists('cs_ivr_log'))
			$this->create_table();
	}
	
	function mysql_table_exists($table,$db=0)
	{
		$table = mysql_real_escape_string($table);
	
		$sql = "SELECT 1 FROM $table LIMIT 0";
	
		$result=sql_query_read($sql,$db);
		if(!$result)
			return 0;
		return 1;
	}
	
	function create_table()
	{
		$sql = "
			CREATE TABLE cs_ivr_log
			(
				iv_call_id VARCHAR(255),
				`iv_datetime` VARCHAR(32), 
				`iv_phone` VARCHAR(32),
				`iv_page_name` VARCHAR(255), 
				`iv_query` TEXT,
				`iv_duration` INT
			);
		";
		sql_query_write($sql);
	}
	
	function store_log($unique_id,$phone,$query,$call_duration,$page_name)
	{
		$sql = "
			INSERT INTO 
				`cs_ivr_log` 
			SET
				`iv_call_id` = '$unique_id', 
				`iv_datetime` = '" . date("Y-m-d H:i:s",time()) . "', 
				`iv_phone` = '$phone',
				`iv_page_name` = '$page_name', 
				`iv_query` = '".quote_smart(serialize($query))."',
				`iv_duration` = '$call_duration'
			";
		sql_query_write($sql);
	
		return 0;
		
		$fp = fopen("log.txt","a");
		$sql = serialize($sql);
		$sql = str_replace("\r","<r>",$sql);
		$sql = str_replace("\n","<n>",$sql);
		fwrite($fp,serialize($sql) . "\r\n");
	}
	
	function get_summary($date_from,$date_to)
	{
		$date_from = date("Y-m-d 00:00:00",$date_from);
		$date_to = date("Y-m-d 23:59:59",$date_to);
		
		$sql = "
			SELECT	
				iv_call_id,
				min(iv_datetime) AS call_start,
				max(iv_datetime) AS call_end,
				iv_phone,
				max(iv_duration) AS call_duration
			FROM
				cs_ivr_log
			WHERE
				iv_datetime BETWEEN '$date_from' AND '$date_to'
			GROUP BY
				iv_call_id
			ORDER BY
				iv_datetime DESC
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$sum = array();
		while($r = mysql_fetch_assoc($res))
			$sum[] = $r;
		return $sum;
	}

	function get_calls_today($phone)
	{
		$date_from = date("Y-m-d 00:00:00",time());
		$date_to = date("Y-m-d 23:59:59",time());

		$sql = "
			SELECT	
				COUNT(DISTINCT(iv_call_id)) AS calls
			FROM
				cs_ivr_log
			WHERE
				iv_phone = '$phone'
				AND iv_datetime BETWEEN '$date_from' AND '$date_to'
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$sum = array();
		$r = mysql_fetch_assoc($res);
		return $r['calls'];
	}
	
	function get_phone_summary($phone)
	{
		$sql = "
			SELECT	
				iv_call_id,
				min(iv_datetime) AS call_start,
				max(iv_datetime) AS call_end,
				iv_phone,
				max(iv_duration) AS call_duration
			FROM
				cs_ivr_log
			WHERE
				iv_phone = '$phone'
			GROUP BY
				iv_call_id
			ORDER BY
				iv_datetime DESC
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$sum = array();
		while($r = mysql_fetch_assoc($res))
			$sum[] = $r;
		return $sum;
	}

	function get_call_details($callid)
	{
		
		$sql = "
			SELECT	
				*
			FROM
				cs_ivr_log
			WHERE
				iv_call_id = '$callid'
			ORDER BY
				iv_datetime ASC
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$call = array();
		while($r = mysql_fetch_assoc($res))
			$call[] = $r;
		return $call;
	}
	
	function get_page_summary($date_from,$date_to)
	{
		$date_from = date("Y-m-d 00:00:00",$date_from);
		$date_to = date("Y-m-d 23:59:59",$date_to);
		
		$sql = "
			SELECT	
				iv_page_name,
				count(*) AS views
			FROM
				cs_ivr_log
			WHERE
				iv_datetime BETWEEN '$date_from' AND '$date_to'
			GROUP BY
				iv_page_name
			ORDER BY
				LOWER(iv_page_name) ASC
		";
		
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$sum = array();
		while($r = mysql_fetch_assoc($res))
			$sum[] = $r;
		return $sum;
	}
	
	function get_session($unique_id)
	{
		$sql = "
			SELECT
				iv_query
			FROM
				cs_ivr_log
			WHERE
				iv_call_id = '$unique_id'
				AND iv_page_name = 'session'
		";
		$res = sql_query_read($sql) or die(mysql_error() . "<pre>$sql</pre>");
		if(!$r = mysql_fetch_assoc($res))
			return NULL;
		return stripslashes($r['iv_query']);
	}
	
	function store_session($unique_id,$query)
	{
		$query = addslashes($query);
		
		if($this->get_session($unique_id) != NULL)
		{
			$sql = "
				UPDATE
					cs_ivr_log
				SET
					iv_query = '$query'
				WHERE
					iv_call_id = '$unique_id'
					AND iv_page_name = 'session'
			";
		}
		else
		{
			$sql = "
				INSERT INTO
					cs_ivr_log
				SET
					iv_query = '$query',
					iv_page_name = 'session',
					iv_call_id = '$unique_id'
			";
		}
		sql_query_write($sql) or die(mysql_error() . "<pre>$sql</pre>");
	}
}

?>