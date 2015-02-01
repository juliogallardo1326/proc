<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\Product\Types;


use CPath\Data\Map\IKeyMap;
use CPath\Data\Map\IKeyMapper;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Render\HTML\Element\HTMLElement;
use CPath\Render\Text\IRenderText;
use CPath\Request\IRequest;
use CPath\Request\Validation\IRequestValidation;
use Processor\Wallet\Type\AbstractWallet;

abstract class AbstractProductType implements \Serializable, IKeyMap, IRenderText
{
	const TYPE_NAME = null;
	const TYPE_DESCRIPTION = null;

	const CLS_FIELDSET_PRODUCT = 'fieldset-product';
	const CLS_FIELDSET_CONFIG = 'fieldset-product-config';

	/**
	 * @return String
	 */
	abstract function getTitle();

	/**
	 * @return mixed
	 */
	abstract function getTotalCost();

	/**
	 * @param IRequest $Request
	 * @return HTMLElement
	 */
	abstract function getOrderFieldSet(IRequest $Request);


	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	abstract function validateOrderRequest(IRequest $Request, HTMLForm $ThrowForm=null);

	/**
	 * @param IRequest $Request
	 * @return HTMLElement
	 */
	abstract function getConfigFieldSet(IRequest $Request);

	/**
	 * Validate the request
	 * @param IRequest $Request
	 * @param HTMLForm $ThrowForm
	 * @throws \CPath\Request\Validation\Exceptions\ValidationException
	 * @return array|void optionally returns an associative array of modified field names and values
	 */
	abstract function validateConfigRequest(IRequest $Request, HTMLForm $ThrowForm=null);

	/**
	 * @param IRequest $Request
	 * @param AbstractWallet $Wallet
	 * @return \Processor\Invoice\Types\AbstractInvoice
	 */
	abstract function createNewInvoice(IRequest $Request, AbstractWallet $Wallet);

	/**
	 * Export to string
	 * @return String
	 */
	abstract function exportToString();

	public function __construct() {

	}

	public function getTypeName() {
		return static::TYPE_NAME;
	}

	public function getDescription() {
		return static::TYPE_DESCRIPTION;
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
		$Map->map('title', $this->getTitle());
		$Map->map('description', $this->getDescription());
		$Map->map('total', $this->getTotalCost());
		$Map->map('type', $this->getTypeName());
	}

	/**
	 * Render request as plain text
	 * @param IRequest $Request the IRequest inst for this render which contains the request and remaining args
	 * @return String|void always returns void
	 */
	function renderText(IRequest $Request) {
		echo $this->exportToString();
	}



	// Static

	/**
	 * @return AbstractProductType[]
	 */
	static function loadAllProductTypes() {
		return array(
			new ShippingProduct()
		);
	}
}


