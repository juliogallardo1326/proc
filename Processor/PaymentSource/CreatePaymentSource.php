<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/27/2015
 * Time: 1:56 PM
 */
namespace Processor\PaymentSource;

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
use CPath\Request\Session\ISessionRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\Common\RedirectResponse;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\PaymentSource\DB\PaymentSourceEntry;
use Processor\PaymentSource\Sources\AbstractPaymentSource;
use Processor\SiteMap;


class CreatePaymentSource implements IExecutable, IBuildable, IRoutable
{
	const CLS_FIELDSET_PAYMENT_SOURCE = 'fieldset-payment-source';

	const TITLE = 'Create a new PaymentSource';

	const FORM_ACTION = '/create/payment-source/';
	const FORM_ACTION2 = '/payment-sources';
	const FORM_METHOD = 'POST';
	const FORM_NAME = 'create-payment-source';

	const PARAM_SOURCE_TYPE = 'payment-source-type';
	const PARAM_SOURCE_STATUS = 'payment-source-status';

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
		/** @var AbstractPaymentSource[] $SourceTypes */
		$SourceTypes = array();
		$SourceForms = array();

		foreach (AbstractPaymentSource::loadAllPaymentSourceTypes() as $SourceType) {
			$SourceTypes[$SourceType->getTypeName()] = $SourceType;
			$FieldSet = $SourceType->getFieldSet($Request);
			$FieldSet->setAttribute('data-' . self::PARAM_SOURCE_TYPE, $SourceType->getTypeName());
			$FieldSet->setAttribute('disabled', 'disabled');
			$SourceForms[] = $FieldSet;
			$sourceOptions[$SourceType->getDescription()] = $SourceType->getTypeName();
		}

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/payment-source.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/payment-source.css'),

			new HTMLElement('fieldset', 'fieldset-create-payment-source',
				new HTMLElement('legend', 'legend-payment-source', self::TITLE),

				new HTMLElement('label', null, "Status<br/>",
					new HTMLSelectField(self::PARAM_SOURCE_STATUS, PaymentSourceEntry::$StatusOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				new HTMLElement('label', null, "Choose a Payment Source<br/>",
					new HTMLSelectField(self::PARAM_SOURCE_TYPE, $sourceOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				$SourceForms,

				"<br/>Submit:<br/>",
				new HTMLButton('submit', 'Submit', 'submit')
			),
			"<br/>"
		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$status = $Form->validateField($Request, self::PARAM_SOURCE_STATUS);

		$sourceType = $Form->validateField($Request, self::PARAM_SOURCE_TYPE);
		$ChosenSource = $SourceTypes[$sourceType];
		$ChosenSource->validateRequest($Request, $Form);

		$id = PaymentSourceEntry::create($Request, $ChosenSource, $status);

		return new RedirectResponse(ManagePaymentSource::getRequestURL($id), "PaymentSource created successfully. Redirecting...", 5);
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
		$RouteBuilder->writeRoute('ANY ' . self::FORM_ACTION2, __CLASS__, IRequest::NAVIGATION_ROUTE, "Payment Source");
	}
}
