<?

require_once("ivr.class.php");
require_once("lookup.class.php");
require_once('../includes/dbconnection.php');
require_once('../includes/transaction.class.php');

class angel_class
{
	var $page_map;
	var $params;
	var $call_center;
	var $cancel_reason;
	var $working_vars;
	var $angel_log;
	
	var $template;
	var $template_def;
	var $template_sel;
	
	function angel_class()
	{
		$this->page_map = array(
		"1"=>"angel_message_page",				
			// leave a message
		"10"=>"angel_greeting",				
			// thank you for calling etelegate
		"11"=>"angel_prompt_field",		
			// cc or check
		"20"=>"angel_prompt_credit",
			// cc number
		"30"=>"angel_prompt_check",
			// checking account number
		"31"=>"angel_prompt_route",						
			// routing number
		"40"=>"angel_prompt_subscription",
			// cc number
		"100"=>"angel_find_transaction",				
			// with cc or account+routing number can we find anything, forwards to 150 single 181 for multiple
		"110"=>"angel_no_transaction",					
			// take a message, we have no clue what you want
		"120"=>"angel_transfer_callcenter",			
			// transfer to 24/6
		"141"=>"angel_no_callcenter",					
			// take a mesage cause 24/7 sucks, sorry
		"150"=>"angel_process_transaction",				
			// cancel/ refund options presented
		"160"=>"angel_confirm_refund_transaction", 		
			//non-existant could be used like 171
		"161"=>"angel_callcenter_refund_transaction",	
			//transfer to 120, 24/7
		"162"=>"angel_refund_transaction",				
			//error
		"170"=>"angel_confirm_cancel_subscription",		
			//reason why they are canceling
		"171"=>"angel_cancel_subscription",				
			//actually cancel the subscription
		"190"=>"angel_multi_trans",						
			// present a transaction, is this what you are talking about?
		"191"=>"angel_multi_trans_over",				
			// no more transactions gives options like list again, or 24/7
		"200"=>"angel_del_vars",							
			//shut your mouth!
		"201"=>"angel_hang_up",				
			//shut your mouth!
		"default"=>"angel_404"
		);
		
		$this->call_center = "4809512273";

		$this->angel_log = new ivr_class(); 
		
		$this->template['8009613389'] = array("name"=>"e telegate","web"=>"Please consider e telegate dot net in the future for all your customer service needs");
		$this->template['8009340498'] = array("name"=>"mature bill","web"=>"");
		
		$this->template_def = "8009613389";
	}
	
	function angel_load_page($params)
	{
		$this->params = $params;
		$page = $params['page'];

		if(!isset($params['DialedNumber']))
			$this->template_sel = $this->template_def;
		else
			$this->template_sel = str_replace("-","",$params['DialedNumber']);
		
		$func = $this->page_map[$page];
		
		if(!$func) return NULL;

		$this->angel_log->store_log($params['CallGUID'],$params['CallerID'],$params,$params['CallDuration'],$func);

//		if($func == "" || !function_exists($this->$func))
//			$func = $this->page_map['default'];
		return $this->$func();
	}
	
	function angel_404($error_str = "")
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<MESSAGE>
			<COMMENT>
				404				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
					You have reached this page in error, you will be returned to the main menu.
					
					$error_str
				</PROMPT>
			</PLAY>
			<GOTO destination=\"/$angel_prompt_field\"/>		
		</MESSAGE>
	</ANGELXML>
		";
	}
	
	
	
	function angel_greeting()
	{
		$call_center = $this->call_center;
		
		foreach($this->page_map as $index => $value)
			${$value} = $index;
			
		$called_today = $this->angel_log->get_calls_today($this->params['CallerID']);
		if($called_today % 3 == 0)
		{
			return $this->angel_transfer_callcenter();
		}
		else
		{
			return "
	<ANGELXML>
		<MESSAGE>
			<COMMENT>
				Greeting				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
				Thanks for calling  " . $this->template[$this->template_sel]['name'] . " .  This system will help you, to view, and refund
				transactions as well as, cancel subscriptions.
				</PROMPT>
			</PLAY>
			<GOTO destination=\"/$angel_prompt_field\" />
		</MESSAGE>
	</ANGELXML>
		";
		}
	}

	function angel_prompt_field()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;
			
		return "
	<ANGELXML>
		<MESSAGE>
			<COMMENT>
				Greeting				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
				To get started, please select which piece of information you would like to use,
				to find a transaction.
				
				Press one, or say, credit, to enter in the credit card number used for the transaction.
				
				Press two, or say, check, to enter in the bank account information used for the transaction.
				
				Press three, or say, subscription, to enter in the subscription eye dee.
				</PROMPT>
			</PLAY>
			<LINK keyword=\"credit\" dtmf=\"1\" destination=\"/$angel_prompt_credit\"/>
			<LINK keyword=\"check\" dtmf=\"2\" destination=\"/$angel_prompt_check\"/>
			<LINK keyword=\"subscription\" dtmf=\"3\" destination=\"/$angel_prompt_subscription\"/>
		</MESSAGE>
	</ANGELXML>
		";
	}
	
	
	
	

	function angel_prompt_subscription()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<QUESTION>
			<COMMENT>
				Greeting				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
					Please speak slowly, and clearly, each number of your, subscription eye dee.
					You can also type the number using the keypad on your phone.
				</PROMPT>
			</PLAY>

			<ERROR_STRATEGY type=\"noinput\" reprompt=\"false\"> 
				<PROMPT type=\"text\"> 
					I'm sorry, I was unable to interpret your response.  Please type in the
					subscription eye dee, using your keypad
				</PROMPT> 
				<GOTO destination=\"/$angel_find_transaction\"/>
			</ERROR_STRATEGY>
			
			<RESPONSE>
				<NUMBER numberType=\"natural\" destination=\"/$angel_find_transaction\" />
			</RESPONSE>
		</QUESTION>
	</ANGELXML>
		";
	}

	function angel_prompt_credit()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<QUESTION>
			<COMMENT>
				Greeting				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
					Please speak slowly, and clearly, each number of your, credit card number.
					You can also type your, credit card number, using the keypad on your phone.
				</PROMPT>
			</PLAY>

			<ERROR_STRATEGY type=\"noinput\" reprompt=\"false\"> 
				<PROMPT type=\"text\"> 
					I'm sorry, I was unable to interpret your response.  Please type in the,
					credit card number, using your keypad
				</PROMPT> 
				<GOTO destination=\"/$angel_find_transaction\"/>
			</ERROR_STRATEGY>
			
			<RESPONSE>
				<NUMBER numberType=\"natural\" destination=\"/$angel_find_transaction\" />
			</RESPONSE>
		</QUESTION>
	</ANGELXML>
		";
	}

	function angel_prompt_check()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<QUESTION>
			<COMMENT>
				Greeting				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
					Please speak slowly, and clearly, each number of your, bank account number.
					You can also type your, account number, using the, keypad on your phone.
				</PROMPT>
			</PLAY>

			<ERROR_STRATEGY type=\"noinput\" reprompt=\"false\"> 
				<PROMPT type=\"text\"> 
					I'm sorry, I was unable to interpret your response.  Please type in the,
					account number, using your keypad.
				</PROMPT> 
				<GOTO destination=\"/$angel_find_transaction\"/>
			</ERROR_STRATEGY>
			
			<RESPONSE>
				<NUMBER numberType=\"natural\" destination=\"/$angel_prompt_route\" />
			</RESPONSE>
		</QUESTION>
	</ANGELXML>
		";
	}


	function angel_prompt_route()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<QUESTION>
			<COMMENT>
				Greeting				
			</COMMENT>
			<PLAY>
				<PROMPT type=\"text\">
					Please speak slowly, and clearly, each number of your bank routing number.
					You can also type your, routing number, using the keypad on your phone.
				</PROMPT>
			</PLAY>

			<ERROR_STRATEGY type=\"noinput\" reprompt=\"false\"> 
				<PROMPT type=\"text\"> 
					I'm sorry, I was unable to interpret your response.  Please type in the
					routing number using your keypad.
				</PROMPT> 
				<GOTO destination=\"/$angel_find_transaction\"/>
			</ERROR_STRATEGY>
			
			<RESPONSE>
				<NUMBER numberType=\"natural\" destination=\"/$angel_find_transaction\" />
			</RESPONSE>
		</QUESTION>
	</ANGELXML>
		";
	}
	
	function angel_find_transaction()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		//checking_account,credit_card,rounting_number
		$lookup = new lookup_class();
			
		$trans = $lookup->find_general_trans($this->params);
			
	toLog('order','customer',"Angel Lookup: 
	".print_r($this->params,true)."
	Found:
	".print_r($trans,true)."\n\n");

			
		if($trans == NULL)
		{
			$this->working_vars['tries'] = isset($this->working_vars['tries']) ? $this->working_vars['tries']+1 : 0;
			if($this->working_vars['tries'] == 2)
			{
				$trans = $lookup->find_general_trans("","",$this->params['CallerID']);
				if($trans == NULL)
					return $this->angel_no_transaction();
				return $this->angel_transfer_callcenter();
			}
			return $this->angel_prompt_field();
		}
		
		
		$this->working_vars['transactions'] = $trans;
		$this->working_vars['current_trans'] = 0;
		
		if(sizeof($trans) > 1)
			return $this->angel_multi_trans();

		$this->working_vars['current_trans'] = 1;
		return $this->angel_process_transaction();
	}
	
	
	
	
	function angel_no_transaction()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<VOICEMAIL mailbox=\"Inbox\">
			<PLAY>
				<PROMPT type=\"text\">
					No transactions could be found for the information you have provided.  In 
					addition, we have checked for transactions based on the number you have called
					us from.  No transactions were found.  
					
					Please check your bill to verify the company the charge was made to.  You can also
					contact the merchant and inquire about your bill.
					
					If your issue cannot be resolved, please leave a message stating the issue
					you are calling about after the beep, along with a number we can reach you at.
					
					" . $this->template[$this->template_sel]['web'] . "
					
					Thank you for contacting " . $this->template[$this->template_sel]['name'] . " support.
				</PROMPT>
			</PLAY>
			<ACTION type=\"hangup\"/>
		</VOICEMAIL>
	</ANGELXML>
		";
	}

	function angel_transfer_callcenter()
	{
		$call_center = $this->call_center;

		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<TRANSFER destination=\"/$call_center\" rings=\"10\" if_fail=\"/$angel_no_callcenter\" if_completed=\"/$angel_hang_up\">
			<PLAY>
			<PROMPT type=\"text\">
				In order to assist you further, we are transfering you, to our, live, support, center.  Please hold.
				
				Thank you for calling " . $this->template[$this->template_sel]['name'] . " support.
			</PROMPT>
			</PLAY>
		</TRANSFER>
	</ANGELXML>	
		";
	}

	
	function angel_no_callcenter()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		return "
	<ANGELXML>
		<VOICEMAIL mailbox=\"Inbox\">
			<PLAY>
				<PROMPT type=\"text\">
					Our call center appears to be busy, please leave a message stating the issue,
					you are calling about after the beep, along with a number we can reach you at.
					
					Thank you for contacting " . $this->template[$this->template_sel]['name'] . " support.
				</PROMPT>
			</PLAY>
			<ACTION type=\"hangup\"/>
		</VOICEMAIL>
	</ANGELXML>
		";
	}
	
	
	function angel_process_transaction()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;



		$lookup = new lookup_class();
		

		$trans = $this->working_vars['transactions'];
		$curr = $this->working_vars['current_trans']-1;
		
		
		if(!isset($trans[$curr]))
			return $this->angel_404("error message: 1000");
		
		$is_sub = $lookup->is_subscription($trans[$curr]['transactionId']);

//		if($is_sub < 0)
//			return $this->angel_404("error message: 1001");

		$sub_message = $is_sub ? "
			To cancel your subscription, say, cancel, or press, 2, on your keypad
		" : "";
		
		return "
	<ANGELXML>
		<MESSAGE>
			<PLAY>
				<PROMPT type=\"text\">
				To request a refund for this transaction, say refund, or press, 1, on your keypad.
				
				$sub_message
				</PROMPT>
			</PLAY>
			<LINK keyword=\"refund\" dtmf=\"1\" destination=\"/$angel_refund_transaction\"/>
			<LINK keyword=\"cancel\" dtmf=\"2\" destination=\"/$angel_confirm_cancel_subscription\"/>
		</MESSAGE>
	</ANGELXML>
		";
			
	}
	
	function angel_callcenter_refund_transaction()
	{
		foreach($this->page_map as $index => $value)
		${$value} = $index;

		return "
		<ANGELXML>
			<MESSAGE>
				<PLAY>
					<PROMPT type=\"text\">
						In order to refund your transaction, we will need to collect some additional
						information from you.
					</PROMPT>
				</PLAY>
				<GOTO destination=\"/$angel_transfer_callcenter\" />
			</MESSAGE>
		</ANGELXML>
		";
	}

	function angel_confirm_refund_transaction()
	{
		foreach($this->page_map as $index => $value)
		${$value} = $index;

		return "
	<ANGELXML>
		<MESSAGE>
			<PLAY>
				<PROMPT type=\"text\">
				
				We are sorry you were not satified with your purchase.  

				</PROMPT>
			</PLAY>
			<LINK keyword=\"merchant\" dtmf=\"1\" destination=\"/$angel_refund_transaction\"/>
			<LINK keyword=\"mind\" dtmf=\"2\" destination=\"/$angel_refund_transaction\"/>
			<LINK keyword=\"other\" dtmf=\"3\" destination=\"/$angel_transfer_callcenter\"/>
		</MESSAGE>
	</ANGELXML>
		";
	}


	function angel_confirm_cancel_subscription()
	{
		foreach($this->page_map as $index => $value)
		${$value} = $index;

	
		return "
	<ANGELXML>
		<MESSAGE>
			<PLAY>
				<PROMPT type=\"text\">
				
				Please let us know why you are cancelling your subscription.

				If you can not get in touch with the merchant, please say merchant, or press 1
				If you have changed your mind, please say mind, or press 2
				If there is another reason not listed, please say Other, or press 3
				
				</PROMPT>
			</PLAY>
			<LINK keyword=\"merchant\" dtmf=\"1\" destination=\"/$angel_cancel_subscription\"/>
			<LINK keyword=\"mind\" dtmf=\"2\" destination=\"/$angel_cancel_subscription\"/>
			<LINK keyword=\"other\" dtmf=\"3\" destination=\"/$angel_transfer_callcenter\"/>
		</MESSAGE>
	</ANGELXML>
		";
	}
		
	function angel_cancel_subscription()
	{ 
		foreach($this->page_map as $index => $value)
			${$value} = $index;
			
		
		$this->cancel_reason = $this->working_vars['cancel_reason'];

		switch ($cancel_reason) {
		case 1:
		   $cancel_reason_decoded = "Cant get in touch with Merchant (Angel cancel)";
		   break;
		case 2:
		   $cancel_reason_decoded = "Changed Mind (Angel cancel)";
		   break;
		default:
		   $cancel_reason_decoded = "Angel Cancel";
		   break;
		}			
		
		
		$lookup = new lookup_class();
		
		$trans = $this->working_vars['transactions'];
		$curr = $this->working_vars['current_trans']-1;
		
		if(!isset($trans[$curr]))
			return $this->angel_404("error message: 1000");
		$etel_debug_mode=1;
		
		if($trans[$curr]['ss_subscription_ID'] == 0)
			return $this->angel_process_transaction();
		$ss_subscription_ID = $trans[$curr]['ss_subscription_ID'];
			
		$trans = new transaction_class(false);
			
		$trans->pull_subscription($ss_subscription_ID,'ss_subscription_ID');
		
		$refinfo = $trans->process_cancel_request(array("actor"=>'Angel','notes'=>$cancel_reason_decoded));
		$refid = $refinfo['ss_cancel_id'];
		if(!$refinfo)
		{ //angel_transfer_callcenter
			return "
		<ANGELXML>
			<MESSAGE>
				<PLAY>
					<PROMPT type=\"text\">
						There was an error cancelling your subscription. It may have already been cancelled.
					</PROMPT>
				</PLAY>
				<GOTO destination=\"/$angel_transfer_callcenter\" />
			</MESSAGE>
		</ANGELXML>
			";
		}
		
		$temp = "";
		$m = strlen($refid);
		for($j=0;$j<$m;$j++)
			$temp .= $refid[$j] . ", ";
		$refid = $temp;
		
		return "
		<ANGELXML>
			<MESSAGE>
				<PLAY>
					<PROMPT type=\"text\">
						Your subscription has been cancelled. 
						
						The cancelation reference number, for this cancellation, is, $refid
						
						Again, that reference number is, $refid
						
						Thank you for calling " . $this->template[$this->template_sel]['name'] . " support.
					</PROMPT>
				</PLAY>
					<ACTION type=\"hangup\"/>
			</MESSAGE>
		</ANGELXML>		
		";
 	}
	
	function angel_refund_transaction()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		$lookup = new lookup_class();
		
		$trans = $this->working_vars['transactions'];
		$curr = $this->working_vars['current_trans']-1;
		
		if(!isset($trans[$curr]))
			return $this->angel_404("error message: 1000");

		if(!$trans[$curr]['reference_number'])
			return $this->angel_process_transaction();
		$reference_number = $trans[$curr]['reference_number'];
		
		$trans = new transaction_class(false);
			
		$trans->pull_transaction($reference_number,'td.reference_number');
		
		$refinfo = $trans->process_refund_request(array("actor"=>'Angel','notes'=>$cancel_reason_decoded));

		if(!$refinfo['success'])
		{ //angel_transfer_callcenter
			return "
		<ANGELXML>
			<MESSAGE>
				<PLAY>
					<PROMPT type=\"text\">
						There was an error processing your refund request. ".$refinfo['status']." 
					</PROMPT>
				</PLAY>
				<GOTO destination=\"/$angel_transfer_callcenter\" />
			</MESSAGE>
		</ANGELXML>
			";
		}
		
		$temp = "";
		$m = strlen($reference_number);
		for($j=0;$j<$m;$j++)
			$temp .= $reference_number[$j] . ", ";
		$refid = $temp;
		
		return "
		<ANGELXML>
			<MESSAGE>
				<PLAY>
					<PROMPT type=\"text\">
						".$refinfo['status'].".  
						
						We are sorry you were not satisfied with your purchase.
												
						The refund reference number for this refund request is, $refid.
						
						Again, that reference number is, $refid
						
						Thank you for calling " . $this->template[$this->template_sel]['name'] . " support.
					</PROMPT>
				</PLAY>
				<ACTION type=\"hangup\"/>
			</MESSAGE>
		</ANGELXML>		
		";		
	}
	
	function angel_multi_trans_over()
	{
		foreach($this->page_map as $index => $value)
		${$value} = $index;

		$this->working_vars['current_trans'] = 0;
		
		return "
	<ANGELXML>
		<MESSAGE>
			<PLAY>
				<PROMPT type=\"text\">
					There are no more transactions for the information you have submitted. 
					Would you like to relist your transactions, or speak to a representative?
					
					To relist your transactions please say, list, or press 1.
					To speak to a representative please say, representative, or press 2.
				</PROMPT>
			</PLAY>
			<LINK keyword=\"list\" dtmf=\"1\" destination=\"/$angel_multi_trans\"/>
			<LINK keyword=\"representative\" dtmf=\"2\" destination=\"/$angel_transfer_callcenter\"/>
		</MESSAGE>
	</ANGELXML>
		";
			
	}
			
	function angel_multi_trans()
	{
		foreach($this->page_map as $index => $value)
			${$value} = $index;

		$trans = $this->working_vars['transactions'];
		$curr = $this->working_vars['current_trans'];
		
		if($this->working_vars['current_trans'] > sizeof($trans)-1) return $this->angel_multi_trans_over();

		if(!isset($trans[$curr])) return $this->angel_404("error message: 1000");

		$this->working_vars['current_trans']++;
		
		$transInfo = $trans[$curr];
		$amount = $transInfo['amount'];
		$date = date("F jS Y",strtotime($transInfo['transactionDate']));
		$address = str_replace("http://","",strtolower($transInfo['cs_URL']));
		$address = str_replace("www.","",$address);
		
		$temp = "";
		$m = strlen($address);
		for($j=0;$j<$m;$j++)
			$temp .= $address[$j] . ", ";
		$address = str_replace(".","dot",$temp);
		
		$money = explode(".",$amount);
		$dollars = $money[0];
		$cents = $money[1];
		
		return "
	<ANGELXML>
		<MESSAGE>
			<PLAY>
				<PROMPT type=\"text\">
				
					We found a transaction from, $address, on, $date, for, $dollars, dollars and, $cents, cents.
					is this the right transaction?
				
					Say yes, or press 1, to select this transaction
					Say no, or press 2, to select another transaction
				</PROMPT>
			</PLAY>
			<LINK keyword=\"yes\" dtmf=\"1\" destination=\"/$angel_process_transaction\"/>
			<LINK keyword=\"no\" dtmf=\"2\" destination=\"/$angel_multi_trans\"/>
		</MESSAGE>
	</ANGELXML>
		";
	
	}
	
	
	function angel_del_vars()
	{
		return "";		//not sure what goes here
	}
		
	function angel_hang_up()
	{
		foreach($this->page_map as $index => $value)
		${$value} = $index;
		
		
/*		
		$sql="INSERT INTO `cs_callnotes` (`transaction_id` , `call_date_time` , `service_notes` , `cancel_status` , `customer_notes` , `solved` , `is_bill_date_changed` , `call_duration` , `customer_service_id` , `prev_bill_date` , `dnc`, `cn_type`, `cn_contactmethod` )
			VALUES ('$transactionId', NOW() , 'User Requests Refund', '', '$customer_notes', '' , '', '', '', '', '', 'refundrequest', '$contactmethod');";
*/

		return "
		<ANGELXML>
			<MESSAGE>
				<PLAY>
					<PROMPT type=\"text\">
					Thank you for calling " . $this->template[$this->template_sel]['name'] . " customer support. 
					
					" . $this->template[$this->template_sel]['web'] . "
					
					Good bye.
					</PROMPT>
					<ACTION type=\"hangup\"/>
			</MESSAGE>
		</ANGELXML>		
		";		
	}

}


?>