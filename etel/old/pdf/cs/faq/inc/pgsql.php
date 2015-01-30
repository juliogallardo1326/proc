<?php
/**
* db_pgsql
*
* The db_pgsql class provides methods and functions for a PostgreSQL
* database.
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       Tom Rochester <tom.rochester@gmail.com>
* @package      db_pgsql
* @since        2003-02-24
* @copyright    (c) 2003-2006 phpMyFAQ Team
*
* The contents of this file are subject to the Mozilla Public License
* Version 1.1 (the "License"); you may not use this file except in
* compliance with the License. You may obtain a copy of the License at
* http://www.mozilla.org/MPL/
*
* Software distributed under the License is distributed on an "AS IS"
* basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
* License for the specific language governing rights and limitations
* under the License.
*/

class db_pgsql
{
    /**
     * The connection object
     *
     * @var   mixed
     * @see   connect(), query(), dbclose()
     */
	var $conn = false;
    
    /**
     * The query log string
     *
     * @var   string
     * @see   query()
     */
	var $sqllog = "";
	
    /**
     * Constructor
     *
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2003-02-24
     */
	function db_pgsql()
    {
    }
    
    /**
     * Connects to the database.
     *
     * This function connects to a MySQL database
     *
     * @param   string $host
     * @param   string $username
     * @param   string $password
     * @param   string $db_name
     * @return  boolean true, if connected, otherwise false
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-02-24
     */
	function connect ($host, $user, $passwd, $db)
    {
		/* if you use mysql_pconnect(), remove the next line: */
        $this->conn = pg_pconnect('host='.$host.' port=5432 dbname='.$db.' user='.$user.' password='.$passwd);
        /* comment out for more speed with mod_php or on Windows */
        // $this->conn = @pg_pconnect("host=$host port=5432 dbname=$db user=$user password=$passwd");
		if (empty($db) || $this->conn == false) {
			print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
            print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
            print "<head>\n";
            print "    <title>phpMyFAQ Error</title>\n";
            print "    <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=utf-8\" />\n";
            print "</head>\n";
            print "<body>\n";
            print "<p align=\"center\">The connection to the PostgreSQL server could not be established.</p>\n";
			print "<p align=\"center\">The error message of the PostgresSQL server:<br />".pg_last_error($this->conn)."</p>\n";
			print "</body>\n";
            print "</html>";
            return false;
			}
		return true;
    }
	
    /**
     * Sends a query to the database.
     *
     * This function sends a query to the database.
     *
     * @param   string $query
     * @return  mixed $result
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-02-24
     */
	function query($query)
    {
		$this->sqllog .= $query."<br />\n";
        if (function_exists('pg_query')) {
            return pg_query($this->conn, $query);
        } else {
            return pg_exec($this->conn, $query);
        }
    }
    
    /**
    * Escapes a string for use in a query
    *
    * @param   string
    * @return  string
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2004-12-16
    */
	function escape_string($string)
    {
        if (function_exists('pg_escape_string')) {
            return pg_escape_string($string);
        } else {
            return addslashes($string);
        }
    }
	
    /**
     * Fetch a result row as an object
     *
     * This function fetches a result row as an object.
     *
     * @param   mixed $result
     * @return  mixed
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-02-24
     */
	function fetch_object($result)
    {
		$ret = pg_fetch_object($result);
        return $ret;
    }
	
	
    
    /**
     * Fetch a result row as an object
     *
     * This function fetches a result as an associative array.
     *
     * @param   mixed $result
     * @return  array
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-02-24
     */
	function fetch_assoc($result)
    {
		if (!function_exists('pg_fetch_assoc')) {
     		$ret = pg_fetch_array($result, NULL, PGSQL_ASSOC);
        } else {
			$ret = pg_fetch_assoc($result);
        }
        return $ret;
    }
	
    /**
     * Number of rows in a result
     *
     * @param   mixed $result
     * @return  integer
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-02-24
     */
	function num_rows($result)
    {
		if (function_exists('pg_num_rows')) {
            return pg_num_rows($result);
        } else {
            return pg_numrows($result);
        }
    }
	
    /**
     * Returns the ID of the latest insert
     *
     * @return  integer
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-08-05
     */
	function insert_id($table, $field)
    {
 		$res = $this->query("SELECT last_value FROM ".$table."_".$field."_seq");
 		$row = pg_fetch_row($res, 0);
 		return $row[0]; 
    }
    
    /**
     * Logs the queries
     *
     * @param   mixed $result
     * @return  integer
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2003-02-24
     */
	function sqllog()
    {
		$ret = $this->sqllog;
        return $ret;
    }
	
    /**
     * Closes the connection to the database.
     *
     * This function closes the connection to the database.
     *
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2003-02-24
     */
	function dbclose()
    {
		$ret = @pg_close($this->conn);
        return $ret;
    }

	/**
     * fti_check.
     *
     * This function test for FULL TEXT INDEXING extension support.
     * FIXME: implement
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2004-08-06
     */
	function fti_check() { return false; }

    /**
    * getOne
    *
    * TODO: add documentation
    *
    * @param    string
    * @return   string
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2004-08-06
    */
	function getOne($query)
	{
		$row = pg_fetch_row($this->query($query));
		return $row[0];
	}

	/**
     * fti_check.
     *
     * This function test for FULL TEXT INDEXING extension support.
     * FIXME: implement
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2004-08-06
     */
	function getTableStatus()
	{
		$select = "SELECT relname FROM pg_stat_user_tables ORDER BY relname;";	
		$arr = array();
		$result = $this->query($select);
		while ($row = $this->fetch_assoc($result)) {
			$count = $this->getOne("SELECT count(1) FROM ".$row["relname"].";");
            $arr[$row["relname"]] = $count;
        }
		return $arr;
	}

	 /**
     * Generates a result based on search a search string.
     *
     * This function generates a result set based on a search string.
     * FIXME: can extend to handle operands like google
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2004-08-06
     */
	function search($table, $assoc, $joinedTable = '', $joinAssoc = array(), $match = array(), $string = '', $cond = array())
	{
		$string = pg_escape_string(trim($string));
		$fields = "";
        $joined = "";
		$where = "";
		foreach ($assoc as $field) {
            
            if (empty($fields)) {
				
                $fields = $field;
			} else {
				
                $fields .= ", ".$field;
            }
		}
        
        if (isset($joinedTable) && $joinedTable != '') {
            
            $joined .= ' LEFT JOIN '.$joinedTable.' ON ';
        }
        
        if (is_array($joinAssoc)) {
            
            foreach ($joinAssoc as $joinedFields) {
                $joined .= $joinedFields.' AND ';
                }
            $joined = substr($joined, 0, -4);
        }
        
		foreach ($cond as $field => $data) {
			if (empty($where)) {
				$where .= $field." = ".$data;
            } else {
				$where .= " AND ".$field." = ".$data;
            }
		}
	    
		$match = implode("|| ' ' ||", $match);
        
		if ($this->fti_check() == false)  {
			$query = "SELECT ".$fields." FROM ".$table.$joined." WHERE (".$match.") ILIKE ('%".$string."%')";
        } else {
			// use fti postgres extension - NOT IMPLEMENTED
		}
        
		if (!empty($where)) {
			$query .= " AND (".$where.")";
        }
		
        if (is_numeric($string)) {
            $query = "SELECT ".$fields." FROM ".$table.$joined." WHERE ".$match." = ".$string;
        }
        
		return $this->query($query);
	}
	
	/**
	* Returns the next ID of a table
	*
	* This function is a replacement for MySQL's auto-increment so that
	* we don't need it anymore.
	*
	* @param   string      the name of the table
	* @param   string      the name of the ID column
	* @return  int
	* @access  public
	* @author  Thorsten Rinne <thorsten@phpmyfaq.de>
	* @since   2004-11-30
	*/
	function nextID($table, $id)
	{
	    $result = $this->query("SELECT nextval('".$table."_".$id."_seq') as current_id;");
	    $currentID = pg_result($result, 0, 'current_id');
	    return ($currentID);
	}
		
	/**
    * Returns the error string.
    *
    * This function returns the last error string.
    * NOTE: can extend to handle operands like google
    * @access  public
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2004-08-06
    */

	function error()
	{ 
	    return pg_last_error(); 
	}

	/**
    * Returns the client version string.
    *
    * This function returns the client version string.
    * NOTE: needs PHP5
    *
    * @access  public
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2004-08-06
    */
  	function client_version()
    {
        if (function_exists('pg_version')) {
            $pg_version = pg_version();
            if (isset($pg_version['client'])) {
                return $pg_version['client'];
            } else {
                return 'n/a';
            }
        } else {
            return 'n/a';
        }
    }

	/**
    * Returns the server version string.
    *
    * This function returns the server version string.
    * NOTE: needs PHP5
    *
    * @access  public
    * @author  Thorsten Rinne
    * @since   2004-11-12
    */
	function server_version()
    {
        if (function_exists('pg_version')) {
            $pg_version = pg_version();
            if (isset($pg_version['server_version'])) {
                return $pg_version['server_version'];
            } else {
                return 'n/a';
            }
        } else {
            return 'n/a';
        }
    }
}