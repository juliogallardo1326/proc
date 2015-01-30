<!--
// Copyright (c) 1998 Sudhakar Chandrasekharan (thaths@netscape.com)
// All rights reserved
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; version 2 dated June, 1991.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA
// 02111-1307, USA.

// Thanks to Martin Honnen (Martin.Honnen@sector27.de) for some coding
// tips.

// Funtion to return the type of credit card
function typeOfCard(number) {
	/* 
	//	Card Prefixes
	//
	//	Mastercard	51-55
	//	Visa		4
	//	AmEx		34,37
	//	Discover	6011
	*/

	var firstNumber = number.substring(0,1);
	var firstThreeNumbers = number.substring(0,3);

	if (firstNumber == 4) {
		return "VISA";
	} 

	var firstTwoNumbers = number.substring(0,2);
	if (firstTwoNumbers > 50 && firstTwoNumbers < 56) {
		return "MASTER";
	}

	if (firstTwoNumbers == 34 || firstTwoNumbers == 37) {
		return "AMEX";
	}

	var firstFourNumbers = number.substring(0,4);
	if (firstFourNumbers == 6011) {
		return "DISCOVER";
	}
	
	return "Invalid Card Number";
}

// Function that determines whether a credit card number is valid
// Please note that a valid credit card number is not essentially a
// credit card in good standing.
function isValidCreditCard(number) {
	var total = 0;
	var flag = 0;
	
	if(number.length<=12) return false;
	
	for (var i=(number.length - 1);i>=0; i--) {
		if (flag == 1) {
			var digits = number.charAt(i) * 2;
			if (digits > 9) digits -= 9;
			total += digits;
//			var reminder = digits % 10;
//			var quotient = (digits - reminder) / 10;
//			total = total + parseInt(reminder);
//			total = total + parseInt(quotient);
			flag = 0;
		} else {
			total = total + parseInt(number.charAt(i));
			flag = 1;
		}
	}
	if ((total%10) == 0) {
		return true;
	} else {
		return false;
	}
}
// -->

