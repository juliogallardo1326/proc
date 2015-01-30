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
use Processor\DB\Schema\Tables\AccountTable;


/**
 * Class AccountEntry
 * @table account
 */
class AccountEntry implements IBuildable
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
	protected $email;

	/**
	 * @column VARCHAR(64)
	 * @select
	 * @update
	 */
	protected $password;

	/**
	 * @column VARCHAR(64)
	 */
	protected $password_salt;

	public function getID() {
		return $this->id;
	}


	// Static

	static function create(IRequest $Request, $email, $password) {
		$id = uniqid('account-');
		$salt = uniqid('salt-');

		$inserted = self::table()->insert(array(
			AccountTable::COLUMN_ID => $id,
			AccountTable::COLUMN_EMAIL => $email,
			AccountTable::COLUMN_PASSWORD => crypt($password, $salt),
			AccountTable::COLUMN_PASSWORD_SALT => $salt,
			AccountTable::COLUMN_STATUS => 0,
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\Tables\AccountTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}