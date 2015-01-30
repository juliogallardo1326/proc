<?
# ============================================================================
#
#                        ___
#                    ,yQ$SSS$Q·,      ,yQQQL
#           i_L   I$;            `$½`       `$$,
#                                 `          I$$
#           .yQ$$$$,            ;        _,d$$'
#        ,d$$P"^```?$b,       _,'  ;  ,d$$P"`
#     ,d$P"`        `"?$$Q#QP½`    $d$$P"`
#   ,$$"         ;       ``       ;$?'
#   $$;        ,dI                I$;
#   `$$,    ,d$$$`               j$I
#     ?$S#S$P'j$'                $$;         Copyright (c) Stuffed Guys
#       `"`  j$'  __....,,,.__  j$I              www.stuffedguys.com
#           j$$½"``           ',$$
#           I$;               ,$$'
#           `$$,         _.u$$½`
#             "?$$Q##Q$$SP½"^`
#                `````
#
# ============================================================================
# $Id: session.class.php,v 1.9 2005/10/12 13:59:54 shalmoo Exp $
# ============================================================================

// class "Session". It is a simple bulkhead on the core PHP sessions. 

//------------------------------------------------------------------------------------
class nsSession  extends nsBase{
	//------------------------------------------------------------------------------------
	function nsSession($sid = FALSE){
		if($sid){ session_id($sid); }
		session_start();
		return session_id();
	}
	//------------------------------------------------------------------------------------
	function set($name, $value=false){
			if($value)	
				$this->__set_session_var($name, $value);
			else 	
				$this->__destroy_session_var($name);			
	}
	function remove($name){  $this->set($name);  }
	//------------------------------------------------------------------------------------
	/**
	* @return mixed
	* @param string $name
	* @desc returns the session variable. If this variable does not exist
	* in the current session - returns false.
	*/
	function get($name){
		return isset($_SESSION[$name]) ? $_SESSION[$name] : false;
	}
	//------------------------------------------------------------------------------------
	function id(){
		return session_id();
	}
	//------------------------------------------------------------------------------------
	function destroy(){
		$_SESSION = array();
		session_destroy();		
	}
	//------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------
	//------------------------------------------------------------------------------------
	function __set_session_var($name, $val){
				$_SESSION[$name] = $val;
	}
	//------------------------------------------------------------------------------------
	function __destroy_session_var($name){
				unset($_SESSION[$name]);
	}
}



?>