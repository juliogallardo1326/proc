PHP ACH Direct Samples

Unzip file and insert contents into web directory.

Zip file contains four files

cc_purchase.htm      Html form for Credit Card purchase
check_purchase.htm   Html form for Check purchase

results_approved.php PHP approval page
results_error.php     PHP error page


Changes that need to be made.

cc_purchase.htm
check_purchase.htm

Merchant ID 
Enter your Merchant ID in the hidden field pg_merchant_id

Password
Enter your Password in the hidden field pg_password

Also included is a file named "php_curl.php".
This file is provided as an example on how to use Curl and PHP together to post securely to PaymentsGateway.net.
(Note this example is provided for developers wanting only a string returned, not a url.)

Helpful Links

PHP Website
http://www.php.net

Referernce to Curl on PHP's Website
http://www.php.net/manual/en/ref.curl.php

Curl Website
http://curl.haxx.se/ 
