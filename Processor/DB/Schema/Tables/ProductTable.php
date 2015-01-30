<?php
namespace Processor\DB\Schema\Tables;

use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use Processor\DB\ProcessorDB as DB;
use Processor\DB\Schema\ProductEntry as Entry;
use CPath\Data\Schema\TableSchema;
use CPath\Data\Schema\IReadableSchema;

/**
 * Class ProductTable
 * @table wallet
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a ProductEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a ProductEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a ProductEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single ProductEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of ProductEntry[]
 */
class ProductTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'wallet';
	const FETCH_CLASS = 'Processor\\DB\\Schema\\ProductEntry';
	const SELECT_COLUMNS = 'id, account_id, status, title, type, product';
	const INSERT_COLUMNS = 'account_id, status, title, type, product';
	const PRIMARY_COLUMN = 'id';
	/**

	 * @column VARCHAR(64) PRIMARY KEY
	 * @select
	 */
	const COLUMN_ID = 'id';
	/**

	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	const COLUMN_ACCOUNT_ID = 'account_id';
	/**

	 * @column TINYINT
	 * @select
	 * @insert
	 */
	const COLUMN_STATUS = 'status';
	/**

	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
	const COLUMN_TITLE = 'title';
	/**

	 * @column ENUM('subscription', 'debit', 'ach')
	 * @select
	 * @insert
	 */
	const COLUMN_TYPE = 'type';
	/**

	 * @column TEXT
	 * @select
	 * @insert
	 */
	const COLUMN_PRODUCT = 'product';
	/**

	 * @index 
	 * @columns account_id
	 */
	const WALLET_ACCOUNT_ID_INDEX = 'wallet_account_id_index';

	function insertRow($account_id = null, $status = null, $title = null, $type = null, $product = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\DB\\Schema\\ProductEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}