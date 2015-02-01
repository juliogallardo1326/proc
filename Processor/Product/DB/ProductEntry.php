<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\Product\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use Processor\DB\ProcessorDB;
use Processor\PaymentSource\DB\PaymentSourceEntry;
use Processor\Product\Types\AbstractProductType;

/**
 * Class ProductEntry
 * @table product
 */
class ProductEntry implements IBuildable, IKeyMap
{
	const STATUS_ACTIVE = 0x01;
	const STATUS_INACTIVE = 0x02;

	static $StatusOptions = array(
		"Active" => self::STATUS_ACTIVE,
		"Inactive" => self::STATUS_INACTIVE,
	);

	/**
	 * @column VARCHAR(64) PRIMARY KEY
	 * @select
	 */
	protected $id;

	/**
	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	protected $account_id;

	/**
	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	protected $payment_source_id;

	/**
	 * @column TINYINT
	 * @select
	 * @insert
	 */
	protected $status;

	/**
	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
//	protected $title;

	/**
	 * @column TEXT
	 * @select
	 * @insert
	 */
	protected $product;


	public function getID() {
		return $this->id;
	}

	public function getPaymentSourceID() {
		return $this->payment_source_id;
	}

//	public function getTitle() {
//		return $this->title;
//	}

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
	 * @return AbstractProductType
	 */
	public function getProduct() {
		if(is_string($this->product))
			$this->product = unserialize($this->product);
		return $this->product;
	}

	function update($Request, $Product, $sourceID=null, $status=null) {
		$update = array(
			ProductTable::COLUMN_PRODUCT => serialize($Product),
		);
		$sourceID === null ?: $update[ProductTable::COLUMN_PAYMENT_SOURCE_ID] = $sourceID;
		$status === null ?: $update[ProductTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(ProductTable::COLUMN_ID, $this->getID())
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$SourceEntry = PaymentSourceEntry::get($this->payment_source_id);
		$Source = $SourceEntry->getPaymentSource();
		$Map->map('product-id', $this->getID());
		$Map->map('currency', $Source->getCurrency());
		$Map->map('payment_source', $Source->getTitle());
		$Map->map('account_id', $this->account_id);
		$Map->map('wallet_id', $this->getStatus());
		$Product = $this->getProduct();
		$Product->mapKeys($Map);
	}

	// Static

	static function create(IRequest $Request, AbstractProductType $Product, $accountID, $sourceID, $status=0) {
		$id = uniqid('product-');

		$inserted = self::table()->insert(array(
			ProductTable::COLUMN_ID => $id,
			ProductTable::COLUMN_ACCOUNT_ID => $accountID,
			ProductTable::COLUMN_PAYMENT_SOURCE_ID=> $sourceID,
			ProductTable::COLUMN_STATUS => $status,
			ProductTable::COLUMN_PRODUCT => serialize($Product),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Product Entry Inserted: " . get_class($Product), $Request::VERBOSE);
		return $id;
	}

	static function delete($Request, $productID) {
		$delete = self::table()->delete(ProductTable::COLUMN_ID, $productID)
			->execute($Request);
		if(!$delete)
			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
	}

	/**
	 * @param $id
	 * @return ProductEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(ProductTable::COLUMN_ID, $id);
	}

	/**
	 * @return ProductTable
	 */
	static function table() {
		return new ProductTable();
	}

	/**
	 * @param ISessionRequest $Request
	 * @return ProductEntry[]
	 */
	public static function loadSessionProducts(ISessionRequest $Request) {
		$ProductTable = new ProductTable();
		return $ProductTable->fetchAll(1);
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\ProductTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}


}