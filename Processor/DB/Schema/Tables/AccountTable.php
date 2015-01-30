<?php
namespace Processor\DB\Schema\Tables;

use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use Processor\DB\ProcessorDB as DB;
use Processor\DB\Schema\AccountEntry as Entry;
use CPath\Data\Schema\TableSchema;
use CPath\Data\Schema\IReadableSchema;

/**
 * Class AccountTable
 * @table account
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a AccountEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a AccountEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a AccountEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single AccountEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of AccountEntry[]
 */
class AccountTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'account';
	const FETCH_CLASS = 'Processor\\DB\\Schema\\AccountEntry';
	const SELECT_COLUMNS = 'id, status, email, password';
	const UPDATE_COLUMNS = 'password';
	const INSERT_COLUMNS = 'status, email';
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
	const COLUMN_EMAIL = 'email';
	/**

	 * @column VARCHAR(64)
	 * @select
	 * @update
	 */
	const COLUMN_PASSWORD = 'password';
	/**

	 * @column VARCHAR(64)
	 */
	const COLUMN_PASSWORD_SALT = 'password_salt';

	function insertRow($status = null, $email = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\DB\\Schema\\AccountEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}