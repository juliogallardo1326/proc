<?

if (!$Db->ID) $nsProduct->Redir("db", "", "error");


if ($nsUser->Logged()) ExtendUser();
if ($nsUser->Logged()&&!$nsUser->ADMIN&&!$nsUser->MERCHANT) {
	$nsUser->Logout();
	$nsProduct->Redir("login", "ac_err=1", "admin");
}


if ($nsProduct->STRT_INT==0) {
	$Query = "UPDATE ".PFX."_system_product SET STRT_INT = '".time()."' WHERE ID = ".$nsProduct->ID;
	$Db->Query($Query);
	TrialExceed();
}

if ($nsProduct->TRIAL_EXCEED) $nsProduct->Redir("license", "EditId=new", "admin");


if (!$nsUser->Logged() && $nsProduct->Action!="login") {
	// user was not logged in, but wanted to open some page that required
  // authorization, we send the user to the login page and also pass
  // the login page our current URL so that after successful login the
  // user will be redirected where he intended to go
	$get = 'redirect='.urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	$nsProduct->Redir('login', $get, 'admin');	
}	

if (ValidVar($_GP['RUpd'])) $Logs->Msg($Lang['RecordUpdated']);
if (ValidVar($_GP['RCrt'])) $Logs->Msg($Lang['RecordCreated']);
if (ValidVar($_GP['RDlt'])) $Logs->Msg($Lang['RecordDeleted']);
?>