 
=========================================
ADWATCHER INSTALL GUIDE
=========================================

Thank you for choosing AdWatcher! This guide will show you how to install
AdWatcher on your own server.

-----------------------------------------
REQUIREMENTS
-----------------------------------------
PHP Version 4.3.7 or Higher
mySQL Database
Zend Optimizer
- if you don't have it, you can download it for free at
http://www.zend.com/store/products/zend-optimizer.php or contact your hosting
company to install it for you.

-----------------------------------------
INSTALLATION
-----------------------------------------

1) Unzip the adwatcher.zip file.

2) Upload the main directory “adwatcher” to your web server. You may change 
the directory name “adwatcher” to what you choose.

!!! NOTE: Make sure to upload all files in BINARY mode (NOT ASCII) !!!

3) Chmod the /data/ directory 777.
/inc/settings.inc.php 	666
/inc/version.inc.php	666

4) Run the install file by going to http://yourdomain.com/adwatcher/install.php
and follow the instructions.

5) You will be asked to enter mySQL database information. Majority of control
panels (CPanel, Plesk, Ensim, H-Sphere, etc) allow you to create a database
quickly and easily. If you have questions, contact your hosting provider or us
at info@adwatcher.com and we'll guide you through this process.

6) When you enter user information, make sure that it matches the information
you used when you were signing up with us. This is a security precaution that
prevents our application to be illegally distributed.

7) YOU'RE DONE. After a successful install, go to
http://yourdomain.com/adwatcher/ and login to your application. Make sure to
delete the install.php file!

-----------------------------------------
FURTHER INFORMATION
-----------------------------------------

*** Cron Jobs ***
If you'd like to receive weekly and monthly reports on your ads' performance,
it's necessary to setup cron jobs. 

PLEASE NOTE THAT IT IS RECOMMENDED TO CONTACT YOUR WEB HOST OR SYSTEM ADMIN TO
COMPLETE THIS IF YOU ARE UNSURE ABOUT SETTING CRON JOBS.

If you want to do it yourself, please follow these instructions:

For weekly reports, setup:

weekly - 0 0 * * 1 curl -s -o /dev/null
http://www.domain.com/adwatcher/inc/mail.inc.php?action=weekly&user=username

For monthly reports, setup:

monthly - 0 1 1 * * curl -s -o /dev/null
http://www.domain.com/adwatcher/inc/mail.inc.php?action=monthly&user=username

You will need to substitute the domain name, the directory you installed adwatcher to,
 and the username with the appropriate information for it to work.


*** Zend Optimizer ***
Zend Optimizer is one of the most popular PHP plugins for
performance-improvement, and has been freely available since the early days of
PHP 4. It improves performance by taking PHP's intermediate code through
multiple Optimization Passes, which replace inefficient code patterns with
efficient code blocks.  The replacement code blocks perform exactly the same
operations as the original code, only faster. In addition to
performance-improvement, the Zend Optimizer also enables PHP to transparently
load files encoded by the Zend Encoder or Zend SafeGuard Suite.

The Zend Optimizer is available at
http://www.zend.com/store/products/zend-optimizer.php

After download, 

1: Login to SSH under ROOT:

2: Type the following:

/scripts/installzendopt

3: Follow the directions.

If you don't have root access, please contact your web host for assistance.

-----------------------------------------
QUESTIONS? COMMENTS? PROBLEMS?
-----------------------------------------

EMail us at info@adwatcher.com and we guarantee a response the next business
day.