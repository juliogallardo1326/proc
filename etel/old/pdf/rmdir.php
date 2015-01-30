<?php
$svr = $_SERVER["PATH_TRANSLATED"];
$path_parts = pathinfo($svr); 
$str_current_path = $path_parts["dirname"];
$mydir=$str_current_path."\\admin\\csv\Integration_guide.zip";

$ret_val = fsize($mydir);
print $ret_val;

function fsize($file) {
       $a = array("B", "KB", "MB", "GB", "TB", "PB");
       $pos = 0;
       $size = filesize($file);
	   print $size."<br>";
       while ($size >= 1024) {
               $size /= 1024;
               $pos++;
       }
       return round($size,2)." ".$a[$pos];
}
exit();
function delete($file) {
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
         delete($file."/".$filename);
       }
     }
     closedir($handle);
     rmdir($file);
   } else {
     unlink($file);
   }
 }
}
delete ($mydir);
?>