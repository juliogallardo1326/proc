<?php
namespace Processor\Product\DB;

use CPath\Data\Schema\IReadableSchema;
use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use CPath\Data\Schema\TableSchema;
use Processor\DB\ProcessorDB as DB;
use Processor\Product\DB\ProductEntry as Entry;

/**
 * Class ProductTable
 * @table product
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a ProductEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a ProductEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a ProductEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single ProductEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of ProductEntry[]
 */
class ProductTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'product';
	const FETCH_CLASS = 'Processor\\Product\\DB\\ProductEntry';
	const SELECT_COLUMNS = 'id, account_id, status, created, product';
	const INSERT_COLUMNS = 'account_id, status, created, product';
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

	 * @column INT
	 * @select
	 * @insert
	 */
	const COLUMN_CREATED = 'created';
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
	const PRODUCT_ACCOUNT_ID_INDEX = 'product_account_id_index';

	function insertRow($account_id = null, $status = null, $created = null, $product = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Product\\DB\\ProductEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}