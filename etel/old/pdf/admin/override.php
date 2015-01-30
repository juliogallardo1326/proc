<?php
include 'includes/sessioncheck.php';
include '../includes/dbconnection.php';
include 'includes/header.php';

$headerInclude="transactions";	

?>
<script language="javascript" src="../scripts/calendar1.js"></script>
<script language="javascript" src="../scripts/general.js"></script>
<?
	$ddCur=date("d");
	$mmCur=date("n");
	$yyyyCur=date("Y");
	$dateval2=$mmCur."/".$ddCur."/".$yyyyCur;
	$show_sql =mysql_query("select distinct userid,companyname from cs_companydetails order by companyname");
	$rst_sql = mysql_query($show_sql,$cnn_cs);
?>

 <table border="0" cellpadding="0" width="100%" cellspacing="0" height="80%">
  <tr>
       <td width="83%" valign="top" align="center"  >
    &nbsp;
    <table border="0" cellpadding="0" cellspacing="0" width="50%" >
      <tr>
        <td width="100%" height="22">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" height="22">
            <tr>
              <td width="17" height="22"><img border="0" SRC="<?=$tmpl_dir?>/images/leftcurve.gif" width="17" height="22"></td>
              <td bgcolor="#1c5abc" >
                <p style="margin-left: 25"><font size="1" face="Verdana" color="#FFFFFF"><b>Search Transactions</b></font></p>
              </td>
              <td width="17" height="22"><img border="0" SRC="<?=$tmpl_dir?>/images/rightcurve.gif" width="17" height="22"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
	      <td width="100%"  valign="top" align="left" style="border:1px solid #1c5abc"> 
		  <form name="dates"  method="POST" action="transactiondetails.php">
              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                <tr> 
                  <td width="35%"><div align="right"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Billing 
                      Date From</font></div></td>
                  <td width="65%"> <input type="text" name="txtDate" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>
                    &nbsp;&nbsp;
                    <input type="button" value="..." onclick="init()" style="font-family:verdana;font-size:10px;"> 
                  </td>
                </tr>
                <tr> 
                  <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Billing 
                      Date To</font></font></div></td>
                  <td> <input type="text" name="txtDate1" style="font-family:verdana;font-size:10px;WIDTH: 140px" value=<?=$dateval2?>></input>
                    &nbsp;&nbsp;
                    <input style="font-family:verdana;font-size:10px;" type="button" value="..." onclick="init1()"> 
                  </td>
                </tr>
                <tr> 
                  <td><div align="right"><font face="Verdana, Arial, Helvetica, sans-serif"><font size="2">Company</font></font></div></td>
                  <td>
				  	<select name="opt_company">
					 <option value="" selected>All companies</option>
					 <?php   
					 $qry_select_company = "select userid,companyname from cs_companydetails order by companyname";
					 $rst_select_company = mysql_query($qry_select_company,$cnn_cs);
					 if (mysql_num_rows($rst_select_company)>0)
					 {
					 	for($i=0;$i<mysql_num_rows($rst_select_company);$i++)
						{
					?>
						<option value="<?php print mysql_result($rst_select_company,$i,0); ?>"><?php print mysql_result($rst_select_company,$i,1); ?></option>
					<?php		
						}
					 }
					 ?>
                    </select>
					</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><input name="imageField" type="image" SRC="<?=$tmpl_dir?>/images/submit.jpg" width="72" height="23" border="0"></td>
                </tr>
              </table>
			</form>
			 </td>
     </tr>
    </table>
    </td>
     </tr>
</table>
<?php
	include 'includes/footer.php';
?>
