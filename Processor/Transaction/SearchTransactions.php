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
use CPath\Render\HTML\Attribute\Attributes;
use CPath\Render\HTML\Element\Form\HTMLButton;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLSequenceTableBody;
use CPath\Render\HTML\Element\Table\HTMLTable;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\IRequest;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\PaymentSource\DB\PaymentSourceEntry;
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

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$Table = new TransactionTable();
		$SearchQuery = $Table
			->select()
			->limit(50);

		$SearchQuery->orderBy(TransactionTable::COLUMN_CREATED, "DESC");

		$SearchTBody = new HTMLSequenceTableBody($SearchQuery, self::CLS_TABLE_TRANSACTION_SEARCH);


		$StatsQuery = $Table
			->select(TransactionTable::COLUMN_AMOUNT, 'total', 'SUM(%s)')

			->select(TransactionTable::COLUMN_PAYMENT_SOURCE_ID, "Currency")
			->select(TransactionTable::COLUMN_PRODUCT_ID, "Product")

			->groupBy(TransactionTable::COLUMN_PAYMENT_SOURCE_ID . ', ' . TransactionTable::COLUMN_PRODUCT_ID)
			->limit(50)
			->addRowCallback(function(&$row) {
				$row['omg'] = 'wut';
			});

		$StatsTBody = new HTMLSequenceTableBody($StatsQuery, self::CLS_TABLE_TRANSACTION_SEARCH);

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '/assets/search-transaction.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/search-transaction.css'),

			new HTMLElement('fieldset', 'fieldset-search fieldset-filter-search',
				new HTMLElement('legend', 'legend-filter-email', self::TITLE),

				new HTMLElement('fieldset', 'fieldset-filter-stats-results',
					new HTMLElement('legend', 'legend-filter-stats-results', 'Stats'),

					new HTMLTable(
						$StatsTBody
					)
				),

				new HTMLElement('fieldset', 'fieldset-filter-search-results',
					new HTMLElement('legend', 'legend-filter-search-results', 'Search Results'),

					new HTMLTable(
						$SearchTBody
					)
				),

				$OptionsFieldSet = new HTMLElement('fieldset', 'fieldset-options fieldset-filter-options',
					new HTMLElement('legend', 'legend-filter-options', "Search Options")
				),
				"<br/>",
				new HTMLButton(null, 'Search')
			),
			"<br/>"
		);

		/** @var HTMLSelectField[] $SelectSorts */
		$SelectSorts = array();
		/** @var HTMLSelectField[] $SelectFilters */
		$SelectFilters = array();
		foreach(
			array(
		        'Email' => TransactionTable::COLUMN_WALLET_ID,
		        'Product' => TransactionTable::COLUMN_PRODUCT_ID,
		        'Source' => TransactionTable::COLUMN_PAYMENT_SOURCE_ID,
		        'Status' => TransactionTable::COLUMN_STATUS,
		        'Date' => TransactionTable::COLUMN_CREATED,
			) as $desc => $column) {
			$OptionsFieldSet->addContent(
				new HTMLElement('fieldset', 'fieldset-filter fieldset-filter-' . $column,
					new HTMLElement('legend', 'legend-filter-' . $column, 'By ' . $desc),
					$SelectFilters[$column] = new HTMLSelectField('filter-' . $column,
						array(
							'Filter By ' . $desc => null,
						)),
					"<br/>",
					$SelectSorts[$column] = new HTMLSelectField('sort-' . $column,
						array(
							'Sort By ' . $desc => null,
							'Ascending' => 'ASC',
							'Descending' => 'DESC',
						)
					)
				)
			);

			if(!empty($Request['filter-' . $column]))
				$SearchQuery->where($column, $Request['filter-' . $column]);

			if(!empty($Request['sort-' . $column]))
				$SearchQuery->orderBy($column,  $Request['sort-' . $column]);
		}


//		$FilterEmail->addOption(null, 'Filter by Email');
//		$FilterProduct->addOption(null, 'Filter by Product');
//		$FilterSource->addOption(null, 'Filter by Payment Source');
//		$FilterStatus->addOption(null, 'Filter by Status');
////		$FilterWallet->addOption(null, 'Filter by Wallet');

		$SourceCache = array();
		$SearchQuery->addRowCallback(function(TransactionEntry $Entry) use ($Form, $Request, &$SourceCache, $SelectFilters) {
			$SelectFilters[TransactionTable::COLUMN_WALLET_ID]->addOption($Entry->getWalletID(), $Entry->getInvoice()->getWallet()->getEmail());
			$SelectFilters[TransactionTable::COLUMN_PRODUCT_ID]->addOption($Entry->getProductID(), $Entry->getInvoice()->getProduct()->getProductTitle());
			$SelectFilters[TransactionTable::COLUMN_STATUS]->addOption($Entry->getStatus(), $Entry->getStatusText());
			$sourceID = $Entry->getPaymentSourceID();
			$Source = isset($SourceCache[$sourceID])
				? $SourceCache[$sourceID]
				: $SourceCache[$sourceID] = PaymentSourceEntry::get($sourceID);
			$SelectFilters[TransactionTable::COLUMN_PAYMENT_SOURCE_ID]->addOption($Entry->getPaymentSourceID(), $Source->getPaymentSource()->getDescription());
//			$FilterWallet->addOption($Entry->getWalletID(), $Entry->getInvoice()->getWallet()->getTitle());
			$Form->setFormValues($Request);
		});

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