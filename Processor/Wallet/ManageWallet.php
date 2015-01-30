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
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\Form\IFormRequest;
use CPath\Request\IRequest;
use CPath\Request\Validation\Exceptions\ValidationException;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\Common\RedirectResponse;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\DB\Schema\WalletEntry;
use Processor\SiteMap;

class ManageWallet implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Manage Wallet';

	const FORM_FORMAT = '/w/%s';
	const FORM_ACTION = '/w/:id';
	const FORM_ACTION2 = '/manage/wallet/:id';
	const FORM_METHOD = 'POST';
	const FORM_NAME = __CLASS__;

	const PARAM_ID = 'id';
	const PARAM_WALLET_EMAIL = 'wallet-email';
	const PARAM_WALLET_NAME = 'wallet-name';
	const PARAM_SUBMIT = 'submit';

	private $id;

	public function __construct($walletID) {
		$this->id = $walletID;
	}

	private function getWalletID() {
		return $this->id;
	}

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$WalletEntry = WalletEntry::get($this->id);
		$Wallet = $WalletEntry->getWallet();
		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '\assets\wallet.js'),
//			new HTMLHeaderStyleSheet(__DIR__ . '\assets\wallet.css'),

			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-submit', self::TITLE),

				"Wallet ID:<br/>",
				new HTMLInputField(self::PARAM_ID, $WalletEntry->getID(),
					new Attributes('disabled', 'disabled')
				),

				"<br/><br/>",
				new HTMLElement('label', null, "Wallet name<br/>",
					new HTMLInputField(self::PARAM_WALLET_NAME, $WalletEntry->getName(),
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				new HTMLElement('label', null, "Wallet email<br/>",
					new HTMLInputField(self::PARAM_WALLET_EMAIL, $WalletEntry->getEmail(),
						new Attributes('disabled', 'disabled')
					)
				),

				"<br/><br/>",

				$Wallet->getFieldSet($Request),

				"<br/><br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Update', 'update'),
				new HTMLButton(self::PARAM_SUBMIT, 'Delete', 'delete')
			),
			"<br/><br/>"

		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		try {
			$Wallet->validateRequest($Request);
		} catch (ValidationException $ex) {
			$ex->setForm($Form);
			throw $ex;
		}

		$submit = $Request[self::PARAM_SUBMIT];
		$name = $Request[self::PARAM_WALLET_NAME];

		switch($submit) {
			case 'update':
				WalletEntry::update($Request, $this->getWalletID(), $Wallet, $name);
				return new RedirectResponse(ManageWallet::getRequestURL($this->getWalletID()), "Wallet updated successfully. Redirecting...", 5);

			case 'delete':
				WalletEntry::delete($Request, $this->getWalletID());
				return new RedirectResponse(WalletRoute::getRequestURL(), "Wallet deleted successfully. Redirecting...", 5);
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
	}

}