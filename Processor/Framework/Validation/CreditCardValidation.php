<?php
/**
 * Created by PhpStorm.
 * User: ari
 * Date: 1/28/2015
 * Time: 1:33 PM
 */
namespace Processor\Framework\Validation;

use CPath\Request\Exceptions\RequestException;
use CPath\Request\IRequest;
use CPath\Request\Validation\IValidation;

class CreditCardValidation implements IValidation
{
	/**
	 * Validate the request value and return the validated value
	 * @param IRequest $Request
	 * @param $value
	 * @param null $fieldName
	 * @throws \CPath\Request\Exceptions\RequestException
	 * @return mixed validated value
	 */
	function validate(IRequest $Request, $value = null, $fieldName = null) {
		$value=preg_replace('/\D/', '', $value);
		if (!$this->luhn_check($value))
			throw new RequestException("Invalid Credit Card: " . $value);
		return $value;
	}

	/**
	 * Luhn algorithm number checker - (c) 2005-2008 shaman - www.planzero.org *
	 * This code has been released into the public domain, however please      *
	 * give credit to the original author where possible.
	 * @param $number
	 * @return bool
	 */
	private function luhn_check($number) {

		// Set the string length and parity
		$number_length=strlen($number);
		$parity=$number_length % 2;

		// Loop through each digit and do the maths
		$total=0;
		for ($i=0; $i<$number_length; $i++) {
			$digit=$number[$i];
			// Multiply alternate digits by two
			if ($i % 2 == $parity) {
				$digit*=2;
				// If the sum is two digits, add them together (in effect)
				if ($digit > 9) {
					$digit-=9;
				}
			}
			// Total up the digits
			$total+=$digit;
		}

		// If the total mod 10 equals 0, the number is valid
		return ($total % 10 == 0) ? TRUE : FALSE;

	}
}


//
//Visa: ^4[0-9]{6,}$ Visa card numbers start with a 4.
//
//MasterCard: ^5[1-5][0-9]{5,}$ MasterCard numbers start with the numbers 51 through 55, but this will only detect MasterCard credit cards; there are other cards issued using the MasterCard system that do not fall into this IIN range.
//
//American Express: ^3[47][0-9]{5,}$ American Express card numbers start with 34 or 37.
//
//Diners Club: ^3(?:0[0-5]|[68][0-9])[0-9]{4,}$ Diners Club card numbers begin with 300 through 305, 36 or 38. There are Diners Club cards that begin with 5 and have 16 digits. These are a joint venture between Diners Club and MasterCard, and should be processed like a MasterCard.
//
//Discover: ^6(?:011|5[0-9]{2})[0-9]{3,}$ Discover card numbers begin with 6011 or 65.
//
//JCB: ^(?:2131|1800|35[0-9]{3})[0-9]{3,}$ JCB cards begin with 2131, 1800 or 35.