<?
function http_post($server, $port, $url, $urlencoded, $username="", $password="")
{

// example:
//  http_post(
//	"www.fat.com",
//	80,
//	"/weightloss.pl",
//	array("name" => "obese bob", "age" => "20")
//	);

	$user_agent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)";

$base_64_auth = base64_encode($username.":".$password);
	$content_length = strlen($urlencoded);
	$headers = "POST $url HTTP/1.1
Accept: */*
Accept-Language: en-au
Content-Type: application/x-www-form-urlencoded
User-Agent: $user_agent
Host: $server
Connection: Keep-Alive
Cache-Control: no-cache
Authorization: Basic $base_64_auth
Content-Length: $content_length

";
etelPrint($headers);
	$time = microtime_float();
	$fp = fsockopen($server, $port, &$errno, &$errstr,45);
	if (!$fp) {
		return "$errno: $errstr";
	}
	fputs($fp, $headers);
	fputs($fp, $urlencoded);

	$ret = "";
	$body = false;
     while (!feof($fp)) {
       $s = @fgets($fp, 1024);

       if ( $body)
           $ret .= $s;
       if ( $s == "\r\n" )
           $body = true;
	   if( (microtime_float()-$time)>45) return "Timeout: $ret";
   }
	fclose($fp);

	return $ret;
}

function http_post2($server, $port, $url, $params, $username="", $password="")
{
	$ch = curl_init();

	$parseurl =	parse_url($url);
	$postmet = $parseurl['scheme'];
	$postser = $parseurl['host'];
	$is_ssl = stristr($postmet,"https") !== FALSE;
	//$postport = stristr($postmet,"https") !== FALSE ? "443" : "80";
	//$postser = (stristr($postmet,"https") !== FALSE ? "ssl://" : "") . $postser;

	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	if($username || $password)
	{
		curl_setopt($ch, CURLOPT_USERPWD, '$username:$password');
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	}

	if($is_ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  0);
	if($is_ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$content = curl_exec ($ch);

	return $content;
}

function http_post3($server, $port, $url, $params, $username="", $password="") 
{
	$ch = curl_init();
	
	$parseurl =	parse_url($url);
	$postmet = $parseurl['scheme'];
	$postser = $parseurl['host'];
	$is_ssl = stristr($postmet,"https") !== FALSE;
	//$postport = stristr($postmet,"https") !== FALSE ? "443" : "80";
	//$postser = (stristr($postmet,"https") !== FALSE ? "ssl://" : "") . $postser;
	
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_TIMEOUT, 50);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	if($username || $password)
	{
		curl_setopt($ch, CURLOPT_USERPWD, '$username:$password');
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	}
	
	if($is_ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
	if($is_ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	$content = curl_exec ($ch);
	return $content;
}

function http_post_retheader($server, $port, $url, $urlencoded, $username="", $password="") 
{
			$user_agent = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)";
			$base_64_auth = base64_encode($username.":".$password);
			$content_length = strlen($urlencoded);
			$headers = "POST $url HTTP/1.1
Accept: */*
Accept-Language: en-au
Content-Type: application/x-www-form-urlencoded
User-Agent: $user_agent
Host: $server
Connection: Keep-Alive
Cache-Control: no-cache
Authorization: Basic $base_64_auth
Content-Length: $content_length

";

			$errno = "";
			$errstr = "";


			$ret = array();
			$ret['body'] = "";
			$ret['head'] = "";
			$ret['url'] = explode("?",$url);
			$ret['url'] = $ret['url'][0];
			$timeout = 5;
			$time = time();
			$fp = @fsockopen($server, $port, &$errno, &$errstr,3);
			if (!$fp) 
				return $ret;

			fputs($fp, $headers);
			fputs($fp, $urlencoded);
			
			$body = false;
			
			while (!feof($fp)) 
			{
				if((time()-$time)>$timeout) 
				{
					//$ret['body'].="--- Timeout after $timeout Seconds ---";
					break;
				}
				$s = @fgets($fp, 1024);
				if ( !$body)
					$ret['head'] .= $s;
				else
					$ret['body'] .= $s;
				if ( $s == "\r\n" )
					$body = true;
			}
			fclose($fp);
			return $ret;
}
?>