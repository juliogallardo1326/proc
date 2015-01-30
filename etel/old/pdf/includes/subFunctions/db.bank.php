<?
function bank_GetByName($bank_name)
{
	$sql="SELECT * FROM cs_bank WHERE bank_name = '$bank_name';";
	$bank_details=sql_query_read($sql) or dieLog(mysql_error() . "<p>$sql</p>");
	return mysql_fetch_assoc($bank_details);
}

function bank_GetByID($bank_id)
{
	$sql="SELECT * FROM cs_bank WHERE bank_id = '$bank_id';";
	$bank_details=sql_query_read($sql) or dieLog(mysql_error() . "<p>$sql</p>");
	return mysql_fetch_assoc($bank_details);
}

function bank_GetAll()
{
	$sql="SELECT * FROM cs_bank;";
	$bank_details=sql_query_read($sql) or dieLog(mysql_error() . "<p>$sql</p>");
	$list = array();
	while($r = mysql_fetch_assoc($bank_details))
		$list[] = $r;
	return $list;
}

function bank_GetAllByID()
{
	$sql="SELECT * FROM cs_bank;";
	$bank_details=sql_query_read($sql) or dieLog(mysql_error() . "<p>$sql</p>");
	$list = array();
	while($r = mysql_fetch_assoc($bank_details))
		$list[$r['bank_id']] = $r;
	return $list;
}

function bank_ChooseSupported($bk_trans_types,$en_ID,$suggested_bank = NULL)
{
	$sql="SELECT * FROM cs_bank as bk left join cs_company_banks as cb on cb.bank_id = bk.bank_id 
			Where cb.cb_en_ID = '$en_ID' and bk.bk_trans_types = '$bk_trans_types'
	;";
	$bank_details=sql_query_read($sql) or dieLog(mysql_error() . "<p>$sql</p>");
	$list = array();
	while($r = mysql_fetch_assoc($bank_details))
	{
		if($r['bank_id']) $chosen_bank = $r['bank_id'];
		$list['banks'][$r['bank_id']] = $r;
	}
	if($list['banks'][$suggested_bank]) $chosen_bank = $suggested_bank;
	$list['chosen'] = $chosen_bank;
	
	return $list;
}
?>