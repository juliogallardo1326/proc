<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 10:04 AM
 */
namespace Processor\Wallet\Type;


use CPath\Data\Map\IKeyMap;
use CPath\Render\HTML\Element\Form\HTMLForm;
use CPath\Request\IRequest;
use CPath\Request\Session\ISessionRequest;
use CPath\Request\Validation\IRequestValidation;
use Processor\Config;
use Processor\Wallet\DB\WalletEntry;

abstract class AbstractWallet implements IRequestValidation, IKeyMap
{
	const TYPE_NAME = null;
	const TYPE_DESCRIPTION = null;

	/**
	 * @param IRequest $Request
	 * @return HTMLForm
	 */
	abstract function getFieldSet(IRequest $Request);

	/**
	 * Generate a hash value for this wallet
	 * @return String
	 */
	abstract function getWalletHash();


	abstract function sanitize();

	function __construct() {
	}

	function getTypeName() {
		return static::TYPE_NAME;
	}

	public function getDescription() {
		return static::TYPE_DESCRIPTION;
	}

	abstract function getEmail();

	/**
	 * Export wallet to string
	 * @return String
	 */
	abstract function exportToString();

	// Static

	static function loadWalletByType($typeName, $walletContent=null) {
		$className = __NAMESPACE__ . '\\' . ucfirst($typeName) . 'Wallet';
		return new $className($walletContent);
	}


	/**
	 * @param ISessionRequest $Request
	 * @return WalletEntry[]
	 */
	static function loadSessionWallets(ISessionRequest $Request) {
		return array();
	}

	/**
	 * @return AbstractWallet[]
	 */
	static function loadAllWalletTypes() {
		$WalletTypes = array();
		foreach (Config::$AvailableWalletTypes as $typeName) {
			$WalletTypes[$typeName] = self::loadWalletByType($typeName);
		}

		return $WalletTypes;

	}
}