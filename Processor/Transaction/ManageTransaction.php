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
use CPath\Data\Map\CallbackSequenceMap;
use CPath\Data\Map\ISequenceMapper;
use CPath\Render\HTML\Attribute\Attributes;
use CPath\Render\HTML\Element\Form\HTMLButton;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\Form\HTMLTextAreaField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\HTML\Element\Table\HTMLSequenceTableBody;
use CPath\Render\HTML\Element\Table\HTMLTable;
use CPath\Render\HTML\Header\HTMLHeaderScript;
use CPath\Render\HTML\Header\HTMLHeaderStyleSheet;
use CPath\Render\HTML\Header\HTMLMetaTag;
use CPath\Render\Map\MapRenderer;
use CPath\Request\Executable\ExecutableRenderer;
use CPath\Request\Executable\IExecutable;
use CPath\Request\Form\IFormRequest;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\Common\RedirectResponse;
use CPath\Response\IResponse;
use CPath\Route\IRoutable;
use CPath\Route\RouteBuilder;
use Processor\Profit\DB\ProfitEntry;
use Processor\SiteMap;
use Processor\Transaction\DB\TransactionEntry;

class ManageTransaction implements IExecutable, IBuildable, IRoutable
{
	const TITLE = 'Manage Transaction';

	const FORM_FORMAT = '/t/%s';
	const FORM_ACTION = '/t/:id';
	const FORM_ACTION2 = '/transaction/:id';
	const FORM_ACTION3 = '/manage/transaction/:id';
	const FORM_METHOD = 'POST';
	const FORM_NAME = __CLASS__;

	const PARAM_ID = 'id';
	const PARAM_TRANSACTION_STATUS = 'transaction-status';
	const PARAM_LOG = 'transaction-log';
	const PARAM_SUBMIT = 'submit';

	private $id;

	public function __construct($transactionID) {
		$this->id = $transactionID;
	}

	private function getTransactionID() {
		return $this->id;
	}

	/**
	 * Execute a command and return a response. Does not render
	 * @param IRequest $Request
	 * @return IResponse the execution response
	 */
	function execute(IRequest $Request) {
		$TransactionEntry = TransactionEntry::get($this->getTransactionID());
		$transactionLog = $TransactionEntry->fetchLog();

		$Invoice = $TransactionEntry->getInvoice();
		$Product = $Invoice->getProduct();
		$Wallet = $Invoice->getWallet();


		$accounts = array();
		$merchantProfit = $Product->calculateProfit($TransactionEntry->getStatus(), $accounts);
		$accounts[$Product->getAccountID()] = $merchantProfit;

		$ProfitTBody = new HTMLSequenceTableBody(
			new CallbackSequenceMap(
				function(ISequenceMapper $Map) use ($accounts) {
					foreach($accounts as $accountID => $profit) {
						$Map->mapNext(array(
							'account' => $accountID,
							'profit' => $profit,
						));
					}
				}
			)
		);

		$Form = new HTMLForm(self::FORM_METHOD, $Request->getPath(), self::FORM_NAME,
			new HTMLMetaTag(HTMLMetaTag::META_TITLE, self::TITLE),
			new HTMLHeaderScript(__DIR__ . '/assets/transaction.js'),
			new HTMLHeaderStyleSheet(__DIR__ . '/assets/transaction.css'),

			new HTMLElement('fieldset', 'fieldset-transaction-info inline',
				new HTMLElement('legend', 'legend-transaction-info', 'Transaction Information'),

				new MapRenderer($TransactionEntry)
			),

			new HTMLElement('fieldset', 'fieldset-product-profit inline',
				new HTMLElement('legend', 'legend-product-profit', 'Profit Information'),

				new HTMLTable(
					$ProfitTBody
				)
			),

			"<br/>",

			new HTMLElement('fieldset', 'fieldset-product-container inline',
				new HTMLElement('legend', 'legend-product', 'Order Information'),

				$Wallet->getFieldSet($Request)
					->setAttribute('disabled', 'disabled'),

				$Product->getOrderFieldSet($Request)
					->setAttribute('disabled', 'disabled'),

				$Product->getConfigFieldSet($Request)
					->setAttribute('disabled', 'disabled'),

				$Product->getFeesFieldSet($Request)
					->setAttribute('disabled', 'disabled'),
				"<br/><br/>",

				new HTMLButton('submit', 'Update', 'submit')
//				"<br/>",
			),
			"<br/>",

			new HTMLElement('fieldset', 'fieldset-transaction-log inline',
				new HTMLElement('legend', 'legend-transaction-log', 'Transaction Log'),

				new HTMLTextAreaField(self::PARAM_LOG . '_disabled', $transactionLog,
					new Attributes('rows', 10, 'cols', 100),
					new Attributes('disabled', 'disabled')
				),
				"<br/>",
				new HTMLTextAreaField(self::PARAM_LOG,
					new Attributes('rows', 3, 'cols', 100)
				),

				"<br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Append', 'append-log')
			),

			new HTMLElement('fieldset', 'fieldset-transaction-manage inline',
				new HTMLElement('legend', 'legend-submit', self::TITLE),

				new HTMLInputField(self::PARAM_ID, $this->id, 'hidden',
					new RequiredValidation()
				),

				new HTMLElement('label', null, "Status<br/>",
					$SelectStatus = new HTMLSelectField(self::PARAM_TRANSACTION_STATUS, TransactionEntry::$StatusOptions,
						new RequiredValidation()
					)
				),

				"<br/><br/>",
				new HTMLButton(self::PARAM_SUBMIT, 'Update', 'submit')
			),

			"<br/>"
		);

		$SelectStatus->setInputValue($TransactionEntry->getStatus());

		if(!$Request instanceof IFormRequest)
			return $Form;

		switch($Request[self::PARAM_SUBMIT]) {
			case 'submit':
				$status = $Form->validateField($Request, self::PARAM_TRANSACTION_STATUS);
				$TransactionEntry->update($Request, $status);
				ProfitEntry::update($Request, $TransactionEntry->getID());
				return new RedirectResponse(ManageTransaction::getRequestURL($TransactionEntry->getID()), "Transaction updated successfully. Redirecting...", 5);

			case 'append-log':
				$appendLog = $Request[self::PARAM_LOG];
				$TransactionEntry->appendLog($appendLog);
				return new RedirectResponse(ManageTransaction::getRequestURL($TransactionEntry->getID()), "Log appended successfully. Redirecting...", 5);

			default:
				throw new \InvalidArgumentException("Invalid Submit");
		}


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