<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\Wallet\Type;


use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Request\Validation\IRequestValidation;
use Processor\Config;
use Processor\DB\Schema\WalletEntry;

abstract class AbstractWallet implements IRequestValidation
{
	const TYPE_NAME = null;
	const TYPE_DESCRIPTION = null;

	private $mFields;
	function __construct($walletContent=array()) {
		if(is_string($walletContent))
			$walletContent = json_decode($walletContent, true);
		$this->mFields = $walletContent;
	}

	/**
	 * @param IRequest $Request
	 * @return HTMLForm
	 */
	abstract function getFieldSet(IRequest $Request);

	function getTypeName() {
		return static::TYPE_NAME;
	}

	public function getDescription() {
		return static::TYPE_DESCRIPTION;
	}

	// Static

	static function loadWalletByType($typeName, $walletContent=null) {
		$className = __NAMESPACE__ . '\\' . ucfirst($typeName) . 'Wallet';
		return new $className($walletContent);
	}


	/**
	 * @param ISessionRequest $Request
	 * @return WalletEntry[]
	 */
	public static function loadSessionWallets(ISessionRequest $Request) {
		return array();
	}

	/**
	 * @return AbstractWallet[]
	 */
	static function loadAllWalletTypes() {
		$WalletTypes = array();
		foreach(Config::$AvailableWalletTypes as $typeName) {
			$WalletTypes[$typeName] = self::loadWalletByType($typeName);
		}
		return $WalletTypes;
	}

}