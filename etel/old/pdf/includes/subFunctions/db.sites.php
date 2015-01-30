<?
function sites_getSiteInfo($site_id)
{
	$site_id = quote_smart($site_id);
	
	$sql = "
		SELECT
			*
		FROM
			cs_company_sites
		WHERE
			cs_id = '$site_id';
	";
	
	$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
	return mysql_fetch_assoc($res);
}

?>