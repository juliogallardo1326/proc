<?php
namespace Processor\Account\DB;

use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use Processor\DB\ProcessorDB as DB;
use Processor\Account\DB\AccountEntry as Entry;
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
	const FETCH_CLASS = 'Processor\\Account\\DB\\AccountEntry';
	const SELECT_COLUMNS = 'id, status, created, email, name, account';
	const UPDATE_COLUMNS = 'account';
	const INSERT_COLUMNS = 'status, created, email, name, account';
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

	 * @column INT
	 * @select
	 * @insert
	 */
	const COLUMN_CREATED = 'created';
	/**

	 * @column VARCHAR(64) NOT NULL
	 * @select
	 * @insert
	 * @unique
	 */
	const COLUMN_EMAIL = 'email';
	/**

	 * @column VARCHAR(64) NOT NULL
	 * @select
	 * @insert
	 * @unique
	 */
	const COLUMN_NAME = 'name';
	/**

	 * @column TEXT
	 * @select
	 * @insert
	 * @update
	 */
	const COLUMN_ACCOUNT = 'account';
	/**

	 * @index UNIQUE
	 * @columns email
	 */
	const ACCOUNT_EMAIL_UNIQUE = 'account_email_unique';
	/**

	 * @index UNIQUE
	 * @columns name
	 */
	const ACCOUNT_NAME_UNIQUE = 'account_name_unique';

	function insertRow($status = null, $created = null, $email = null, $name = null, $account = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Account\\DB\\AccountEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}