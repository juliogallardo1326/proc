<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/30/2015
 * Time: 8:11 PM
 */
namespace Processor\PaymentSource\Sources;

use CPath\Data\Map\IKeyMapper;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\Form\HTMLSelectField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Request\IRequest;
use CPath\Request\Validation\RequiredValidation;
use CPath\Response\IResponse;
use CPath\Response\Response;
use Processor\Transaction\Currency;
use Processor\Transaction\DB\TransactionEntry;
use Processor\Wallet\Type\AbstractWallet;

class TestPaymentSource extends AbstractPaymentSource
{
	const SOURCE_NAME = 'test';
	const SOURCE_DESCRIPTION = 'Test Payment Source';
	const CLS_FIELDSET_PAYMENT_SOURCE = 'fieldset-payment-source';

	const PARAM_SOURCE_TITLE = 'source-title';
	const PARAM_SOURCE_STATUS = 'source-status';
	const PARAM_SOURCE_CURRENCY = 'source-currency';

	public $created;
	public $currency;

	/**
	 * @param IRequest $Request
	 * @return HTMLForm
	 */
	function getFieldSet(IRequest $Request) {
		$Fieldset = new HTMLElement('fieldset', self::CLS_FIELDSET_PAYMENT_SOURCE . ' fieldset-' . self::SOURCE_NAME,

			new HTMLElement('legend', 'legend-' . self::SOURCE_NAME, static::SOURCE_DESCRIPTION),

			new HTMLElement('label', 'label-' . self::PARAM_SOURCE_TITLE, "Title<br/>",
				new HTMLInputField(self::PARAM_SOURCE_TITLE, $this->title,
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_SOURCE_CURRENCY, "Currency<br/>",
				$SelectCurrency = new HTMLSelectField(self::PARAM_SOURCE_CURRENCY, Currency::$CurrencyOptions,
					new RequiredValidation()
				)
			)
		);

		$SelectCurrency->setInputValue($this->currency ?: 'USD');
		return $Fieldset;
	}

	/**
	 * Generate a hash value for this source
	 * @return String
	 */
	function getPaymentSourceHash() {
		$text = static::SOURCE_NAME
			. $this->title;
		$text = strtolower($text);
		$text = preg_replace('/[^a-z0-9]/', '', $text);
		$hash = sha1($text);
		return $hash;
	}

	/**
	 * Get payment currency for this source
	 * @return String
	 */
	function getCurrency() {
		return $this->currency;
	}

	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	function validateRequest(IRequest $Request, HTMLForm $ThrowForm=null) {
		$Form = new HTMLForm('POST',
			$this->getFieldSet($Request)
		);
		$Form->validateRequest($Request, $ThrowForm);

		$this->title = $Request[self::PARAM_SOURCE_TITLE];
		$this->currency = $Request[self::PARAM_SOURCE_CURRENCY];
		$this->created ?: $this->created = time();
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Map->map('title', $this->getTitle());
		$Map->map('description', $this->getDescription());
		$Map->map('created', $this->created);
		$Map->map('currency', $this->getCurrency());
	}

	/**
	 * Return a list of wallet types available to this product
	 * @param AbstractWallet $Wallet
	 * @return IResponse
	 */
	function executeWalletTransaction(AbstractWallet $Wallet) {
		return new Response("SUCCESS", TransactionEntry::STATUS_APPROVED);
	}

	/**
	 * Returns true if this wallet is supported
	 * @param $ChosenWallet
	 * @return bool
	 */
	function supportsWalletType($ChosenWallet) {
		return true;
	}


}