<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\DB\Schema;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use Processor\DB\ProcessorDB;
use Processor\DB\Schema\Tables\WalletTable;
use Processor\Wallet\Type\AbstractWallet;


/**
 * Class WalletEntry
 * @table wallet
 */
class WalletEntry implements IBuildable
{
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
	protected $name;

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

	public function getEmail() {
		return $this->email;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * @return AbstractWallet
	 */
	public function getWallet() {
		if(is_string($this->wallet))
			$this->wallet = unserialize($this->wallet);
		return $this->wallet;
	}

	// Static

	static function create(IRequest $Request, AbstractWallet $Wallet, $name, $email) {
		$id = uniqid('wallet-');

		$inserted = self::table()->insert(array(
			WalletTable::COLUMN_ID => $id,
			WalletTable::COLUMN_NAME => $name,
			WalletTable::COLUMN_EMAIL => $email,
			WalletTable::COLUMN_STATUS => 0,
			WalletTable::COLUMN_WALLET => serialize($Wallet),
		))
		->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Wallet Entry Inserted: " . get_class($Wallet), $Request::VERBOSE);
		return $id;
	}

	static function update($Request, $walletID, $Wallet, $name=null, $status=null) {
		$update = array(
			WalletTable::COLUMN_WALLET => serialize($Wallet),
		);
		$name === null ?: $update[WalletTable::COLUMN_NAME] = $name;
		$status === null ?: $update[WalletTable::COLUMN_STATUS] = $status;
		$update = self::table()->update($update)
			->where(WalletTable::COLUMN_ID, $walletID)
			->execute($Request);
		if(!$update)
			throw new \InvalidArgumentException("Could not update " . __CLASS__);
	}


	static function delete($Request, $walletID) {
		$delete = self::table()->delete(WalletTable::COLUMN_ID, $walletID)
			->execute($Request);
		if(!$delete)
			throw new \InvalidArgumentException("Could not delete " . __CLASS__);
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\Tables\WalletTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}