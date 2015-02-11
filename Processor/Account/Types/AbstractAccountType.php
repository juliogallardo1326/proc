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
use CPath\Request\Validation\RequiredValidation;
use Processor\Account\Exceptions\InvalidAccountPassword;

abstract class AbstractAccountType implements \Serializable, IKeyMap
{
	const CLS_FIELDSET_ACCOUNT = 'fieldset-account';
	const PARAM_ACCOUNT_TYPE = 'account-type';
	const PARAM_ACCOUNT_NAME = 'account-name';
	const PARAM_ACCOUNT_PASSWORD = 'account-password';

	public $email;
	public $name;
	public $pass;

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

	function tryPassword($password) {
		if (crypt($password, $this->pass) !== $this->pass)
			throw new InvalidAccountPassword("Invalid password");
	}

	/**
	 * @param IRequest $Request
	 * @param bool $withConfirmField
	 * @return HTMLElement
	 */
	function getFieldSet(IRequest $Request, $withConfirmField=true) {
		return new HTMLElement('fieldset', self::CLS_FIELDSET_ACCOUNT,
			new Attributes('data-' . static::PARAM_ACCOUNT_TYPE, $this->getTypeName()),

			new HTMLElement('legend', 'legend-shipping', ucfirst($this->getTypeName()) . " Account"),

			new HTMLElement('label', 'label-' . self::PARAM_ACCOUNT_NAME, "Email<br/>",
				new HTMLInputField(self::PARAM_ACCOUNT_NAME, $this->email,
					new Attributes('placeholder', '"my@email.com"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_ACCOUNT_NAME, "Name<br/>",
				new HTMLInputField(self::PARAM_ACCOUNT_NAME, $this->name,
					new Attributes('placeholder', '"myuser"'),
					new RequiredValidation()
				)
			),

			"<br/><br/>",
			new HTMLElement('label', 'label-' . self::PARAM_ACCOUNT_PASSWORD, "Password<br/>",
				new HTMLInputField(self::PARAM_ACCOUNT_PASSWORD, $this->pass ? '****' : null, 'password',
					new RequiredValidation()
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

		if(!empty($Request[self::PARAM_ACCOUNT_PASSWORD])) {
			$salt = uniqid('', true);
			$this->pass = crypt($Request[self::PARAM_ACCOUNT_PASSWORD], $salt);
		}
		if(!empty($Request[self::PARAM_ACCOUNT_NAME]))
			$this->name = $Request[self::PARAM_ACCOUNT_NAME];
	}

	public function __construct() {

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
		$Map->map('type', $this->getTypeName());
	}

	// Static

	/**
	 * @return AbstractAccountType[]
	 */
	static function loadAllAccountTypes() {
		return array(
			new MerchantAccount(),
			new ResellerAccount(),
			new AdministratorAccount(),
			new ProcessorAccount(),
		);
	}
}

