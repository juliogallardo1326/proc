<?php
	

	$sArgument					= $HTTP_POST_VARS["hdArg"];
	$arrValues					= split(",",$sArgument);
	$iCompanyId					= $arrValues[0];	
	$sBillingDate				= $arrValues[1];
	$iTotalTransaction			= $arrValues[2];	
	$iApprovedTransactions 		= $arrValues[3];
	$iDeclinedTransactions		= $arrValues[4];
	$iTransactionFee			= $arrValues[5];		
	$iDiscountRate				= $arrValues[6];
	$iChargeBack				= $arrValues[7];
	$iCredit					= $arrValues[8];
	$iMiscFee					= $arrValues[9];
	$iRolling					= $arrValues[10];	
	$iTotalAmount				= $arrValues[11];
	$iTotalDeduction			= $arrValues[12];
	$iNetAmount					= $arrValues[13]; 
	$qrySelect					= "select * from cs_companydetails where  userId = $iCompanyId";
	$rstSelect					= mysql_query($qrySelect,$cnn_cs);
	$sCompanyName				= "";
	$sAddress					= "";
	if ( mysql_num_rows($rstSelect)>0 )
	{
		$sCompanyName				= mysql_result($rstSelect,0,3);
		$sAddress					= mysql_result($rstSelect,0,5);
	} 
	if(!($file = fopen("pdf/template.html", "r")))
	{
		print("Can not open file");
		exit();
	}	
	$content = fread($file, filesize("pdf/template.html"));
	$content = explode("\r\n", $content);
	fclose($file);
	$file_content = "";
	for($i=0;$i<count($content);$i++)
	{
		$file_content .= $content[$i];
	} 
	
	$file_content = str_replace("{cname}",$sCompanyName,$file_content);
	$file_content = str_replace("{caddress}",$sAddress,$file_content);
	$file_content = str_replace("{bdate}",$sBillingDate,$file_content);
	$file_content = str_replace("{ttrans}",$iTotalTransaction,$file_content);
	$file_content = str_replace("{atrans}",$iApprovedTransactions ,$file_content);
	$file_content = str_replace("{dtrans}",$iDeclinedTransactions,$file_content);
	$file_content = str_replace("{tfee}",$iTransactionFee,$file_content);
	$file_content = str_replace("{drate}",$iDiscountRate,$file_content);
	$file_content = str_replace("{cback}",$iChargeBack,$file_content);
	$file_content = str_replace("{credit}",$iCredit,$file_content);
	$file_content = str_replace("{misc}",$iMiscFee,$file_content);
	$file_content = str_replace("{roling}",$iRolling,$file_content);
	$file_content = str_replace("{tamount}",$iTotalAmount,$file_content);
	$file_content = str_replace("{tdeduction}",$iTotalDeduction,$file_content);
	$file_content = str_replace("{net}",$iNetAmount,$file_content);
			
	
	
	
	
	//print($file_content);
	$sWriteFile = date("Y").date("m").date("d").date("h").date("i").$iCompanyId;
	
	$filename = $sWriteFile.".html"; 
	//$filename = "template.html"; 
	
	if(!($handle = fopen("pdf/$filename", "w")))
	{
		print("Can not open file");
		exit();
	}
	if (!fwrite($handle, $file_content)) { 
       print "Cannot write to file ($filename)"; 
       exit; 
   }
    fclose($handle);
	require_once dirname(__FILE__) . '/pdf/HTML_ToPDF.php';
	$htmlFile = dirname(__FILE__) . '/pdf/'.$filename;
	$defaultDomain = 'www.etelegate.com';
	$pdfFile = dirname(__FILE__) . '/pdf/'.$sWriteFile.'.pdf';
	@unlink($pdfFile);
	$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain, $pdfFile);
	$pdf->setHeader('color', 'white');
	$pdf->setFooter('left', 'Invoice');
	$pdf->setFooter('right', '$D');
	$result = $pdf->convert();


	if (PEAR::isError($result)) {
    	die($result->getMessage());
	}
	else {
?>	    
		<table border="0" align="center" width="100%" cellpadding="4" cellspacing="4">
		<tr><td align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="-2">
			Please <a href="pdf/<?=$sWriteFile?>.pdf">Click Here </a> to download the pdf file for invoice
		</font></td></tr>
		</table>
<?
}
?>