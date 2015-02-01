<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/29/2015
 * Time: 9:46 AM
 */
namespace Processor\Invoice\Types;

use CPath\Data\Map\IKeyMap;
use Processor\Product\Types\AbstractProductType;
use Processor\Wallet\Type\AbstractWallet;

abstract class AbstractInvoice implements IKeyMap
{
	/**
	 * @return mixed
	 */
	abstract function getInvoiceTotal();

	/**
	 * @return AbstractProductType
	 */
	abstract function getProduct();

	/**
	 * @return AbstractWallet
	 */
	abstract function getWallet();

	/**
	 * Export product invoice to string
	 * @return mixed
	 */
	abstract function exportToString();

	function __toString() { return $this->exportToString(); }
}
