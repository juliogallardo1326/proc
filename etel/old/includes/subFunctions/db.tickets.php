<?
	function tickets_getGatewayID($ticketid)
	{
		$ticketid = mysql_real_escape_string($ticketid);
		$qry = "
				SELECT user.cs_gateway_id
				FROM
					tickets_tickets AS tick,
					tickets_users AS user
				WHERE
					tick.tickets_username = user.tickets_users_username
					AND tick.tickets_id = '$ticketid'
				";
		
		$res = sql_query_read($qry);
		$row = mysql_fetch_assoc($res);
		return $row['cs_gateway_id'];
	}							
?>