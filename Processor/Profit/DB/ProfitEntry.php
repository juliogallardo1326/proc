<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\Profit\DB;
use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Data\Schema\PDO\PDOTableClassWriter;
use CPath\Data\Schema\PDO\PDOTableWriter;
use CPath\Data\Schema\TableSchema;
use CPath\Request\IRequest;
use Processor\DB\ProcessorDB;
use Processor\Transaction\DB\TransactionEntry;

/**
 * Class ProfitEntry
 * @table profit
 */
class ProfitEntry implements IBuildable
{
	/**
	 * @column VARCHAR(64)
	 * @unique --name unique_profit_transaction_id_account_id
	 * @select
	 */
	protected $transaction_id;

	/**
	 * @column VARCHAR(64)
	 * @unique --name unique_profit_transaction_id_account_id
	 * @select
	 * @insert
	 */
	protected $account_id;

	/**
	 * @column  NUMERIC(15,2)
	 * @select
	 * @update
	 * @insert
	 */
	protected $profit;


	public function getTransactionID() {
		return $this->transaction_id;
	}

	public function getAccountID() {
		return $this->account_id;
	}

	public function getProfit() {
		return $this->profit ?: '0.00';
	}

	// Static

	static function update(IRequest $Request, $id) {
		$TransactionEntry = TransactionEntry::get($id);

		$accounts = array();
		$Invoice = $TransactionEntry->getInvoice();
		$Product = $Invoice->getProduct();
		$merchantProfit = $Product->calculateProfit($TransactionEntry->getStatus(), $accounts);

		$accounts[$Product->getProductID()] = $merchantProfit;

		foreach($accounts as $accountID => $profit) {
			$Table = self::table();
			$Table->insert(
				ProfitTable::COLUMN_TRANSACTION_ID,
				ProfitTable::COLUMN_ACCOUNT_ID,
				ProfitTable::COLUMN_PROFIT
			)->values(
				$TransactionEntry->getID(),
				$accountID,
				$profit
			)->onDuplicateKeyUpdate(ProfitTable::COLUMN_PROFIT, $merchantProfit)
				->execute($Request);
		}
	}

	/**
	 * @return ProfitTable
	 */
	static function table() {
		return new ProfitTable();
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\ProfitTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}


}