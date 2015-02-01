<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 2:11 PM
 */
namespace Processor\Wallet\Type;

class AmexWallet extends AbstractCreditCardWallet
{
	const TYPE_NAME = 'amex';
	const TYPE_DESCRIPTION = 'American Express Credit Card';

	function __construct() {
		parent::__construct();
	}
}