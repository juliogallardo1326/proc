<?php
/***************************************************************************
File Name 	: footer.php
Domain		: http://www.PHPSupportTickets.com/
----------------------------------------------------------------------------
Author		: Ian Warner
Copyright	: (C) 2001 Triangle Solutions Ltd
Email		: iwarner@triangle-solutions.com
URL		: http://www.triangle-solutions.com/
Description	: Displays the footer for the Admin area.
Date Created	: Wednesday 19 January 2005 16:15:35
File Version	: 1.9
\\||************************************************************************/
?>
	<table width="<?php echo $maintablewidth ?>" cellspacing="1" cellpadding="1" align="<?php echo $maintablealign ?>">
	  <tr>
		<td align="center">
		<?php echo $version ?><br />
		<br />
		</td>
	  </tr>
	</table>
	<script language="javascript">
	placeFocus();
	</script>
	
<?php
include($rootdir.'admin/includes/footer.php');
?>