<?php
/**
 * Project: CleverPath Framework
 * IDE: JetBrains PhpStorm
 * Author: Ari Asulin
 * Email: ari.asulin@gmail.com
 * Date: 4/06/11 */
namespace Processor;

define('CONFIG_CONTENT_PATH', dirname(__DIR__));
class Config {
    static $ProfileSalt = 'QtbeMAJCJlGtZZaJlGbeS6mVGUw';
    static $ContentPath = CONFIG_CONTENT_PATH;

	static $AvailableWalletTypes = array(
		'Visa' => 'visa',
		'MasterCard' => 'mastercard',
		'American Express' => 'amex',
		'Discover' => 'discover',
	);
//	public static $TemplateClass = 'Processor\\Render\\DefaultTemplate';
	public static $TemplateClass = 'Processor\\Render\\NicheBillTemplate';
//	public static $TemplateClass = 'Processor\\Render\\EtelegateTemplate';


	static function getContentPath($additionalPath=null) {
		return self::$ContentPath . ($additionalPath ? '/' . $additionalPath : '');
	}
}

