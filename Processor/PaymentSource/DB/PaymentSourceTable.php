<?php
namespace Processor\PaymentSource\DB;

use CPath\Data\Schema\IReadableSchema;
use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use CPath\Data\Schema\TableSchema;
use Processor\DB\ProcessorDB as DB;
use Processor\PaymentSource\DB\PaymentSourceEntry as Entry;

/**
 * Class PaymentSourceTable
 * @table payment
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a PaymentSourceEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a PaymentSourceEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a PaymentSourceEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single PaymentSourceEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of PaymentSourceEntry[]
 */
class PaymentSourceTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'payment';
	const FETCH_CLASS = 'Processor\\PaymentSource\\DB\\PaymentSourceEntry';
	const SELECT_COLUMNS = 'id, status, source';
	const INSERT_COLUMNS = 'status, source';
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

	 * @column TEXT
	 * @select
	 * @insert
	 */
	const COLUMN_SOURCE = 'source';

	function insertRow($status = null, $source = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\PaymentSource\\DB\\PaymentSourceEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}