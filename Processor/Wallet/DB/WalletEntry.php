<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\Wallet\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use Processor\DB\ProcessorDB;
use Processor\Wallet\Type\AbstractWallet;


/**
 * Class WalletEntry
 * @table wallet
 */
class WalletEntry implements IBuildable, IKeyMap
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
	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
	protected $hash;

	/**
	 * @column VARCHAR(64)
	 * @select
	 * @insert
	 */
	protected $email;

	/**
	 * @column TEXT
	 * @select
	 * @insert
	 */
	protected $wallet;



	public function getID() {
		return $this->id;
	}

	public function getStatus() {
		return $this->status;
	}

	public function getStatusText() {
		return array_search($this->getStatus(), self::$StatusOptions);
	}

	public function hasStatus($flags) {
		return $this->status & $flags;
	}

	public function getEmail() {
		return $this->email;
	}

	public function getHash() {
		return $this->hash;
	}

	function update($Request, $Wallet, $status=null) {
		$update = array(
			WalletTable::COLUMN_WALLET => serialize($Wallet),
		);
		$status === null ?: $update[WalletTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(WalletTable::COLUMN_ID, $this->id)
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}

	/**
	 * @return AbstractWallet
	 */
	public function getWallet() {
		if(is_string($this->wallet))
			$this->wallet = unserialize($this->wallet);
		return $this->wallet;
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Wallet = $this->getWallet();

		$Map->map('wallet-id', $this->getID());
		$Map->map('email', $this->getEmail()); // , $Product->getTitle());
		$Map->map('hash', $this->getHash());
		$Map->map('status', $this->getStatus(), $this->getStatusText());
		$Wallet->mapKeys($Map);
	}

	// Static

	static function create(IRequest $Request, AbstractWallet $Wallet, $email) {
		$id = uniqid('wallet-');

		$inserted = self::table()->insert(array(
			WalletTable::COLUMN_ID => $id,
			WalletTable::COLUMN_EMAIL => $email,
			WalletTable::COLUMN_STATUS => 0,
			WalletTable::COLUMN_WALLET => serialize($Wallet),
		))
		->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Wallet Entry Inserted: " . $id, $Request::VERBOSE);
		return $id;
	}

	static function createOrUpdate(IRequest $Request, AbstractWallet $ChosenWallet, $email=null, $status=null) {
		$Entry = self::findWalletEntry($ChosenWallet);
		if(!$Entry)
			return self::create($Request, $ChosenWallet, $email);

		$Entry->update($Request, $ChosenWallet, $status);
		$Request->log("Wallet Entry Updated: " . $Entry->getID(), $Request::VERBOSE);
		return $Entry->getID();
	}

	static function delete($Request, $walletID) {
		$delete = self::table()->delete(WalletTable::COLUMN_ID, $walletID)
			->execute($Request);
		if(!$delete)
			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
	}

	/**
	 * @param AbstractWallet $Wallet
	 * @return WalletEntry|null
	 */
	static function findWalletEntry(AbstractWallet $Wallet) {
		$hash = $Wallet->getWalletHash();
		return self::table()->fetch(WalletTable::COLUMN_HASH, $hash);
	}

	/**
	 * @param $id
	 * @return WalletEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(WalletTable::COLUMN_ID, $id);
	}

	/**
	 * @return WalletTable
	 */
	static function table() {
		return new WalletTable();
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\WalletTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}