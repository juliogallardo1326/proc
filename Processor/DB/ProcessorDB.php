<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 12/19/2014
 * Time: 4:02 PM
 */
namespace Processor\DB;

use CPath\Data\Schema\IReadableSchema;
use CPath\Data\Schema\IRepairableSchema;
use CPath\Data\Schema\IWritableSchema;
use CPath\Data\Schema\PDO\AbstractPDOTable;
use PDO;
use Processor\Account\DB\AccountTable;
use Processor\PaymentSource\DB\PaymentSourceTable;
use Processor\Product\DB\ProductTable;
use Processor\Subscription\DB\SubscriptionTable;
use Processor\Transaction\DB\TransactionTable;
use Processor\Wallet\DB\WalletTable;

class ProcessorDB extends \PDO implements IReadableSchema, IRepairableSchema
{
	public function __construct($options = null) {
		$host     = DBConfig::$DB_HOST;
		$dbname   = DBConfig::$DB_NAME;
		$port     = DBConfig::$DB_PORT;
		$username = DBConfig::$DB_USERNAME;
		$passwd   = DBConfig::$DB_PASSWORD;
//		CREATE DATABASE `processor` /*!40100 COLLATE 'utf8_general_ci' */

		$options ?: $options = array(
			\PDO::ATTR_PERSISTENT => true,
		);

		try {
			parent::__construct("mysql:host={$host};port={$port};dbname={$dbname}", $username, $passwd, $options);
		} catch (\PDOException $ex) {
			if(strpos($ex->getMessage(), 'Unknown database') !== false) {
				$PDO = new PDO("mysql:host={$host};port={$port}", $username, $passwd, $options);
				$PDO->query("create database {$dbname}");
				$PDO->query("use {$dbname}");
				parent::__construct("mysql:host={$host};port={$port};dbname={$dbname}", $username, $passwd, $options);
			} else {
				throw $ex;
			}
		}

		$this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
//		$this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
	}

	/**
	 * Write schema to a writable source
	 * @param IWritableSchema $DB
	 */
	public function writeSchema(IWritableSchema $DB) {
		foreach(
			array(
				new TransactionTable(),
				new SubscriptionTable(),
				new WalletTable(),
				new AccountTable(),
				new ProductTable(),
				new PaymentSourceTable(),
			) as $Table) {
			/** @var AbstractPDOTable $Table */
			$Table->writeSchema($DB);
		}
	}

	/**
	 * Attempt to repair a writable schema
	 * @param IWritableSchema $DB
	 * @param \Exception $ex
	 */
	public function repairSchema(IWritableSchema $DB, \Exception $ex) {
		$this->writeSchema($DB);
	}
}