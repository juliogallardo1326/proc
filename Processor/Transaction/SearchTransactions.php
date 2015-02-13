<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\Transaction;

use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLSubmit;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLPDOQueryTable;
use CPath\Render\HTML\Element\Table\HTMLPDOQueryTableBody;
use CPath\Render\HTML\Element\Table\HTMLSequenceTableBody;
use CPath\Render\HTML\Element\Table\HTMLTable;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Render\HTML\Pagination\HTMLPagination;
use CPath\Request\Exceptions\RequestException;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\Account\Types\AbstractAccountType;
use Processor\Account\Types\AdministratorAccount;
use Processor\Account\Types\MerchantAccount;
use Processor\PaymentSource\DB\PaymentSourceTable;
use Processor\PaymentSource\Sources\AbstractPaymentSource;
use Processor\Product\DB\ProductTable;
use Processor\SiteMap;
use Processor\Transaction\DB\TransactionEntry;
use Processor\Transaction\DB\TransactionTable;

class SearchTransactions implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Search Transactions';

	const FORM_ACTION = '/transactions';
	const FORM_ACTION2 = '/search/transactions';
	const FORM_METHOD = 'GET';
	const FORM_NAME = 'search-transaction';
	const CLS_TABLE_TRANSACTION_SEARCH = 'table-transaction-search';

//	const PARAM_FILTER_EMAIL = 'filter-email';
	const PARAM_FILTER_PRODUCT_ID = 'filter-product-id';
	const PARAM_FILTER_WALLET_ID = 'filter-wallet-id';
	const PARAM_FILTER_STATUS = 'filter-status';
	const PARAM_FILTER_PAYMENT_SOURCE_ID = 'filter-payment-source-id';
	const PARAM_FILTER_CREATE_DATE = 'filter-create-date';

	const PARAM_SORT_STATUS = 'sort-status';
	const PARAM_SORT_WALLET_ID = 'sort-wallet-id';
	const PARAM_SORT_PRODUCT_ID = 'sort-product-id';
	const PARAM_SORT_PAYMENT_SOURCE_ID = 'sort-payment-source-id';
	const PARAM_SORT_CREATE_DATE = 'sort-create-date';
	const PARAM_PAGE = 'page';

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @throws RequestException
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$SessionRequest = $Request;
		if (!$SessionRequest instanceof ISessionRequest)
			throw new RequestException("Session required");

		$page = 0;
		$total = null;
		$row_count = 5;
		if(isset($Request[self::PARAM_PAGE]))
			$page = $Request[self::PARAM_PAGE];
		$offset = $page * $row_count;

		$Pagination = new HTMLPagination($row_count, $page, $total);

		$Table = new TransactionTable();
		$SearchQuery = $Table
			->select()
			->limit("{$row_count} OFFSET {$offset}");

//		$SearchQuery->orderBy(TransactionTable::COLUMN_CREATED, "DESC");

		$SearchTable = new HTMLPDOQueryTable($SearchQuery);
		$SearchTable->addColumn('id', "transaction");
		$SearchTable->addColumn('product', "product");
		$SearchTable->addColumn('wallet', "wallet");

		$SearchTable->addColumn('created', "created");
		$SearchTable->addColumn('status', "status");
		$SearchTable->addColumn('amount', "amount");
		$SearchTable->addColumn('email', "email");
		$SearchTable->addColumn('product', "product");
		$SearchTable->addColumn('currency', "currency");

		$SearchTable->addSearchColumn(TransactionTable::COLUMN_ID, "transaction");
		$SearchTable->addSearchColumn(TransactionTable::COLUMN_WALLET_ID, "wallet");
		$SearchTable->addSearchColumn(TransactionTable::COLUMN_PRODUCT_ID, "product");

		$SearchTable->addSortColumn(TransactionTable::COLUMN_CREATED, "created");
		$SearchTable->addSortColumn(TransactionTable::COLUMN_STATUS, "status");
		$SearchTable->addSortColumn(TransactionTable::COLUMN_AMOUNT, "amount");

		$SearchTable->validateRequest($Request);


		$StatsQuery = $Table
			->select(TransactionTable::COLUMN_AMOUNT, 'count', 'COUNT(%s)')
			->select(TransactionTable::COLUMN_AMOUNT, 'total', 'SUM(%s)')

			->select(TransactionTable::COLUMN_STATUS, 'approves', 'SUM(%s = ' . TransactionEntry::STATUS_APPROVED . ')')
			->select(TransactionTable::COLUMN_STATUS, 'approves_total', 'SUM(IF(%s = ' . TransactionEntry::STATUS_APPROVED . ', ' . TransactionTable::COLUMN_AMOUNT . ', 0))')

			->select(TransactionTable::COLUMN_STATUS, 'pending', 'SUM(%s = ' . TransactionEntry::STATUS_PENDING . ')')
			->select(TransactionTable::COLUMN_STATUS, 'pending_total', 'SUM(IF(%s = ' . TransactionEntry::STATUS_PENDING . ', ' . TransactionTable::COLUMN_AMOUNT . ', 0))')

			->select(TransactionTable::COLUMN_STATUS, 'declines', 'SUM(%s = ' . TransactionEntry::STATUS_DECLINED . ')')
			->select(TransactionTable::COLUMN_STATUS, 'declines_total', 'SUM(IF(%s = ' . TransactionEntry::STATUS_DECLINED . ', ' . TransactionTable::COLUMN_AMOUNT . ', 0))')

			->select(TransactionTable::COLUMN_STATUS, 'refunds', 'SUM(%s = ' . TransactionEntry::STATUS_REFUNDED . ')')
			->select(TransactionTable::COLUMN_STATUS, 'refunds_total', 'SUM(IF(%s = ' . TransactionEntry::STATUS_REFUNDED . ', ' . TransactionTable::COLUMN_AMOUNT . ', 0))')

			->select(TransactionTable::COLUMN_STATUS, 'chargebacks', 'SUM(%s = ' . TransactionEntry::STATUS_CHARGE_BACK . ')')
			->select(TransactionTable::COLUMN_STATUS, 'chargebacks_total', 'SUM(IF(%s = ' . TransactionEntry::STATUS_CHARGE_BACK . ', ' . TransactionTable::COLUMN_AMOUNT . ', 0))')

			->select(TransactionTable::COLUMN_PAYMENT_SOURCE_ID, PaymentSourceTable::COLUMN_SOURCE,
				"(Select " . PaymentSourceTable::COLUMN_SOURCE
				. " FROM " . PaymentSourceTable::TABLE_NAME
				. " WHERE " . PaymentSourceTable::COLUMN_ID . '=' . TransactionTable::COLUMN_PAYMENT_SOURCE_ID
				. ")")

			->select(TransactionTable::COLUMN_PRODUCT_ID, "Product")

			->groupBy(TransactionTable::COLUMN_PAYMENT_SOURCE_ID . ', ' . TransactionTable::COLUMN_PRODUCT_ID)
			->limit(50)
			->addRowCallback(function(&$row) {
				/** @var AbstractPaymentSource $Source */
				$Source = unserialize($row[PaymentSourceTable::COLUMN_SOURCE]);
				unset($row[PaymentSourceTable::COLUMN_SOURCE]);

				$cur = $Source->getCurrency();

				$row['total '] = vsprintf('(%0d) <span class="total">%1.2f</span>', $row) . ' ' . $cur;
				unset($row['count'], $row['total']);

				$row['approves '] = vsprintf('(%0d) <span class="total">%1.2f</span>', $row) . ' ' . $cur;
				unset($row['approves'], $row['approves_total']);

				$row['pending '] = vsprintf('(%0d) <span class="total">%1.2f</span>', $row) . ' ' . $cur;
				unset($row['pending'], $row['pending_total']);

				$row['declines '] = vsprintf('(%0d) <span class="total">%1.2f</span>', $row) . ' ' . $cur;
				unset($row['declines'], $row['declines_total']);

				$row['refunds '] = vsprintf('(%0d) <span class="total">%1.2f</span>', $row) . ' ' . $cur;
				unset($row['refunds'], $row['refunds_total']);

				$row['chargebacks '] = vsprintf('(%0d) <span class="total">%1.2f</span>', $row) . ' ' . $cur;
				unset($row['chargebacks'], $row['chargebacks_total']);

			});

		$StatsTHead = new HTMLPDOQueryTableBody($StatsQuery);
		$StatsTBody = new HTMLSequenceTableBody($StatsQuery, self::CLS_TABLE_TRANSACTION_SEARCH);

		$Account = AbstractAccountType::loadFromSession($SessionRequest);
		if ($Account instanceof MerchantAccount) {
			$SearchQuery->where(TransactionTable::COLUMN_PRODUCT_ID, $Account->getID(),
				"IN (Select " . ProductTable::COLUMN_ID
				. "\n\tFROM " . ProductTable::TABLE_NAME
				. "\n\tWHERE " . ProductTable::COLUMN_ACCOUNT_ID . " = ?)"
			);
			$StatsQuery->where(TransactionTable::COLUMN_PRODUCT_ID, $Account->getID(),
				"IN (Select " . ProductTable::COLUMN_ID
				. "\n\tFROM " . ProductTable::TABLE_NAME
				. "\n\tWHERE " . ProductTable::COLUMN_ACCOUNT_ID . " = ?)"
			);

		} else if ($Account instanceof AdministratorAccount) {


//		} else if ($Account instanceof ProcessorAccount) {
//			$SearchQuery->where(TransactionTable::COLUMN_PAYMENT_SOURCE_ID, $Account->getID(),
//				"IN (Select " . PaymentSourceTable::COLUMN_ID
//				. "\n\tFROM " . PaymentSourceTable::TABLE_NAME
//				. "\n\tWHERE " . PaymentSourceTable::C. " = ?)"
//			);


		} else {
			$SearchQuery->where(TransactionTable::COLUMN_ID, '-1');

		}


		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '/assets/search-transaction.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/search-transaction.css'),

			new HTMLElement('fieldset', 'fieldset-search fieldset-filter-search',
				new HTMLElement('legend', 'legend-filter-search', self::TITLE),

				new HTMLElement('fieldset', 'fieldset-filter-stats-results',
					new HTMLElement('legend', 'legend-filter-stats-results', 'Stats'),


					new HTMLTable(
						$StatsTHead,
						$StatsTBody
					)
				),

				"<br/>",
				new HTMLElement('fieldset', 'fieldset-filter-search-results',
					new HTMLElement('legend', 'legend-filter-search-results', 'Search Results'),

					$SearchTable,
					$Pagination
				),

				"<br/>",
				new HTMLSubmit(null, 'Search')
			),
			"<br/>"
		);

		$Form->setFormValues($Request);

		return $Form;
	}

	// Static

	public static function getRequestURL() {
		return self::FORM_ACTION;
	}

	/**
	 * Route the request to this class object and return the object
	 * @param IRequest $Request the IRequest inst for this render
	 * @param array|null $Previous all previous response object that were passed from a handler, if any
	 * @param null|mixed $_arg [varargs] passed by route map
	 * @return void|bool|Object returns a response object
	 * If nothing is returned (or bool[true]), it is assumed that rendering has occurred and the request ends
	 * If false is returned, this static handler will be called again if another handler returns an object
	 * If an object is returned, it is passed along to the next handler
	 */
	static function routeRequestStatic(IRequest $Request, Array &$Previous = array(), $_arg = null) {
		return new ExecutableRenderer(new static(), true);
	}

	/**
	 * Handle this request and render any content
	 * @param IBuildRequest $Request the build request inst for this build session
	 * @return void
	 * @build --disable 0
	 * Note: Use doctag 'build' with '--disable 1' to have this IBuildable class skipped during a build
	 */
	static function handleBuildStatic(IBuildRequest $Request) {
		$RouteBuilder = new RouteBuilder($Request, new SiteMap());
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION, __CLASS__);
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__);
	}
}