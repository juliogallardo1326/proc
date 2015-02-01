<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 2:11 PM
 */
namespace Processor\Wallet\Type;

class DiscoverWallet extends AbstractCreditCardWallet
{
	const TYPE_NAME = 'discover';
	const TYPE_DESCRIPTION = 'Discover Credit Card';

	function __construct() {
		parent::__construct();
	}
}