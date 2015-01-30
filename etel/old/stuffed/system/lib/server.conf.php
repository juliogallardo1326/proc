<?


if(!isset($_SERVER['REQUEST_URI'])){
			$_SERVER['REQUEST_URI'] = $_SERVER['PHP_SELF'];
			if(isset($_SERVER['QUERY_STRING'])) 
				$_SERVER['REQUEST_URI'] .= '?'.$_SERVER['QUERY_STRING'];
}			









?>