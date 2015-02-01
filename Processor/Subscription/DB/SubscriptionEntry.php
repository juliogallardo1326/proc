<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\Subscription\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use Processor\DB\ProcessorDB;
use Processor\DB\Schema\AbstractSubscription;


/**
 * Class SubscriptionEntry
 * @table subscription
 */
class SubscriptionEntry implements IBuildable
{
	/**
	 * @column VARCHAR(64) PRIMARY KEY
	 * @select
	 */
	protected $id;

	/**
	 * @column VARCHAR(64)
	 * @index
	 * @select
	 * @insert
	 */
	protected $wallet_id;

	/**
	 * @column TINYINT
	 * @select
	 * @insert
	 */
	protected $status;

	/**
	 * @column TEXT
	 * @select
	 * @insert
	 */
	protected $subscription;

	public function getID() {
		return $this->id;
	}

	public function getStatus() {
		return (int)$this->status;
	}

	public function hasStatus($flags) {
		return $this->status & $flags;
	}

	// Static

	static function create(IRequest $Request, AbstractSubscription $Subscription, $wallet_id) {
		$id = uniqid('subscription-');

		$inserted = self::table()->insert(array(
			SubscriptionTable::COLUMN_ID => $id,
			SubscriptionTable::COLUMN_WALLET_ID => $wallet_id,
			SubscriptionTable::COLUMN_STATUS => 0,
			SubscriptionTable::COLUMN_SUBSCRIPTION => serialize($Subscription),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Subscription Entry Inserted: " . get_class($Subscription), $Request::VERBOSE);
		return $id;
	}

	static function update($Request, $subscriptionID, $Subscription, $status=null) {
		$update = array(
			SubscriptionTable::COLUMN_SUBSCRIPTION => serialize($Subscription),
		);
		$status === null ?: $update[SubscriptionTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(SubscriptionTable::COLUMN_ID, $subscriptionID)
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}


	static function delete($Request, $subscriptionID) {
		$delete = self::table()->delete(SubscriptionTable::COLUMN_ID, $subscriptionID)
			->execute($Request);
		if(!$delete)
			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
	}

	/**
	 * @param $id
	 * @return SubscriptionEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(SubscriptionTable::COLUMN_ID, $id);
	}

	/**
	 * @return SubscriptionTable
	 */
	static function table() {
		return new SubscriptionTable();
	}

	/**
	 * Handle this request and render any content
	 * @param IBuildRequest $Request the build request inst for this build session
	 * @return void
	 * @build --disable 0
	 * Note: Use doctag 'build' with '--disable 1' to have this IBuildable class skipped during a build
	 */
	static function handleBuildStatic(IBuildRequest $Request) {
		$Schema = new TableSchema(__CLASS__);
		$DB = new ProcessorDB();
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\SubscriptionTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}