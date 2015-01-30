<?

//////////////////////////
function ResortTable($Table, $ByField=false, $Where = false, $Direction = false)
{
	if (!$Table) return false;
	global $Db;
	if (!$ByField) $ByField="POSITION";
	if ($Where) $Where = "WHERE ".$Where;

	if ($Direction == 1) {
		$Query = "SELECT MAX($ByField) AS MAX_POS FROM $Table";
		$Check = $Db->Select($Query);
		$MaxPos = $Check->MAX_POS+1;
	}

	$Query = "SELECT ID, $ByField FROM $Table $Where ORDER BY $ByField ASC";
	$Sql = new Query($Query);
	while ($Row = $Sql->Row())
	{
		if ($Row->$ByField == 0 && $Direction == 1 && $MaxPos) {
			$Query = "UPDATE $Table SET $ByField = $MaxPos WHERE ID = ".$Row->ID;
			$Db->Query($Query);
		}
		if ($Row->$ByField != $Sql->Position+1) {
			if ($Direction == 1 && $Row->$ByField == 0) continue;
			$Query = "UPDATE $Table SET $ByField = ".($Sql->Position+1)." WHERE ID = ".$Row->ID."";
			$Db->Query($Query);
		}
	}
}

//////////////////////////
function SortTable($Table, $ByField=false, $Id, $To, $Where = false)
{
	if (!$Table || !$Id || !$To) return false;
	if ($To != "Up" && $To != "Down") return false;
	global $Db;
	if (!$ByField) $ByField="POSITION";
	if ($Where) $WhereStr = " AND ".$Where;
	else $WhereStr="";

	$Query = "SELECT ID, $ByField FROM $Table WHERE ID = $Id";
	$El1 = $Db->Select($Query);
	if ($To=="Up") $Pos2 = $El1->POSITION - 1;
	if ($To=="Down") $Pos2 = $El1->POSITION + 1;
	$Query = "SELECT ID, $ByField FROM $Table WHERE POSITION = ".$Pos2." $WhereStr";
	$El2 = $Db->Select($Query);
	$Query = "UPDATE $Table SET $ByField = ".$Pos2." WHERE ID = ".$El1->ID."";
	$Db->Query($Query);
	$Query = "UPDATE $Table SET $ByField = ".$El1->POSITION." WHERE ID = ".$El2->ID."";
	$Db->Query($Query);
	ResortTable($Table, $ByField, $Where);
}

?>