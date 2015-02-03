<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 2/2/2015
 * Time: 4:52 PM
 */
namespace Processor\Account\Types;

class MerchantAccount extends AbstractAccountType
{
	const TYPE_NAME = 'merchant';

	function getTypeName() { return static::TYPE_NAME; }
}