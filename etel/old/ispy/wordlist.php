<?php
	require_once('includes/sp_db.php');
	$recordSet = &$DB->Execute('select * from '.TBL_WORDLIST);
	if (!$recordSet) 	
		print $DB->ErrorMsg();
		
	else 
	{
		echo "<TABLE>";
		echo "<TR><TH>Word</TH><TH>Weight</TH><TH>Type</TH></TR>";
		while (!$recordSet->EOF) 
		{	
			echo "<TR>";
			echo "<TD>".$recordSet->fields[sp_word]."</TD>";
			echo "<TD>".$recordSet->fields[sp_weight]."</TD>";
			echo "<TD>".$recordSet->fields[sp_type]."</TD>";
			echo "<TD><a href=wordchange.php?word=".$recordSet->fields[sp_word].">Edit</a></TD>";
			echo "</TR>";
			$recordSet->MoveNext();
		}
		echo "</TABLE>";
	}
?>		
