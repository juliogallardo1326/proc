<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 2/6/2015
 * Time: 9:57 AM
 */
namespace Processor\Account\Types;

class ProcessorAccount extends AbstractAccountType
{
	const TYPE_NAME = 'processor';

	function getTypeName() { return static::TYPE_NAME; }
}