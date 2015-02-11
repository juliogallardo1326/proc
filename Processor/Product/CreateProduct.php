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
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\Form\IFormRequest;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\Common\RedirectResponse;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\PaymentSource\DB\PaymentSourceTable;
use Processor\Product\DB\ProductEntry;
use Processor\Product\Types\AbstractProductType;
use Processor\SiteMap;


class CreateProduct implements IExecutable, IBuildable, IRoutable
{
	const CLS_FIELDSET_PRODUCT = 'fieldset-product';
	const CLS_FIELDSET_CONFIG = 'fieldset-product-config';

	const TITLE = 'Create a new Product';

	const FORM_ACTION = '/create/product/';
	const FORM_ACTION2 = '/products';
	const FORM_METHOD = 'POST';
	const FORM_NAME = 'create-product';

	const PARAM_PRODUCT_TYPE = 'product-type';
	const PARAM_PRODUCT_STATUS = 'product-status';
	const PARAM_PAYMENT_SOURCE_TYPE = 'payment-source-type';
	const PARAM_PRODUCT_TOTAL_COST = 'product-total-cost';

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @throws \Exception
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$SessionRequest = $Request;
		if (!$SessionRequest instanceof ISessionRequest)
			throw new \Exception("Session required");

		$sourceOptions = array("Choose a Payment Source" => null);
		$PaymentSourceTable = new PaymentSourceTable();
		foreach($PaymentSourceTable->fetchAll(1) as $PaymentSourceEntry) {
			$PaymentSource = $PaymentSourceEntry->getPaymentSource();
			$sourceOptions[$PaymentSource->getCurrency() . ' - ' . $PaymentSource->getTitle()] = $PaymentSourceEntry->getID();
		}

		$productOptions = array("Choose a Product Type" => null);
		/** @var AbstractProductType[] $ProductTypes */
		$ProductTypes = array();
		$ProductForms = array();

		foreach (Types\AbstractProductType::loadAllProductTypes() as $ProductType) {
			$ProductTypes[$ProductType->getTypeName()] = $ProductType;
			$FieldSet = $ProductType->getConfigFieldSet($Request);
			$FieldSet->setAttribute('disabled', 'disabled');
			$ProductForms[] = $FieldSet;
			$productOptions[$ProductType->getTypeDescription()] = $ProductType->getTypeName();
		}

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/product.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/product.css'),

			new HTMLElement('fieldset', 'fieldset-create-product',
				new HTMLElement('legend', 'legend-product', self::TITLE),

				new HTMLElement('label', null, "Status<br/>",
					new HTMLSelectField(self::PARAM_PRODUCT_STATUS, ProductEntry::$StatusOptions,
						new RequiredValidation()
					)
				),
//
//				"<br/><br/>",
//				new HTMLElement('label', null, "Total Cost<br/>",
//					new HTMLInputField(self::PARAM_PRODUCT_TOTAL_COST, null,
//						new Attributes('placeholder', '"9.99"'),
//						new RequiredValidation()
//					)
//				),

				"<br/><br/>",
				new HTMLElement('label', null, "Choose a Payment Source<br/>",
					new HTMLSelectField(self::PARAM_PAYMENT_SOURCE_TYPE, $sourceOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				new HTMLElement('label', null, "Choose a Product Type<br/>",
					new HTMLSelectField(self::PARAM_PRODUCT_TYPE, $productOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				$ProductForms,

				"<br/><br/>Submit:<br/>",
				new HTMLButton('submit', 'Submit', 'submit')
			),
			"<br/>"
		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$status = $Form->validateField($Request, self::PARAM_PRODUCT_STATUS);

		$productType = $Form->validateField($Request, self::PARAM_PRODUCT_TYPE);
		$sourceID = $Form->validateField($Request, self::PARAM_PAYMENT_SOURCE_TYPE);
		$ChosenProduct = $ProductTypes[$productType];
		$ChosenProduct->validateConfigRequest($Request, $Form);

		$accountID = 'default';
		$id = ProductEntry::create($Request, $ChosenProduct, $accountID, $sourceID, $status);

		return new RedirectResponse(ManageProduct::getRequestURL($id), "Product created successfully. Redirecting...", 5);
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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__, IRequest::NAVIGATION_ROUTE, "Products");
	}
}