<?php

/*
	SQLyog v4.0
	Copyright 2003-2004, Webyog
    http://www.webyog.com    
    
	HTTP Tunneling Page
    
    This page exposes the MySQL API as a set of web-services which is consumed by SQLyog - the most popular GUI to MySQL.
    
    This page allows SQLyog to manage MySQL even if the MySQL port is blocked or remote access to MySQL is not allowed.
	
*/

/* PHP for PHP/MySQL tunneling */

/* states of comments currently being worked on in the query. */


/* check whether global variables are registered or not */
if (!get_cfg_var("register_globals")) { 
 extract($_REQUEST);
}

define ( "COMMENT_OFF", 0 );
define ( "COMMENT_HASH", 1 );
define ( "COMMENT_DASH", 2 );
define ( "COMMENT_START", 0 );

/* current element state while parsing XML received as post */

define ( "XML_NOSTATE", 0 );
define ( "XML_HOST", 1 );
define ( "XML_USER", 2 );
define ( "XML_DB", 3 );
define ( "XML_PWD", 4 );
define ( "XML_PORT", 5 );
define ( "XML_QUERY", 6 );

/* uncomment this line to create a debug log */
/*define ( "DEBUG", 1 );*/

/* current character in the query */
$curpos			= 0;

/* version constant */
$tunnelversion  = '4.1';

/* global variable to keep the state of current XML element */
$xml_state		= XML_NOSTATE;

/* global variables to track various informations about the query */

$host			= NULL;
$port			= NULL;
$db				= NULL;
$username		= "etel_";
$pwd			= "WSD%780=";                                          
$batch			= 0;
$base			= 0;
$query			= NULL;
/* we stop all error reporting as we check for all sort of errors */
error_reporting ( 0 );
set_time_limit ( 0 );

/* we can now use SQLyogTunnel.php to log debug informations, which will help us to point out the error */
function WriteLog ( $loginfo )
{
    if ( defined("DEBUG") ) 
    {
        $fp = fopen ( "yogtunnel.log", "a" );

        if ( $fp == FALSE )
            return;

        fwrite ( $fp, $loginfo . chr(13) );
        fclose ( $fp );
    }
}

/* we check if all the external libraries support i.e. expat and mysql in our case is built in or not */
/* if any of the libraries are not found then we show a warning and exit */

if ( AreModulesInstalled () == TRUE ) {
    WriteLog ( "Enter AreModulesInstalled" );
	ProcessQuery ();
    WriteLog ( "Exit AreModulesInstalled" );
}

function convertxmlchars ( $string )  
{   
    WriteLog ( "Enter convertxmlchars" );
    WriteLog ( "Input: " . $string );
	
    $result = $string;   
	
	$result = eregi_replace('&', '&amp;', $result);  
	$result = eregi_replace('<', '&lt;', $result);   
	$result = eregi_replace('>', '&gt;', $result);   
	$result = eregi_replace('\'', '&apos;', $result);
	$result = eregi_replace('\"', '&quot;', $result);

    WriteLog ( "Output: " . $result );
    WriteLog ( "Exit convertxmlchars" );
 
	return $result;  
}

/* we dont allow an user to connect directly to this page from a browser. It can only be accessed using SQLyog */

function ShowAccessError ()
{
	global 	$tunnelversion;

    WriteLog ( "Enter showaccesserror" );

    $errmsg  = '<p><b>Tunnel version: ' . $tunnelversion . '</b>.<p>This PHP page exposes the MySQL API as a set of webservices.<br><br>This page allows SQLyog to manage a MySQL server even if the MySQL port is blocked or remote access to MySQL is not allowed.<br><br>Visit <a href ="http://www.webyog.com">Webyog</a> to get more details about SQLyog.';

    echo ( '<html><head><title>SQLyog HTTP Tunneling</title></head><body leftmargin="0" topmargin="0"><img src="http://www.webyog.com/images/webban.jpg" alt="Webyog"><p>' );
    echo ( '<table width="100%" cellpadding="3" border="0"><tr><td><font face="Verdana" size="2">' . $errmsg . '</td</tr></table>' );
    echo ( '</body></html>' );

    WriteLog ( "Exit showaccesserror" );
}

/* function checks if a required module is installed or not */

function AreModulesInstalled ()
{
	global 	$tunnelversion;

    WriteLog ( "Enter aremodulesinstalled" );
	
	$modules 		= get_loaded_extensions();
	$modulenotfound = '';

	if ( extension_loaded  ( "xml" ) != TRUE ) {
		$modulenotfound = 'XML';
	} else if ( extension_loaded  ( "mysql" ) != TRUE ) {
		$modulenotfound = 'MySQL';	
	} else {
		return TRUE;
	}

    $errmsg   = '<b>Error:</b> Extension <b>' . $modulenotfound . '</b> was not found compiled and loaded in the PHP interpreter. SQLyog requires this extension to work properly.';   
	$errmsg  .= '<p><b>Tunnel version: ' . $tunnelversion . '</b>.<p>This PHP page exposes the MySQL API as a set of webservices.<br><br>This page allows SQLyog to manage a MySQL server even if the MySQL port is blocked or remote access to MySQL is not allowed.<br><br>Visit <a href ="http://www.webyog.com">Webyog</a> to get more details about SQLyog.';

    echo ( '<html><head><title>SQLyog HTTP Tunneling</title></head><body leftmargin="0" topmargin="0"><img src="http://www.webyog.com/images/webban.jpg" alt="Webyog"><p>' );
    echo ( '<table width="100%" cellpadding="3" border="0"><tr><td><font face="Verdana" size="2">' . $errmsg . '</td</tr></table>' );
    echo ( '</body></html>' );

    WriteLog ( "Exit aremodulesinstalled" );
}

function ProcessQuery ()
{

    WriteLog ( "Enter processquery" );

	/* check that user has not executed the phptunnel.php from browser */
	if ( !isset ( $_POST['textfield'] ) )
	{
        ShowAccessError ();
		return;
	} 

    /* fix in v4.1 that checks for global configuration flag get_magic_quotes_gpc() to stripslashes or not */
    if (get_magic_quotes_gpc()) {
        $xmlrecvd = stripslashes ( urldecode ( $_POST['textfield'] ) );
    } else {
        $xmlrecvd = urldecode ( $_POST['textfield'] );
    }    

	global	$host;
	global	$port;
	global	$username;
	global	$pwd;
	global  $db;
	global 	$batch;
	global	$query;
	global	$base;

	$ret = GetDetailsFromPostedXML ( $xmlrecvd );

	if ( $ret == FALSE )
		return;

    /* connect to the mysql server */
    WriteLog ( "Trying to connect" );
	$mysql		= mysql_connect ( "$host:$port", $username, $pwd );
	if ( !$mysql )
	{
		HandleError ( mysql_errno(), mysql_error() );
        WriteLog ( mysql_error() );
		return;
	}

    WriteLog ( "Connected" );

	mysql_select_db ( str_replace ( '`', '', $db ), $mysql );

	if ( $batch ) {
		ExecuteBatchQuery ( $mysql, $query );
	}
	else 
		ExecuteSingleQuery ( $mysql, $query );

	mysql_close ( $mysql );

    WriteLog ( "Exit processquery" );
}

/* Start element handler for the parser */

function startElement ( $parser, $name, $attrs )
{
	global  $xml_state;
	global	$host;
	global	$port;
	global  $db;
	global	$username;
	global	$pwd;
	global 	$batch;
	global	$query;
	global	$base;

    WriteLog ( "Enter startelement" );

	if ( strtolower ( $name ) == "host" ) 
	{
		$xml_state 	= XML_HOST;
	}
	else if ( strtolower ( $name ) == "db" ) 
	{
		$xml_state	= XML_DB;
	}
	else if ( strtolower ( $name ) == "user" ) 
	{
		$xml_state 	= XML_USER;
	}
	else if ( strtolower ( $name ) == "password" )
	{
		$xml_state	= XML_PWD;
	}
	else if ( strtolower ( $name ) == "port" )
	{
		$xml_state 	= XML_PORT;
	}
	else if ( strtolower ( $name ) == "query" )
	{
		$xml_state	= XML_QUERY;

		/* track whether the query(s) has to be processed in batch mode */
		$batch = (( $attrs['B'] == '1' )?(1):(0));
		$base  = (( $attrs['E'] == '1' )?(1):(0));  	
	}

    WriteLog ( "Exit startelement" );
}

/* End element handler for the XML parser */

function endElement ( $parser, $name )
{
    WriteLog ( "Enter endElement" );
    
    global $xml_state;
	
	$xml_state	=	XML_NOSTATE;

    WriteLog ( "Exit  endElement" );
}

/* Character data handler for the parser */

function charHandler ( $parser, $data )
{
	
	global  $xml_state;
	global	$host;
	global	$port;
	global  $db;
	global	$username;
	global	$pwd;
	global 	$batch;
	global	$query;
	global	$base;

    WriteLog ( "Enter charhandler" );

	if ( $xml_state == XML_HOST ) 
	{
		$host 		.= $data;
	}
	else if ($xml_state == XML_DB ) 
	{
		$db 		.= $data;
	}
	else if ( $xml_state == XML_USER ) 
	{
		$username	.= $data;
	}
	else if ( $xml_state == XML_PWD )
	{
		$pwd		.= $data;
	}
	else if ( $xml_state == XML_PORT )
	{
		$port 		.= $data;
	}
	else if ( $xml_state == XML_QUERY )
	{
		if ( $base ) {
			$query		.= base64_decode ( $data );
		} else {
			$query		.= $data;	
		}
	}

    WriteLog ( "Exit charhandler" );
}

/* Parses the XML received and stores information into the variables passed as parameter */

function GetDetailsFromPostedXML ( $xmlrecvd )
{

    WriteLog ( "Enter getdetailsfrompostedxml" );

	$xml_parser		= xml_parser_create ();
	xml_set_element_handler($xml_parser, "startElement", "endElement");
	xml_set_character_data_handler ( $xml_parser, "charHandler" );

	$ret = xml_parse ( $xml_parser, $xmlrecvd );
	if ( !$ret ) 
	{
		HandleError ( xml_get_error_code ( $xml_parser ), xml_error_string ( xml_get_error_code ( $xml_parser ) ) );
		return FALSE;
	}

    xml_parser_free($xml_parser);

    WriteLog ( "Exit getdetailsfrompostedxml" );

	return TRUE;
}

/* Function writes down the correct XML for handling mysql_pconnect() error */

function HandleError ( $errno, $error )
{
	global         $tunnelversion;

    WriteLog ( "Enter handleerror" );
	
	echo "<xml v=\"" . $tunnelversion . "\"><e_i><e_n>$errno</e_n><e_d>" . convertxmlchars($error) . "</e_d></e_i></xml>";

    WriteLog ( "Exit handleerror" );
}

/* Process when only a single query is called. */

function ExecuteSingleQuery ( $mysql, $query )
{

	global			$tunnelversion;

    $result		= mysql_query ( $query, $mysql );

    WriteLog ( "Enter ExecuteSingleQuery" );

	if ( !$result ) {
		HandleError ( mysql_errno(), mysql_error() );
		return;
	}

	/* query execute was successful so we need to echo the correct xml */
	/* the query may or may not return any result */
    WriteLog ( "mysql_num_rows in ExecuteSingleQuery" );
	if ( !mysql_num_rows ( $result ) && !mysql_num_fields ( $result ) )
	{
		/* is a non-result query */
		echo "<xml v=\"" . $tunnelversion . "\">";
		echo "<e_i></e_i>";
		HandleExtraInfo ( $mysql );
		echo "<f_i c=\"0\"></f_i><r_i></r_i></xml>";
		return;
	}

	/* handle result query like SELECT,SHOW,EXPLAIN or DESCRIBE */
	echo '<xml v="' . $tunnelversion . '">';
	echo "<e_i></e_i>";
	
	/* add some extra info */
	HandleExtraInfo ( $mysql );

	/* add the field count information */
	$fieldcount		= mysql_num_fields ( $result );
	print ( $fieldcount );
	echo "<f_i c=\"$fieldcount\">";

	/* retrieve information about each fields */
	$i = 0;
	while ($i < $fieldcount ) 
	{
		$meta = mysql_fetch_field($result);

		echo "<f>";
		echo "<n>" . convertxmlchars($meta->name) . "</n>";
		echo "<t>" . convertxmlchars($meta->table) . "</t>";
		echo "<m>" . convertxmlchars($meta->max_length) . "</m>";
		echo "<d></d>";
		echo "<ty>" . GetCorrectDataType ( $result, $i ) . "</ty>";
		echo "</f>";

		$i++;
	}

	/* end field informations */
	echo "</f_i>";

	/* get information about number of rows in the resultset */
	$numrows	= mysql_num_rows ( $result );
	echo "<r_i c=\"$numrows\">";

	/* add up each row information */
	while ( $row = mysql_fetch_array ( $result ) )
	{
		$lengths = mysql_fetch_lengths ( $result );

		/* start of a row */
		echo "<r>";

		for ( $i=0; $i < $fieldcount; $i++ ) 
		{
			/* start of a col */
			echo "<c l=\"$lengths[$i]\">";

			if ( !isset($row[$i]) /*== NULL*/ ) 
			{
				echo "(NULL)";
			}
			else 
			{
				if ( mysql_field_type ( $result, $i ) == "blob" ) 
				{
					if ( $lengths[$i] == 0 ) 
					{
						echo "_";
					}
					else
					{
						echo convertxmlchars ( base64_encode ( $row[$i] ) );
					}
				}
				else
				{
					if ( $lengths[$i] == 0 ) 
					{
						echo "_";
					}
					else
					{
						echo convertxmlchars($row[$i]);	
					}
				}
			}

			/* end of a col */
			echo "</c>";
		}

		/* end of a row */
		echo "</r>";
	}

	/* close the xml output */
	echo "</r_i></xml>";
	
	/* free the result */
	mysql_free_result ( $result );

    WriteLog ( "Exit ExecuteSingleQuery" );
}

/* function finds and returns the correct type understood by MySQL C API() */

function GetCorrectDataType ( $result, $j )
{
	$data	= NULL;

    WriteLog ( "Enter GetCorrectDataType" );

	switch( mysql_field_type ( $result, $j ) )
	{
		case "int":
			if ( mysql_field_len ( $result, $j ) <= 4 )
			{
				$data = "smallint";
			}
			elseif ( mysql_field_len ( $result, $j ) <= 9 )
			{
				$data = "mediumint";
			}
			else
			{
				$data = "int";
			}
			break;
    
		case "real":
			if (mysql_field_len($result,$j) <= 10 )
			{
				$data = "float";                                             
			}
			else
			{
				$data = "double";
			}
			break;

		case "string":
			$data = "varchar";
			break;

		case "blob":
			$textblob = "TEXT";
			if ( strpos ( mysql_field_flags ($result,$j),"binary") )
			{
				$textblob = "BLOB";
			}
			if (mysql_field_len($result,$j) <= 255)
			{
				if ( $textblob == "TEXT" )
				{
                    $data = "tinytext";
                }
                else
                {
                    $data = "tinyblob";
                }
			}
			elseif (mysql_field_len($result, $j) <= 65535 )
			{
				if ( $textblob == "TEXT" ) {
                    $data = "mediumtext";
                }
                else
                {
                    $data = "mediumblob";
                }
			}
			else
			{
				if ( $textblob == "TEXT" ) {
                    $data = "longtext";
                }
                else
                {
                    $data = "longblob"; 
                }
			}
			break;

		case "date":
			$data = "date";
			break;

		case "time":
			$data = "time";
			break;

		case "datetime":
			$data = "datetime";
			break;
	}

    WriteLog ( "Exit GetCorrectDataType" );

	return (convertxmlchars($data));
}

/* Processes a set of queries. The queries are delimited with ;. Will return result for the last query only. */
/* If it encounters any error in between will return error values for that query */

function ExecuteBatchQuery ( $mysql, $query )
{

    WriteLog ( "Enter ExecuteBatchQuery" );

	$found		= FALSE;
	$token		= NULL;
	$prev		= NULL;

	$token 		= my_strtok ( $query, $found );

	while ( !empty($token) )
	{
		$prev = $token;

		$token 		= my_strtok ( $query, $found );

		if ( empty($token) )
		{
			return ExecuteSingleQuery ( $mysql, $prev );
		}

		$result = mysql_query ( $prev, $mysql );

		if ( !$result )  {
			return HandleError ( mysql_errno(), mysql_error() );
        }

		mysql_free_result ( $result );
	}

    WriteLog ( "Exit ExecuteBatchQuery" );

	return;
}

/* */

function HandleExtraInfo ( $mysql )
{

    WriteLog ( "Enter HandleExtraInfo" );

	echo "<s_v>" . mysql_get_server_info ( $mysql ) . "</s_v>";
	echo "<m_i></m_i>";
	echo "<a_r>" . mysql_affected_rows ( $mysql ) . "</a_r>";
	echo "<i_i>" . mysql_insert_id ( $mysql ) . "</i_i>";

    WriteLog ( "Exit HandleExtraInfo" );

}

/* implementation of my_strtok() in PHP */

function my_strtok ( $query, &$found )
{
    WriteLog ( "Enter my_strok" );

	global			$curpos;
	
	$delimiter		= ';';
	$quote			= NULL;
	$escapedchar	= NULL;
	$ch				= NULL;
	$i				= $curpos;
	$quotestart		= 0;
	$comment 		= COMMENT_OFF;
	$found			= TRUE;
			
	if ( $query[$curpos] == NULL )
		return 0;

	for ( ; $query[$curpos] != NULL; $curpos++ )
	{
		$ch = $query[$curpos];

		if ( $ch == $delimiter )
		{
			if ( isset ( $quote ) == FALSE && ( $comment==COMMENT_OFF ) )
			{
				$curpos++;
				$temp = substr ( $query, $i, $curpos-$i-1 );
				return ( $temp );
			}
			else
				continue;
		}
		else if ( $ch == '\'' || $ch == '\"' )
		{
			// we only do this if the quote is  open
			if ( $quote )
			{
				// we just check if its the same quote.
				if ( $quote != $query[$curpos] )
					continue;

				$quote = NULL;

			} else if ( $comment == COMMENT_OFF ) {

				$quote = $query[$curpos];
				continue;
			}
		}
        else if ( $query[$curpos] == '#' ) {
            if ( $comment == COMMENT_OFF && $quote == NULL ) 
                $comment = COMMENT_HASH;
        }
		else if ( $query[$curpos] == '-' && $query[$curpos+1] == '-' && $query[$curpos+2] == ' ' && $quote == NULL )
		{
			if ( $comment == COMMENT_OFF	)
			{
				$curpos 	+= 2;
				$comment 	= COMMENT_DASH;
			}
		}
		else if ( $query[$curpos] == '/' && $query[$curpos+1] == '*' && $quote == NULL )
		{
			if ( $comment == COMMENT_OFF	)
			{
				$curpos		+= 1;
				$comment	= COMMENT_START;
			}
		}
		else if ( $query[$curpos] == chr(13) || $query[$curpos] == chr(10) )
		{
			if ( $comment != COMMENT_OFF && $comment != COMMENT_START )
				$comment = COMMENT_OFF;
		}
		else if  ( $query[$curpos] == '*' && $query[$curpos+1] == '/' )
		{
			if ( $comment == COMMENT_START )
				$comment = COMMENT_OFF;

		} else  if ( $query[$curpos] == '\\' ) {

			if ( $quote )
				$curpos++;
		}
	}

    WriteLog ( "Exit my_strok" );

	return substr ( $query, $i, $curpos-$i );
}
?>