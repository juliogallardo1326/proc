<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\PaymentSource\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use Processor\DB\ProcessorDB;
use Processor\PaymentSource\Sources\AbstractPaymentSource;

/**
 * Class PaymentSourceEntry
 * @table payment
 */
class PaymentSourceEntry implements IBuildable, IKeyMap
{
	const STATUS_ACTIVE = 0x01;
	const STATUS_INACTIVE = 0x02;

	static $StatusOptions = array(
		"Active" => self::STATUS_ACTIVE,
		"Inactive" => self::STATUS_INACTIVE,
	);
	
	/**
	 * @column VARCHAR(64) PRIMARY KEY
	 * @select
	 */
	protected $id;

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
	protected $source;


	public function getID() {
		return $this->id;
	}

	public function getStatus() {
		return (int)$this->status;
	}

	public function hasStatus($flags) {
		return $this->getStatus() & $flags;
	}

	public function getStatusText() {
		return array_search($this->getStatus(), self::$StatusOptions);
	}


	/**
	 * @return AbstractPaymentSource
	 */
	public function getPaymentSource() {
		if(is_string($this->source))
			$this->source = unserialize($this->source);
		return $this->source;
	}

	function update($Request, AbstractPaymentSource $PaymentSource, $status=null) {
		$update = array(
			PaymentSourceTable::COLUMN_SOURCE => serialize($PaymentSource),
		);
		$status === null ?: $update[PaymentSourceTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(PaymentSourceTable::COLUMN_ID, $this->id)
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}

	// Static

	static function create(IRequest $Request, AbstractPaymentSource $PaymentSource, $status=0) {
		$id = uniqid('psource-');

		$inserted = self::table()->insert(array(
			PaymentSourceTable::COLUMN_ID => $id,
			PaymentSourceTable::COLUMN_STATUS => $status,
			PaymentSourceTable::COLUMN_SOURCE => serialize($PaymentSource),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New PaymentSource Entry Inserted: " . get_class($PaymentSource), $Request::VERBOSE);
		return $id;
	}

	static function delete($Request, $paymentSourceID) {
		$delete = self::table()->delete(PaymentSourceTable::COLUMN_ID, $paymentSourceID)
			->execute($Request);
		if(!$delete)
			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
	}

	/**
	 * @param $id
	 * @return PaymentSourceEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(PaymentSourceTable::COLUMN_ID, $id);
	}

	/**
	 * @return PaymentSourceTable
	 */
	static function table() {
		return new PaymentSourceTable();
	}

	/**
	 * @param ISessionRequest $Request
	 * @return PaymentSourceEntry[]
	 */
	public static function loadSessionPaymentSources(ISessionRequest $Request) {
		$PaymentSourceTable = new PaymentSourceTable();
		return $PaymentSourceTable->fetchAll(1);
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\PaymentSourceTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Map->map('payment-source-id', $this->getID());
		$Map->map('status', $this->getStatusText());
		$Source = $this->getPaymentSource();
		$Source->mapKeys($Map);
	}
}