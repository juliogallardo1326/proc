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
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLAnchor;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\Form\IFormRequest;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\Common\RedirectResponse;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\PaymentSource\DB\PaymentSourceTable;
use Processor\Product\DB\ProductEntry;
use Processor\SiteMap;
use Processor\OrderForm\OrderForm;

class ManageProduct implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Manage Product';

	const FORM_FORMAT = '/p/%s';
	const FORM_ACTION = '/p/:id';
	const FORM_ACTION2 = '/product/:id';
	const FORM_ACTION3 = '/manage/product/:id';
	const FORM_METHOD = 'POST';
	const FORM_NAME = __CLASS__;

	const PARAM_PRODUCT_TYPE = 'product-type';
	const PARAM_PRODUCT_STATUS = 'product-status';
	const PARAM_ID = 'id';
	const PARAM_SUBMIT = 'submit';
	const PARAM_PAYMENT_SOURCE_TYPE = 'payment-source-type';

	private $id;

	public function __construct($productID) {
		$this->id = $productID;
	}

	private function getProductID() {
		return $this->id;
	}

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$ProductEntry = ProductEntry::get($this->id);
		$Product = $ProductEntry->getProduct();

		$sourceOptions = array("Choose a Payment Product" => null);
		$PaymentSourceTable = new PaymentSourceTable();
		foreach($PaymentSourceTable->fetchAll(1) as $PaymentSourceEntry) {
			$PaymentSource = $PaymentSourceEntry->getPaymentSource();
			$sourceOptions[$PaymentSource->getTitle()] = $PaymentSourceEntry->getID();
		}

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/product.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/product.css'),


			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-order-page', "Try Order Page"),

				new HTMLAnchor(OrderForm::getRequestURL($this->getProductID()), "Order Page")
			),
			
			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-submit', self::TITLE),

				new HTMLInputField(self::PARAM_ID, $this->id, 'hidden'),
				new HTMLInputField(self::PARAM_PRODUCT_TYPE, $Product->getTypeName(), 'hidden'),

				new HTMLElement('label', null, "Status<br/>",
					$SelectStatus = new HTMLSelectField(self::PARAM_PRODUCT_STATUS, ProductEntry::$StatusOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				new HTMLElement('label', null, "Choose a Payment Source<br/>",
					$SelectSource = new HTMLSelectField(self::PARAM_PAYMENT_SOURCE_TYPE, $sourceOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				$Product->getConfigFieldSet($Request),

				"<br/><br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Update', 'update'),
				new HTMLButton(self::PARAM_SUBMIT, 'Delete', 'delete')
			),
			"<br/>"
		);

		$SelectStatus->setInputValue($ProductEntry->getStatus());
		$SelectSource->setInputValue($ProductEntry->getPaymentSourceID());

		if(!$Request instanceof IFormRequest)
			return $Form;

		$submit = $Request[self::PARAM_SUBMIT];
		$sourceID = $Request[self::PARAM_PAYMENT_SOURCE_TYPE];

		switch($submit) {
			case 'update':
				$status = $Request[self::PARAM_PRODUCT_STATUS];
				$Product->validateConfigRequest($Request, $Form);
				$ProductEntry->update($Request, $Product, $sourceID, $status);
				return new RedirectResponse(ManageProduct::getRequestURL($this->getProductID()), "Product updated successfully. Redirecting...", 5);

			case 'delete':
				ProductEntry::delete($Request, $this->getProductID());
				return new RedirectResponse(SearchProducts::getRequestURL(), "Product deleted successfully. Redirecting...", 5);
		}

		throw new \InvalidArgumentException($submit);
	}

	// Static

	public static function getRequestURL($id) {
		return sprintf(self::FORM_FORMAT, $id);
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
		return new ExecutableRenderer(new static($Request[self::PARAM_ID]), true);
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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION3, __CLASS__);
	}
}