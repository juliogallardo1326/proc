<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\Account;

use CPath\Build\IBuildable;
use CPath\Build\IBuildRequest;
use CPath\Render\HTML\Element\Form\HTMLButton;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\SiteMap;

class ManageAccount implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Manage Account';

	const FORM_ACTION = '/a/:id';
	const FORM_FORMAT = '/a/%s';
	const FORM_ACTION2 = '/manage/account/:id';
	const FORM_METHOD = 'POST';
	const FORM_NAME = __CLASS__;

	const PARAM_ID = 'id';

	private $id;

	public function __construct($accountID) {
		$this->id = $accountID;
	}

	private function getAccountID() {
		return $this->id;
	}


	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
//			new HTMLHeaderScript(__DIR__ . '\assets\form-login.js'),
//			new HTMLHeaderStyleSheet(__DIR__ . '\assets\form-login.css'),

//			new HTMLElement('h3', null, self::TITLE),

			new HTMLElement('fieldset',
				new HTMLElement('legend', 'legend-submit', self::TITLE),

				"Account ID:<br/>",
				new HTMLInputField(self::PARAM_ID,
					new RequiredValidation()
				),
				new HTMLButton('submit', 'Submit', 'submit')
			),
			"<br/>"
		);

		return $Form;
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