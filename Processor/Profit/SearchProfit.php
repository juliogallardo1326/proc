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
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLPDOQueryTable;
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

			->select(ProfitTable::COLUMN_PROFIT, 'profit', 'SUM(%s)')
			->select(ProfitTable::COLUMN_PROFIT, 'count', 'COUNT(%s)')

			->groupBy(ProfitTable::COLUMN_ACCOUNT_ID)
			->limit(50);

		$StatsTable = new HTMLPDOQueryTable($StatsQuery);
		$StatsTable->addColumn('account');
		$StatsTable->addColumn('profit');
		$StatsTable->addColumn('count');

		$StatsTable->addSearchColumn(ProfitTable::COLUMN_ACCOUNT_ID, "account");

		$StatsTable->validateRequest($Request);

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '/assets/search-profit.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/search-profit.css'),

			new HTMLElement('fieldset', 'fieldset-search fieldset-filter-search inline',
				new HTMLElement('legend', 'legend-filter-search', self::TITLE),

				$StatsTable,
				"<br/>",
				new HTMLButton(null, 'Report')
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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION, __CLASS__,
			IRequest::NAVIGATION_ROUTE |
			IRequest::MATCH_SESSION_ONLY,
			"Profit");
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__);
	}
}