<?
chdir("../../");
//echo system("srcbk/backup_recent.sh",$res);
//print_r($res);die();
$url="srcbk/source_recent.tar.gz";
if(!file_exists($url)) { print_r($res); echo "File Missing: ".getcwd().'/'.$url;die();}
 header('Content-Description: File Transfer');
           header('Content-Type: application/force-download');
           header("Content-Disposition: attachment; filename=\"".basename($url)."\";");

           header('Content-Length: ' . filesize($url));
readfile($url);
?>