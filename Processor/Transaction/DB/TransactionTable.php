<?php
namespace Processor\Transaction\DB;

use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use Processor\DB\ProcessorDB as DB;
use Processor\Transaction\DB\TransactionEntry as Entry;
use CPath\Data\Schema\TableSchema;
use CPath\Data\Schema\IReadableSchema;

/**
 * Class TransactionTable
 * @table transaction
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a TransactionEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a TransactionEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a TransactionEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single TransactionEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of TransactionEntry[]
 */
class TransactionTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'transaction';
	const FETCH_CLASS = 'Processor\\Transaction\\DB\\TransactionEntry';
	const SELECT_COLUMNS = 'id, status, amount, wallet_id, payment_source_id, product_id, invoice';
	const INSERT_COLUMNS = 'status, amount, wallet_id, payment_source_id, product_id, invoice';
	const PRIMARY_COLUMN = 'id';
	/**

	 * @column VARCHAR(64) PRIMARY KEY
	 * @select
	 */
	const COLUMN_ID = 'id';
	/**

	 * @column TINYINT
	 * @select
	 * @insert
	 */
	const COLUMN_STATUS = 'status';
	/**

	 * @column VARCHAR(16)
	 * @select
	 * @insert
	 */
	const COLUMN_AMOUNT = 'amount';
	/**

	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	const COLUMN_WALLET_ID = 'wallet_id';
	/**

	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	const COLUMN_PAYMENT_SOURCE_ID = 'payment_source_id';
	/**

	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	const COLUMN_PRODUCT_ID = 'product_id';
	/**

	 * @column TEXT
	 * @select
	 * @insert
	 */
	const COLUMN_INVOICE = 'invoice';
	/**

	 * @index 
	 * @columns wallet_id
	 */
	const TRANSACTION_WALLET_ID_INDEX = 'transaction_wallet_id_index';
	/**

	 * @index 
	 * @columns payment_source_id
	 */
	const TRANSACTION_PAYMENT_SOURCE_ID_INDEX = 'transaction_payment_source_id_index';
	/**

	 * @index 
	 * @columns product_id
	 */
	const TRANSACTION_PRODUCT_ID_INDEX = 'transaction_product_id_index';

	function insertRow($status = null, $amount = null, $wallet_id = null, $payment_source_id = null, $product_id = null, $invoice = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Transaction\\DB\\TransactionEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}