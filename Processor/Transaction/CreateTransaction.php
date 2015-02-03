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
use CPath\Render\HTML\Element\HTMLLabel;
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
use Processor\Product\DB\ProductEntry;
use Processor\SiteMap;
use Processor\Transaction\DB\TransactionEntry;
use Processor\Wallet\DB\WalletEntry;
use Processor\Wallet\Type\AbstractWallet;


class CreateTransaction implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Create a new Transaction';

	const FORM_ACTION = '/create/transaction/';
	const FORM_ACTION2 = '/transactions';
	const FORM_METHOD = 'POST';
	const FORM_NAME = 'create-transaction';

	const PARAM_WALLET_ID = 'wallet-id';
	const PARAM_PRODUCT_ID = 'product-id';
	const PARAM_TRANSACTION_STATUS = 'transaction-status';
//	const PARAM_TRANSACTION_EMAIL = 'transaction-email';

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

		$ProductForms = array();

		$Products = ProductEntry::loadSessionProducts($SessionRequest);
		$productOptions = array('Choose a Product' => null);
		foreach($Products as $ProductEntry) {
			$Product = $ProductEntry->getProduct();
			$productOptions[$Product->getTotalCost() . ' - ' . $Product->getProductTitle()] = $ProductEntry->getID();
			$Product = $ProductEntry->getProduct();
			$FieldSet = $Product->getOrderFieldSet($Request);
			$key = $ProductEntry->getID();
			$FieldSet->setAttribute('data-' . self::PARAM_PRODUCT_ID, $key);
			$ProductForms[] = $FieldSet;
		}

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

		foreach (AbstractWallet::loadAllWalletTypes() as $Wallet) {
			$key = $Wallet->getTypeName();
			$WalletTypes[$key] = $Wallet;
			$FieldSet = $Wallet->getFieldSet($Request);
			$FieldSet->setAttribute('data-' . self::PARAM_WALLET_ID, $key);
			$FieldSet->setAttribute('disabled', 'disabled');
			$WalletForms[] = $FieldSet;
			$walletOptions['New ' . $Wallet->getDescription()] = $key;
		}

//		$walletTypes = Config::$AvailableWalletTypes;
		$Form = new HTMLForm(self::FORM_METHOD, self::FORM_ACTION, self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/create-transaction.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/create-transaction.css'),

////			new HTMLElement('h3', null, self::TITLE),

			new HTMLElement('fieldset', 'fieldset-create-transaction',
				new HTMLElement('legend', 'legend-wallet', self::TITLE),

				new HTMLElement('fieldset', 'fieldset-transaction',
					new HTMLElement('legend', 'legend-transaction', 'Transaction Details'),

					new HTMLElement('label', null, "Status<br/>",
						new HTMLSelectField(self::PARAM_TRANSACTION_STATUS, TransactionEntry::$StatusOptions,
							new RequiredValidation()
						)
					),

//					"<br/><br/>",
//					new HTMLElement('label', null, "Customer Email Address<br/>",
//						new HTMLInputField(self::PARAM_TRANSACTION_EMAIL,
//							new RequiredValidation()
//						)
//					),

					"<br/><br/>",
					new HTMLElement('label', null, "Product<br/>",
						new HTMLSelectField(self::PARAM_PRODUCT_ID, $productOptions,
							new RequiredValidation()
						)
					),

					"<br/><br/>",
					$ProductForms
				),

				new HTMLElement('fieldset', 'fieldset-choose-wallet',
					new HTMLElement('legend', 'legend-wallet', 'Choose a Wallet'),

					new HTMLElement('label', null,
						new HTMLSelectField(self::PARAM_WALLET_ID, $walletOptions,
							//						new Attributes('onchange', 'jQuery(this.form).removeClass(this.lastVal || null).addClass(this.lastVal = jQuery(this).val());'),
							new RequiredValidation()
						)
					),

					"<br/><br/>",
					$WalletForms
				),

				"<br/><br/>Submit:<br/>",
				new HTMLButton('submit', 'Create Transaction', 'submit')
			),
			"<br/>"
		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$Form->setFormValues($Request);

		$status = $Form->validateField($Request, self::PARAM_TRANSACTION_STATUS);
//		$email = $Form->validateField($Request, self::PARAM_TRANSACTION_EMAIL);

		$walletType = $Form->validateField($Request, self::PARAM_WALLET_ID);
		$ChosenWallet = $WalletTypes[$walletType];
		$ChosenWallet->validateRequest($Request, $Form);

		$productID = $Form->validateField($Request, self::PARAM_PRODUCT_ID);
		$ProductEntry = ProductEntry::get($productID);
		$Product = $ProductEntry->getProduct();
		$Invoice = $Product->createNewInvoice($Request, $ChosenWallet);

		$paymentSourceID = $ProductEntry->getPaymentSourceID();

		$walletID = WalletEntry::createOrUpdate($Request, $ChosenWallet);

		$id = TransactionEntry::create($Request, $Invoice, $status, $walletID, $productID, $paymentSourceID);

		return new RedirectResponse(ManageTransaction::getRequestURL($id), "Transaction created successfully. Redirecting...", 5);
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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__, IRequest::NAVIGATION_ROUTE, "Transactions");
	}
}