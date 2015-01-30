<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/29/2015
 * Time: 9:46 AM
 */
namespace Processor\Framework\Product\Invoice;

use Processor\Framework\Product\AbstractProduct;
use Processor\Wallet\Type\AbstractWallet;

abstract class AbstractInvoice
{
	/**
	 * @return mixed
	 */
	abstract function getInvoiceTotal();

	/**
	 * @return AbstractProduct
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
