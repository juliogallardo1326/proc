<?

$Action=($SaveMode=="PAID")?"paid_constructor":"natural_constructor";


if(isset($WhereArr[0])) {
	$ProgPath[0]['Name']=$PageTitle." (".$WhereArr[0]['Name'].")";
	$ProgPath[0]['Url']=getURL($Action, $WhereArr[0]['Url'], "report");
}
else {
	$ProgPath[0]['Name']=$PageTitle;
	$ProgPath[0]['Url']=$nsProduct->SelfAction("CpId=$CpId");
}


for($i=0;$i<count($WhereArr);$i++) {
	if (isset($WhereArr[$i]['Id'])&&isset($WhereArr[$i]['Name2'])) {
	
		if (isset($WhereArr[$i+1])) $ProgPath[$i+1]['Url']=getURL($Action, $WhereArr[$i+1]['Url'], "report");
		else $ProgPath[$i+1]['Url']=getURL($Action, $WhereArr[$i]['Url2'], "report");

		$ProgPath[$i+1]['Name']="<b>".$WhereArr[$i]['Name2']."</b> ";
		if (isset($WhereArr[$i+1])) $ProgPath[$i+1]['Name'].="(".$WhereArr[$i+1]['Name'].")";
	
	}
}


?>