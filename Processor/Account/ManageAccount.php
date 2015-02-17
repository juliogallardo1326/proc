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
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLPDOQueryTable;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Request\Exceptions\RequestException;
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
use Processor\Account\DB\AccountAffiliationEntry;
use Processor\Account\DB\AccountEntry;
use Processor\Account\Types\AbstractAccountType;
use Processor\Account\Types\AdministratorAccount;
use Processor\SiteMap;

class ManageAccount implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Manage Account';

	const FORM_FORMAT = '/a/%s';
	const FORM_ACTION = '/a/:id';
	const FORM_ACTION2 = '/account/:id';
	const FORM_ACTION3 = '/manage/account/:id';
	const FORM_METHOD = 'POST';
	const FORM_NAME = __CLASS__;

	const PARAM_ACCOUNT_TYPE = 'account-type';
	const PARAM_ACCOUNT_STATUS = 'account-status';
	const PARAM_ID = 'id';
	const PARAM_SUBMIT = 'submit';
	const PARAM_AFFILIATE_TYPE = 'affiliate-type';
	const PARAM_AFFILIATE_ID = 'affiliate-id';
	const PARAM_APPROVE_AFFILIATE_ID = 'approve-affiliate-id';

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
	 * @throws RequestException
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @throws \Exception
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$SessionRequest = $Request;
		if (!$SessionRequest instanceof ISessionRequest)
			throw new \Exception("Session required");

		$AccountEntry = AccountEntry::get($this->id);
		$Account = $AccountEntry->getAccount();

		$AffiliateQuery = AccountAffiliationEntry::queryAccountAffiliates($Account->getID());
		$AffiliateListTable = new HTMLPDOQueryTable($AffiliateQuery);
		$AffiliateListTable->addColumn('affiliate');
		$AffiliateListTable->addColumn('type');

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/account.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/account.css'),

			new HTMLElement('fieldset', 'fieldset-manage inline',
				new HTMLElement('legend', 'legend-manage', self::TITLE),

				new HTMLInputField(self::PARAM_ID, $this->id, 'hidden'),
				new HTMLInputField(self::PARAM_ACCOUNT_TYPE, $Account->getTypeName(), 'hidden'),

				new HTMLElement('label', null, "Status<br/>",
					$SelectStatus = new HTMLSelectField(self::PARAM_ACCOUNT_STATUS, AccountEntry::$StatusOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				$Account->getFieldSet($Request),

				"<br/><br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Update', 'update'),
				new HTMLButton(self::PARAM_SUBMIT, 'Delete', 'delete')
			),

			new HTMLElement('fieldset', 'fieldset-affiliates inline',
				new HTMLElement('legend', 'legend-affiliates', "Add affiliation"),

				"Account ID:<br/>",
				new HTMLInputField(self::PARAM_AFFILIATE_ID),

				"<br/><br/>Affiliation Type:<br/>",
				$TypeSelect = new HTMLSelectField(self::PARAM_AFFILIATE_TYPE, array(
					"Request New Affiliate" => AccountAffiliationEntry::TYPE_REQUEST_AFFILIATE,
					"Request New Reseller" => AccountAffiliationEntry::TYPE_REQUEST_RESELLER,
					"Request New Processor" => AccountAffiliationEntry::TYPE_REQUEST_PROCESSOR,
				)),

				"<br/><br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Request', 'request')

			),

			new HTMLElement('fieldset', 'fieldset-affiliate-list inline',
				new HTMLElement('legend', 'legend-affiliate-list', "Affiliates"),
				$AffiliateListTable
			),
			new HTMLElement('fieldset', 'fieldset-affiliate-approve inline',
				new HTMLElement('legend', 'legend-affiliate-approve', "Approve Affiliates"),

				$ApproveSelect = new HTMLSelectField(self::PARAM_APPROVE_AFFILIATE_ID, array(
					'Pending affiliate approvals' => null,
				)),

				"<br/><br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Approve', 'approve')

			)

		);

		$SelectStatus->setInputValue($AccountEntry->getStatus());


		$Account = AbstractAccountType::loadFromSession($SessionRequest);
		if ($Account instanceof AdministratorAccount) {
			$TypeSelect->setOptions(AccountAffiliationEntry::$TypeOptions);
		}

		$AffiliateQuery->addRowCallback(
			function(AccountAffiliationEntry $Affiliation) use ($Account, $ApproveSelect) {
				if(
					$Affiliation->getType() & $Affiliation::TYPE_REQUEST_RESELLER
					&& $Affiliation->getAffiliateID() === $Account->getID()
				) {
					$ApproveSelect->addOption($Affiliation->getAffiliateID(),
						"Approve '" . $Affiliation->getTypeText() . "' - " . $Affiliation->getAffiliateID()
					);
				}
			}
		);

		if(!$Request instanceof IFormRequest)
			return $Form;

		$submit = $Request[self::PARAM_SUBMIT];

		switch($submit) {
			case 'update':
				$status = $Form->validateField($Request, self::PARAM_ACCOUNT_STATUS);
				$AccountEntry->update($Request, $Account, $status);
				return new RedirectResponse(ManageAccount::getRequestURL($this->getAccountID()), "Account updated successfully. Redirecting...", 5);

			case 'delete':
				AccountEntry::delete($Request, $this->getAccountID());
				return new RedirectResponse(SearchAccounts::getRequestURL(), "Account deleted successfully. Redirecting...", 5);

			case 'approve':
				$affiliateID = $Form->validateField($Request, self::PARAM_APPROVE_AFFILIATE_ID);
				AccountAffiliationEntry::approveAffiliation($Request, $this->getAccountID(), $affiliateID);
				return new RedirectResponse(SearchAccounts::getRequestURL(), "Account deleted successfully. Redirecting...", 5);

			case 'request':
				$affiliateID = $Form->validateField($Request, self::PARAM_AFFILIATE_ID);
				$type = $Form->validateField($Request, self::PARAM_AFFILIATE_TYPE);

				AccountAffiliationEntry::setAffiliate($Request, $this->getAccountID(), $affiliateID, $type);
				return new RedirectResponse(ManageAccount::getRequestURL($this->getAccountID()), "Affiliation requested successfully. Redirecting...", 5);
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
