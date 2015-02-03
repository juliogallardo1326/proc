<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\Wallet;

use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Render\HTML\Attribute\Attributes;
use CPath\Render\HTML\Element\Form\HTMLButton;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
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
use Processor\SiteMap;
use Processor\Wallet\DB\WalletEntry;
use Processor\Wallet\Type\AbstractWallet;

class CreateWallet implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Create Wallet';

	const FORM_ACTION = '/create/wallet';
	const FORM_ACTION2 = '/wallets';
	const FORM_METHOD = 'POST';
	const FORM_NAME = 'create-wallet';

//	const PARAM_WALLET_ID = 'wallet-id';
	const PARAM_WALLET_TYPE = 'wallet-type';
	const PARAM_WALLET_NAME = 'wallet-name';
//	const PARAM_WALLET_EMAIL = 'wallet-email';
	const PARAM_WALLET_STATUS = 'wallet-status';

	public static function getRequestURL() {
		return self::FORM_ACTION;
	}

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$walletOptions = array('Choose a Wallet' => null);
		/** @var AbstractWallet[] $WalletTypes */
		$WalletTypes = array();
		$WalletForms = array();

		foreach (AbstractWallet::loadAllWalletTypes() as $WalletType) {
			$WalletTypes[$WalletType->getTypeName()] = $WalletType;
			$FieldSet = $WalletType->getFieldSet($Request);
			$FieldSet->setAttribute('data-' . self::PARAM_WALLET_TYPE, $WalletType->getTypeName());
			$FieldSet->setAttribute('disabled', 'disabled');
			$WalletForms[] = $FieldSet;
			$walletOptions[$WalletType->getDescription()] = $WalletType->getTypeName();
		}

		$Form = new HTMLForm(self::FORM_METHOD, self::FORM_ACTION, self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '\assets\create-wallet.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '\assets\create-wallet.css'),
//			new HTMLHeaderStyleSheet(__DIR__ . '\assets\wallet.css'),

//			new HTMLElement('h3', null, self::TITLE),

			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-wallet', 'Create a new Wallet'),

//				new HTMLElement('label', null, "New Wallet name<br/>",
//					new HTMLInputField(self::PARAM_WALLET_NAME
////						new RequiredValidation()
//					)
//				),
//
//				"<br/><br/>",
//				new HTMLElement('label', null, "New Wallet email<br/>",
//					new HTMLInputField(self::PARAM_WALLET_EMAIL,
//						new RequiredValidation()
//					)
//				),

				new HTMLElement('label', null, "New Wallet type<br/>",
					new HTMLSelectField(self::PARAM_WALLET_TYPE, $walletOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				$WalletForms,

				"<br/><br/>",
				new HTMLButton('create', "Create New Wallet")
			)
		);

		$Form->setFormValues($Request);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$walletType = $Form->validateField($Request, self::PARAM_WALLET_TYPE);
		$NewWallet = $WalletTypes[$walletType];
		$NewWallet->validateRequest($Request, $Form);

//		$name = $Request[self::PARAM_WALLET_NAME];
//		$email = $Request[self::PARAM_WALLET_EMAIL];

		$id = WalletEntry::create($Request, $NewWallet);

		return new RedirectResponse(ManageWallet::getRequestURL($id), "Wallet created successfully. Redirecting...", 5);
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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__, IRequest::NAVIGATION_ROUTE, "Wallets");
	}
}