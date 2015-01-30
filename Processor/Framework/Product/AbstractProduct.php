<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\Framework\Product;


use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Request\IRequest;

abstract class AbstractProduct
{
	const TYPE_NAME = null;
	const TYPE_DESCRIPTION = null;

	const CLS_FIELDSET_PRODUCT = 'fieldset-product';

	abstract function getTotalCost();

	/**
	 * @param IRequest $Request
	 * @return HTMLForm
	 */
	abstract function getFieldSet(IRequest $Request);

	/**
	 * @param IRequest $Request
	 * @return \Processor\Framework\Product\Invoice\AbstractInvoice
	 */
	abstract function createNewInvoice(IRequest $Request);

	public function getDescription() {
		return static::TYPE_DESCRIPTION;
	}
}


