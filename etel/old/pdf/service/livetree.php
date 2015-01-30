<?php
		$rootdir="../";
		$headerInclude = "service";
		include($rootdir."includes/sessioncheckserviceuser.php");
		include($rootdir."includes/dbconnection.php");
		require_once($rootdir."includes/function.php");
		$_SESSION['gw_phone_support'] = 'N/A';
		include($rootdir."includes/header.php");

	$search_by_array = array('CCnumber','bankaccountnumber','phonenumber','reference_number',
							 'email','username','password','ReferenceNumber');

	if(!isset($_SESSION['ticket_start_time']))
		$_SESSION['ticket_start_time'] = microtime_float();

	$lt_ID = intval($_REQUEST['lt_ID']);
	if(!$lt_ID) 
	{
		$lt_ID = 1;
		//if($csUserInfo["cs_type"]=='merchant') $lt_ID = 43;

		$_SESSION['cs_found_reference_number']=NULL;
	}
	if($_POST['addnew'] && $lt_ID && !$etel_repost_warning)
	{
		$lt_option_text = quote_smart($_POST['lt_option_text']);
		$lt_question_text = quote_smart($_POST['lt_question_text']);
		$lt_subject = quote_smart($_POST['lt_subject']);
		$lt_type = quote_smart($_POST['lt_type']);
		$sql = "insert into cs_live_tree set lt_parent_ID = '$lt_ID', lt_subject = '$lt_subject', lt_question_text = '$lt_question_text', lt_option_text = '$lt_option_text', lt_type='$lt_type';";
		if($lt_option_text) $result = mysql_query($sql) or dieLog(mysql_error());
		message("Option Added Successfully","","Success","livetree.php?lt_ID=".$lt_ID,false);
		include("../admin/includes/footer.php");
		die();
	}
	
	if($csUserInfo["cs_type"]!='all')
		$sql_lt_type = "AND (lt.lt_type='".$csUserInfo["cs_type"]."' OR lt.lt_type='all')";
	//$sql_lt_type = "";
	
	$sql = "
			SELECT 
				lt.*,
				plt.lt_subject AS parent_lt_subject 
			FROM 
				cs_live_tree AS lt 
			LEFT JOIN cs_live_tree AS plt on lt.lt_parent_ID = plt.lt_ID 
			WHERE 
				lt.lt_ID = '$lt_ID' 
			$sql_lt_type				
	;";
	$result = mysql_query($sql) or dieLog(mysql_error());
	$cs_live_tree = mysql_fetch_assoc($result);
	$cd_userId = $_SESSION['cs_found_merchant'];
	
	if($_POST['searchmerc'])
	{
		$sql_where = "";
				
		foreach($search_by_array as $search_type)
		{
			if($_POST['phonenumber']) $_POST['phonenumber'] = preg_replace('/[^0-9]/','',$_POST['phonenumber']);
			if(isset($_POST[$search_type])) $sql_where.=($sql_where?" and ":"")." $search_type='".quote_smart($_POST[$search_type]?$_POST[$search_type]:"Invalid")."'";
		}
		if($sql_where)
		{
			$sql = "select userId, username, password
			
			 from cs_companydetails where $sql_where";
			$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
			$num_rows = mysql_num_rows($result);
			$_SESSION['cs_found_merchant'] = NULL;
			if($num_rows)
			{
				$cd_userId = mysql_result($result,0,0);	
				$cd_username = mysql_result($result,0,1);	
				$cd_password = mysql_result($result,0,2);	
				$_SESSION['cs_found_merchant'] = $cd_userId;
				$_SESSION['cs_found_merchant_username'] = $cd_username;
				$_SESSION['cs_found_merchant_password'] = $cd_password;
			}
			else
			{
				$_SESSION['cs_found_merchant'] = NULL;
				$_SESSION['cs_found_merchant_username'] = NULL;
				$_SESSION['cs_found_merchant_password'] = NULL;
				$cs_error_msg = "Merchant could not be found. Please try another search method.<BR>If you have tried all methods, please select 'Could Not Find Merchant' option.";
			}
		}
	}
		
	if($cd_userId) 
	{
		$sql = "select * from cs_companydetails where userId = '$cd_userId'";
		$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
		$companyInfo = mysql_fetch_assoc($result);
		if($companyInfo)
		{
			if($_REQUEST['branchonsuccess_id']) $lt_ID = intval($_REQUEST['branchonsuccess_id']);
			$sql = "select lt.*,plt.lt_subject as parent_lt_subject from cs_live_tree as lt left join cs_live_tree as plt on lt.lt_parent_ID = plt.lt_ID where lt.lt_ID = '$lt_ID' $sql_lt_type;";
			$result = mysql_query($sql) or dieLog(mysql_error());
			$cs_live_tree = mysql_fetch_assoc($result);
		}
		else
		{
			$_SESSION['cs_found_merchant'] = NULL;
			$cs_error_msg = "Merchant could not be found. Please try another search method.<BR>If you have tried all methods, please select 'Could Not Find Merchant' option.";
		}
	}
	
	$reference_number = $_SESSION['cs_found_reference_number'];
	
	if($_POST['searchtrans'])
	{
		$sql_where = "";
		if($_POST['CCnumber'])$_POST['CCnumber']=etelEnc($_POST['CCnumber']);
		if($_POST['bankaccountnumber'])$_POST['bankaccountnumber']=etelEnc($_POST['bankaccountnumber']);
		if($_POST['phonenumber']) $_POST['phonenumber'] = preg_replace('/[^0-9]/','',$_POST['phonenumber']);
		
		foreach($search_by_array as $search_type)
		{
			if(isset($_POST[$search_type])) 
				$sql_where.=	($sql_where?" AND ":"") . " $search_type='".quote_smart($_POST[$search_type]?$_POST[$search_type]:"Invalid")."'";
		}
		
		if($sql_where)
		{
			$sql = "select reference_number,concat('Time: ',transactiondate,' - Ref: ',reference_number,' for ','$',amount) as title,
			productdescription as descr
			
			 from cs_transactiondetails where $sql_where and status='A'";
			$result = mysql_query($sql) or dieLog(mysql_error()." ~ $sql");
			$num_rows = mysql_num_rows($result);
			$_SESSION['cs_found_transaction_array'] = NULL;
			if($num_rows)
			{
				if($num_rows>1)
				{
					for($i=0;$i<$num_rows;$i++)
						$_SESSION['cs_found_transaction_array'][$i] = mysql_fetch_assoc($result);
					$reference_number = $_SESSION['cs_found_transaction_array'][0]['reference_number'];	
				}
				else
					$reference_number = mysql_result($result,0,0);	
						
				$_SESSION['cs_found_reference_number'] = $reference_number;
			}
			else
			{
				$_SESSION['cs_found_reference_number'] = NULL;
				$_SESSION['cs_found_transaction_array'] = NULL;
				$cs_error_msg = "Transaction could not be found. Please try another search method.<BR>If you have tried all methods, please select 'Could Not Find Transaction' option.";
			}
		}
	}	
				
	if ($cs_live_tree['lt_action']=='transaction')
	{
		if(sizeof($_SESSION['cs_found_transaction_array'])>1)
		{
			if($_REQUEST['selecttd'] && in_array($_REQUEST['selecttd'],$_SESSION['cs_found_transaction_array']))
			{
				$reference_number = $_REQUEST['selecttd'];
				$_SESSION['cs_found_transaction_array'] = NULL;
			}
			else
			{
				$cs_live_tree['lt_question_text'] = 'I found multiple transactions from your info. Please tell me which date/referenceID/description your purchase was for.';
				$options = NULL;
				$actionurl = "livetree.php?lt_ID=".$cs_live_tree['lt_ID'];
				foreach($_SESSION['cs_found_transaction_array'] as $tid)
					$options[] = array('url'=>$actionurl."&selecttd=".$tid['reference_number'],'title'=>$tid['title'],'descr'=>$tid['descr']);
			}
		}

	}
	
	
	if($reference_number) 
	{
		$transactionInfo = getTransactionInfo($reference_number,false,'reference_number');

		if($transactionInfo != -1)
		{
			if($_REQUEST['branchonsuccess_id']) $lt_ID = intval($_REQUEST['branchonsuccess_id']);
			$sql = "select lt.*,plt.lt_subject as parent_lt_subject from cs_live_tree as lt left join cs_live_tree as plt on lt.lt_parent_ID = plt.lt_ID where lt.lt_ID = '$lt_ID' $sql_lt_type;";
			$result = mysql_query($sql) or dieLog(mysql_error());
			$cs_live_tree = mysql_fetch_assoc($result);
		}
		else
		{
			$_SESSION['cs_found_reference_number'] = NULL;
			$cs_error_msg = "Transaction could not be found. Please try another search method.<BR>If you have tried all methods, please select 'Could Not Find Transaction' option.";
		}
		
		
	}
	
	if ($cs_live_tree['lt_action']=='refund')
	{
		if($_REQUEST['refundtd'] && ($_REQUEST['refundtd']==$reference_number))
		{
			$refund_type = "Customer Service Refund";
			$error_msg = exec_refund_request($transactionInfo['transactionId'],$refund_type,$_REQUEST['refund_reason']);
			$cs_live_tree['lt_question_text'] = "Refund Request Completed. You will recieve an email notification as soon as your refund is processed. ($error_msg)";
			$_SESSION['cs_found_call_log']=$_REQUEST['refund_reason'];
			$_SESSION['cs_found_call_subject'] = 'Refund Request for '.$transactionInfo['reference_number'];
			$_SESSION['cs_found_call_resolved'] = true;
		}
		else
		{
			$options = NULL;
			$actionurl = "livetree.php?lt_ID=".$cs_live_tree['lt_ID'];
			$options[] = array("name" => "refund_text", "descr" => "Please Enter the Reason for this Refund:","type" => "textarea","title"=>"Refund Request for $reference_number:\n");
			$options[] = array('url'=>$actionurl."&refundtd=".$reference_number,'title'=>'Refund Order','descr'=>'Refund Order:',"fields" => array(array("var" => "refund_reason","val" => "refund_text")));
		}
	}
	
	if ($cs_live_tree['lt_action']=='cancel')
	{
		if($_REQUEST['canceltd'] && ($_REQUEST['canceltd']==$reference_number))
		{
			$trans = new transaction_class(false);
			$trans->pull_transaction($transactionInfo['transactionId']);
			$status = $trans->process_cancel_request(array("actor"=>'Customer Service'));
			$msg = "Subscription Cancelled";
		
			$cs_live_tree['lt_question_text'] = "Subscription Cancelation Complete. Your order will not be rebilled.";
			$_SESSION['cs_found_call_log']="Customer Service Subscription Cancel for ".$transactionInfo['reference_number'];
			$_SESSION['cs_found_call_subject'] = 'Customer Service Subscription Cancel for '.$transactionInfo['reference_number'];
			$_SESSION['cs_found_call_resolved'] = true;
		}
		else
		{
			$options = NULL;
			$actionurl = "livetree.php?lt_ID=".$cs_live_tree['lt_ID'];
			$options[] = array('url'=>$actionurl."&canceltd=".$reference_number,'title'=>'Cancel Subscription','descr'=>'Cancel Subscription');
		}
	}

	
	
	// Replace Variables
	if($reference_number)
	{
		foreach($transactionInfo as $key=>$data)
			$cs_live_tree['lt_question_text']=str_replace('['.$key.']',$data,$cs_live_tree['lt_question_text']);
	}
	if($companyInfo)
	{
		foreach($companyInfo as $key=>$data)
			$cs_live_tree['lt_question_text']=str_replace('['.$key.']',$data,$cs_live_tree['lt_question_text']);
	}
	foreach($gwInfo as $key=>$data)
		$cs_live_tree['lt_question_text']=str_replace('['.$key.']',$data,$cs_live_tree['lt_question_text']);
		
	$sql = "select * from cs_live_tree as lt where lt_parent_ID = '$lt_ID'
	$sql_lt_type
	order by lt_option_text asc";
	
	$subresult = mysql_query($sql) or dieLog(mysql_error());
?><style type="text/css">
<!--
.style1 {
	font-size: 12px;
	color: #FF6633;
}
-->
</style>

<HEAD>

<SCRIPT LANGUAGE="JavaScript">
<!-- Idea by:  Nic Wolfe (Nic@TimelapseProductions.com) -->
<!-- Web URL:  http://fineline.xs.mw -->

<!-- This script and many more are available free online at -->
<!-- The JavaScript Source!! http://javascript.internet.com -->

<!-- Begin
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=900,height=600,left = 340,top = 312');");
}
// End -->

function forwardto(URL)
{
	document.location.href = URL;
}
</script>


<table border="0" cellpadding="0" width="100%" cellspacing="0" height="61%">
  <tr>
    <td width="83%" valign="top" align="center"  >
	
	
	<?php if($_GET['addnew'] && $lt_ID) { ?>
	<form name="addnew" method="post" action="livetree.php?lt_ID=<?=$lt_ID?>" >
	<table width="500" border="0">
      <tr>
        <td><strong>Enter the Topic </strong></td>
        <td><input name="lt_subject" type="text" id="lt_subject" size="40"></td>
      </tr>
      <tr>
        <td><strong>Briefly enter the Customer's Question </strong></td>
        <td><textarea name="lt_option_text" cols="40" rows="2" id="lt_option_text"></textarea></td>
      </tr>
      <tr>
        <td><strong>Answer to Question <br>
          (Leave Blank) </strong></td>
        <td><textarea name="lt_question_text" cols="40" rows="5" id="lt_question_text"></textarea></td>
      </tr>
			<tr>
			  <td> </td>
			  <td><input name="addnew" type='submit' style="height:30;" value = 'Add New Option'></td>
			</tr>
    </table>
	<input name="lt_type" type='hidden' style="height:30;" value = '<?=$_REQUEST['lt_type']?>'>
	</form>
	<?php } else { ?>
	
	<table width="500" border="0">
      <tr style="text-align:center; font-size:14px; font-weight:bold;" valign="top">
        <td colspan="2"><?=$cs_live_tree['lt_subject']?></td>
      </tr>
      <tr valign="top">
        <td colspan="2" style="text-align:justify; font-size:18px; font-weight:bold; color:#0000FF"><?=$cs_live_tree['lt_question_text']?></td>
      </tr>
	  <?php if($cs_error_msg) { ?>
		<tr>
		  <td colspan="2"><hr></td>
		</tr>
      <tr style="text-align:center; font-size:14px; font-weight:bold; color:#FF0000" valign="top">
        <td colspan="2"><?=$cs_error_msg?></td>
      </tr>
	  <?php } ?>
		<tr>
		  <td colspan="2"><hr></td>
		</tr>
	  <?php 
	  if($options) { 
	  	foreach($options as $option)
	  	{
			?>
			<tr>
			  <td colspan="2" style="color:#CC0000; font-weight:bold">Info: <?=nl2br($option['descr'])?>
			  </td>
			</tr>
			<tr>
			  <td></td>
			<?
			switch($option['type'])
			{
				case "textarea":
	  ?>
			  <td><textarea name="<?=$option['name']?>" id="<?=$option['name']?>" style="padding:5;" rows="4" cols="40" ><?=$option['title']?></textarea></td>
	  <?php 
				break;
			default:
			  $dest = "'" . $option['url'] . "'";
			  if(isset($option['fields']))
			  {
			  	$m = sizeof($option['fields']);
				for($j=0;$j<$m;$j++)
					$dest .= "+ '&" . $option['fields'][$j]['var'] . "=' + document.getElementById('" . $option['fields'][$j]['val'] . "').value";
			  }
			  ?>
			  <td><input name="button" type='button' style="padding:5;" onClick="forwardto(<?=$dest?>)" value = '<?=$option['title']?>'></td>
	  <?php 
	  			break;
	  		}
	?>
			</tr>
			<tr> <td colspan="2"><hr> </td></tr>
	<?
	  	} 
	  }
	  ?>
		
		<?php
			
			while($cs_sub_node = mysql_fetch_assoc($subresult))
			{
				$branch_to_id=NULL;
				$actionurl=NULL;
				switch ($cs_sub_node['lt_action'])
				{
					case 'redir':
						$actionurl = "loginas.php?redir=".$cs_sub_node['lt_action_item'];
					case 'transaction':
					case 'branch':
					case 'cancel':
						$branch_to_id = $cs_sub_node['lt_ID'];
						if($cs_sub_node['lt_action_item']) $branch_to_id = $cs_sub_node['lt_action_item'];
					case 'branchonsuccess':
					case 'refund':
						if(!$branch_to_id) $branch_to_id = $cs_sub_node['lt_ID'];
						if(!$actionurl) $actionurl = "livetree.php?lt_ID=$branch_to_id";
						?>
						<tr>
						  <td></td>
						  <td>
						  <input name="button" type='button' style="padding:7;" onClick='document.location.href="<?=$actionurl?>";' value = '<?=str_replace("'",'`',$cs_sub_node['lt_option_text'])?>'></td>
						</tr>
						<?php
						break;
					case 'searchtrans':
					case 'searchmerc':
					
						parse_str($cs_sub_node['lt_action_item'],$search_fields);
						?>
						<?php if($not_first_search) { ?>
						<tr>
						  <td>  </td><td style="padding-left:50px; ">- OR -</td>
						</tr>
						<?php } else $not_first_search = 1; ?>
						<form name="search_by" method="post">
						  <?php if($cs_live_tree['lt_action']=='branchonsuccess') { ?><input type="hidden" name="branchonsuccess_id" value="<?=intval($cs_live_tree['lt_action_item'])?>"><?php } ?>
						
						  <?php foreach($search_fields as $key=>$name) { 
							$type = 'text';
							if($key=='password') $type = 'password';
						  
						  ?>
						  <tr><td><?=$name?>:</td><td><input name="<?=$key?>" type='<?=$type?>' value = ''><br></td></tr>
						  <?php } ?>
						  <tr><td></td><td><input name="<?=$cs_sub_node['lt_action']?>" type='submit' style="height:30;" value = '<?=$cs_sub_node['lt_option_text']?>'></td></tr>
						  <tr><td colspan="2"><hr></td></tr>
						
						</form>
						<?php
						break;
				}
			}
			
			if($cs_live_tree['parent_lt_subject'])
			{
		?>
						  <tr><td colspan="2"><hr></td></tr>
				<tr>
				  <td></td>
				  <td><input name="button" type='button' style="height:30;" onClick='document.location.href="livetree.php?lt_ID=<?=$cs_live_tree['lt_parent_ID']?>";' value = " Return to '<?=$cs_live_tree['parent_lt_subject']?>'"></td>
				</tr>
			<?php
			}
			
			if($csUserInfo["cs_type"]=='merchant' || $csUserInfo["cs_type"]=='all')
			{
			?>
			<tr>
			  <td> </td>
			  <td><input name="button" type='button' style="height:30;" onClick='document.location.href="livetree.php?lt_ID=<?=$lt_ID?>&lt_type=<?=$cs_live_tree['lt_type']?>&addnew=1";' value = 'Other (Option Not Listed)'></td>
			</tr>
			<?php } ?>
			<tr>
			  <td> </td>
			  <td><span class="style1" style="font-weight:bold;">
			  <hr>
			  	<? if($reference_number != NULL) { ?>
				  <p>
			    <input name="button" type='button' style="height:30;" onClick="javascript:popUp('transactiondetails.php?id=<?=$reference_number?>')" value = 'View Transaction Details'><br>
			    </p>
				<? } ?>
				<p>
				<input name="button" type='button' style="height:30;" onClick='document.location.href="../support/index.php?caseid=NewTicket";' value = 'Log a Call'>
			    </p>
		      Please Log the Call at the end of every call before hanging up. Please select the 'Log a Call' button and fill the support ticket with the following information:</p>
		        <ul>
		          <li>Customer Full Name</li>
                  <li>Customer Phone Number and/or Email Address </li>
	              <li>The Problem, Question, or Request</li>
	              <li>A Brief Description of the problem</li>
	              <li>Has the problem been resolved? Please specify</li>
	          </ul>
			  </span></td>
			</tr>
    </table>
	<?php } ?>
	
	</td>
  </tr>
</table>	
<?php
	include("../admin/includes/footer.php");
?>	
