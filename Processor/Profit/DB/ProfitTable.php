<?php
namespace Processor\Profit\DB;

use CPath\Data\Schema\IReadableSchema;
use CPath\Data\Schema\PDO\AbstractPDOTable as AbstractBase;
use CPath\Data\Schema\TableSchema;
use Processor\DB\ProcessorDB as DB;
use Processor\Profit\DB\ProfitEntry as Entry;

/**
 * Class ProfitTable
 * @table profit
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a ProfitEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single ProfitEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of ProfitEntry[]
 */
class ProfitTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'profit';
	const FETCH_CLASS = 'Processor\\Profit\\DB\\ProfitEntry';
	const SELECT_COLUMNS = 'transaction_id, account_id, profit';
	const UPDATE_COLUMNS = 'profit';
	const INSERT_COLUMNS = 'account_id, profit';
	/**

	 * @column VARCHAR(64)
	 * @unique --name unique_profit_transaction_id_account_id
	 * @select
	 */
	const COLUMN_TRANSACTION_ID = 'transaction_id';
	/**

	 * @column VARCHAR(64)
	 * @unique --name unique_profit_transaction_id_account_id
	 * @select
	 * @insert
	 */
	const COLUMN_ACCOUNT_ID = 'account_id';
	/**

	 * @column  NUMERIC(15,2)
	 * @select
	 * @update
	 * @insert
	 */
	const COLUMN_PROFIT = 'profit';
	/**

	 * @index UNIQUE
	 * @columns transaction_id, account_id
	 */
	const UNIQUE_PROFIT_TRANSACTION_ID_ACCOUNT_ID = 'unique_profit_transaction_id_account_id';

	function insertRow($account_id = null, $profit = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Profit\\DB\\ProfitEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}