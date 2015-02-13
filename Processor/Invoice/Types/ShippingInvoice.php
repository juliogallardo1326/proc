<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/30/2015
 * Time: 8:59 PM
 */
namespace Processor\Invoice\Types;

use CPath\Data\Map\IKeyMapper;
use CPath\Render\Text\IRenderText;
use CPath\Request\IRequest;
use Processor\Framework\Product\Invoice\ShippingInvoiceItem;
use Processor\Product\Types\ShippingProduct;
use Processor\Wallet\Type\AbstractWallet;

class ShippingInvoice extends AbstractInvoice implements IRenderText
{
	public $title;
	public $total;
	public $order;

	/** @var ShippingInvoiceItem[] */
	public $items;

	public $Product;
	public $Wallet;

	function __construct(ShippingProduct $Product, AbstractWallet $Wallet) {
		$this->Product = $Product;
		$this->Wallet = clone $Wallet;
		$this->Wallet->sanitize();

		$this->total = $Product->getTotalCost();
		$this->date = time();
		$this->title = $Product->getProductTitle();
	}

	/**
	 * @return mixed
	 */
	function getInvoiceTotal() {
		return $this->total;
	}

	/**
	 * @return ShippingProduct
	 */
	function getProduct() {
		return $this->Product;
	}

	/**
	 * @return AbstractWallet
	 */
	function getWallet() {
		return $this->Wallet;
	}


	/**
	 * Export product invoice to string
	 * @return mixed
	 */
	function exportToString() {
		$export = '';

		$export .= "\nInvoice: " . $this->title;
		$export .= "\n";
		$export .= "\nDate: " . date('l jS \of F Y h:i:s A', $this->date);
		if ($this->order)
			$export .= "\nOrder No.: " . $this->order;

		$export .= $this->getProduct()->exportToString();
		$export .= $this->getWallet()->exportToString();

		return $export;
	}

	/**
	 * Map data to the key map
	 * @param IKeyMapper $Map the map inst to add data to
	 * @internal param \CPath\Request\IRequest $Request
	 * @internal param \CPath\Request\IRequest $Request
	 * @return void
	 */
	function mapKeys(IKeyMapper $Map) {
		$this->getProduct()->mapKeys($Map);
		$this->getWallet()->mapKeys($Map);
	}

	/**
	 * Render request as plain text
	 * @param IRequest $Request the IRequest inst for this render which contains the request and remaining args
	 * @return String|void always returns void
	 */
	function renderText(IRequest $Request) {
		echo $this->exportToString();
	}
}