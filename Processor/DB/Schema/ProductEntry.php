<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\DB\Schema;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use Processor\DB\ProcessorDB;
use Processor\DB\Schema\Tables\ProductTable;
use Processor\Framework\Product\AbstractProduct;
use Processor\Framework\Product\ShippingProduct;


/**
 * Class ProductEntry
 * @table wallet
 */
class ProductEntry implements IBuildable
{
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
	protected $title;

	/**
	 * @column ENUM('subscription', 'debit', 'ach')
	 * @select
	 * @insert
	 */
	protected $type;

	/**
	 * @column TEXT
	 * @select
	 * @insert
	 */
	protected $product;


	public function getID() {
		return $this->id;
	}

	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return AbstractProduct
	 */
	public function getProductInstance() {
		if(is_string($this->product))
			$this->product = unserialize($this->product);
		return $this->product;
	}


	// Static

	static function create(IRequest $Request, AbstractProduct $Product, $account_id, $title) {
		$id = uniqid('wallet-');

		$inserted = self::table()->insert(array(
			ProductTable::COLUMN_ID => $id,
			ProductTable::COLUMN_ACCOUNT_ID => $account_id,
			ProductTable::COLUMN_TITLE => $title,
			ProductTable::COLUMN_STATUS => 0,
			ProductTable::COLUMN_PRODUCT => serialize($Product),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Product Entry Inserted: " . get_class($Product), $Request::VERBOSE);
		return $id;
	}

	static function update($Request, $productID, $Product, $title=null) {
		$update = array(
			ProductTable::COLUMN_PRODUCT => serialize($Product),
		);
		$title === null ?: $update[ProductTable::COLUMN_TITLE] = $title;
		$update = self::table()->update($update)
			->where(ProductTable::COLUMN_ID, $productID)
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
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
		$Entry = new ProductEntry();
		$Entry->id = 'testproduct';
		$Entry->title = "$9.99 USD - MyProduct";
		$Entry->product = new ShippingProduct('9.99');
		return array(
			$Entry
		);
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\Tables\ProductTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}

}