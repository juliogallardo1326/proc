<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\Transaction\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use Processor\DB\ProcessorDB;
use Processor\Invoice\Types\AbstractInvoice;
use Processor\PaymentSource\DB\PaymentSourceEntry;
use Processor\Product\DB\ProductEntry;

/**
 * Class TransactionEntry
 * @table transaction
 */
class TransactionEntry implements IBuildable, IKeyMap
{
	const STATUS_DECLINED = 0x01;
	const STATUS_PENDING = 0x02;
	const STATUS_APPROVED = 0x04;

	const STATUS_REFUNDED = 0x10;
	const STATUS_CHARGE_BACK = 0x20;

	static $StatusOptions = array(
		"Pending"      => self::STATUS_PENDING,
		"Declined"     => self::STATUS_DECLINED,
		"Approved"     => self::STATUS_APPROVED,

		"Refunded"     => self::STATUS_REFUNDED,
		"Charged Back" => self::STATUS_CHARGE_BACK,
	);

	/**
	 * @column VARCHAR(64) PRIMARY KEY
	 * @select
	 */
	protected $id;

	/**
	 * @column TINYINT
	 * @select
	 * @insert
	 */
	protected $status;

	/**
	 * @column VARCHAR(16)
	 * @select
	 * @insert
	 */
	protected $amount;

//	/**
//	 * @column ENUM('ALL', 'AFN', 'ARS', 'AWG', 'AUD', 'AZN', 'BSD', 'BBD', 'BDT', 'BYR', 'BZD', 'BMD', 'BOB', 'BAM', 'BWP', 'BGN', 'BRL', 'BND', 'KHR', 'CAD', 'KYD', 'CLP', 'CNY', 'COP', 'CRC', 'HRK', 'CUP', 'CZK', 'DKK', 'DOP', 'XCD', 'EGP', 'SVC', 'EEK', 'EUR', 'FKP', 'FJD', 'GHC', 'GIP', 'GTQ', 'GGP', 'GYD', 'HNL', 'HKD', 'HUF', 'ISK', 'INR', 'IDR', 'IRR', 'IMP', 'ILS', 'JMD', 'JPY', 'JEP', 'KZT', 'KPW', 'KRW', 'KGS', 'LAK', 'LVL', 'LBP', 'LRD', 'LTL', 'MKD', 'MYR', 'MUR', 'MXN', 'MNT', 'MZN', 'NAD', 'NPR', 'ANG', 'NZD', 'NIO', 'NGN', 'NOK', 'OMR', 'PKR', 'PAB', 'PYG', 'PEN', 'PHP', 'PLN', 'QAR', 'RON', 'RUB', 'SHP', 'SAR', 'RSD', 'SCR', 'SGD', 'SBD', 'SOS', 'ZAR', 'LKR', 'SEK', 'CHF', 'SRD', 'SYP', 'TWD', 'THB', 'TTD', 'TRY', 'TRL', 'TVD', 'UAH', 'GBP', 'USD', 'UYU', 'UZS', 'VEF', 'VND', 'YER', 'ZWD')
//	 * @select
//	 * @insert
//	 */
//	protected $currency;

	/**
	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	protected $wallet_id;

	/**
	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	protected $payment_source_id;

	/**
	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	protected $product_id;

	/**
	 * @column TEXT
	 * @select
	 * @insert
	 */
	protected $invoice;

	public function getID() {
		return $this->id;
	}

	public function getStatus() {
		return (int)$this->status;
	}

	public function hasStatus($flags) {
		return $this->getStatus() & $flags;
	}

	public function getStatusText() {
		return array_search($this->getStatus(), self::$StatusOptions);
	}

	/**
	 * @return AbstractInvoice
	 */
	public function getInvoice() {
		if(is_string($this->invoice))
			$this->invoice = unserialize($this->invoice);
		return $this->invoice;
	}

	function update($Request, $status=null) {
		$update = array();
//		$Invoice === null ?: $update[TransactionTable::COLUMN_INVOICE] = serialize($Invoice);
		$status === null ?: $update[TransactionTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(TransactionTable::COLUMN_ID, $this->getID())
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}


	// Static

	static function create(IRequest $Request, AbstractInvoice $Invoice, $status, $wallet_id, $product_id, $source_id) {
		$id = uniqid('trans-');

		$amount = $Invoice->getProduct()->getTotalCost();

		$inserted = self::table()->insert(array(
			TransactionTable::COLUMN_ID => $id,
			TransactionTable::COLUMN_WALLET_ID => $wallet_id,
			TransactionTable::COLUMN_PRODUCT_ID => $product_id,
			TransactionTable::COLUMN_PAYMENT_SOURCE_ID => $source_id,
			TransactionTable::COLUMN_AMOUNT => $amount,
			TransactionTable::COLUMN_STATUS => $status,
			TransactionTable::COLUMN_INVOICE => serialize($Invoice),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Transaction Entry Inserted: " . $id, $Request::VERBOSE);
		return $id;
	}

//	static function delete($Request, $transactionID) {
//		$delete = self::table()->delete(TransactionTable::COLUMN_ID, $transactionID)
//			->execute($Request);
//		if(!$delete)
//			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
//	}

	/**
	 * @param $id
	 * @return TransactionEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(TransactionTable::COLUMN_ID, $id);
	}

	/**
	 * @return TransactionTable
	 */
	static function table() {
		return new TransactionTable();
	}

	/**
	 * Handle this request and render any content
	 * @param IBuildRequest $Request the build request inst for this build session
	 * @return void
	 * @build --disable 0
	 * Note: Use doctag 'build' with '--disable 1' to have this IBuildable class skipped during a build
	 */
	static function handleBuildStatic(IBuildRequest $Request) {
		$Schema = new TableSchema(__CLASS__);
		$DB = new ProcessorDB();
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\TransactionTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$SourceEntry = PaymentSourceEntry::get($this->payment_source_id); // TODO: inefficient
		$Source = $SourceEntry->getPaymentSource();
		$Invoice = $this->getInvoice();
		$Product = $Invoice->getProduct();

		$Map->map('transaction-id', $this->getID());
		$Map->map('wallet-id', $this->wallet_id);
		$Map->map('product-id', $this->product_id); // , $Product->getTitle());
		$Map->map('status', $this->getStatusText());
		$Map->map('amount', $this->amount);
		$Map->map('email', $Invoice->getWallet()->getEmail());

		$Map->map('description', $Product->getDescription());

		$Map->map('currency', $Source->getCurrency());
//		$Map->map('payment-source', $Source->getTitle()); //todo: flags for mapping headers?

//		$Map->map('product', $ProductEntry);
//		$Map->map('product-title', $Product->getTitle());
//		$Map->map('total', $Product->getTotalCost());
//		$Map->map('type', $Product->getTypeName());

//		$Map->map('invoice', $Invoice->getProduct()->);

	}
}