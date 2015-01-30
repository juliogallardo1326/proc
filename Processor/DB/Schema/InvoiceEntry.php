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
use Processor\DB\Schema\Tables\InvoiceTable;
use Processor\Framework\Product\Invoice\AbstractInvoice;


/**
 * Class InvoiceEntry
 * @table wallet
 */
class InvoiceEntry implements IBuildable
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
	protected $title;

	/**
	 * @column TEXT
	 * @insert
	 */
	protected $invoice;

	public function getID() {
		return $this->id;
	}


	// Static

	static function create(IRequest $Request, AbstractInvoice $Invoice, $title) {
		$id = uniqid('wallet-');

		$inserted = self::table()->insert(array(
			InvoiceTable::COLUMN_ID => $id,
			InvoiceTable::COLUMN_TITLE => $title,
			InvoiceTable::COLUMN_STATUS => 0,
			InvoiceTable::COLUMN_INVOICE => serialize($Invoice),
		))
			->execute($Request);

		if(!$inserted)
			throw new \InvalidArgumentException("Could not insert " . __CLASS__);
		$Request->log("New Invoice Entry Inserted: " . get_class($Invoice), $Request::VERBOSE);
		return $id;
	}

	/**
	 * @param $id
	 * @return InvoiceEntry
	 */
	static function get($id) {
		return self::table()->fetchOne(InvoiceTable::COLUMN_ID, $id);
	}

	/**
	 * @return InvoiceTable
	 */
	static function table() {
		return new InvoiceTable();
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
		$ClassWriter = new PDOTableClassWriter($DB, __NAMESPACE__ . '\Tables\InvoiceTable', __CLASS__);
		$Schema->writeSchema($ClassWriter);
		$DBWriter = new PDOTableWriter($DB);
		$Schema->writeSchema($DBWriter);
	}
}