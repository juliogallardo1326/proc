<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\PaymentSource\Sources;


use CPath\Data\Map\IKeyMap;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Request\IRequest;
use CPath\Request\Validation\IRequestValidation;
use CPath\Response\IResponse;
use Processor\Wallet\Type\AbstractWallet;

abstract class AbstractPaymentSource implements IRequestValidation, IKeyMap
{
	const SOURCE_NAME = null;
	const SOURCE_DESCRIPTION = null;

	public $title;

	/**
	 * @param IRequest $Request
	 * @return HTMLForm
	 */
	abstract function getFieldSet(IRequest $Request);

	/**
	 * Returns true if this wallet is supported
	 * @param $ChosenWallet
	 * @return bool
	 */
	abstract function supportsWalletType($ChosenWallet);

	/**
	 * Return a list of wallet types available to this product
	 * @param AbstractWallet $Wallet
	 * @return IResponse
	 */
	abstract function executeWalletTransaction(AbstractWallet $Wallet);

	/**
	 * Generate a hash value for this source
	 * @return String
	 */
	abstract function getPaymentSourceHash();

	/**
	 * Get payment currency for this source
	 * @return String
	 */
	abstract function getCurrency();

	function getTypeName() {
		return static::SOURCE_NAME;
	}

	public function getTitle() {
		return $this->title;
	}

	public function getDescription() {
		return static::SOURCE_DESCRIPTION;
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

	// Static

	/**
	 * @return AbstractPaymentSource[]
	 */
	static function loadAllPaymentSourceTypes() {
		return array(
			new TestPaymentSource()
		);
	}

}

