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
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLAnchor;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\Text\IRenderText;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use Processor\Account\DB\AccountAffiliationEntry;
use Processor\Account\DB\AccountAffiliationTable;
use Processor\Account\DB\AccountEntry;
use Processor\Account\DB\AccountTable;
use Processor\Account\ManageAccount;
use Processor\Product\DB\ProductEntry;
use Processor\Transaction\DB\TransactionEntry;
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
	const PARAM_PAYMENT_WALLET_TYPES = 'payment-wallet-types';

	public $id;
	public $account_id;
	public $title;
	public $total;
	public $wallet_flags;
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

	/**
	 * Get Product ID
	 * @return String
	 */
	function getProductID() { return $this->id; }

	public function setProductID($id) {
		if(!$id)
			throw new \InvalidArgumentException("Invalid ID");
		$this->id = $id;
	}

	/**
	 * Get Account ID
	 * @return String
	 */
	function getAccountID() { return $this->account_id; }

	public function setAccountID($id) {
		if(!$id)
			throw new \InvalidArgumentException("Invalid Account ID");
		$this->account_id = $id;
	}

	/**
	 * Return a list of wallet types available to this product
	 * @return AbstractWallet[]
	 */
	public function getWalletTypes() {
		$WalletTypes = array();
		foreach(AbstractWallet::loadAllWalletTypes() as $WalletType) {
			if($WalletType::ID_FLAG & $this->wallet_flags) {
				$WalletTypes[] = $WalletType;
			}
		}
		return $WalletTypes;
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

	function getFeesFieldSet() {
		$Table = new AccountTable();
		$Query = $Table
			->select(AccountTable::COLUMN_ID)
			->select(AccountTable::COLUMN_NAME)
			->select(AccountAffiliationTable::COLUMN_TYPE)
			->join(AccountAffiliationTable::TABLE_NAME, $Table::COLUMN_ID, AccountAffiliationTable::COLUMN_AFFILIATE_ID)
			->where(AccountAffiliationTable::COLUMN_ACCOUNT_ID, $this->getAccountID());

		$FieldsetFees = new HTMLElement('fieldset', 'fieldset-fees inline',
			new HTMLElement('legend', 'legend-fees', "Product Rates and Fees"),
			new HTMLAnchor(ManageAccount::getRequestURL($this->getAccountID()), "Add Affiliates"),

			"<br/><br/>"
		);

		while($row = $Query->fetch()) {
			list($accountID, $accountName, $accountType) = array_values($row);
			$accountTypeText = array_search($accountType, AccountAffiliationEntry::$TypeOptions);
			$FieldsetFees->addAll(
				new HTMLElement('label', 'label-' . self::PARAM_PRODUCT_FEE, "{$accountName} - {$accountTypeText}<br/>",
					new HTMLInputField(self::PARAM_PRODUCT_FEE . '[' . $accountID . ']', $this->fees[$accountID],
						new Attributes('placeholder', 'Set fee "9.99" or rate "%1.50"'),
						new RequiredValidation()
					)
				),
				"<br/><br/>"
			);
		}
		return $FieldsetFees;
	}

	function getConfigFieldSet() {

		$FieldSet = new HTMLElement('fieldset', self::CLS_FIELDSET_CONFIG . ' inline',
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
			new HTMLElement('label', null, "Choose Wallet Types(s)<br/>",
				$SourceSelect = new HTMLSelectField(self::PARAM_PAYMENT_WALLET_TYPES . '[]',
					new Attributes('multiple', 'multiple')
//					new RequiredValidation()
				)
			)

		);

		foreach(AbstractWallet::loadAllWalletTypes() as $WalletType) {
			$SourceSelect->addOption($WalletType::ID_FLAG, $WalletType->getDescription());
			if($WalletType::ID_FLAG & $this->wallet_flags) {
				$SourceSelect->select($WalletType::ID_FLAG);
			}
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
		$this->wallet_flags = array_sum((array)$Request[self::PARAM_PAYMENT_WALLET_TYPES]);
	}

	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	function validateFeesRequest(IRequest $Request, HTMLForm $ThrowForm=null) {
		$Form = new HTMLForm('POST',
			$this->getFeesFieldSet($Request)
		);
		$Form->setFormValues($Request);
		$Form->validateRequest($Request, $ThrowForm);

		$this->fees = $Request[self::PARAM_PRODUCT_FEE];
		foreach($Request[self::PARAM_PRODUCT_FEE] as $accountID => $fee) {
			$fees = explode(';', $fee);
			foreach($fees as &$f) {
				$f = preg_replace('/[^0-9;.%]/', '', $f);
				if(!$f)
					$f = null;
				else if(strpos($fee, '.') === false)
					$f .= '.00';
			}
			$this->fees[$accountID] = implode('; ', $fees) ?: '0.00';
		}
	}

	public function exportFeesToString() {
//		foreach($this->fees as $accountID => $fee) {
//			$export .= "\n" . str_pad($accountID, 16) . "'{$fee}'";
//		}

		return implode(" / ", $this->fees ?: array());
	}


	/**
	 * Determine the amount (if any) an account is owed from transaction fees
	 * @param $status
	 * @param array $accounts
	 * @throws \Exception
	 * @return String
	 */
	function calculateProfit($status, &$accounts = array()) {
		switch($status) {
			case TransactionEntry::STATUS_APPROVED:
				$profit = $this->getTotalCost();
				break;

			default:
				$profit = '0.00';
				break;
		}
		if(!is_numeric($profit))
			throw new \Exception("Invalid total cost: " . $profit);

		foreach((array)$this->fees as $accountID => $fee) {
			$fees = explode(',', $fee);
			switch($status) {
				case TransactionEntry::STATUS_APPROVED:
					$fee = $fees[0];
					break;

				default:
					$fee = isset($fees[1]) ? $fees[1] : '0.00';
					break;
			}

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

//		if($profit <= 0)
//			throw new \Exception("Invalid Profit: " . $profit);
		return sprintf('%.2f', $profit);
	}

//	/**
//	 * Determine the amount (if any) an account is owed from transaction fees
//	 * @param $accountID
//	 * @throws \Exception
//	 * @return String
//	 */
//	function calculateProfitForAccount($accountID) {
//		if(empty($this->fees[$accountID]))
//			throw new \InvalidArgumentException("Account does not have fees associated with it: " . $accountID);
//		$accounts = array();
//		$this->calculateProfit(, $accounts);
//		if(!isset($accounts[$accountID]))
//			throw new \InvalidArgumentException("Account does not have profit: " . $accountID);
//
//		return $accounts[$accountID];
//	}

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


