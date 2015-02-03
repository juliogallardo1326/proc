<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 2/2/2015
 * Time: 4:52 PM
 */
namespace Processor\Account\Types;

class CustomerServiceAccount extends AbstractAccountType
{
	const TYPE_NAME = 'customer-service';

	function getTypeName() { return static::TYPE_NAME; }
}