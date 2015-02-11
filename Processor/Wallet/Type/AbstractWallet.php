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
use Processor\Wallet\DB\WalletTable;

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


	abstract function getTitle();

	abstract function getEmail();

	abstract function sanitize();

	function __construct() {
	}

	function getTypeName() {
		return static::TYPE_NAME;
	}

	public function getDescription() {
		return static::TYPE_DESCRIPTION;
	}

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
		$Table = new WalletTable();
		return $Table
			->select()
			->limit(50);
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



	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * String representation of object
	 * @link http://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize() {
		$values = (array)$this;
		foreach(array_keys($values) as $key)
			if($values[$key] === null)
				unset($values[$key]);
		return json_encode($values);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Constructs the object
	 * @link http://php.net/manual/en/serializable.unserialize.php
	 * @param string $serialized <p>
	 * The string representation of the object.
	 * </p>
	 * @return void
	 */
	public function unserialize($serialized) {
		foreach(json_decode($serialized, true) as $name => $value)
			$this->$name = $value;
	}
}