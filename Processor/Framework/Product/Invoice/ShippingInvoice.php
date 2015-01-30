<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/29/2015
 * Time: 9:56 AM
 */
namespace Processor\Framework\Product\Invoice;

class ShippingInvoice extends AbstractInvoice
{
	public $title;

	public $date;
	public $order;

	public $total;

	public $name;
	public $address;
	public $address2;
	public $city;
	public $state;
	public $zip;
	public $country;

	/** @var ShippingInvoiceItem[] */
	private $items;

	/**
	 * @return mixed
	 */
	function getInvoiceTotal() {
		return $this->total;
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
		if($this->order)
			$export .= "\nOrder No.: " . $this->order;

		$export .= "\n";
		$export .= "\nName:    " . $this->name;
		$export .= "\nAddress: " . $this->address;
		$export .= "\n         " . $this->address2;
		$export .= "\nCity:    " . $this->city;
		$export .= "\nState:   " . $this->state;
		$export .= "\nZip:     " . $this->zip;
		$export .= "\nCountry: " . $this->country;

		return $export;
	}
}


class ShippingInvoiceItem {

}