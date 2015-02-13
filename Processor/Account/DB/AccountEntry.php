<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\Account\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use Processor\Account\Types\AbstractAccountType;
use Processor\DB\ProcessorDB;


/**
 * Class AccountEntry
 * @table account
 */
class AccountEntry implements IBuildable, IKeyMap
{
	const STATUS_NEEDS_APPROVAL = 0x00;
	const STATUS_ACTIVE = 0x01;
	const STATUS_INACTIVE = 0x02;
	const ID_PREFIX = 'A';
	const SESSION_KEY = 'session_account';

	static $StatusOptions = array(
		"Needs Approval" => self::STATUS_NEEDS_APPROVAL,
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
	 * @column INT
	 * @select
	 * @insert
	 */
	protected $created;

	/**
	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
//	protected $email;

	/**
	 * @column VARCHAR(64)
	 * @select
	 * @update
	 */
//	protected $password;

	/**
	 * @column TEXT
	 * @select
	 * @insert
	 * @update
	 */
	protected $account;

	/**
	 * @column VARCHAR(64)
	 */
//	protected $password_salt;

	public function getID() {
		return $this->id;
	}

	public function getStatus() {
		return (int)$this->status;
	}

	public function hasStatus($flags) {
		return $this->status & $flags;
	}

	public function getStatusText() {
		return array_search($this->getStatus(), self::$StatusOptions);
	}

	public function getCreatedTimestamp() {
		return $this->created;
	}

	/**
	 * @return AbstractAccountType
	 */
	public function getAccount() {
		if(is_string($this->account))
			$this->account = unserialize($this->account);
		return $this->account;
	}

	public function update($Request, AbstractAccountType $Account, $status) {
		$Account->setID($this->getID());
		$update = array(
			AccountTable::COLUMN_ACCOUNT => serialize($Account),
		);
		$status === null ?: $update[AccountTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(AccountTable::COLUMN_ID, $this->getID())
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Map->map('account-id', $this->getID());
		$this->getAccount()->mapKeys($Map);
		$Map->map('created', $this->getCreatedTimestamp());
		$Map->map('status', $this->getStatus(), $this->getStatusText());
	}

	// Static

	static function create(IRequest $Request, AbstractAccountType $Account) {
		$id = strtoupper(uniqid(self::ID_PREFIX));

		$Account->setID($id);
		$inserted = self::table()->insert(array(
			AccountTable::COLUMN_ID => $id,
			AccountTable::COLUMN_STATUS => 0,
			AccountTable::COLUMN_CREATED => time(),
			AccountTable::COLUMN_ACCOUNT => serialize($Account),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Account Entry Inserted: " . $id, $Request::VERBOSE);
		return $id;
	}

//	static function update($Request, $walletID, $Account, $name=null, $status=null) {
//		$update = array(
//			AccountTable::COLUMN_WALLET => serialize($Account),
//		);
//		$name === null ?: $update[AccountTable::COLUMN_NAME] = $name;
//		$status === null ?: $update[AccountTable::COLUMN_STATUS] = $status;
//		$update = self::table()->update($update)
//			->where(AccountTable::COLUMN_ID, $walletID)
//			->execute($Request);
//		if(!$update)
//			throw new \InvalidArgumentException("Could not update " . __CLASS__);
//	}


	static function delete($Request, $accountID) {
		$delete = self::table()->delete(AccountTable::COLUMN_ID, $accountID)
			->execute($Request);
		if(!$delete)
			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
	}

	/**
	 * @param $id
	 * @return AccountEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(AccountTable::COLUMN_ID, $id);
	}

	/**
	 * @return AccountTable
	 */
	static function table() {
		return new AccountTable();
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\AccountTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}