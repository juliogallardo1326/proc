<?php
/**
* This file has some very basic functions in it. Generic functions are in here and should be useful with any product.
* This includes debug functions, file handling and so on.
*
* @version     $Id: general.php,v 1.5 2005/10/20 03:33:00 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package Library
* @subpackage General
* @filesource
*/

/**
* Timedifference
* Returns the time difference in an easy format / unit system (eg how many seconds, minutes, hours etc).
*
* @param int Time difference as an integer to transform.
*
* @return string Time difference plus units.
*/
function timedifference($origtimedifference) {
	$timedifference = abs($origtimedifference);
	if ($timedifference < 60) {
		$timechange = number_format($timedifference, 0) . ' second';
		if ($timedifference > 1) $timechange .= 's';
	}

	if ($timedifference >= 60 && $timedifference < 3600) {
		$num_mins = floor($timedifference / 60);
		$timechange  = number_format($num_mins, 0) . ' minute';
		if ($num_mins > 1) $timechange .= 's';
	}

	if ($timedifference >= 3600) {
		$hours = floor($timedifference/3600);
		$mins = floor($timedifference % 3600) / 60;

		$timechange = number_format($hours, 0) . ' hour';
		if ($hours > 1) $timechange .= 's';

		$timechange .= ' and ' . number_format($mins, 0) . ' minute';
		if ($mins > 1) $timechange .= 's';
	}
	if ($origtimedifference < 0) $timechange = 'MINUS ' . $timechange;
	return $timechange;
}


/**
* stripslashes_array
* Recursive function to remove slashes from an array or single keyword.
*
* @param array If the parameter is an array it will be called recursively. Will eventually just stripslashes from the keys and values for each element.
*
* @return mixed Returns either an array or string - depending on what was passed in.
*/
function stripslashes_array($array) {
	if (is_array($array)) {
		$return = array();
		foreach($array as $key => $value) {
			$key = stripslashes($key);
			if (is_array($value)) {
				$return[$key] = stripslashes_array($value);
				continue;
			}
			$return[$key] = stripslashes($value);
		}
	} else {
		$return = stripslashes($array);
	}
	return $return;
}

/**
* remove_directory
* Will recursively remove directories and clean up files in each directory.
*
* @param directory Name of directory to clean up and clear.
*
* @return boolean Returns false if it can't remove a directory or a file. Returns true if it all worked ok.
*/
function remove_directory($directory='') {
	if (!is_dir($directory)) return true;
	if (!$handle = opendir($directory)) {
		return false;
	}

	while (($file = readdir($handle)) !== false) {
		if ($file == '.' || $file == '..') continue;
		$f = $directory . '/' . $file;
		if (is_dir($f)) {
			remove_directory($directory . '/' . $f);
		}
		if (is_file($f)) {
			if (!unlink($f)) {
				closedir($handle);
				return false;
			}
		}
	}
	closedir($handle);
	return true;
}

/**
* list_files
* Lists files in a directory. Can also skip particular types of files.
*
* @param dir Name of directory to list files for.
* @param skip_files Filenames to skip. Can be a single file or an array of filenames.
*
* @return mixed Returns false if it can't open a directory, else it returns a multi-dimensional array.
*/
function list_files($dir='', $skip_files = null) {
	if (empty($dir) || !is_dir($dir)) return false;
	$file_list = array();

	if (!$handle = opendir($dir)) {
		return false;
	}

	while (($file = readdir($handle)) !== false) {
		if ($file == '.' || $file == '..') continue;
		if (is_file($dir.'/'.$file)) {
			if (empty($skip_files)) {
				$file_list[] = $file;
				continue;
			}
			if (!empty($skip_files)) {
				if (is_array($skip_files) && !in_array($file, $skip_files)) {
					$file_list[] = $file;
				}
				if (!is_array($skip_files) && $file != $skip_files) {
					$file_list[] = $file;
				}
			}
		}
	}
	closedir($handle);
	return $file_list;
}

/**
* array_contents
* Recursively prints an array. Works well with associative arrays and objects.
*
* @see bam
*
* @param array Array or object to print
* @param max_depth Maximum depth to print
* @param depth Used internally to make sure the array doesn't go past max_depth.
* @param ignore_ints So it doesn't show numbers only.
*
* @return string The contents of the array / object is returned as a string.
*/
function array_contents(&$array, $max_depth=0, $depth=0, $ignore_ints=false) {
	$string = $indent = "";
	for ($i = 0; $i < $depth; $i++) $indent .= "\t";
	if (!empty($max_depth) && $depth >= $max_depth) {
		return $indent."[Max Depth Reached]\n";
	}
	if (empty($array)) return $indent."[Empty]\n";
	reset($array);
	while ( list($key,$value) = each($array) ) {
		$print_key = str_replace("\n","\\n",str_replace("\r","\\r",str_replace("\t","\\t",addslashes($key))));
		if ($ignore_ints && gettype($key) == "integer") continue;
		$type = gettype($value);
		if ($type == "array" || $type == "object") {
			$string .= $indent
					.  ((is_string($key)) ? "\"$print_key\"": $key) . " => "
					.  (($type == "array")?"array (\n":"")
					.  (($type == "object")?"new ".get_class($value)." Object (\n":"");
			$string .= array_contents($value, $max_depth, $depth + 1,  $ignore_ints);
			$string .= $indent . "),\n";
		} else {
			if (is_string($value)) $value = str_replace("\n","\\n",str_replace("\r","\\r",str_replace("\t","\\t",addslashes($value))));
			$string .= $indent
					.  ((is_string($key)) ? "\"$print_key\"": $key) . " => "
					.  ((is_string($value)) ? "\"$value\"": $value) . ",\n";
		}
	}
	$string[ strlen($string) - 2 ] = " ";
	return $string;
}

/**
* bam
* Prints out a variable, possibly recursively if the variable is an array or object.
*
* @see array_contents
*
* @param x Message to print out.
* @param max_depth Maximum depth to print out of the variable if it's an object / array.
* @param style Stylesheet to apply.
*
* @return void
*/
function bam($x='BAM!', $max_depth=0, $style='') {
?> 
	<div align="left"><pre style="<?=$style?>font-family: courier, monospace;"><?php
	$type = gettype($x);
	if ($type == "object" && !$max_depth) {
		print_r($x);
	} else {
		if ($type == "object" || $type == "array") {
			# get the contents, then 
			if (!$max_depth) $max_depth = 10;
			$x = array_contents($x, $max_depth); 
		}
		echo htmlspecialchars(ereg_replace("\t", str_repeat (" ", 4), $x)); 
	}#end switch
	?></pre></div>
<?php
}

?>
