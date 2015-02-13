<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\Profit;

use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Render\HTML\Element\Form\HTMLButton;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLSequenceTableBody;
use CPath\Render\HTML\Element\Table\HTMLTable;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\IRequest;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\Profit\DB\ProfitTable;
use Processor\SiteMap;
use Processor\Transaction\DB\TransactionTable;

class SearchProfit implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Search Profit';

	const FORM_ACTION = '/profit';
	const FORM_ACTION2 = '/search/profit';
	const FORM_METHOD = 'GET';
	const FORM_NAME = 'search-profit';
	const CLS_TABLE_PROFIT_SEARCH = 'table-profit-search';

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
		$Table = new ProfitTable();

		$StatsQuery = $Table
			->select(ProfitTable::COLUMN_ACCOUNT_ID, 'account')
//		, AccountTable::COLUMN_ACCOUNT,
//				"(Select " . AccountTable::COLUMN_ACCOUNT
//				. " FROM " . AccountTable::TABLE_NAME
//				. " WHERE " . AccountTable::COLUMN_ID . '=' . ProfitTable::COLUMN_ACCOUNT_ID
//				. ")")

			->select(ProfitTable::COLUMN_PROFIT, 'profit', 'SUM(%s)')
			->select(ProfitTable::COLUMN_PROFIT, 'count', 'COUNT(%s)')

			->groupBy(ProfitTable::COLUMN_ACCOUNT_ID)
			->limit(50);
//			->addRowCallback(function(&$row) {
//				/** @var AbstractPaymentSource $Source */
//				$Source = unserialize($row[PaymentSourceTable::COLUMN_SOURCE]);
//				unset($row[PaymentSourceTable::COLUMN_SOURCE]);
//				$row['total'] = $row['total'] . ' ' . $Source->getCurrency() . ' (' . $row['count'] . ')';
//				unset($row['count']);
//
//				if($row['approves'])
//					$row['approves'] = '(' . $row['approves'] . ') <span class="total">' . $row['approve_total'] . '</span> ' . $Source->getCurrency();
//				unset($row['approve_total']);
//
//				if($row['pending'])
//					$row['pending'] = '(' . $row['pending'] . ') <span class="total">' . $row['pending_total'] . '</span> ' . $Source->getCurrency();
//				unset($row['pending_total']);
//
//				if($row['declines'])
//					$row['declines'] = '(' . $row['declines'] . ') <span class="total">' . $row['decline_total'] . '</span> ' . $Source->getCurrency();
//				unset($row['decline_total']);
//
//				if($row['refunds'])
//					$row['refunds'] = '(' . $row['refunds'] . ') <span class="total">' . $row['refund_total'] . '</span> ' . $Source->getCurrency();
//				unset($row['refund_total']);
//
//				if($row['chargebacks'])
//					$row['chargebacks'] = '(' . $row['chargebacks'] . ') <span class="total">' . $row['chargeback_total'] . '</span> ' . $Source->getCurrency();
//				unset($row['chargeback_total']);
//			});

		$StatsTBody = new HTMLSequenceTableBody($StatsQuery, self::CLS_TABLE_PROFIT_SEARCH);

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '/assets/search-profit.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/search-profit.css'),

			new HTMLElement('fieldset', 'fieldset-search fieldset-filter-search',
				new HTMLElement('legend', 'legend-filter-search', self::TITLE),

				new HTMLElement('fieldset', 'fieldset-filter-stats-results',
					new HTMLElement('legend', 'legend-filter-stats-results', 'Stats'),

					new HTMLTable(
						$StatsTBody
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
				'Account' => ProfitTable::COLUMN_ACCOUNT_ID,
				'Profit' => ProfitTable::COLUMN_PROFIT,
		        'Product' => TransactionTable::COLUMN_PRODUCT_ID,
		        'Source' => TransactionTable::COLUMN_PAYMENT_SOURCE_ID,
		        'Status' => TransactionTable::COLUMN_STATUS,
		        'Date' => TransactionTable::COLUMN_CREATED,
			) as $desc => $column) {
			$OptionsFieldSet->addContent(
				new HTMLElement('fieldset', 'fieldset-filter fieldset-filter-' . $column . ' inline',
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

			if(!empty($Request['filter-' . $column])) {
				$StatsQuery->where($column, $Request['filter-' . $column]);
			}

			if(!empty($Request['sort-' . $column])) {
//				$SearchQuery->orderBy($column,  $Request['sort-' . $column]);
//				$StatsQuery->orderBy($column, $Request['sort-' . $column]);
			}
		}

		$SourceCache = array();
//		$SearchQuery->addRowCallback(function(TransactionEntry $Entry) use ($Form, $Request, &$SourceCache, $SelectFilters) {
//			$SelectFilters[TransactionTable::COLUMN_WALLET_ID]->addOption($Entry->getWalletID(), $Entry->getInvoice()->getWallet()->getEmail());
//			$SelectFilters[TransactionTable::COLUMN_PRODUCT_ID]->addOption($Entry->getProductID(), $Entry->getInvoice()->getProduct()->getProductTitle());
//			$SelectFilters[TransactionTable::COLUMN_STATUS]->addOption($Entry->getStatus(), $Entry->getStatusText());
//			$sourceID = $Entry->getPaymentSourceID();
//			$Source = isset($SourceCache[$sourceID])
//				? $SourceCache[$sourceID]
//				: $SourceCache[$sourceID] = PaymentSourceEntry::get($sourceID);
//			$SelectFilters[TransactionTable::COLUMN_PAYMENT_SOURCE_ID]->addOption($Entry->getPaymentSourceID(), $Source->getPaymentSource()->getDescription());
////			$FilterWallet->addOption($Entry->getWalletID(), $Entry->getInvoice()->getWallet()->getTitle());
//			$Form->setFormValues($Request);
//		});

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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION, __CLASS__, IRequest::NAVIGATION_ROUTE, "Profit");
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__);
	}
}