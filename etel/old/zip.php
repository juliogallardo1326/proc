<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php
	    $result = exec('zip -L', $data, $return);
        // if it do not work try this:
        // $result = exec('/usr/bin/zip -L', $data, $return);
		for($i=0;$i<count($data);$i++) {
			print($data[$i]);
			print("<br>");
		}
		echo($result);
//        echo print_r($data);
?>
</body>
</html>
