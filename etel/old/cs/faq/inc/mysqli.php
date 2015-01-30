<?php
/**
* $Id: mysqli.php,v 1.2.2.7.2.2 2006/03/11 21:48:21 thorstenr Exp $
*
* db_mysqli
*
* The db_mysqli class provides methods and functions for a MySQL 4.1.x 
* and 5.0.x database.
*
* @author       Thorsten Rinne <thorsten@phpmyfaq.de>
* @author       David Soria Parra <dsoria@gmx.net>
* @package      db_mysqli
* @since        2005-02-21
* @copyright:   (c) 2006 phpMyFAQ Team
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

class db_mysqli
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
     * @since   2005-02-21
     */
    function __construct()
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
     * @since   2005-02-21
     */
	function connect ($host, $user, $passwd, $db)
    {
		$this->conn = new mysqli($host, $user, $passwd, $db);
		if (mysqli_connect_errno()) {
            print "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
            print "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\" lang=\"en\">\n";
            print "<head>\n";
            print "    <title>phpMyFAQ Error</title>\n";
            print "    <meta http-equiv=\"content-type\" content=\"application/xhtml+xml; charset=utf-8\" />\n";
            print "</head>\n";
            print "<body>\n";
            print "<p align=\"center\">The connection to the MySQL server could not be established.</p>\n";
            print "<p align=\"center\">The error message of the MySQL server:<br />".mysqli_connect_error()."</p>\n";
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
     * @since   2005-02-21
     */
	function query($query)
    {
		$this->sqllog .= $query."<br />\n";
		return $this->conn->query($query);
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
      return $this->conn->real_escape_string($string);
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
     * @since   2005-02-21
     */
	function fetch_object($result)
    {
		return $result->fetch_object();
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
     * @since   2005-02-21
     */
	function fetch_assoc($result)
    {
		return $result->fetch_assoc();
    }
	
    
    
    /**
     * Number of rows in a result
     *
     * @param   mixed $result
     * @return  integer
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2005-02-21
     */
	function num_rows($result)
    {
		return $result->num_rows;
    }
	
    /**
     * Returns the ID of the latest insert
     *
     * @return  integer
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2005-02-21
     */
	function insert_id($table, $field)
    {
		$result = $this->query('SELECT max('.$field.') AS last_id FROM '.$table);
		return $this->fetch_object($result)->last_id;
    }
    
    /**
     * Logs the queries
     *
     * @param   mixed $result
     * @return  integer
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2005-02-21
     */
	function sqllog()
    {
		return $this->sqllog;
    }
	
    
    
	 /**
     * Generates a result based on search a search string.
     *
     * This function generates a result set based on a search string.
     * FIXME: can extend to handle operands like google
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2005-02-21
     */
	function search($table, $assoc, $joinedTable = '', $joinAssoc = array(), $match = array(), $string = '', $cond = array())
	{
		$string = $this->escape_string(trim($string));
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
	    
		$match = implode(",", $match);
        
        if (is_numeric($string)) {
            $query = "SELECT ".$fields." FROM ".$table.$joined." WHERE ".$match." = ".$string;
        } else {
            $query = "SELECT ".$fields." FROM ".$table.$joined." WHERE MATCH (".$match.") AGAINST ('".$string."' IN BOOLEAN MODE)";
        }
        
		if (!empty($where)) {
			$query .= " AND (".$where.")";
        }
        
		return $this->query($query);
	}

	 /**
     * Returns the error string.
     *
     * This function returns the table status.
     *
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2005-02-21
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
	* @since   2005-02-21
	*/
	function nextID($table, $id)
	{
	    $result = $this->query('SELECT max('.$id.') as current_id FROM '.$table);
	    $currentID = $this->fetch_object($result)->current_id;
	    return ($currentID + 1);
	}
	
	 /**
     * Returns the error string.
     *
     * This function returns the last error string.
     *
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2005-02-21
     */
	function error()
    {
        return @$this->conn->error();
    }

    /**
     * Returns the client version string.
     *
     * This function returns the version string.
     *
     * @access  public
     * @author  Tom Rochester <tom.rochester@gmail.com>
     * @since   2005-02-21
     */
	function client_version()
    {
        return @$this->conn->get_client_info();
    }

    /**
     * Returns the server version string.
     *
     * This function returns the version string.
     *
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2005-02-21
     */
	function server_version()
    {
        return @$this->conn->get_server_info();
    }

    /**
     * Closes the connection to the database.
     *
     * This function closes the connection to the database.
     *
     * @access  public
     * @author  Thorsten Rinne <thorsten@phpmyfaq.de>
     * @since   2005-02-21
     */
	function dbclose()
    {
		return @$this->conn->close();
    }

}
?>
