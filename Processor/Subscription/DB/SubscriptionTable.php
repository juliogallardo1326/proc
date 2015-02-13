<?php
namespace Processor\Subscription\DB;

use CPath\Data\Schema\IReadableSchema;
use CPath\Data\Schema\PDO\AbstractPDOPrimaryKeyTable as AbstractBase;
use CPath\Data\Schema\TableSchema;
use Processor\DB\ProcessorDB as DB;
use Processor\Subscription\DB\SubscriptionEntry as Entry;

/**
 * Class SubscriptionTable
 * @table subscription
 * @method Entry insertOrUpdate($id, Array $insertData) insert or update a SubscriptionEntry instance
 * @method Entry insertAndFetch(Array $insertData) insert and fetch a SubscriptionEntry instance
 * @method Entry fetch($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a SubscriptionEntry instance
 * @method Entry fetchOne($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch a single SubscriptionEntry
 * @method Entry[] fetchAll($whereColumn, $whereValue=null, $compare='=?', $selectColumns=null) fetch an array of SubscriptionEntry[]
 */
class SubscriptionTable extends AbstractBase implements IReadableSchema {
	const TABLE_NAME = 'subscription';
	const FETCH_CLASS = 'Processor\\Subscription\\DB\\SubscriptionEntry';
	const SELECT_COLUMNS = 'id, wallet_id, status, subscription';
	const INSERT_COLUMNS = 'wallet_id, status, subscription';
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
	const COLUMN_WALLET_ID = 'wallet_id';
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
	const COLUMN_SUBSCRIPTION = 'subscription';
	/**

	 * @index 
	 * @columns wallet_id
	 */
	const SUBSCRIPTION_WALLET_ID_INDEX = 'subscription_wallet_id_index';

	function insertRow($wallet_id = null, $status = null, $subscription = null) { 
		return $this->insert(get_defined_vars());
	}

	function getSchema() { return new TableSchema('Processor\\Subscription\\DB\\SubscriptionEntry'); }

	private $mDB = null;
	function getDatabase() { return $this->mDB ?: $this->mDB = new DB(); }
}