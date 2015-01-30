<?php
/**
* This file has the login functions in it. Shows the login page and then authenticates upon submission.
*
* @version     $Id: login.php,v 1.11 2005/12/12 00:20:18 chris Exp $
* @author Chris <chris@interspire.com>
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
* @filesource
*/

/**
* Include the base trackpoint functions.
*/
require_once(dirname(__FILE__) . '/trackpoint_functions.php');

/**
* Class for the login page. Will show the login screen, authenticate and set the session details as it needs to.
*
* @package TrackPoint
* @subpackage TrackPoint_Functions
*/
class Login extends TrackPoint_Functions {

	/**
	* Constructor
	* Does nothing.
	*
	* @return void
	*/
	function Login() {
	}
	
	/**
	* Process
	* All the action happens here.
	* If you are not logged in, it will print the login form.
	* Submitting that form will then try to authenticate you.
	* If you are successfully authenticated, you get redirected back to the main index page (quickstats etc).
	* Otherwise, will show an error message and the login form again.
	* Also handles the 'forgot password' process.
	*
	* @see ShowLoginForm
	* @see GetAuthenticationSystem
	* @see AuthenticationSystem::Authenticate
	* @see GetUser
	* @see GetSession
	* @see Session::Set
	* @see ShowForgotForm
	*
	* @return void
	*/
	function Process() {
		$action = (isset($_GET['Action'])) ? strtolower($_GET['Action']) : '';
		switch($action) {
			case 'forgotpass':
				$this->ShowForgotForm();
			break;
			
			case 'sendpass':
				$user = &new User();
				$username = stripslashes($_POST['username']);
				$password = stripslashes($_POST['password']);
				$confirm  = stripslashes($_POST['password_confirm']);
				
				if ($password != $confirm) {
					$this->ShowForgotForm('errormsg', GetLang('PasswordsDontMatch'));
					break;
				}
				
				$founduser = $user->Find($username);
				if (!$founduser) {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Forgot'));
					break;
				}
				
				$user->Load($founduser['userid']);
				
				$newpass = sprintf("_%s_", base64_encode(base64_encode(base64_encode(base64_encode(sprintf("%s~~~~~%s", $username, $password))))));

				$link = TRACKPOINT_APPLICATION_URL . '/index.php?Page=Login&Action=ConfirmPass&Data=' . $newpass;
				
				$headers = "From: " . TRACKPOINT_EMAIL_ADDRESS . "\n";
				$headers = "Reply-To: " . TRACKPOINT_EMAIL_ADDRESS . "\n";
				$headers .= "Content-Type: text/plain\n";

				$message = sprintf(GetLang('ChangePasswordEmail'), $link);
				if (TRACKPOINT_SAFE_MODE) {
					mail($user->emailaddress, GetLang('ChangePasswordSubject'), $message, $headers);
				} else {
					mail($user->emailaddress, GetLang('ChangePasswordSubject'), $message, $headers, "-f" . TRACKPOINT_EMAIL_ADDRESS);
				}
				
				$this->ShowLoginForm('successmsg', GetLang('ChangePassword_Emailed'));
			break;
			
			case 'confirmpass':
			$data = (isset($_GET['Data'])) ? $_GET['Data'] : false;
			if (!$data) {
				$this->ShowForgotForm('errormsg', GetLang('BadLogin_Forgot'));
				break;
			}
			
			$data = eregi_replace("^\_", "", $data);
			$data = eregi_replace("\_$", "", $data);
			$data = base64_decode(base64_decode(base64_decode(base64_decode($data))));
			$arrData = explode("~~~~~", $data);

				if(sizeof($arrData) == 2)
				{
					$username = $arrData[0];
					$password = $arrData[1];

					$user = &new User();
					$founduser = $user->Find($username);
					if (!$founduser) {
						$this->ShowForgotForm('errormsg', GetLang('BadLogin_Forgot'));
						break;
					}
					
					$user->Load($founduser['userid']);
					
					$user->password = $password;
					
					$user->Save();
					
					$this->ShowLoginForm('successmsg', GetLang('PasswordUpdated'));
				} else {
					$this->ShowForgotForm('errormsg', GetLang('BadLogin_Forgot'));
				}
			break;

			case 'login':
				$auth_system = &GetAuthenticationSystem();
				$username = isset($_POST['username']) ? $_POST['username'] : '';
				$password = isset($_POST['password']) ? $_POST['password'] : '';

                                if (!isset($_POST['username']) || !isset($_POST['password'])) {
                                        $this->ShowLoginForm('errormsg', GetLang('EnterUsername'));
                                        break;
                                }

				$rememberdetails = (isset($_POST['rememberme'])) ? true : false;

				$result = $auth_system->Authenticate($username, $password);
				if (!$result) {
					$this->ShowLoginForm('errormsg', GetLang('BadLogin'));
					break;
				}
				$user = &GetUser($result['userid']);

				$rand_check = uniqid(true);

				$user->settings['LoginCheck'] = $rand_check;
				$user->SaveSettings();

				$session = &GetSession();
				$session->Set('UserDetails', $user);

				$oneyear = time() + (365 * 24 * 3600); // one year's time.
				
				if ($rememberdetails) {
					$usercookie_info = array('user' => $user->userid, 'time' => time(), 'rand' => $rand_check);
					setcookie('TrackPointLogin', base64_encode(serialize($usercookie_info)), $oneyear, '/');
				}
				header('Location: ' . $_SERVER['PHP_SELF']);
				exit();
			break;
			default:
				$msg = false; $template = false;
				if ($action == 'logout') {
					$msg = GetLang('LogoutSuccessful');
					$template = 'successmsg';
				}
				$this->ShowLoginForm($template, $msg);
		}
	}

	/**
	* ShowLoginForm
	* This shows the login form.
	* If there is a template to use in the data/templates folder it will use that as the login form.
	* Otherwise it uses the default one below. If you pass in a message it will show that message above the login form.
	*
	* @access Private
	*
	* @param template Uses the template passed in for the message (eg success / error).
	* @param msg Prints the message passed in above the login form (eg unsuccessful attempt).
	*
	* @see FetchTemplate
	* @see GetSession
	* @see Session::LoggedIn
	* @see PrintHeader
	* @see PrintFooter
	*
	* @return void
	*/
	function ShowLoginForm($template=false, $msg=false) {
		$this->PrintHeader();

		if ($template && $msg) {
			switch(strtolower($template)) {
				case 'errormsg':
					$this->GlobalAreas['Error'] = $msg;
				break;
				case 'successmsg':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$this->GlobalAreas['Message'] = $this->ParseTemplate($template, true, false);
		}

		$GLOBALS['username'] = '';

		if (isset($_POST['username'])) {
			$GLOBALS['username'] = htmlentities(stripslashes($_POST['username']));
		}

		$this->GlobalAreas['SubmitAction'] = 'Login';

		$this->ParseTemplate('login');

		$this->PrintFooter();
	}
	
	/**
	* ShowForgotForm
	* This shows the forgot password form and handles the multiple stages of actions.
	*
	* @param template If there is a template (will either be success or error template) use that as a message.
	* @param msg This also tells us what's going on (password has been reset and so on).
	*
	* @see PrintHeader
	* @see ParseTemplate
	* @see PrintFooter
	*
	* @return void
	*/
	function ShowForgotForm($template=false, $msg=false) {
		$this->PrintHeader();

		if ($template && $msg) {
			switch(strtolower($template)) {
				case 'errormsg':
					$this->GlobalAreas['Error'] = $msg;
				break;
				case 'successmsg':
					$this->GlobalAreas['Success'] = $msg;
				break;
			}
			$this->GlobalAreas['Message'] = $this->ParseTemplate($template, true, false);
		}

		$GLOBALS['SubmitAction'] = 'SendPass';

		$this->ParseTemplate('ForgotPassword');

		$this->PrintFooter();
	}
}

?>
