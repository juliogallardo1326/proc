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

