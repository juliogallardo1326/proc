<?
require_once(SYS.'/system/class/email/Net/POP3.php');


class nsEmail{
	var $_pop3;
	var $_messageCash;
	var $_filespath;
	var $_messageNum;
	var $host;
	var $port;
	var $login;
	var $pwd;
	var $LastError;
	var $messageList;
	var $attachments;
	//------------------------------------------------------------------------
	function nsEmail($host=false, $port=false, $login=false, $pwd=false){
		$this->_filespath = false;
		$this->attachments = false;
		$this->_pop3 = & new Net_POP3();
		if($host){ if(!$this->Connect($host, $port)) return false; }
		if($login){ if(!$this->Login($login, $pwd)) return false; }		
	}
	//------------------------------------------------------------------------
	function Connect($host, $port){
		$this->host = $host;
		$this->port = $port;
		if(PEAR::isError($ret=$this->_pop3->connect($host, $port))){
			$this->LastError = $ret->getMessage();
			return false;
		}
		return true;
	}
	//------------------------------------------------------------------------
	function Login($login, $pwd, $apop='USER'){
		$this->login = $login;
		$this->pwd = $pwd;
		if(PEAR::isError($ret=$this->_pop3->login($login, $pwd, $apop))){
			$this->LastError = $ret->getMessage();
			return false;
		}
		return true;
	}
	//------------------------------------------------------------------------
	function DeleteMessage($message_id){
		$this->_pop3->deleteMsg($message_id);
	}
	//------------------------------------------------------------------------
	function GetMessage($message_id, $external_id=0){ 		
		if(!isset($this->_messageCash[$message_id])){
				$raw_message = $this->_pop3->getMsg($message_id);
				
				require_once(SYS.'/system/class/email/mime/mimeDecode.php');
				$params['include_bodies'] = true;
				$params['decode_bodies']  = true;
				$params['decode_headers'] = true;
				
				$decoder = new Mail_mimeDecode($raw_message);
				$msg = $decoder->decode($params);				
//				Dump($msg, true);				
				
				
				$msg->external_id = $external_id;		
							
				
				
				if(isset($msg->headers['date']))
					$msg->Moment = strtotime($msg->headers['date']);
				else $msg->Moment = 0;
				$msg = $this->ProcessMessageBody($msg);
				if(strtoupper($msg->charset)=='KOI8-R'){
						$msg->body = convert_cyr_string($msg->body, 'koi8-r', 'Windows-1251');
						$msg->headers['subject'] = 
									convert_cyr_string($msg->headers['subject'], 'koi8-r', 'Windows-1251');
						$msg->charset = 'Windows-1251';
				}
				$this->_messageCash[$message_id] = $msg;
				$this->got_inner_body=null;
		}
		return $this->_messageCash[$message_id];
	}
	//------------------------------------------------------------------------
	function ProcessMessageBody($msg){
		if($this->processAlternative($msg, $msg)){}
		else if($msg->ctype_secondary == "mixed")
				$msg = $this->ProcessBodyAttachments($msg);
		return $msg;
	}
	//------------------------------------------------------------------------
	function processAlternative($part, &$msg, $get_html=true){				
				if($part->ctype_secondary=='alternative'){
						$part_i = $part->parts[0]->ctype_secondary == "html" && $get_html
																					?0:1;
						$msg->body = $part->parts[$part_i]->body;
						$msg->charset = $part->parts[$part_i]->ctype_parameters['charset'];
						$msg->contentType = 'text/'.($get_html ? 'html':'plain');
						return true;
				}elseif($part->ctype_secondary!='mixed' && 
									$part->ctype_primary=='text' && !isset($this->got_inner_body)){
						$this->got_inner_body=true;
						$msg->body = $part->body;						
						$msg->charset = $part->ctype_parameters['charset'];
						$msg->contentType = 'text/'.$part->ctype_secondary;
						return true;						
				}
				return false;
	}
	//------------------------------------------------------------------------
	function ProcessBodyAttachments($msg){
			foreach($msg->parts as $i => $part){
				if($this->processAlternative($part, $msg)){
					$msg->parts[$i]=null;
				}
			}						
			$this->attachments = $msg->parts;
			return $msg;
	}
	//------------------------------------------------------------------------
	function saveAttachments($dir, $assigned_to_table, $assigned_to_id){
		global $Db;		
		if($this->attachments===false) return false;		
		
		$gd_installed = function_exists('imagecreatetruecolor');		
		
		$parts = $this->attachments;
		$this->_filespath = $dir;
		foreach ($parts as $i => $part){
				if(!is_null($part)){
						$file_out->type = $part->ctype_primary.'/'.$part->ctype_secondary;
						$file_out->original_name = $part->d_parameters['filename'];
						if(strpos($file_out->original_name, '.')!==false){
							list(, $ext) = split('\.', $file_out->original_name);
							$ext = '.'.$ext;
						}else $ext = '';	
						$file_out->path = $this->_filespath.'/'
										.$file_out->original_name.'--'
										.md5(uniqid(rand(), true).$file_out->original_name).$ext;
										
						$filename = $file_out->path;
						if (	($handle = fopen($filename, 'wb'))
										&& (fwrite($handle, $part->body) !== false)
										) {						    
										$Db->Query('INSERT INTO '.PFX.'_fn_file (TYPE, ORIGINAL_NAME, PATH,
												MOMENT)
												VALUES (?, ?, ?, ?)',
												$file_out->type, $file_out->original_name, $file_out->path, time());				
										$fileid = $Db->LastInsertId;
										$Db->Query('INSERT INTO '.PFX.'_fn_file2assigned_to 
													(FILE_ID, ASSIGNED_TO_ID, ASSIGNED_TO_TABLE)
													VALUES (?, ?, ?)', $fileid, $assigned_to_id, $assigned_to_table);												
						    fclose($handle);						                    
						}else{
								// error! cannot save the file
						}
						// is it an image? 
						if(strpos($file_out->type, 'image/')!==false && $gd_installed){

								$DEF_IMG_WIDTH = GetParam('thumbnail_width', 'INTVAL');
								$DEF_IMG_HEIGHT = GetParam('thumbnail_height', 'INTVAL');

								// Yes, it is - I have to create the thumbnail for it:
								list($xtype, $xsubtype) = explode('/', $file_out->type);
							if($xsubtype!='gif' || 
											(($xsubtype=='gif' && function_exists('imagegif'))))	
								switch($xsubtype){
								case 'gif':	      case 'jpeg': 
								case 'png':		case 'x-png':
									if($xsubtype=='x-png')$xsubtype = 'png';
									list($width, $height, ,) = getimagesize($filename);
									$bwidth =$width; $bheight=$height;
								
								 if($height > $DEF_IMG_HEIGHT){	
									$width = intval($DEF_IMG_HEIGHT * ($width/$height));
									$height = $DEF_IMG_HEIGHT;
								 }
								 if($width > $DEF_IMG_WIDTH){	
										$height = intval($DEF_IMG_WIDTH * ($height/$width));
										$width = $DEF_IMG_WIDTH;	
								 }

								 $d_width=$width;$d_height=$height;	
									$im = eval("return imagecreatefrom$xsubtype('$filename');");
									
									// resampling:
									
									$im2  = imagecreatetruecolor($d_width, $d_height);
									imageinterlace($im2, 0);
									imagecopyresampled($im2, $im, 0, 0, 0, 0, $d_width, $d_height
										, $bwidth, $bheight);
										
									
									imagejpeg($im2, ''.$filename.'.thumb.jpeg', 90);
								break;
								}
						}
				}	
		}		
	}
	//------------------------------------------------------------------------
	function clearAttachments(){
		$this->attachments = false;		
	}
	//------------------------------------------------------------------------
	function GetListing(){ $this->_SetListing();
		return $this->messageList;
	}
	//------------------------------------------------------------------------
	function LastMessageId(){ $this->_SetListing();
		return $this->messageList[count($this->messageList)-1]['msg_id'];
	}
	//------------------------------------------------------------------------
	function _SetListing($hard=false){
		if($hard || !isset($this->messageList)){
			$this->messageList = $this->_pop3->getListing();
		}
	}
	//------------------------------------------------------------------------
	function HardReset(){
		$this->_SetListing(true);
		unset($this->_messageCash, $LastError);
	}
	//------------------------------------------------------------------------
	function numMsg(){
		if(!isset($this->_messageNum)){
			$this->_messageNum = $this->_pop3->numMsg();
		}
		return $this->_messageNum;
	}
	//------------------------------------------------------------------------
	function Debug($on=true){ $this->_pop3->setDebug($on); }
	function Disconnect(){ $this->_pop3->disconnect(); }
	//------------------------------------------------------------------------
	function send($to, $headers, $body){
			global $global_smtp_configs, $global_mail_method;
			include_once(SYS.'/system/class/email/mail.php');
			$mmethod = $global_mail_method;
			if($mmethod=='smtp' && isset($global_smtp_configs)){
				$sender = Mail::factory('smtp', 
										$global_smtp_configs
				);		
			}else if($mmethod=='sendmail'){
			  $sender = Mail::factory('sendmail', 
						array(
							'sendmail_path' => GetParam('sendmail_path', 'STRVAL'),
							'sendmail_args' => ''
						)
				);				   
			}else{
			  $sender = Mail::factory('mail', '');				
		  }
		  $sender->send($to, $headers, $body);
	}
	//------------------------------------------------------------------------
	//------------------------------------------------------------------------
	//------------------------------------------------------------------------
	//------------------------------------------------------------------------
}

?>
