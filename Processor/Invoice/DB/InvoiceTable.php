<?php
namespace Processor\Invoice\DB;

use CPath\Data\Schema\IReadableSchema;
use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use CPath\Data\Schema\TableSchema;
use Processor\DB\ProcessorDB as DB;
use Processor\Invoice\DB\InvoiceEntry as Entry;

/**
 * Class InvoiceTable
 * @table wallet
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a InvoiceEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a InvoiceEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a InvoiceEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single InvoiceEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of InvoiceEntry[]
 */
class InvoiceTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'wallet';
	const FETCH_CLASS = 'Processor\\Invoice\\DB\\InvoiceEntry';
	const SELECT_COLUMNS = 'id, status, title';
	const INSERT_COLUMNS = 'status, title, invoice';
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

	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
	const COLUMN_TITLE = 'title';
	/**

	 * @column TEXT
	 * @insert
	 */
	const COLUMN_INVOICE = 'invoice';

	function insertRow($status = null, $title = null, $invoice = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Invoice\\DB\\InvoiceEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}