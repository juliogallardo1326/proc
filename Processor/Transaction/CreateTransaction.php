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
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\HTMLLabel;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\IExecutable;
use CPath\Request\Form\IFormRequest;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\IResponse;
use CPath\Response\Response;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\DB\Schema\ProductEntry;
use Processor\DB\Schema\Tables\TransactionTable;
use Processor\DB\Schema\TransactionEntry;
use Processor\SiteMap;
use Processor\Wallet\Type\AbstractWallet;


class CreateTransaction implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Create a new Transaction';

	const FORM_ACTION = '/create/transaction/';
	const FORM_METHOD = 'POST';
	const FORM_NAME = 'create-transaction';

	const PARAM_WALLET_ID = 'wallet-id';
	const PARAM_PRODUCT_ID = 'product-id';

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
		$productOptions = array(); // 'Choose a Product' => null
		foreach($Products as $ProductEntry) {
			$productOptions[$ProductEntry->getTitle()] = $ProductEntry->getID();
			$Product = $ProductEntry->getProductInstance();
			$Form = $Product->getFieldSet($Request);
			$key = $ProductEntry->getID();
			$Form->addAttributes(new Attributes('data-' . self::PARAM_PRODUCT_ID, $key));
			$ProductForms[] = $Form;
		}

		$walletOptions = array('Choose a Wallet' => null);
		$WalletForms = array();
		/** @var AbstractWallet[] $WalletTypes */
		$WalletTypes = array();

		foreach (AbstractWallet::loadAllWalletTypes() as $WalletType) {
			$key = 'new-' . $WalletType->getTypeName();
			$WalletTypes[$key] = $WalletType;
			$Form = $WalletType->getFieldSet($Request);
			$Form->addAttributes(new Attributes('data-' . self::PARAM_WALLET_ID, $key));
			$WalletForms[] = $Form;
			$walletOptions['New ' . $WalletType->getTypeName()] = $key;
		}

		$SessionWalletEntries = AbstractWallet::loadSessionWallets($SessionRequest);
		foreach ($SessionWalletEntries as $WalletEntry) {
			$WalletType = $WalletEntry->getWallet();
			$key = $WalletType->getTypeName() . '-' . $WalletEntry->getID();
			$WalletTypes[$key] = $WalletType;
			$Form = $WalletType->getFieldSet($Request);
			$Form->addAttributes(new Attributes('data-' . self::PARAM_WALLET_ID, $key));
			$WalletForms[] = $Form;
			$walletOptions['New ' . $WalletType->getDescription()] = $key;
		}

//		$walletTypes = Config::$AvailableWalletTypes;
		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/create.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/create.css'),


			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-wallet', 'Choose a Wallet'),

				new HTMLLabel(
					new HTMLSelectField(self::PARAM_WALLET_ID, $walletOptions,
//						new Attributes('onchange', 'jQuery(this.form).removeClass(this.lastVal || null).addClass(this.lastVal = jQuery(this).val());'),
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				$WalletForms
			),

			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-transaction', 'Transaction Details'),
//
//				"Amount:<br/>",
//				new HTMLInputField(TransactionTable::COLUMN_AMOUNT,
//					new RequiredValidation()
//				),

				"Product:<br/>",
				new HTMLSelectField(self::PARAM_PRODUCT_ID, $productOptions,
					new RequiredValidation()
				),

				"<br/><br/>Status:<br/>",
				new HTMLSelectField(TransactionTable::COLUMN_STATUS, TransactionEntry::$StatusOptions,
					new RequiredValidation()
				),

				"<br/><br/>",
				$ProductForms,
//
//				"<br/><br/>Invoice:<br/>",
//				new HTMLTextAreaField(TransactionTable::COLUMN_STATUS,
//					new RequiredValidation()
//				),

				"<br/><br/>Submit:<br/>",
				new HTMLButton('submit', 'Submit', 'submit')
			)
		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$walletKey = $Form->validateField($Request, self::PARAM_WALLET_ID);
		$WalletType = $WalletTypes[$walletKey];
		$WalletType->validateRequest($Request);

		return new Response("Complete");
	}

	// Static

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
	}
}