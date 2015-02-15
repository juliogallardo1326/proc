<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\Account\Types;


use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Render\HTML\Attribute\Attributes;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\Form\HTMLInputField;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Request\Validation\EmailValidation;
use CPath\Request\Validation\RequiredValidation;
use Processor\Account\DB\AccountEntry;
use Processor\Account\Exceptions\InvalidAccountPassword;

abstract class AbstractAccountType implements \Serializable, IKeyMap
{
	const CLS_FIELDSET_ACCOUNT = 'fieldset-account';
	const PARAM_ACCOUNT_TYPE = 'account-type';
	const PARAM_ACCOUNT_NAME = 'account-name';
	const PARAM_ACCOUNT_PASSWORD = 'account-password';
	const PARAM_ACCOUNT_EMAIL = 'account-email';
	const PASS_BLANK = '****';

	public $id;
	public $email;
	public $name;
	public $pass;

	/**
	 * Get Account Name
	 * @return String
	 */
	function getID() { return $this->id; }

	public function setID($id) {
		if(!$id)
			throw new \InvalidArgumentException("Invalid ID");
		$this->id = $id;
	}

	/**
	 * Get Account Name
	 * @return String
	 */
	function getAccountName() { return $this->name; }

	/**
	 * Get Account Email
	 * @return String
	 */
	function getAccountEmail() { return $this->email; }

	abstract function getTypeName();


	function assertPassword($password) {
		if (crypt($password, $this->pass) !== $this->pass) {
			throw new InvalidAccountPassword("Invalid password");
		}
	}

	/**
	 * @return HTMLElement
	 */
	function getFieldSet() {
		return new HTMLElement('fieldset', self::CLS_FIELDSET_ACCOUNT,
			new Attributes('data-' . static::PARAM_ACCOUNT_TYPE, $this->getTypeName()),

			new HTMLElement('legend', 'legend-shipping', ucfirst($this->getTypeName()) . " Account"),


			new HTMLElement('label', 'label-' . self::PARAM_ACCOUNT_NAME, "Name<br/>",
				new HTMLInputField(self::PARAM_ACCOUNT_NAME, $this->name,
					new Attributes('placeholder', '"myuser"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_ACCOUNT_EMAIL, "Email<br/>",
				new HTMLInputField(self::PARAM_ACCOUNT_EMAIL, $this->email,
					new Attributes('placeholder', '"my@email.com"'),
					new EmailValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_ACCOUNT_PASSWORD, "Password<br/>",
				new HTMLInputField(self::PARAM_ACCOUNT_PASSWORD, null, 'password'
//					new RequiredValidation()
				)
			)
		);
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

		$pass = $Request[self::PARAM_ACCOUNT_PASSWORD];
		if($pass && $pass !== self::PASS_BLANK) {
			$salt = uniqid('', true);
			$this->pass = crypt($Request[self::PARAM_ACCOUNT_PASSWORD], $salt);
		}
		$this->name = $Request[self::PARAM_ACCOUNT_NAME];
		$this->email = $Request[self::PARAM_ACCOUNT_EMAIL];
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize() {
		$values = (array)$this;
		foreach(array_keys($values) as $key)
			if($values[$key] === null)
				unset($values[$key]);
		return json_encode($values);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return void
	 */
	public function unserialize($serialized) {
		foreach(json_decode($serialized, true) as $name => $value)
			$this->$name = $value;
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$Map->map('name', $this->getAccountName());
		$Map->map('email', $this->getAccountEmail());
		$Map->map('type', $this->getTypeName());
	}

	public function startSession(ISessionRequest $SessionRequest) {
		$SessionRequest->startSession();
		$Session = &$SessionRequest->getSession();
		$Session[AccountEntry::SESSION_KEY] = serialize($this);
		$SessionRequest->endSession();
//		$Account = self::loadFromSession($SessionRequest);
	}

	// Static

	static function loadFromSession(ISessionRequest $SessionRequest) {
		if(!$SessionRequest->isStarted())
			$SessionRequest->startSession();
		$Session = $SessionRequest->getSession();

		/** @var AbstractAccountType $Account */
		$Account = unserialize($Session[AccountEntry::SESSION_KEY]);
		if(!$Account) {
			$SessionRequest->destroySession();
		}
		$SessionRequest->endSession();

		return $Account;
	}

	static function hasActiveSession(ISessionRequest $SessionRequest) {
		if(!$SessionRequest->isStarted())
			$SessionRequest->startSession();
		$Session = $SessionRequest->getSession();

		$active = !empty($Session[AccountEntry::SESSION_KEY]);
		$SessionRequest->endSession();
		return $active;
	}

	/**
	 * @return AbstractAccountType[]
	 */
	static function loadAllAccountTypes() {
		return array(
			new MerchantAccount(),
			new ResellerAccount(),
			new AdministratorAccount(),
			new ProcessorAccount(),
			new CustomerServiceAccount(),
		);
	}
}

