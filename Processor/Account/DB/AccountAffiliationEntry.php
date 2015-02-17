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
use Processor\DB\ProcessorDB;


/**
 * Class AccountAffiliationEntry
 * @table account_affiliation
 */
class AccountAffiliationEntry implements IBuildable, IKeyMap
{
	const TYPE_NONE =               0x000;
	const TYPE_REQUEST_AFFILIATE =  0x001;
	const TYPE_REQUEST_RESELLER =   0x002;
	const TYPE_REQUEST_PROCESSOR =  0x004;

	const TYPE_AFFILIATE =          0x010;
	const TYPE_RESELLER =           0x020;
	const TYPE_PROCESSOR =          0x040;

	static $TypeOptions = array(
		"No affiliation" => self::TYPE_NONE,

		"Reseller Requested" => self::TYPE_REQUEST_RESELLER,
		"Affiliate Requested" => self::TYPE_REQUEST_AFFILIATE,
		"Processor Requested" => self::TYPE_REQUEST_AFFILIATE,

		"Reseller" => self::TYPE_RESELLER,
		"Affiliate" => self::TYPE_AFFILIATE,
		"Processor" => self::TYPE_PROCESSOR,
	);

	/**
	 * @column VARCHAR(64) NOT NULL
	 * @unique --name unique_affiliation
	 * @select
	 * @search
	 */
	protected $account;

	/**
	 * @column VARCHAR(64) NOT NULL
	 * @unique --name unique_affiliation
	 * @select
	 * @search
	 */
	protected $affiliate;

	/**
	 * @column TINYINT
	 * @select
	 * @insert
	 * @index
	 * @search
	 */
	protected $type;

	/**
	 * @column INT
	 * @select
	 * @insert
	 */
	protected $created;


	public function isReseller() {
		return (int)$this->type & self::TYPE_RESELLER;
	}

	public function isAffiliate() {
		return (int)$this->type & self::TYPE_AFFILIATE;
	}
	public function isAwaitingApproval() {
		return (int)$this->type & (self::TYPE_REQUEST_AFFILIATE | self::TYPE_REQUEST_RESELLER | self::TYPE_REQUEST_PROCESSOR);
	}

	public function getAccountID() { return $this->account; }
	public function getAffiliateID() { return $this->affiliate; }

	public function getCreatedTimestamp() { return $this->created; }

	public function getType() {
		return $this->type;
	}

	public function getTypeText() {
		return array_search($this->getType(), self::$TypeOptions);
	}


	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Map->map('account', $this->getAccountID());
		$Map->map('affiliate', $this->getAffiliateID());
		$Map->map('type', $this->getTypeText());
	}


	// Static

	static function queryAccountAffiliates($accountID, $typeFilter = null) {
		$Query = self::table()
			->where(AccountAffiliationTable::COLUMN_ACCOUNT, $accountID);
		if($typeFilter !== null)
			$Query->where(AccountAffiliationTable::COLUMN_TYPE, $typeFilter, '&?');
		return $Query;
	}

	static function approveAffiliation(IRequest $Request, $accountID, $affiliateID) {
		/** @var AccountAffiliationEntry $Affiliation */
		$Affiliation = self::table()
			->select()
			->where(AccountAffiliationTable::COLUMN_ACCOUNT, $accountID)
			->where(AccountAffiliationTable::COLUMN_AFFILIATE, $affiliateID)
			->fetchOne();

		switch($Affiliation->getType()) {
			case AccountAffiliationEntry::TYPE_REQUEST_RESELLER:
				$type = AccountAffiliationEntry::TYPE_RESELLER;
				break;

			case AccountAffiliationEntry::TYPE_REQUEST_AFFILIATE:
				$type = AccountAffiliationEntry::TYPE_AFFILIATE;
				break;

			case AccountAffiliationEntry::TYPE_REQUEST_PROCESSOR:
				$type = AccountAffiliationEntry::TYPE_PROCESSOR;
				break;

			default:
				throw new \InvalidArgumentException("No pending authorization found");
		}

		$update = self::table()
			->update(AccountAffiliationTable::COLUMN_TYPE, $type)
			->where(AccountAffiliationTable::COLUMN_ACCOUNT, $accountID)
			->where(AccountAffiliationTable::COLUMN_AFFILIATE, $affiliateID)
			->execute($Request);

		if(!$update)
			throw new \Exception("Affiliation update failed");
	}


	static function setAffiliate(IRequest $Request, $accountID, $affiliateID, $type = self::TYPE_AFFILIATE, $orUpdate=false) {
		AccountEntry::get($accountID);
		AccountEntry::get($affiliateID);
		$Table = self::table();
		$Insert = $Table->insert(
			AccountAffiliationTable::COLUMN_ACCOUNT,
			AccountAffiliationTable::COLUMN_AFFILIATE,
			AccountAffiliationTable::COLUMN_TYPE
		)->values(
			$accountID,
			$affiliateID,
			$type
		);

		if($orUpdate)
			$Insert->onDuplicateKeyUpdate(AccountAffiliationTable::COLUMN_TYPE, $type);
		$Insert->execute($Request);
	}

	/**
	 * @return AccountAffiliationTable
	 */
	static function table() {
		return new AccountAffiliationTable();
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\AccountAffiliationTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}

}