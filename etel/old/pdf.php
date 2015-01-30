<?php
//$pdf = pdf_new(); 
//phpinfo();
 /*$email = "johnny.rocket@example.com";
   list($username,$domain) = split("@",$email);
   if (getmxrr($domain,$mxrecords))
      echo "Email domain exists!";
   else
      echo "Email domain does not exist!";
*/
header("Content-type: plain/text");

$data = "hi, \r\n\r\n";
$data .= "how r u?, \r\n";
print(nl2br($data));
?>