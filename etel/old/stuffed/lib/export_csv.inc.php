<?


function ExportCsv($DataArr, $Separator=";", $NamesArr=false, $Expanded=false)
{
	$Export="";
	if (ValidArr($NamesArr)) {
		foreach($NamesArr as $i=>$Row) $NamesArr[$i]=CsvPrepare($NamesArr[$i], $Separator);
		$Export.=implode($Separator, $NamesArr);
		$Export.="\n";
	}

	foreach ($DataArr as $i=>$Row) {
		$TmpArr=array();
		foreach($NamesArr as $Key=>$SubRow) {
			$Str="";
			$KeyArr=array();
			$KeyArr=explode("|", $Key);
			if (!$Expanded) $KeyArr = array_slice($KeyArr, 0, 1);
			for($z=0;$z<count($KeyArr);$z++) {
				if (!ValidVar($KeyArr[$z])) continue;
				if ($z>0) $Str.=" (";
				$Str.=$Row[$KeyArr[$z]];
				if ($z>0) $Str.=")";
			}
			$TmpArr[]=CsvPrepare($Str, $Separator);
		}
		$Export.=implode($Separator, $TmpArr);
		$Export.="\n";
	}

	return $Export;
}


function CsvPrepare($Str, $Separator) 
{
	if(strpos($Str, $Separator)!==false) {
		return "\"".CsvEscape($Str)."\"";
	}
	else return $Str;
}

function CsvEscape($Str)
{
	return str_replace('"', '""', $Str);
}




function send_file_to_client($filename, $data){ 
   header("Content-type: application/ofx"); 
   header("Content-Disposition: attachment; filename=$filename"); 
   echo $data;   
   exit;
}


?>