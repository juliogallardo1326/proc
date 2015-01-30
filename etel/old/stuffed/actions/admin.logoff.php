<?
if (!$nsUser->Logged()) $nsProduct->Redir("default");
$nsUser->Logout();
$nsProduct->Redir("login");
?>