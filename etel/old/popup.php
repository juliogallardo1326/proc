<?php
$name = (isset($HTTP_GET_VARS["name"])?Trim($HTTP_GET_VARS["name"]):"");
$cmpny = (isset($HTTP_GET_VARS["cmpny"])?Trim($HTTP_GET_VARS["cmpny"]):"");
$email = (isset($HTTP_GET_VARS["email"])?Trim($HTTP_GET_VARS["email"]):"");
if($name==1){
echo"<table width='75%' height='50%' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing username !! </font></td></tr><tr><td align='center'><a href='javascript:self.close();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
}
elseif($cmpny==1){
echo"<table width='75%' height='50%' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing Company Name !! </font></td></tr><tr><td align='center'><a href='javascript:self.close();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
}
elseif($email==1){
echo"<table width='75%' height='50%' align='center' valign='middle' style='border:1px solid black'><tr><td align='center' valign='middle'><font face='verdana' size='1' color='red'>Existing Email !! </font></td></tr><tr><td align='center'><a href='javascript:self.close();'><img border='0' src='images/back.jpg'></a></td></tr></table>";
}
?>