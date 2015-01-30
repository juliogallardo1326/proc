<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:05 AM
 */
namespace Processor\Wallet\Type;

use Processor\Wallet\Type\AbstractCreditCardWallet;

class VisaWallet extends AbstractCreditCardWallet
{
	const TYPE_NAME = 'visa';
	const TYPE_DESCRIPTION = 'Visa Credit Card';

	function __construct($walletContent = array()) {
		parent::__construct($walletContent);
	}
}



