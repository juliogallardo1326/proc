<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\OrderForm;

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
use CPath\Request\Validation\Exceptions\ValidationException;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\Common\RedirectResponse;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\PaymentSource\DB\PaymentSourceEntry;
use Processor\Product\DB\ProductEntry;
use Processor\Profit\DB\ProfitEntry;
use Processor\SiteMap;
use Processor\Transaction\DB\TransactionEntry;
use Processor\Transaction\ManageTransaction;
use Processor\Wallet\DB\WalletEntry;
use Processor\Wallet\Type\AbstractWallet;

class OrderForm implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Order Form';

	const FORM_FORMAT = '/order/%s';
	const FORM_ACTION = '/order/:id';
	const FORM_METHOD = 'POST';
	const FORM_NAME = 'form-order-page';

	const PARAM_PRODUCT_ID = 'id';
	const PARAM_SUBMIT = 'submit';
//	const PARAM_PAYMENT_SOURCE_TYPE = 'payment-source-type';
	const PARAM_WALLET_ID = 'wallet-id';

	private $id;

	public function __construct($productID) {
		$this->id = $productID;
	}

	function getProductID() {
		return $this->id;
	}

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @throws \Exception
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$ProductEntry = ProductEntry::get($this->id);
		$Product = $ProductEntry->getProduct();

		$SessionRequest = $Request;
		if (!$SessionRequest instanceof ISessionRequest)
			throw new \Exception("Session required");

		$walletOptions = array('Choose a Wallet' => null);
		$WalletForms = array();
		/** @var AbstractWallet[] $WalletTypes */
		$WalletTypes = array();

		$SessionWalletEntries = AbstractWallet::loadSessionWallets($SessionRequest);
		foreach ($SessionWalletEntries as $WalletEntry) {
			$Wallet = $WalletEntry->getWallet();
			$key = $WalletEntry->getID();
			$WalletTypes[$key] = $Wallet;
			$FieldSet = $Wallet->getFieldSet($Request);
			$FieldSet->setAttribute('data-' . self::PARAM_WALLET_ID, $key);
			$FieldSet->setAttribute('disabled', 'disabled');
			$WalletForms[] = $FieldSet;
			$walletOptions[$Wallet->getTitle() . ' - ' . $Wallet->getDescription()] = $key;
		}

		foreach ($Product->getWalletTypes() as $Wallet) {
			$key = $Wallet->getTypeName();
			$WalletTypes[$key] = $Wallet;
			$FieldSet = $Wallet->getFieldSet($Request);
			$FieldSet->setAttribute('data-' . self::PARAM_WALLET_ID, $key);
			$FieldSet->setAttribute('disabled', 'disabled');
			$WalletForms[] = $FieldSet;
			$walletOptions['New ' . $Wallet->getDescription()] = $key;
		}

//		$walletTypes = Config::$AvailableWalletTypes;
		$Form = new HTMLForm(self::FORM_METHOD, self::getRequestURL($this->id), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/order-form.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/order-form.css'),

////			new HTMLElement('h3', null, self::TITLE),

			new HTMLElement('fieldset', 'fieldset-order-form',
				new HTMLElement('legend', 'legend-order-form', self::TITLE),

				new HTMLElement('fieldset', 'fieldset-choose-wallet',
					new HTMLElement('legend', 'legend-choose-wallet', 'Choose a Wallet'),

					new HTMLElement('label', null,
						new HTMLSelectField(self::PARAM_WALLET_ID, $walletOptions,
							new RequiredValidation()
						)
					),

					"<br/><br/>",
					$WalletForms
				),

				new HTMLElement('fieldset', 'fieldset-transaction-details',
					new HTMLElement('legend', 'legend-transaction-details', 'Transaction Details'),
					$Product->getTypeDescription(),
					"<br/>",
					$Product->getOrderFieldSet($Request)
				),

				"<br/><br/>",
				new HTMLElement('fieldset', 'fieldset-submit',
					new HTMLElement('legend', 'legend-submit', 'Submit'),
					new HTMLButton('submit', 'Submit', 'submit')
				)
			),
			"<br/>"
		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$Form->setFormValues($Request);
		$Form->validateRequest($Request);


		$walletType = $Form->validateField($Request, self::PARAM_WALLET_ID);
		$ChosenWallet = $WalletTypes[$walletType];
		$ChosenWallet->validateRequest($Request, $Form);

		$productID = $this->getProductID();
		//$Form->validateField($Request, self::PARAM_PRODUCT_ID);
		$ProductEntry = ProductEntry::get($productID);
		$Product = $ProductEntry->getProduct();
		$Invoice = $Product->createNewInvoice($Request, $ChosenWallet);


		$responses = array();
		foreach(PaymentSourceEntry::getActiveSources() as $PaymentSourceEntry) {
			$PaymentSource = $PaymentSourceEntry->getPaymentSource();
			if($PaymentSource->supportsWalletType($ChosenWallet)) {
				$Response = $PaymentSource->executeWalletTransaction($ChosenWallet);
				$responses[] = $Response->getMessage();
				$paymentSourceID = $PaymentSourceEntry->getID();
				$walletID = WalletEntry::createOrUpdate($Request, $ChosenWallet);

				if($Response->getCode() === TransactionEntry::STATUS_APPROVED) {
					$status = TransactionEntry::STATUS_APPROVED;
					$id = TransactionEntry::create($Request, $Invoice, $status, $walletID, $productID, $paymentSourceID);
					ProfitEntry::update($Request, $id);
					return new RedirectResponse(ManageTransaction::getRequestURL($id), "Transaction created successfully. Redirecting...", 5);

				} else {
					$status = TransactionEntry::STATUS_DECLINED;
					$id = TransactionEntry::create($Request, $Invoice, $status, $walletID, $productID, $paymentSourceID);
					ProfitEntry::update($Request, $id);
				}
			}
		}

		throw new ValidationException($Form, "Transaction declined: \n\t" . implode("\n\t", $responses));
//
//
//		if(true) {
//			$status = TransactionEntry::STATUS_APPROVED;
//
//			$id = TransactionEntry::create($Request, $Invoice, $status, $walletID, $productID, $paymentSourceID);
//			ProfitEntry::update($Request, $id);
//			return new RedirectResponse(ManageTransaction::getRequestURL($id), "Purchase was successful. Redirecting...", 5);
//
//		} else {
//			$status = TransactionEntry::STATUS_DECLINED;
//			$id = TransactionEntry::create($Request, $Invoice, $status, $walletID, $productID, $paymentSourceID);
//			ProfitEntry::update($Request, $id);
//			return new RequestException("Transaction has declined");
//		}

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
		return new ExecutableRenderer(new static($Request[self::PARAM_PRODUCT_ID]), true);
//		$Render = new HTMLResponseBody($Render);
//		$Render->renderHTML($Request);
//		return true;
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
	}
}