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
use Processor\DB\ProcessorDB;
use Processor\DB\Schema\Tables\TransactionTable;
use Processor\Framework\Product\Invoice\AbstractInvoice;


/**
 * Class TransactionEntry
 * @table transaction
 */
class TransactionEntry implements IBuildable
{
	const STATUS_DECLINED = 0x01;
	const STATUS_PENDING = 0x02;
	const STATUS_APPROVED = 0x04;

	const STATUS_REFUNDED = 0x10;
	const STATUS_CHARGE_BACK = 0x20;

	static $StatusOptions = array(
		"Pending" => self::STATUS_PENDING,
		"Declined" => self::STATUS_DECLINED,
		"Approved" => self::STATUS_APPROVED,

		"Refunded" => self::STATUS_REFUNDED,
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
	 * @column INT
	 * @select
	 * @insert
	 */
	protected $amount;

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
	protected $product_id;

	/**
	 * @column TEXT
	 * @insert
	 */
	protected $invoice;

	public function getID() {
		return $this->id;
	}


	// Static

	static function create(IRequest $Request, AbstractInvoice $Invoice, $wallet_id, $product_id, $amount) {
		$id = uniqid('wallet-');

		$inserted = self::table()->insert(array(
			TransactionTable::COLUMN_ID => $id,
			TransactionTable::COLUMN_WALLET_ID => $wallet_id,
			TransactionTable::COLUMN_PRODUCT_ID => $product_id,
			TransactionTable::COLUMN_AMOUNT => $amount,
			TransactionTable::COLUMN_STATUS => 0,
			TransactionTable::COLUMN_INVOICE => serialize($Invoice),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Transaction Entry Inserted: " . $id, $Request::VERBOSE);
		return $id;
	}

	static function update($Request, $transactionID, AbstractInvoice $Invoice=null, $status=null) {
		$update = array();
		$Invoice === null ?: $update[TransactionTable::COLUMN_INVOICE] = serialize($Invoice);
		$status === null ?: $update[TransactionTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(TransactionTable::COLUMN_ID, $transactionID)
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\Tables\TransactionTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}