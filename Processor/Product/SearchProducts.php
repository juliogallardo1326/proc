<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\Product;

use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Render\HTML\Element\Form\HTMLButton;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLPDOQueryTable;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\IExecutable;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\Account\Types\AbstractAccountType;
use Processor\Account\Types\AdministratorAccount;
use Processor\Account\Types\MerchantAccount;
use Processor\Product\DB\ProductTable;
use Processor\SiteMap;

class SearchProducts implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Search Products';

	const FORM_ACTION = '/products';
	const FORM_ACTION2 = '/search/products';
	const FORM_METHOD = 'POST';
	const FORM_NAME = __CLASS__;
	const CLS_TABLE_PRODUCT_SEARCH = 'search-product';

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$SessionRequest = $Request;
		if (!$SessionRequest instanceof ISessionRequest)
			throw new \Exception("Session required");

		$Table = new ProductTable();
		$StatsQuery = $Table
			->select()
			->limit(50);

		$StatsTable = new HTMLPDOQueryTable($StatsQuery);
		$StatsTable->addColumn('product');
		$StatsTable->addColumn('type');
		$StatsTable->addColumn('total');
		$StatsTable->addColumn('account');
		$StatsTable->addColumn('description');
		$StatsTable->addColumn('fees');
		$StatsTable->addColumn('test-url');

		$StatsTable->addSearchColumn(ProductTable::COLUMN_ID, "product");
		$StatsTable->addSearchColumn(ProductTable::COLUMN_ACCOUNT_ID, "account");

		$StatsTable->addSortColumn(ProductTable::COLUMN_ID, "product");
		$StatsTable->addSortColumn(ProductTable::COLUMN_ACCOUNT_ID, "account");

		$StatsTable->validateRequest($Request);

		$Account = AbstractAccountType::loadFromSession($SessionRequest);
		if($Account instanceof AdministratorAccount) {

		} else if ($Account instanceof MerchantAccount) {
			$StatsQuery->where(ProductTable::COLUMN_ACCOUNT_ID, $Account->getID());

		} else {
			$StatsQuery->where(ProductTable::COLUMN_ACCOUNT_ID, -1);

		}

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '\assets\form-login.js'),
//			new HTMLHeaderStyleSheet(__DIR__ . '\assets\form-login.css'),

//			new HTMLElement('h3', null, self::TITLE),

			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-submit', self::TITLE),

				$StatsTable,
				new HTMLButton('submit', 'Submit', 'submit')
			),
			"<br/>"
		);

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
		return new static();
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