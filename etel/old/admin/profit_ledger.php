<?


$headerInclude="ledgers";
$periodhead="Ledgers";

require_once("includes/sessioncheck.php");
require_once("../includes/dbconnection.php");
require_once('../includes/function.php');
require_once("../includes/integration.php");
require_once('../includes/subFunctions/smart_search.php');
require_once('../includes/subFunctions/color_manip.php');
require_once("../includes/transaction.class.php");
require_once("../includes/subscription.class.php");
require_once("../includes/calendar.class.php");
require_once("../includes/entities.class.php");
require_once("../includes/profit.class.php");

$sessionlogin =isset($HTTP_SESSION_VARS["sessionlogin"])?$HTTP_SESSION_VARS["sessionlogin"]:"";



/**************
Define pairs
**************/

$my_sql['pairs']['DisplayRange'] = array(
	array("display" => "Display All", "value"=>"","default"=>1),
	array("display" => "Selected Dates", "value"=>"1")
	);

$my_sql['pairs']['entity_types'] = array(
	array("display" => "Merchant", "value"=>"merchant"),
	array("display" => "Bank", "value"=>"bank"),
	array("display" => "Processor", "value"=>"processor"),
	array("display" => "System User", "value"=>"sys_user")
);

$my_sql['pairs']['ResultsPerPage'] = array(
	array("display" => "50", "value"=>"50","default"=>1),
	array("display" => "25", "value"=>"25"),
	array("display" => "10", "value"=>"10"),
	array("display" => "100", "value"=>"100"),
	array("display" => "All", "value"=>"1000")
	);

/****************
Define Search Fields and Action Fields
****************/


$my_sql['search']['entity_type'] = array("input_type" => "select", "display" => "Entity Type");
$my_sql['search']['entity_type']['options']['source']['pairs'] = "entity_types";

$my_sql['search']['entity_name'] = array("input_type" => "text","display" => "Entity Name");

$my_sql['search']['displayrange'] = array("input_type" => "select","display"=>"Display Range");
$my_sql['search']['displayrange']['options']['source']['pairs'] = "DisplayRange";

$my_sql['search']['tran_date'] = array("input_type" => "date","display"=>"Date","date_format" => "Y-n-j");

$my_sql['search']['page_count'] = array("input_type" => "select","display"=>"Results Per Page");
$my_sql['search']['page_count']['options']['source']['pairs'] = "ResultsPerPage";

$my_sql['search']['page_offset'] = array("input_type" => "hidden_field", "value" => 0);

	
$my_sql['postpage'] = $_SERVER['PHP_SELF'];
$my_sql['title'] = "Profit Ledger";

/****************
Process and Render Forms
****************/
require_once('includes/header.php');

$temp = $_REQUEST['frm_page_offset'];
if($_REQUEST['frm_page_count'] > 1000)
	$_REQUEST['frm_page_count'] = 1000;

$_REQUEST['frm_page_offset'] = 0;
smart_search_form($my_sql);
$_REQUEST['frm_page_offset'] = $temp;

if(smart_process_mysql_form($my_sql))
{
	
	$profit = new profit_class();
	$entities = new entities_class();
	
	$entity_id = $entities->get_entity_id_by_name($_REQUEST['frm_entity_type'],$_REQUEST['frm_entity_name']);
	
	if($entity_id > 0)
	{
	$ledger = $profit->get_ledger_reconcile_entity($entity_id);

beginTable();
?>
<table width='550px'>
	<tr>
		<td><font style='font-size:8pt;'><b>Charge Type</b></font></td>
		<td><font style='font-size:8pt;'><b># Transactions</b></font></td>
		<td><font style='font-size:8pt;'><b>Debit</b></font></font></td>
		<td><font style='font-size:8pt;'><b>Credit</b></td>
		<td><font style='font-size:8pt;'><b>Total</b></font></td>
	</tr>
<?
$total = array();
foreach($ledger as $type => $details)
{
?>
	<tr>
		<td><font style='font-size:8pt;'><?=ucwords($type)?></font></td>
		<td><font style='font-size:8pt;'><?=$details['count']?></font></td>
		<td><font style='font-size:8pt;'><?=$details['debit']?></font></td>
		<td><font style='font-size:8pt;'><?=$details['credit']?></font></td>
		<td><font style='font-size:8pt;'><?=$details['debit']+$details['credit']?></font></td>
	</tr>
<?
	$total['count'] += $details['count'];
	$total['debit'] += $details['debit'];
	$total['credit'] += $details['credit'];
}
?>
	<tr>
		<td><font style='font-size:8pt;'>Total</font></td>
		<td><font style='font-size:8pt;'><?=$total['count']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['debit']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['credit']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['debit']+$total['credit']?></font></td>
	</tr>
</table>
<?
endTable("Reconcile Details");
beginTable();

	
	if(isset($_REQUEST['frm_displayrange']) && $_REQUEST['frm_displayrange'] == 1)
		$ledger = $profit->get_entity_ledger($entity_id,strtotime($_REQUEST['frm_tran_date_from']),strtotime($_REQUEST['frm_tran_date_to']),$_REQUEST['frm_page_offset']*$_REQUEST['frm_page_count'],$_REQUEST['frm_page_count']);
	else
		$ledger = $profit->get_entity_ledger($entity_id,strtotime("1/1/2000"),strtotime("12/31/2037"),$_REQUEST['frm_page_offset']*$_REQUEST['frm_page_count'],$_REQUEST['frm_page_count']);

beginTable();
?>
<center>
<table width='550px'>
	<tr>
		<td><font style='font-size:8pt;'><b># Transactions</b></font></td>
		<td><font style='font-size:8pt;'><b>Debit</b></font></td>
		<td><font style='font-size:8pt;'><b>Credit</b></font></td>
		<td><font style='font-size:8pt;'><b>Total</b></font></td>
	</tr>
	<tr>
		<td><font style='font-size:8pt;'><?=$ledger['summary']['count']?></font></td>
		<td><font style='font-size:8pt;'><?=$ledger['summary']['debit']?></font></td>
		<td><font style='font-size:8pt;'><?=$ledger['summary']['credit']?></font></td>
		<td><font style='font-size:8pt;'><?=$ledger['summary']['debit']+$ledger['summary']['credit']?></font></td>
	</tr>
</table>
<? 
endTable("Summary");
beginTable();
?>
<table width='550px'>
	<tr>
		<td><font style='font-size:8pt;'><b>Charge Type</b></font></td>
		<td><font style='font-size:8pt;'><b># Transactions</b></font></td>
		<td><font style='font-size:8pt;'><b>Debit</b></font></font></td>
		<td><font style='font-size:8pt;'><b>Credit</b></td>
		<td><font style='font-size:8pt;'><b>Total</b></font></td>
	</tr>
<?
$total = array();
foreach($ledger['summary_details'] as $type => $details)
{
?>
	<tr>
		<td><font style='font-size:8pt;'><?=ucwords($type)?></font></td>
		<td><font style='font-size:8pt;'><?=$details['count']?></font></td>
		<td><font style='font-size:8pt;'><?=$details['debit']?></font></td>
		<td><font style='font-size:8pt;'><?=$details['credit']?></font></td>
		<td><font style='font-size:8pt;'><?=$details['debit']+$details['credit']?></font></td>
	</tr>
<?
	$total['count'] += $details['count'];
	$total['debit'] += $details['debit'];
	$total['credit'] += $details['credit'];
}
?>
	<tr>
		<td><font style='font-size:8pt;'>Total</font></td>
		<td><font style='font-size:8pt;'><?=$total['count']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['debit']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['credit']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['debit']+$total['credit']?></font></td>
	</tr>
</table>
<?
endTable("Summary Details");
beginTable();
?>
<table width='550px'>
	<tr>
		<td colspan=4 align='center'>
<?
		$cur_page = $_REQUEST['frm_page_offset'];
		$num_page = ceil($ledger['summary']['count']/$_REQUEST['frm_page_count']);
		$end_page = $cur_page + 5 < $num_page ? $cur_page + 5 : $num_page;
		$sta_page = $cur_page - 5 > 0 ? $cur_page - 5 : 0;
		
		echo "Page";
		
		for($j=$sta_page;$j<$end_page;$j++)
			if($j == $cur_page)
				echo " <b>" . ($j+1) . "</b>";
			else
				echo " <a name='' onMouseOver=\"this.style.cursor='pointer';\" onClick=\"document.search_form.frm_page_offset.value=$j; document.search_form.submit();\">" . ($j+1) . "</a>";

		echo " of " . $num_page;
?>
		</td>
	</tr>
	<tr>
		<td><font style='font-size:8pt;'><b>Date</b></font></font></td>
		<td><font style='font-size:8pt;'><b>Charge Type</b></font></td>
		<td><font style='font-size:8pt;'><b>Debit</b></font></td>
		<td><font style='font-size:8pt;'><b>Credit</b></font></td>
	</tr>
<?
$total = array();
foreach($ledger['ledger'] as $index => $details)
{
?>
	<tr>
		<td><font style='font-size:8pt;'><?=date("M jS Y",$details['pt_date_entered'])?></font></td>
		<td><font style='font-size:8pt;'><?=ucwords($details['pt_type'])?></font></td>
		<? 
			if($details['pt_amount'] < 0)
			{
				$total['debit'] += $details['pt_amount'];
		?>
		<td><font style='font-size:8pt;'><?=$details['pt_amount']?></font></td>
		<td></td>
		<?
			}
			else
			{
		?>
		<td></td>
		<td><font style='font-size:8pt;'><?=$details['pt_amount']?></font></td>
		<?
				$total['credit'] += $details['pt_amount'];
			}
		?>
	</tr>
<?
}
?>
	<tr>
		<td><font style='font-size:8pt;'>Total</font></td>
		<td></td>
		<td><font style='font-size:8pt;'><?=$total['debit']?></font></td>
		<td><font style='font-size:8pt;'><?=$total['credit']?></font></td>
	</tr>
</table>
<? endTable("Charge Details"); ?>
</center>
<?
	}
}

require_once("includes/footer.php");
?>
