<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 11/20/14
 * Time: 1:11 PM
 */
namespace Processor\DB;

use BC\Config;

class DBConfig
{
	static $DB_USERNAME = 'root';
	static $DB_PASSWORD = null;
	static $DB_NAME = 'processor';
	static $DB_PORT = 3306;
	static $DB_HOST = 'localhost';
	static $GrantSalt = 'eS6bZZlQaKM66ZZCGetAJVGJA6ZfZ3UsUbw';
	static $GrantContentPath;

	static function getContentPath($additionalPath=null) {
		return self::$GrantContentPath . ($additionalPath ? '/' . $additionalPath : '');
	}
}

