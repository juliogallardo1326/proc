<?php

	$siteID = intval($_GET['siteID']);
	
	if(!$siteID)
	{
	
		$order = "DESC";
		if ($_GET['order']=='ASC') $order = "ASC";
		$fliporder = "ASC";
		if ($order=='ASC') $fliporder = "DESC";
		$orderby = sp_clean($_GET['orderby'],'sqlfield');
		if(!$orderby) $orderby = 'score';
?>	
<table width="640" border="1">
  <tr>
    <th scope="col" colspan="9">Website Overview</th>
  </tr>
	
  <tr style='font-weight:bold'>
  	<td><a href='?act=reporting&orderby=<?=FLD_SITE_NAME?>&order=<?=$fliporder?>'>Site Name</a></td>
  	<td><a href='?act=reporting&orderby=linksfound&order=<?=$fliporder?>'>Links Parsed/Found</a></td>
  	<td><a href='?act=reporting&orderby=linksfound_ext&order=<?=$fliporder?>'>External Links Found</a></td>
  	<td><a href='?act=reporting&orderby=linksfound_http&order=<?=$fliporder?>'>HTTP</a></td>
  	<td><a href='?act=reporting&orderby=linksfound_ftp&order=<?=$fliporder?>'>FTP</a></td>
  	<td><a href='?act=reporting&orderby=disallowed&order=<?=$fliporder?>'>Disallowed Score</a></td>
  	<td><a href='?act=reporting&orderby=required&order=<?=$fliporder?>'>Required Score</a></td>
  	<td><a href='?act=reporting&orderby=score&order=<?=$fliporder?>'>Score</a></td>
  	<td><a href='?act=reporting&orderby=score&order=<?=$fliporder?>'>Review</a></td>
  </tr>
  <?php
		$sql = "SELECT si.*,
			avg(li.".FLD_LINKS_SCORE_DISALLOWED.") as disallowed,
			min(li.".FLD_LINKS_SCORE_REQUIRED.") as required,
			avg(li.".FLD_LINKS_SCORE_DISALLOWED.")+min(li.".FLD_LINKS_SCORE_REQUIRED.") as score,
			count(li.".FLD_LINKS_ID.") as linksfound,
			sum(li.".FLD_LINKS_TYPE."='http') as linksfound_http,
			sum(li.".FLD_LINKS_TYPE."='ftp') as linksfound_ftp,
			sum(li.".FLD_LINKS_EXTERNAL.") as linksfound_ext,
			sum(li.".FLD_LINKS_PAGE_HASH." is not null) as linksparsed
			
			FROM ".TBL_SITES." as si left join ".TBL_LINKS." as li on li.".FLD_LINKS_SITE_ID." = ".FLD_SITE_ID." 
			where (li.".FLD_LINKS_ID." is not null)
			group by ".FLD_SITE_ID."
			ORDER BY $orderby $order ";
		$rs = $DB->Execute($sql);
		
		if(!$rs)print $DB->ErrorMsg();
		while (!$rs->EOF) 
		{	
			$siteID = $rs->fields[FLD_SITE_ID];
			$points = round($rs->fields['disallowed']+$rs->fields['required'],2);
			$review = 'Not Required';
			if($points>60) $review = 'Recommended';
			echo "<TR align='center'>\n";
			echo "<TD style='font-weight:bold'>".$rs->fields[FLD_SITE_NAME]."</TD>\n";
			echo "<TD>".$rs->fields['linksparsed'].' / '.$rs->fields['linksfound']."</TD>\n";
			echo "<TD>".$rs->fields['linksfound_ext'].' / '.$rs->fields['linksfound']."</TD>\n";
			echo "<TD>".$rs->fields['linksfound_http']."</TD>\n";
			echo "<TD>".$rs->fields['linksfound_ftp']."</TD>\n";
			echo "<TD>".round($rs->fields['disallowed'],2)."</TD>\n";
			echo "<TD>".$rs->fields['required']."</TD>\n";
			echo "<TD><a href='?act=reporting&siteID=$siteID'>".round($rs->fields['score'],2)."</a></TD>\n";
			echo "<TD>".$review."</TD>\n";
			echo "</TR>\n";
			$rs->MoveNext();
		}
	}
	else if($siteID)
	{
		$sql = "SELECT *
		FROM ".TBL_SITES." as si 
		where ".FLD_SITE_ID." = $siteID";
		
		$siteInfo = $DB->GetRow($sql);
		
		$reportInfo = unserialize($siteInfo[FLD_SITE_REPORT]);
	
		$order = "DESC";
		if ($_GET['order']=='ASC') $order = "ASC";
		$fliporder = "ASC";
		if ($order=='ASC') $fliporder = "DESC";
		$orderby = sp_clean($_GET['orderby'],'sqlfield');
		if(!$orderby) $orderby = FLD_LINKS_SCORE_DISALLOWED;

?>	
<table width="640" border="1">
  <tr>
    <th scope="col" colspan="2">Website Overview</th>
  </tr>
  <tr style='font-weight:bold' align="center">
  	<td>Website:</td>
  	<td align="left"><?=nl2br($siteInfo[FLD_SITE_NAME])?></td>
  </tr>
  <tr style='font-weight:bold' align="center">
  	<td>Summary:</td>
  	<td align="left"><?=nl2br($reportInfo['summary'])?></td>
  </tr>
  <tr style='font-weight:bold' align="center">
  	<td>Final Score:</td>
  	<td align="left"><?=round($reportInfo['score'],2)?></td>
  </tr>
  <tr style='font-weight:bold' align="center">
  	<td>Review:</td>
  	<td align="left"><?=nl2br($reportInfo['review'])?></td>
  </tr>
 </table>
<table width="640" border="1">
  <tr>
    <th scope="col" colspan="7">Link Breakdown</th>
  </tr>
	
  <tr style='font-weight:bold' align="center">
  	<td><a href='?act=reporting&siteID=<?=$siteID?>&orderby=<?=FLD_LINKS_NAME?>&order=<?=$fliporder?>'>Link Name</a></td>
  	<td><a href='?act=reporting&siteID=<?=$siteID?>&orderby=<?=FLD_LINKS_URL?>&order=<?=$fliporder?>'>Url</a></td>
  	<td><a href='?act=reporting&siteID=<?=$siteID?>&orderby=<?=FLD_LINKS_LINKS_FOUND?>&order=<?=$fliporder?>'>Links Found</a></td>
  	<td><a href='?act=reporting&siteID=<?=$siteID?>&orderby=<?=FLD_LINKS_SCORE_DISALLOWED?>&order=<?=$fliporder?>'>Disallowed Score</a></td>
  	<td><a href='?act=reporting&siteID=<?=$siteID?>&orderby=<?=FLD_LINKS_SCORE_REQUIRED?>&order=<?=$fliporder?>'>Required Score</a></td>
  </tr>
  <?php
		$sql = "SELECT li.*
			
			FROM ".TBL_LINKS." as li
			where ".FLD_LINKS_SITE_ID." = $siteID
			ORDER BY $orderby $order limit 200 ";
		$rs = $DB->Execute($sql);
		
		if(!$rs)print $DB->ErrorMsg();
		while (!$rs->EOF) 
		{	
		
			$name = preg_replace('/<[^<]*>/','',$rs->fields[FLD_LINKS_NAME]);
			if(!$name) $name = 'N/A';
			echo "<TR align='center'>\n";
			echo "<TD style='font-weight:bold'>".$name."</TD>\n";
			echo "<TD><a title='".$rs->fields[FLD_LINKS_URL]."' target='_blank' href='".$rs->fields[FLD_LINKS_URL]."'>".substr($rs->fields[FLD_LINKS_URL],0,40)."...</a></TD>\n";
			echo "<TD>".intval($rs->fields[FLD_LINKS_LINKS_FOUND])."</TD>\n";
			echo "<TD>".$rs->fields[FLD_LINKS_SCORE_DISALLOWED]."</TD>\n";
			echo "<TD>".$rs->fields[FLD_LINKS_SCORE_REQUIRED]."</TD>\n";
			echo "</TR>\n";
			$rs->MoveNext();
		}
	}
	
?>


</table>