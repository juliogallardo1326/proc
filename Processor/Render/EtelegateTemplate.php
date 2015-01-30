<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/29/2015
 * Time: 2:39 PM
 */
namespace Processor\Render;

class EtelegateTemplate extends DefaultTemplate
{
	public function __construct($_content = null) {
		parent::__construct();
		$this->addHeaderStyleSheet(__DIR__ . '/assets/etelegate-template.css');
		$this->addAll(func_get_args());
	}
}