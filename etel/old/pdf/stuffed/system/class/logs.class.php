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
# $Id: logs.class.php,v 1.28 2005/12/26 12:25:18 kuindji Exp $
# ============================================================================


/////////////////////////
//
//
/////////////////////////



class nsLogs
{
	var $USE_LOG;
	var $LogFile;
	var $Type;
	var $EchoLog;
	var $Errors;
	var $Messages;
	var $Alerts;
	var $OwnLog;
	var $LastErr;
	var $Count;
	var $TableStyle;
	var $TdStyle;
	var $MsgStyle;
	var $ErrStyle;

	function nsLogs($LogFile = false, $Type = "ARR")
	{
		$Type="ARR";
		$this->USE_LOG = true;
		$this->Type = $Type;
		$this->EchoLog = false;
		$this->Errors = Array();
		$this->Messages = Array();
		$this->OwnLog = Array();
		$this->Alerts = Array();
		$this->LastErr = "";
		$this->Count =0;
		$this->TableStyle=false;
		$this->TdStyle=false;
		$this->MsgStyle=false;
		$this->ErrStyle=false;
	}

	// public function DbErr
	// Gets mysql_error() 

	function DbErr($Query = false, $Comment = false, $Die = false)
	{
		if (!$this->USE_LOG) return false;
		$Error = @mysql_error();
		$Text = "";
		if ($Error) $Text .= $Error." || ";
		if ($Comment) $Text .= $Comment." || ";
		if ($Query) $Text .= $Query;
		if ($this->EchoLog) echo $Text;
		$this->MakeLog($Text);
		if ($Die) die($Comment);
	}

	function ClearError()
	{
		$this->Errors=array();
	}

	function ClearMsg()
	{
		$this->Messages=array();
	}

	function ClearAlert()
	{
		$this->Alerts=array();
	}

	function Clear()
	{
		$this->ClearError();
		$this->ClearMsg();
		$this->ClearAlert();
	}

	// public function Err
	// Uses $Comment only

	function Msg($Comment = false)
	{	
		if (!$Comment) return false;
		$this->Messages[] = nl2br($Comment);
	}

	function Err($Comment = false, $Die = false)
	{	
		if (!$this->USE_LOG) return false;
		if (!$Comment) return false;
		$Text = "";
		if ($Comment) $Text .= $Comment;
		if ($this->EchoLog) echo $Text;
		$this->MakeLog($Text);
		if ($Die) die($Comment);
	}

	function Alert($Comment=false)
	{
		if (!$Comment) return false;
		$this->Alerts[] = nl2br($Comment);
	}


	// private function MakeLog
	// common function
	
	function MakeLog($Text = false)
	{
		if (!$Text) return false;
		$this->Count++;
		$Date = date("d.m.Y H:i:s");
		switch ($this->Type) 
		{
			case "ARR":
			{
   				$this->Errors[] = $Text;
				break;
			}
		}
		$this->LastErr = $Text;
	}


	function HaveMsg()
	{
		if (count($this->Messages)>0) return true;
		return false;
	}

	function HaveErr()
	{
		if (count($this->Errors)>0) return true;
		return false;
	}

	function HaveAlert()
	{
		if (count($this->Alerts)>0) return true;
		return false;
	}

	

	function Error($ErrID=false)
	{
		global $nsErrorMessage, $Lang;	
		if (!$ErrID) return false;
		switch ($ErrID){
			case "NO_PROD": die($Lang['UnableInit']); break;
			case "NO_ACTION": die($Lang['NoSuchAction']); break;
			case "ACTION_RESTRICT": die($Lang['PermissionDenied']); break;
			case "NO_PERMISSION": die($Lang['PermissionDenied']); break;

			
			case 'USER:NO_USER': $nsErrorMessage =($Lang['NoUser']); break;
			case 'USER:WRONG_PWD': $nsErrorMessage =($Lang['WrongPwd']); break;
			
			default: die($Lang['UnknownError']); break;
			
		}	
		
	}

}
?>