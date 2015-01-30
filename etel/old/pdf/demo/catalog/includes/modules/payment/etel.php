<?php
/*
  Etelegate.com OSCommerse Plugin

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class etel {
    var $code, $title, $description, $enabled, $mt_reference_id, $mt_language, $mt_secretkey, $testmode ;

// class constructor
    function etel() {
      global $order;

      $this->code = 'etel';
      $this->title = MODULE_PAYMENT_ETEL_TEXT_TITLE;
      $this->description = MODULE_PAYMENT_ETEL_TEXT_DESCRIPTION;
      $this->mt_reference_id = '2D82C48CF8B0';
      $this->mt_language = MODULE_PAYMENT_ETEL_LANG;
      $this->mt_secretkey = 'yUXDTdSE5AnT2i4J';
      $this->testmode = MODULE_PAYMENT_ETEL_TEST_MODE;
      $this->sort_order = MODULE_PAYMENT_ETEL_SORT_ORDER;
      $this->enabled = ((MODULE_PAYMENT_ETEL_STATUS == 'True') ? true : false);
      $this->form_action_url = 'https://secure.etelegate.com/secure/PaymentEntry.php';
	  if($this->testmode!='Live Mode')  $this->form_action_url = 'https://secure.etelegate.com/secure/testintegration.php';

      if ((int)MODULE_PAYMENT_ETEL_ORDER_STATUS_ID > 0) {
        $this->order_status = MODULE_PAYMENT_ETEL_ORDER_STATUS_ID;
      }

      if (is_object($order)) $this->update_status();
    }

// class methods
    function update_status() {
      global $order;

      if ( ($this->enabled == true) && ((int)MODULE_PAYMENT_ETEL_ZONE > 0) ) {
        $check_flag = false;
        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }

    function javascript_validation() {

    }

    function selection() {

      $selection = array('id' => $this->code,
                         'module' => $this->title,
                         'fields' => array());

      return $selection;
    }

    function pre_confirmation_check() {
      global $HTTP_POST_VARS;

    }

    function confirmation() {
      global $HTTP_POST_VARS;

      $confirmation = array('title' => $this->title . ': ' . $this->ETEL_card_type,
                            'fields' => array());

      return $confirmation;
    }

    function process_button() {
      global $HTTP_POST_VARS, $order;
	  $mt_checksum = md5($this->mt_secretkey.$this->mt_reference_id.$order->info['total'].$mt_product_id);


	  // If you have some kind of tracking number or product id at this point, put it in $mt_product_id.

	  foreach($order->products as $ar)
	  	$mt_prod_desc .= $ar['model'].": ".$ar['final_price']."<BR>";
	  $mt_prod_desc .= $order->info['total']."<BR>";

      $process_button_string = "\n".
	  						   tep_draw_hidden_field('mt_reference_id', $this->mt_reference_id) . "\n".
                               tep_draw_hidden_field('mt_language', $this->mt_language) . "\n".
                               tep_draw_hidden_field('mt_amount', $order->info['total']) . "\n".
                               tep_draw_hidden_field('mt_checksum', $mt_checksum) . "\n".
                               tep_draw_hidden_field('mt_product_id', $mt_product_id) .  "\n".   //
                               tep_draw_hidden_field('mt_prod_desc', $mt_prod_desc) .		 "\n".
                               tep_draw_hidden_field('firstname', $order->customer['firstname']) . "\n".
                               tep_draw_hidden_field('lastname',  $order->customer['lastname']) . "\n".
                               tep_draw_hidden_field('address',  $order->customer['street_address']) . "\n".
                               tep_draw_hidden_field('city',  $order->customer['city']) . "\n".
                               tep_draw_hidden_field('zipcode',  $order->customer['postcode']) . "\n".
                               tep_draw_hidden_field('country',  $order->customer['country']['iso_code_2']) . "\n".
                               tep_draw_hidden_field('telephone',  $order->customer['telephone']) . "\n".
                               tep_draw_hidden_field('email',  $order->customer['email_address']) . "\n".
                               tep_draw_hidden_field(tep_session_name(), tep_session_id()."\n");
							   //MD5( Secret Key + Website Reference ID + Amount to Charge + Product ID )


      return $process_button_string;
    }

    function before_process() {
      global $HTTP_POST_VARS, $order;
	  $md5_verify = md5($this->mt_secretkey.$this->mt_reference_id.$_REQUEST['mt_total_amount'].$_REQUEST['mt_reference_number']);

	  //MD5( Secret Key + Reference ID + Amount to Charge + Product Reference Number )
 		if($_REQUEST['verify_checksum'] != $md5_verify) die("Order Verification Failed. Please contact support.");
    }

    function after_process() {
      global $insert_id;
    }

    function get_error() {
      global $HTTP_GET_VARS;

      $error = array('title' => MODULE_PAYMENT_ETEL_TEXT_ERROR,
                     'error' => stripslashes(urldecode($HTTP_GET_VARS['error'])));

      return $error;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_PAYMENT_ETEL_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Credit Card Module', 'MODULE_PAYMENT_ETEL_STATUS', 'True', 'Do you want to use Etelegate.com gateway processing?', '6', '0', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort order of display.', 'MODULE_PAYMENT_ETEL_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0' , now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Website Reference Id', 'MODULE_PAYMENT_ETEL_REFERENCE_ID', '', 'Your Etelegate Website Reference ID.', '6', '0', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Secret Key', 'MODULE_PAYMENT_ETEL_SECRET_KEY', '', 'Your Etelegate Secret Key (Found in your Pricing Options Backend).', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Language', 'MODULE_PAYMENT_ETEL_LANG', 'eng', 'Optional Order Page Language.', '6', '2', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Processing Mode', 'MODULE_PAYMENT_ETEL_TEST_MODE', 'Test Mode', 'Processing Mode (Set to Test Mode for testing)', '6', '2','tep_cfg_select_option(array(\'Test Mode\', \'Live Mode\'), ' , now())");

    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_PAYMENT_ETEL_TEST_MODE', 'MODULE_PAYMENT_ETEL_STATUS', 'MODULE_PAYMENT_ETEL_LANG', 'MODULE_PAYMENT_ETEL_REFERENCE_ID', 'MODULE_PAYMENT_ETEL_SECRET_KEY', 'MODULE_PAYMENT_ETEL_SORT_ORDER');
    }
  }
?>
