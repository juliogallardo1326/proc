<?
	$CurPath = str_replace("\\", '/', __FILE__);
	$CurPath = preg_replace("/\/+/", "/", $CurPath);
	$CurPath = substr($CurPath, 0, strrpos($CurPath, '/'));
if(!isset($_SERVER['HTTP_HOST'])){
 if($_SERVER['argc']>1){	
	$argc = $_SERVER['argc'];
	$argv = $_SERVER['argv'];
	
	$argv2 = explode('=', $argv[2]);
	if(!isset($_REQUEST[$argv2[0]]) || $_REQUEST[$argv2[0]]!=$argv2[1]){
		foreach($argv as $v)if($v!=$argv[0]){
			$w = explode('=', $v);
			$_REQUEST[$w[0]]=$w[1];
			$_GET[$w[0]]=$w[1];
		}	
	}
 }	
	$ProdPath = '';
	$DocRoot  =$CurPath;
	$_SERVER['SERVER_NAME'] = 'no-server-name';
	$_SERVER['HTTP_HOST'] = 'no-http-host';
	$_SERVER['HTTP_USER_AGENT'] = 'command line';
	$_SERVER['REMOTE_ADDR'] = '1.1.1.1';
	$_SERVER['REQUEST_URI'] = $_SERVER['HTTP_HOST'].'/'.$argv[0];
	$from_command_line=true;
}else{
	$ProdPath = str_replace("\\", '/', $_SERVER['PHP_SELF']);
	$ProdPath = preg_replace("/\/+/", "/", $ProdPath);
	$ProdPath = substr($ProdPath, 0, strrpos($ProdPath, '/'));
	$DocRoot=str_replace($ProdPath, "", $CurPath);
	if (@is_dir("system")) $SPath="";
	else $SPath="/..";

}
if(!isset($_SERVER['DOCUMENT_ROOT']))
	$_SERVER['DOCUMENT_ROOT'] = $DocRoot;
	
	
?>