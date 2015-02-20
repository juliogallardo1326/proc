<?php
namespace Processor\Account\DB;

use CPath\Data\Schema\PDO\AbstractPDOTable as AbstractBase;
use Processor\DB\ProcessorDB as DB;
use Processor\Account\DB\AccountAffiliationEntry as Entry;
use CPath\Data\Schema\TableSchema;
use CPath\Data\Schema\IReadableSchema;

/**
 * Class AccountAffiliationTable
 * @table account_affiliation
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a AccountAffiliationEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single AccountAffiliationEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of AccountAffiliationEntry[]
 */
class AccountAffiliationTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'account_affiliation';
	const FETCH_CLASS = 'Processor\\Account\\DB\\AccountAffiliationEntry';
	const SELECT_COLUMNS = 'account, affiliate, type, created';
	const INSERT_COLUMNS = 'type, created';
	const SEARCH_COLUMNS = 'account, affiliate, type';
	/**

	 * @column VARCHAR(64) NOT NULL
	 * @unique --name unique_affiliation
	 * @select
	 * @search
	 */
	const COLUMN_ACCOUNT_ID = 'account_id';
	/**

	 * @column VARCHAR(64) NOT NULL
	 * @unique --name unique_affiliation
	 * @select
	 * @search
	 */
	const COLUMN_AFFILIATE_ID = 'affiliate_id';
	/**

	 * @column TINYINT
	 * @select
	 * @insert
	 * @index
	 * @search
	 */
	const COLUMN_TYPE = 'type';
	/**

	 * @column INT
	 * @select
	 * @insert
	 */
	const COLUMN_CREATED = 'created';
	/**

	 * @index UNIQUE
	 * @columns account, affiliate
	 */
	const UNIQUE_AFFILIATION = 'unique_affiliation';
	/**

	 * @index 
	 * @columns type
	 */
	const ACCOUNT_AFFILIATION_TYPE_INDEX = 'account_affiliation_type_index';

	function insertRow($type = null, $created = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Account\\DB\\AccountAffiliationEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}