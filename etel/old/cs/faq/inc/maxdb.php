<?php
/**
* $Id: maxdb.php,v 1.5.2.5.2.2 2006/05/11 12:54:07 thorstenr Exp $
*
* db_maxdb
*
* The db_maxdb class provides methods and functions for a MaxDB 7.5.x database.
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @package      db_maxdb
* @since        2005-09-05
*
* Copyright:    (c) 2006 phpMyFAQ Team
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

class db_maxdb
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
	var $sqllog = '';
	
    /**
    * Constructor
    *
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function db_maxdb()
    {
    }
    
    function __construct()
    {
    }
    
    /**
    * connect()
    *
    * This function connects to a MaxDB database
    *
    * @param   string $host
    * @param   string $username
    * @param   string $password
    * @param   string $db_name
    * @return  boolean true, if connected, otherwise false
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function connect($host, $user, $passwd, $db)
    {
		$this->conn = maxdb_connect($host, $user, $passwd, $db);
		if (empty($db) || $this->conn == false) {
            print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
            print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
            print "<head>\n";
            print "    <title>phpMyFAQ Error</title>\n";
            print "    <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=utf-8\" />\n";
            print "</head>\n";
            print "<body>\n";
            print "<p align=\"center\">The connection to the maxdb server could not be established.</p>\n";
            print "<p align=\"center\">The error message of the maxdb server:<br />".maxdb_connect_error()."</p>\n";
            print "</body>\n";
            print "</html>";
            return false;
        }
		return $this->conn;
    }
	
    /**
    * query()
    *
    * This function sends a query to the database.
    *
    * @param   string $query
    * @return  mixed $result
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function query($query)
    {
		$this->sqllog .= $query."<br />\n";
		return maxdb_query($this->conn, $query);
    }
	
    /**
    * escape_string()
    *
    * Escapes a string for use in a query
    *
    * @param   string
    * @return  string
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function escape_string($string)
    {
      return maxdb_real_escape_string($this->conn, $string);
    }
    
    /**
    * fetch_object()
    *
    * This function fetches a result row as an object.
    *
    * @param   mixed $result
    * @return  mixed
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function fetch_object($result)
    {
		return maxdb_fetch_object($result);
    }
	
    
	
    /**
    * fetch_assoc()
    *
    * This function fetches a result as an associative array.
    *
    * @param   mixed $result
    * @return  array
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function fetch_assoc($result)
    {
		return maxdb_fetch_assoc($result);
    }
	
    /**
    * num_rows()
    *
    * Number of rows in a result
    *
    * @param   mixed $result
    * @return  integer
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function num_rows($result)
    {
		return maxdb_num_rows($result);
    }
	
    /**
    * insert_id()
    *
    * Returns the ID of the latest insert
    *
    * @return  integer
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function insert_id($table, $field)
    {
		$result = $this->query('SELECT max('.$field.') AS last_id FROM '.$table);
        $row = $this->fetch_object($result);
		return $row->last_id;
    }
    
    /**
    * sqllog()
    *
    * Returns the logged queries
    *
    * @param   mixed $result
    * @return  integer
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function sqllog()
    {
		return $this->sqllog;
    }
	
    
    
	/**
    * search()
    *
    * This function generates a result set based on a search string.
    *
    * @access  public
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2005-09-05
    */
	function search($table, $assoc, $joinedTable = '', $joinAssoc = array(), $match = array(), $string = '', $cond = array())
	{
		$string = addslashes(trim($string));
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
        
        $keys = preg_split("/\s+/", $string);
        $numKeys = count($keys);
		$numMatch = count($match);
		
		for ($i = 0; $i < $numKeys; $i++) {
            if (strlen($where) != 0 ) {
                $where = $where." OR";
            }
			$where = $where." (";
			for ($j = 0; $j < $numMatch; $j++) {
				if ($j != 0) {
				    $where = $where." OR ";
				}
		    	$where = $where.$match[$j]." LIKE '%".addslashes($keys[$i])."%'";
		    }
			
			$where .= ")";
		}
        
		foreach ($cond as $field => $data) {
			if (empty($where)) {
				$where .= $field." = ".$data;
            } else {
				$where .= " AND ".$field." = ".$data;
            }
		}
        
        $query = "SELECT ".$fields." FROM ".$table.$joined." WHERE";
        
		if (!empty($where)) {
			$query .= " AND (".$where.")";
        }
        
        if (is_numeric($string)) {
            $query = "SELECT ".$fields." FROM ".$table.$joined." WHERE ".$match." = ".$string;
        }
        
        return $this->query($query);
	}
    
	/**
    * getTableStatus()
    *
    * This function returns the table status.
    *
    * @access  public
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2005-09-05
    */
	function getTableStatus() 
	{
		$arr = array();
		$result = $this->query("SHOW TABLE STATUS");
		while ($row = $this->fetch_assoc($result)) {
            $arr[$row["Name"]] = $row["Rows"];
        }
		return $arr;
	}
    
	/**
	* nextID()
	*
	* This function is a replacement for maxdb's auto-increment so that
	* we don't need it anymore.
	*
	* @param   string      the name of the table
	* @param   string      the name of the ID column
	* @return  int
	* @access  public
	* @author  Thorsten Rinne <thorsten@phpmyfaq.de>
	* @since   2005-09-05
	*/
	function nextID($table, $id)
	{
	    $result = $this->query('SELECT max('.$id.') as current_id FROM '.$table);
        $res = $this->fetch_object($result);
		return ($res->current_id + 1);
	}
	
	/**
    * error()
    *
    * This function returns the last error string.
    *
    * @access  public
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2005-09-05
    */
	function error()
    {
        return maxdb_error();
    }
    
    /**
    * client_version()
    *
    * This function returns the version string.
    *
    * @access  public
    * @author  Tom Rochester <tom.rochester@gmail.com>
    * @since   2005-09-05
    */
	function client_version()
    {
        return maxdb_get_client_info();
    }
    
    /**
    * server_version()
    *
    * This function returns the version string.
    *
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function server_version()
    {
        return maxdb_get_server_info();
    }
    
    /**
    * dbclose()
    *
    * This function closes the connection to the database.
    *
    * @access  public
    * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
    * @since   2005-09-05
    */
	function dbclose()
    {
		return maxdb_close($this->conn);
    }
}
