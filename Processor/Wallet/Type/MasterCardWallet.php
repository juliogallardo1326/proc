<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 2:11 PM
 */
namespace Processor\Wallet\Type;

use Processor\Wallet\Type\AbstractCreditCardWallet;

class MasterCardWallet extends AbstractCreditCardWallet
{
	const TYPE_NAME = 'mastercard';
	const TYPE_DESCRIPTION = 'MasterCard Credit Card';

	function __construct($walletContent = array()) {
		parent::__construct($walletContent);
	}
}