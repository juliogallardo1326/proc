<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\Product\Types;


use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Render\HTML\Attribute\Attributes;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\Text\IRenderText;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use Processor\Account\DB\AccountEntry;
use Processor\Account\DB\AccountTable;
use Processor\Account\Types\AbstractAccountType;
use Processor\Product\DB\ProductEntry;
use Processor\Wallet\Type\AbstractWallet;

abstract class AbstractProductType implements \Serializable, IKeyMap, IRenderText
{
	const TYPE_NAME = null;
	const TYPE_DESCRIPTION = null;

	const CLS_FIELDSET_PRODUCT = 'fieldset-product';
	const CLS_FIELDSET_CONFIG = 'fieldset-product-config';
	const CURRENCY_LOCALE = 'en_US';

	const PARAM_PRODUCT_TITLE = 'product-title';
	const PARAM_PRODUCT_TOTAL_COST = 'product-total-cost';
	const PARAM_PRODUCT_FEE = 'product-fee';
	const PARAM_PRODUCT_TYPE = 'product-type';

	public $title;
	public $total;
	public $fees = null;

	/**
	 * @return String
	 */
	abstract function getProductTitle();

	/**
	 * @return mixed
	 */
	abstract function getTotalCost();

	/**
	 * @param IRequest $Request
	 * @return HTMLElement
	 */
	abstract function getOrderFieldSet(IRequest $Request);


	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	abstract function validateOrderRequest(IRequest $Request, HTMLForm $ThrowForm=null);

//	/**
//	 * @param IRequest $Request
//	 * @return HTMLElement
//	 */
//	abstract function getConfigFieldSet(IRequest $Request);
//
//	/**
//	 * Validate the request
//	 * @param IRequest $Request
//	 * @param HTMLForm $ThrowForm
//	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
//	 * @return array|void optionally returns an associative array of modified field names and values
//	 */
//	abstract function validateConfigRequest(IRequest $Request, HTMLForm $ThrowForm=null);

	/**
	 * @param IRequest $Request
	 * @param AbstractWallet $Wallet
	 * @return \Processor\Invoice\Types\AbstractInvoice
	 */
	abstract function createNewInvoice(IRequest $Request, AbstractWallet $Wallet);

	/**
	 * Export to string
	 * @return String
	 */
	abstract function exportToString();

	public function __construct() {

	}

	public function getTypeName() {
		return static::TYPE_NAME;
	}

	public function getTypeDescription() {
		return static::TYPE_DESCRIPTION;
	}

	public function profitAddApproval(IRequest $Request, $product_id) {
		$profit = $this->calculateProfit();
		$ProductEntry = ProductEntry::get($product_id);
		$ProductEntry->addProfit($Request, $profit);
	}

	public function profitRefundTransaction($product_id) {

	}

	protected function setRate($accountID, $percentage) {
		$this->fees ?: $this->fees = array();
		$this->fees[$accountID] = '%' . $percentage;
		return $this;
	}

	protected function setFee($accountID, $fee) {
		$this->fees ?: $this->fees = array();
		$this->fees[$accountID] = $fee;
		return $this;
	}


	function getConfigFieldSet() {
		$Table = new AccountTable();
		$Query = $Table
			->select()
			->limit(5);

		$FieldSet = new HTMLElement('fieldset', self::CLS_FIELDSET_CONFIG,
			new Attributes('data-' . static::PARAM_PRODUCT_TYPE, $this->getTypeName()),

			new HTMLElement('legend', 'legend-shipping', "Configure Product"),

			new HTMLElement('label', 'label-' . self::PARAM_PRODUCT_TITLE, "Product Title<br/>",
				new HTMLInputField(self::PARAM_PRODUCT_TITLE, $this->title,
					new Attributes('placeholder', '"My Product - $9.99"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_PRODUCT_TOTAL_COST, "Total Cost<br/>",
				new HTMLInputField(self::PARAM_PRODUCT_TOTAL_COST, $this->total,
					new Attributes('placeholder', '"9.99"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			$FieldsetFees = new HTMLElement('fieldset', 'fieldset-fees',
				new HTMLElement('legend', 'legend-fees', "Set Product Rates and Fees")
			)
		);

		while($AccountEntry = $Query->fetch()) {
			/** @var AccountEntry $AccountEntry */
			$Account = $AccountEntry->getAccount();
			$accountID = $AccountEntry->getID();
			$FieldsetFees->addAll(
				new HTMLElement('label', 'label-' . self::PARAM_PRODUCT_FEE, $Account->getAccountName() . "<br/>",
					new HTMLInputField(self::PARAM_PRODUCT_FEE . '[' . $accountID . ']', $this->fees[$accountID],
						new Attributes('placeholder', 'Set fee "9.99" or rate "%1.50"'),
						new RequiredValidation()
					)
				),
				"<br/><br/>"
			);
		}

		return $FieldSet;
	}

	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	function validateConfigRequest(IRequest $Request, HTMLForm $ThrowForm=null) {
		$Form = new HTMLForm('POST',
			$this->getConfigFieldSet($Request)
		);
		$Form->setFormValues($Request);
		$Form->validateRequest($Request, $ThrowForm);

		$this->title = $Request[self::PARAM_PRODUCT_TITLE];
		$this->total = $Request[self::PARAM_PRODUCT_TOTAL_COST];
		$this->fees = $Request[self::PARAM_PRODUCT_FEE];
	}

	public function exportFeesToString() {
//		foreach($this->fees as $accountID => $fee) {
//			$export .= "\n" . str_pad($accountID, 16) . "'{$fee}'";
//		}

		return implode(" / ", $this->fees ?: array());
	}


	/**
	 * Determine the amount (if any) an account is owed from transaction fees
	 * @param array $accounts
	 * @throws \Exception
	 * @return String
	 */
	function calculateProfit(&$accounts = array()) {
		$profit = $this->getTotalCost();
		if(!is_numeric($profit))
			throw new \Exception("Invalid total cost: " . $profit);

		foreach($this->fees as $accountID => $fee) {
			if(strpos($fee, '%') !== false) {
				$percent = str_replace('%', '', $fee);
				if(!is_numeric($percent))
					throw new \Exception("Invalid percentage: " . $percent);
				$calculatedFee = (floatval($this->getTotalCost()) * floatval($percent)) / 100;

			} else {
				$calculatedFee = $fee;
			}
			$calculatedFee = sprintf('%.2f', $calculatedFee);
			$accounts[$accountID] = $calculatedFee;
			$profit -= $calculatedFee;
		}

		if($profit <= 0)
			throw new \Exception("Invalid Profit: " . $profit);
		return $profit;
	}

	/**
	 * Determine the amount (if any) an account is owed from transaction fees
	 * @param $accountID
	 * @throws \Exception
	 * @return String
	 */
	function calculateProfitForAccount($accountID) {
		if(empty($this->fees[$accountID]))
			throw new \InvalidArgumentException("Account does not have fees associated with it: " . $accountID);
		$accounts = array();
		$this->calculateProfit($accounts);
		if(!isset($accounts[$accountID]))
			throw new \InvalidArgumentException("Account does not have profit: " . $accountID);

		return $accounts[$accountID];
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize() {
		$values = (array)$this;
		foreach(array_keys($values) as $key)
			if($values[$key] === null)
				unset($values[$key]);
		return json_encode($values);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return void
	 */
	public function unserialize($serialized) {
		foreach(json_decode($serialized, true) as $name => $value)
			$this->$name = $value;
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Map->map('title', $this->getProductTitle());
		$Map->map('description', $this->getTypeDescription());
		$Map->map('total', $this->getTotalCost());
		$Map->map('type', $this->getTypeName());
	}

	/**
	 * Render request as plain text
	 * @param IRequest $Request the IRequest inst for this render which contains the request and remaining args
	 * @return String|void always returns void
	 */
	function renderText(IRequest $Request) {
		echo $this->exportToString();
	}



	// Static

	/**
	 * @return AbstractProductType[]
	 */
	static function loadAllProductTypes() {
		return array(
			new ShippingProduct()
		);
	}
}


