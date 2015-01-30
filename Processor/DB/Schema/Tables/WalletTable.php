<?php
namespace Processor\DB\Schema\Tables;

use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use Processor\DB\ProcessorDB as DB;
use Processor\DB\Schema\WalletEntry as Entry;
use CPath\Data\Schema\TableSchema;
use CPath\Data\Schema\IReadableSchema;

/**
 * Class WalletTable
 * @table wallet
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a WalletEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a WalletEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a WalletEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single WalletEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of WalletEntry[]
 */
class WalletTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'wallet';
	const FETCH_CLASS = 'Processor\\DB\\Schema\\WalletEntry';
	const SELECT_COLUMNS = 'id, status, name, email, wallet';
	const INSERT_COLUMNS = 'status, name, email, wallet';
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
	const COLUMN_NAME = 'name';
	/**

	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
	const COLUMN_EMAIL = 'email';
	/**

	 * @column TEXT
	 * @select
	 * @insert
	 */
	const COLUMN_WALLET = 'wallet';

	function insertRow($status = null, $name = null, $email = null, $wallet = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\DB\\Schema\\WalletEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}