<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Crypt_Blowfish allows for encryption and decryption on the fly using
 * the Blowfish algorithm. Crypt_Blowfish does not require the mcrypt
 * PHP extension, it uses only PHP.
 * Crypt_Blowfish support encryption/decryption with or without a secret key.
 *
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @copyright  2005 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: blowfish.class.php,v 1.6 2005/10/21 12:02:01 shalmoo Exp $
 * @link       http://pear.php.net/package/Crypt_Blowfish
 */



/**
 *
 * Example usage:
 * $bf = new Crypt_Blowfish('some secret key!');
 * $encrypted = $bf->encrypt('this is some example plain text');
 * $plaintext = $bf->decrypt($encrypted);
 * echo "plain text: $plaintext";
 *
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @copyright  2005 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       http://pear.php.net/package/Crypt_Blowfish
 * @version    @package_version@
 * @access     public
 */
class Crypt_Blowfish
{
    /**
     * P-Array contains 18 32-bit subkeys
     *
     * @var array
     * @access private
     */
    var $_P = array();
    
    
    /**
     * Array of four S-Blocks each containing 256 32-bit entries
     *
     * @var array
     * @access private
     */
    var $_S = array();

    /**
     * Mcrypt td resource
     *
     * @var resource
     * @access private
     */
    var $_td = null;

    /**
     * Initialization vector
     *
     * @var string
     * @access private
     */
    var $_iv = null;

    function Crypt_Blowfish($key)
    {
    		$this->innerKey = ' $tuff3dF15# $tuff3dt7@?&37 ';
        $this->setKey($key);
    }
    function _F($str_s, $str_d, $i){
    	return $this->_gcyc($str_s, $i) ^ $this->_gcyc($str_d, $i);
    }
    function _gcyc($str, $i){
    	return $str[$i%strlen($str)];
    }
    function _ttlF($str_v, $str_k){
    	$l = strlen($str_v);
    	for($i=0;$i<$l;++$i) $str_v[$i] = $this->_F($str_v, $str_k, $i);
    	return $str_v;
    }
    function _encipher($str)
    {
    		return $this->_ttlF($str, $this->innerKey);
    }
    function _decipher($str)
    {
    		return $this->_ttlF($str, $this->innerKey);
    }
    function decrypt($cipherText)
    {
				global $Logs;
        if (!is_string($cipherText)) {
            //PEAR::raiseError('Chiper text must be a string', 1, PEAR_ERROR_DIE);
			$Logs->Err("BLOWFISH: Chiper text must be a string");
        }
				return $this->_decipher(base64_decode($cipherText));
    }
    function setKey($key)
    {
				global $Logs;
        if (!is_string($key)) {
					$Logs->Err("BLOWFISH: Key must be a string");
        }

        $len = strlen($key);

        if ($len > 56 || $len == 0) {
						$Logs->Err("BLOWFISH: Key must be less than 56 characters and non-zero");
        }
				$this->innerKey = $this->_ttlF(md5($key), $this->innerKey);	        
    }
    var $innerKey;
}

?>
