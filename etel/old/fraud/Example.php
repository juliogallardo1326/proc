#!/usr/bin/php -q
<?php
require("CreditCardFraudDetection.php");

//first we create a new CreditCardFraudDetection object
$ccfs = new CreditCardFraudDetection;

// Set inputs and store them in a hash
// See http://www.maxmind.com/app/ccv for more details on the input fields

// Enter your license key here (non registered users limited to 20 lookups per day)
// $h["license_key"] = "YOUR_LICENSE_KEY_HERE";

// Required fields
$h["i"] = "24.24.24.24";             // set the client ip address
$h["city"] = "New York";             // set the billing city
$h["region"] = "NY";                 // set the billing state
$h["postal"] = "11434";              // set the billing zip code
$h["country"] = "US";                // set the billing country

// Recommended fields
$h["domain"] = "yahoo.com";		// Email domain
$h["bin"] = "549099";			// bank identification number
$h["forwardedIP"] = "24.24.24.25";	// X-Forwarded-For or Client-IP HTTP Header
$h["custPhone"] = "212-242";		// Area-code and local prefix of customer phone number

// Optional fields
$h["binName"] = "MBNA America Bank";	// bank name
$h["binPhone"] = "800-421-2110";	// bank customer service phone number on back of credit card
$h["requested_type"] = "premium";	// Which level (free, city, premium) of CCFD to use
$h["emailMD5"] = "Adeeb@Hackstyle.com"; // CreditCardFraudDetection.php will take
// MD5 hash of e-mail address passed to emailMD5 if it detects '@' in the string
$h["shipAddr"] = "145-50 157TH STREET";	// Shipping Address
$h["txnID"] = "1234";			// Transaction ID
$h["sessionID"] = "abcd9876";		// Session ID

// If you want to disable Secure HTTPS or don't have Curl and OpenSSL installed
// uncomment the next line
// $ccfs->isSecure = 0;

//set the time out to be five seconds
$ccfs->timeout = 5;

//uncomment to turn on debugging
// $ccfs->debug = 1;

//next we pass the input hash to the server
$ccfs->input($h);

//then we query the server
$ccfs->query();

//then we get the result from the server
$h = $ccfs->output();

//then finally we print out the result
$outputkeys = array_keys($h);
$numoutputkeys = count($h);
for ($i = 0; $i < $numoutputkeys; $i++) {
  $key = $outputkeys[$i];
  $value = $h[$key];
  print $key . " = " . $value . "\n";
}
?>
