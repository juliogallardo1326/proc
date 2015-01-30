<?php 

$etel_debug_mode=0;
$showgraph = $_REQUEST['showgraph'];
if($showgraph) ob_start();
$pageConfig['Title'] = 'Quick Statistics';
include 'includes/sessioncheck.php';
require_once("includes/dbconnection.php");
if(!$noheader) $headerInclude="reports";
$periodhead="Ledgers";
$display_statd_wait = true;
require_once("includes/header.php");
chdir('includes');
require_once("Image/Graph.php");

$cs_ID = $_REQUEST['cs_ID'];
$style = $_REQUEST['style'];


function currencyformat($val)
{
	return('$'.formatMoney($val));
}

function dateformat($val)
{
	return date('jS',strtotime(date('y-m-'.$val)));
	//$array = preg_split('/ /', $val, -1, PREG_SPLIT_NO_EMPTY);
	//return implode("\n",$array);
}


$my_sql['tables'] = array("cs_transactiondetails AS td");
$my_sql['joins'] = array(
		array("table"=>"cs_companydetails AS cd",
				"on"=>
					array(
						array("field_a"=>"td.userId","field_b"=>"cd.userId","compare"=>"=")
					)
				),
		array("table"=>"cs_company_sites AS cs",
				"on"=>
					array(
						array("field_a"=>"td.td_site_ID","field_b"=>"cs.cs_ID","compare"=>"=")
					)
				)
		);

$my_sql['sql_config'] = array('TimeOut'=>10);
$my_sql['sql_nodie'] = true;

if($cs_ID)
	$my_sql['where']['cs.cs_ID'] = array("value" => $cs_ID, "compare" => "=");
$my_sql['where']['td.userId'] = array("value" => $companyInfo['userId'], "compare" => "=");
$my_sql['where']['1'] = array("value" => "1 and td.bank_id>0 and (status!='D' || td.td_non_unique=0) and (td.status!='P' || td.cardtype='Check')  $bank_sql_limit", "compare" => "=");
//and (td.td_non_unique=0 or status!='D')

$my_sql['subquery']['title'] = "Transaction Summary";

$my_sql['subquery']['queries']['01|Approved'] = array("name"=>"amount_approved", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status ='A',td.amount,0)),2),' (' , SUM(if(td.status ='A',1,0)), ')')",'linkadd'=>'&frm_td_status%5B%5D=A');
$my_sql['subquery']['queries']['02|Transactions'] = array("name"=>"number_transactions", "source" => "COUNT(td.amount)","hidden"=>1);

$my_sql['subquery']['queries']['03|Declined'] = array("name"=>"amount_declined", "source" => "CONCAT('\$',FORMAT(SUM(if(td.status = 'D',td.amount,0)),2),' (' ,SUM(if(td.status = 'D',1,0)) , ')')",'linkadd'=>'&frm_td_status%5B%5D=D');
$my_sql['subquery']['queries']['04|Attempted'] = array("name"=>"total_transactions", "source" => "CONCAT('\$',FORMAT(SUM(td.amount),2),' (',count(*),')')");
$my_sql['subquery']['queries']['05|Approval Rate'] = array("name"=>"percent_approved", "source" => "CONCAT(FORMAT(SUM(if(td.status ='A',td.amount,0))*100/SUM(if(td.status <> 'P',td.amount,0)),2),'%')");

$my_sql['subquery']['queries']['06|Rebills'] = array("name"=>"amount_rebilled", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_a_rebill = 1 && td.status ='A',td.amount,0)),2),' (' ,SUM(if(td.td_is_a_rebill = 1 && td.status ='A',1,0)) , ')')",'linkadd'=>'&frm_td_td_is_a_rebill%5B%5D=1');
$my_sql['subquery']['queries']['07|New Sales'] = array("name"=>"amount_newsales", "source" => "CONCAT('\$',FORMAT(SUM(if(td.td_is_a_rebill = 0 && td.status ='A',td.amount,0)),2),' (',SUM(if(td.td_is_a_rebill = 0 && td.status ='A',1,0)),')')",'linkadd'=>'&frm_td_td_is_a_rebill%5B%5D=0');


$my_sql['subquery']['queries']['08|Checks Submit'] = array("name"=>"submit_checks", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype = 'Check' && td.status ='P',td.amount,0)),2),' (',SUM(if(td.cardtype = 'Check' && td.status ='P',1,0)),')')",'linkadd'=>'&frm_td_cardtype%5B%5D=Check');
//$my_sql['subquery']['queries']['09|Credit Cards'] = array("name"=>"amount_credit", "source" => "CONCAT('\$',FORMAT(SUM(if(td.cardtype != 'Check' && td.status ='A',td.amount,0)),2),' (',SUM(if(td.cardtype != 'Check' && td.status ='A',1,0)),')')");

$my_sql['skip_query']=true;
$my_sql['postpage'] = "report_Smart.php";
$my_sql['title'] = "Find Transactions";



//echo "<PRE>";
//echo phpformat($mysql_info,0);
//etelDie();

if(!$showgraph)
{
	
	$timeArray = array(
		'Today'=>array('fromstamp'=>time(),'tostamp'=>time()),
			
		'Yesterday'=>array('fromstamp'=>time()-60*60*24,'tostamp'=>time()-60*60*24),
			
		'One Week to Date'=>array('fromstamp'=>time()-60*60*24*7,'tostamp'=>time()),
			
		'Two Weeks to Date'=>array('fromstamp'=>time()-60*60*24*14,'tostamp'=>time()),
			
		'One Month to Date'=>array('fromstamp'=>time()-60*60*24*30,'tostamp'=>time()),
			
		'Three Months to Date'=>array('fromstamp'=>time()-60*60*24*90,'tostamp'=>time()),
			
		'Since Jan 1st'=>array('fromstamp'=>strtotime(date('Y-01-01 00:00:00')),'tostamp'=>time())
	);
	
	foreach($timeArray as $t=>$d)
	{
		$my_sql['where']['td.transactionDate'] = array("value" => "'".date('Y-m-d 00:00:00',$d['fromstamp'])."' and '".date('Y-m-d 23:59:59',$d['tostamp'])."'", "compare" => "between");
		$timeArray[$t]['result'] = smart_search($my_sql);
		$timeArray[$t]['link'] = "report_Smart.php?frm_td_transactiondate_from=".urlencode(date('m/d/Y',$d['fromstamp']))."&frm_td_transactiondate_to=".urlencode(date('m/d/Y',$d['tostamp']))."";
		if($cs_ID)
			$timeArray[$t]['link'] .= "&frm_td_td_site_ID%5B%5D=$cs_ID";
	}
	$row = 1;
	
	?>
	<script language="javascript">
	function showGraph(url) {
		window.open (url,'',"'scrollbars=no,titlebar=no,resizable=no,width=830, height=630'");
	}
	</script>
	<?
	
	beginTable();
	
		echo "<table width='100%' class='report'>";
		echo "
		<tr>
			<td class='row".($row=3-$row)."' colspan='".(sizeof($my_sql['subquery']['queries'])+1)."'>
				<select name='cs_ID' id='cs_ID'>
				<option value='0'>All Sites</option>";      
		echo get_fill_combo_conditionally("SELECT cs_ID,cs_name FROM `cs_company_sites` WHERE cs_company_ID = '".$companyInfo['userId']."' AND cs_hide = '0' ORDER BY `cs_name` ASC",$cs_ID);
		echo "  </select>	
				<input type='submit' value='Update'>
			</td>
		</tr>";
		
		echo "<tr class='header2'><td></td>";
		ksort($my_sql['subquery']['queries']);
		foreach($my_sql['subquery']['queries'] as $title => $sq)
		{
			if($sq['hidden']) continue;
			$title = explode('|',$title);
			echo "<td>".$title[1]."</td>";
		}
		echo "</tr>";
			
		foreach($timeArray as $t=>$d)
		{
			if(!$d['result']) continue;
			echo "<tr class='row".($row=3-$row)."'>";
			echo "<td class='header2'><a href='".$d['link']."'>$t</a></td>";
			if($d['result']['sub_row'][0]['number_transactions']>0)
				foreach($my_sql['subquery']['queries'] as $title => $sq)
				{
					if($sq['hidden']) continue;
					echo "<td>";
					if($sq['linkadd'])	echo "<a href='".$d['link'].$sq['linkadd']."'>";
					echo $d['result']['sub_row'][0][$sq['name']];
					if($sq['linkadd'])	echo "</a>";
					echo "</td>";
				}
			else
				echo "<td colspan='".(sizeof($my_sql['subquery']['queries']))."' style='text-align:center'> No Activity </td>";
			echo "</tr>";
		}
	
		echo "</table>";
	endTable('Quick Stats',"");
	
	beginTable();
	echo "<div width='600px' style='white-space: nowrap;' >";
	echo "<a href='javascript:showGraph(\"?showgraph=1&mb=0&style=full&cs_ID=$cs_ID\")'><img src='?showgraph=1&mb=0&style=minimal&cs_ID=$cs_ID' alt='No Data Available' border='0' ></a><br>";
	echo "<a href='javascript:showGraph(\"?showgraph=1&mb=1&style=full&cs_ID=$cs_ID\")'><img src='?showgraph=1&mb=1&style=tiny&cs_ID=$cs_ID' alt='No Data Available' border='0' ></a>";
	echo "<a href='javascript:showGraph(\"?showgraph=1&mb=2&style=full&cs_ID=$cs_ID\")'><img src='?showgraph=1&mb=2&style=tiny&cs_ID=$cs_ID' alt='No Data Available' border='0' ></a><br>";
	echo "<a href='javascript:showGraph(\"?showgraph=1&mb=3&style=full&cs_ID=$cs_ID\")'><img src='?showgraph=1&mb=3&style=tiny&cs_ID=$cs_ID' alt='No Data Available' border='0' ></a>";
	echo "<a href='javascript:showGraph(\"?showgraph=1&mb=4&style=full&cs_ID=$cs_ID\")'><img src='?showgraph=1&mb=4&style=tiny&cs_ID=$cs_ID' alt='No Data Available' border='0' ></a><br>";
	echo "</div>";
	echo "Click on a graph for more information.";
	endTable('Graph Stats',"");
}
else
{

	$monthsback = intval($_REQUEST['mb']);
	$daysinmonth = intval(date('t',time()+60*60*24*(30*(-$monthsback))));
	if($monthsback<0)$monthsback=0;
	if($monthsback>144)$monthsback=144;
	ob_clean();
	$my_sql['subgroupby'] = array("subgroup_by");
	$my_sql['suborderby'] = "is_rollup desc, subgroup_by asc";
	$my_sql['subrollup'] = false;
	$my_sql['subgroupby'] = "DATE_FORMAT( transactionDate , '%y-%m-%d' )";
	$my_sql['subgrouptitle'] = "DATE_FORMAT( transactionDate , '%d' ) ";
	$my_sql['subgrouptitlekey'] = true;
	$my_sql['subgrouprolluptitle'] = "CONCAT('Total - ',daterange)";
	
	unset($my_sql['subquery']['queries']);
	$my_sql['subquery']['queries']['01|Approved'] = array("name"=>"amount_approved", "source" => "SUM(td.amount)",'linkadd'=>'&frm_td_status%5B%5D=A');
	//$my_sql['subquery']['queries']['02|Transactions'] = array("name"=>"number_transactions", "source" => "COUNT(td.amount)","hidden"=>1);

	$sizefactor = 1;
	if($style=='minimal') $sizefactor = .5;
	if($style=='tiny') $sizefactor = .25;

	if($style=='full')
	{
		$my_sql['subquery']['queries']['06|Rebills'] = array("name"=>"amount_rebilled", "source" => "SUM(if(td.td_is_a_rebill = 1,td.amount,0))",'linkadd'=>'&frm_td_td_is_a_rebill%5B%5D=1');
		$my_sql['subquery']['queries']['07|New Sales'] = array("name"=>"amount_newsales", "source" => "SUM(if(td.td_is_a_rebill = 0,td.amount,0))",'linkadd'=>'&frm_td_td_is_a_rebill%5B%5D=0');
	}

	$my_sql['where']['td.transactionDate'] = array("value" => "'".date('Y-m-01 00:00:00',time()+60*60*24*(30*(-$monthsback)))."' and '".date('Y-m-01 00:00:00',time()+60*60*24*(30*(1-$monthsback)))."'", "compare" => "between");
	$my_sql['where']['td.status'] = array("value" => "'A'", "compare" => "=");
	$daily = smart_search($my_sql);
	
	for($i=1;$i<=$daysinmonth;$i++)
	{
		$key = str_pad($i,2,'0',STR_PAD_LEFT);
		if(!$daily['sub_row'][$key]) $daily['sub_row'][$key] = array();
	}
	
	
	ksort($daily['sub_row']);
	//etelPrint($daily['sub_row']);etelDie();
	
	$w = 600;$h=200;
	if($style=='tiny'){$w = 300;$h=150;}
	else if($style=='full') {$w = 800;$h=600;}
	
	$Canvas =& Image_Canvas::factory('png', array('width' => $w, 'height' => $h, 'antialias' => 'native'));      
	
	// create the graph
	$Graph =& Image_Graph::factory('graph', $Canvas);
	$Graph->setPadding(10);
	$Graph->add(
		Image_Graph::vertical(
			Image_Graph::factory('title', array('Daily Sales for '.date('F',time()+60*60*24*(30*(-$monthsback))), 12)),
			$Plotarea = Image_Graph::factory('plotarea'),
			8
		)
	); 
	
	$Graph->setBackground(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'lightsteelblue', 'papayawhip'))); 
	
	$Font =& $Graph->addNew('font', 'Verdana');
	
	$Font->setSize(10);
	
	$Graph->setFont($Font); 
	
	
	$AxisX =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
	$AxisX->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Function', 'dateformat'));
	$AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y); 
	
	//$AxisX->setFontAngle('vertical');   
	
	$AxisY->setDataPreprocessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Function', 'currencyformat')); 
	if (sizeof($daily['sub_row'])>=28)
		$AxisX->setLabelInterval(2/$sizefactor);  
	$AxisX->setFontSize(8);        
	
	
	//$AxisX->setTitle('Very obvious', array('angle' => 0, 'size' => 10)); 
	
	//$Fillerset =& Image_Graph::factory('dataset');
	$Datasets[0] =& Image_Graph::factory('dataset');
	if($style=='full')
	{
		$Datasets[1] =& Image_Graph::factory('dataset');
		$Datasets[2] =& Image_Graph::factory('dataset');
		$Plotarea->addNew('line_grid', false, IMAGE_GRAPH_AXIS_X);
		$Plotarea->addNew('line_grid', false, IMAGE_GRAPH_AXIS_Y); 
	}
	//etelPrint($daily);etelDie();
	$max=0;
	if(sizeof($daily['sub_row']))
	foreach($daily['sub_row'] as $index => $data)
	{
		if($max<$data['amount_approved'])$max=$data['amount_approved'];
		$Datasets[0]->addPoint(intval($index), floatval($data['amount_approved']));
		if($style=='full')
		{
			$Datasets[1]->addPoint(intval($index), floatval($data['amount_rebilled']));
			$Datasets[2]->addPoint(intval($index), floatval($data['amount_newsales']));
		}
		//$Fillerset->addPoint(intval($index), 0);
	}	
	//else if($style!='full') die();

	$AxisY->forceMaximum($max*1.2); 
	$Ydivs = intval($max*1.2/(10*$sizefactor)); 
	$Ydivs = intval($Ydivs/50)*50;
	if($Ydivs<50)$Ydivs=50;
	$AxisY->setLabelInterval($Ydivs);
	//$AxisX->forceMaximum(40); 
	
	
	
	$Plot1 =& $Plotarea->addNew('Image_Graph_Plot_Area', array(&$Datasets[0]));	

	$Plot1->setFillStyle(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'lightsteelblue@0.2', 'gray@0.2'))); 
	//$Plot1->setFillColor('lightsteelblue@0.2'); 

	if($style=='full')
	{
		
		$Plot2 =& $Plotarea->addNew('Image_Graph_Plot_Area', array(&$Datasets[1]));
		$Plot3 =& $Plotarea->addNew('Image_Graph_Plot_Area', array(&$Datasets[2]));
		$Plot2->setTitle('Total Rebilled'); 
		$Plot3->setTitle('Total New Sales'); 
		$Plot2->setLineColor('blue');
		$Plot3->setLineColor('red');
		$Plot2->setFillColor('blue@0.1'); 
		$Plot3->setFillColor('red@0.1'); 
			
		$Marker =& $Plot1->addNew('Image_Graph_Marker_Value', IMAGE_GRAPH_VALUE_Y);
		//$Marker->setLabelInterval(2); 
		
		$PointingMarker =& $Plot1->addNew('Image_Graph_Marker_Pointing_Angular', array(30, &$Marker));
		$PointingMarker->setFillColor('green@0.1'); 
		
		$Plot1->setMarker($PointingMarker);    
		
		$Marker->setDataPreProcessor(Image_Graph::factory('Image_Graph_DataPreprocessor_Function', 'currencyformat'));
		$Marker->setFontSize(8); 
	
	}
	//$FillerLine =& $Plotarea->addNew('Image_Graph_Plot_Area', array(&$Fillerset));
	//$FillerLine->setFillColor('blue@0.0'); 
	//$FillerLine->setLineColor('lightsteelblue@0.0'); 
	//$FillerLine->setTitle(''); 
	//$FillerLine->hide(); 
	

	
	if($style!='tiny')
	{
		$Legend =& $Plotarea->addNew('legend');
		$Legend->setFillColor('white@0.2');
		$Legend->setFillStyle(Image_Graph::factory('gradient', array(IMAGE_GRAPH_GRAD_VERTICAL, 'lightsteelblue@0.2', 'white@0.2'))); 
		$Legend->setLineColor('gray@0.7');
		$Legend->setFontSize(8);
		$Legend->showShadow('gray@0.2',6); 
	}   
		
	$Plot1->setTitle('Total Approved'); 
	
	$Plot1->setLineColor('green');
	//$Plot3->setLineColor('green');
	

	$Graph->done();
}
include("includes/footer.php");
?>