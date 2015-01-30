<?
class entities_class
{
	var $entities;
	
	function entities_class()
	{
		$this->entities['merchant'] = array(
			"table"=>"cs_companydetails",
			"id_field"=>"userid",
			"user_field"=>"username",
			"pass_field"=>"password"
			);

		$this->entities['sys_user'] = array(
			"table"=>"cs_login",
			"id_field"=>"userid",
			"user_field"=>"username",
			"pass_field"=>"password"
			);

/*
		$this->entities['customer'] = array(
			"table"=>"cs_transactiondetails",
			"id_field"=>"transactionid",
			"user_field"=>"td_username",
			"pass_field"=>"td_password"
			);
*/

		$this->entities['bank'] = array(
			"table"=>"cs_bank",
			"id_field"=>"bank_id",
			"pass_field"=>"1",
			"user_field"=>"bank_name"
			);
		
		$this->entities['processor'] = array(
			"table"=>"cs_processors",
			"id_field"=>"pr_ID",
			"pass_field"=>"1",
			"user_field"=>"pr_name"
			);
	}
	
	function add_entity($type,$user,$pass,$table_id)
	{
		$type = quote_smart($type);
		$user = quote_smart($user);
		$pass = quote_smart($pass);
		$table_id = quote_smart($table_id);
		
		if(!isset($this->entities[$type]))
			return -1;
			
		$sql = "
			INSERT INTO
				cs_entities
			SET
				et_type = '$type',
				et_username = '$user',
				et_password = '$pass',
				et_table_ID = '$table_id'
			";
		sql_query_write($sql);
	}
	
	function compile_entities()
	{
		foreach($this->entities as $type => $info)
		{
			$sql = "
				SELECT
					" . $info['id_field'] . " AS table_id,
					" . $info['user_field'] . " AS user,
					" . $info['pass_field'] . " AS pass
				FROM
					" . $info['table'] . "
			";
			$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
			while($r = mysql_fetch_assoc($res))
				$this->add_entity($type,$r['user'],$r['pass'],$r['table_id']);
		}
	}
	
	function get_entity_id($type,$table_id)
	{
		$type = quote_smart($type);
		$table_id = quote_smart($table_id);

		$sql = "
			SELECT
				et_ID
			FROM
				cs_entities
			WHERE
				et_type = '$type'
				AND et_table_id = '$table_id'
		";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$r = mysql_fetch_assoc($res);
		return $r['et_ID'] != 0 ? $r['et_ID'] : -1;
	}

	function get_entity_id_by_name($type,$name)
	{
		$type = quote_smart($type);
		$name = quote_smart($name);

		$sql = "
			SELECT
				et_ID
			FROM
				cs_entities
			WHERE
				et_type = '$type'
				AND et_username = '$name'
		";
		$res = sql_query_read($sql) or dieLog(mysql_error() . "<pre>$sql</pre>");
		$r = mysql_fetch_assoc($res);
		return $r['et_ID'];
	}
}
?>