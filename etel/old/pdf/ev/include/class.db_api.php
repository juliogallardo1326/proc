<?php
/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | Eventum - Issue Tracking System                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2003, 2004, 2005 MySQL AB                              |
// |                                                                      |
// | This program is free software; you can redistribute it and/or modify |
// | it under the terms of the GNU General Public License as published by |
// | the Free Software Foundation; either version 2 of the License, or    |
// | (at your option) any later version.                                  |
// |                                                                      |
// | This program is distributed in the hope that it will be useful,      |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of       |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the        |
// | GNU General Public License for more details.                         |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// | along with this program; if not, write to:                           |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+
// | Authors: Jo�o Prado Maia <jpm@mysql.com>                             |
// +----------------------------------------------------------------------+
//
// @(#) $Id: s.class.db_api.php 1.19 04/01/19 15:19:26-00:00 jpradomaia $
//

$TOTAL_QUERIES = 0;

include_once("DB.php");
include_once(APP_INC_PATH . "class.error_handler.php");

/**
 * Class to manage all tasks related to the DB abstraction module. This is only
 * useful to mantain a data dictionary of the current database schema tables.
 *
 * @version 1.0
 * @author Jo�o Prado Maia <jpm@mysql.com>
 */

class DB_API
{
    var $dbh;

    /**
     * Connects to the database and creates a data dictionary array to be used
     * on database related schema dynamic lookups.
     *
     * @access public
     */
    function DB_API()
    {
        $dsn = array(
            'phptype'  => APP_SQL_DBTYPE,
            'hostspec' => APP_SQL_DBHOST,
            'database' => APP_SQL_DBNAME,
            'username' => APP_SQL_DBUSER,
            'password' => APP_SQL_DBPASS
        );
        // if we are using some non-standard mysql port, pass that value in the dsn
        if ((defined('APP_SQL_DBPORT')) && (APP_SQL_DBPORT != 3306)) {
            $dsn['port'] = APP_SQL_DBPORT;
        }
        $this->dbh = DB::connect($dsn);
        if (PEAR::isError($this->dbh)) {
            Error_Handler::logError(array($this->dbh->getMessage(), $this->dbh->getDebugInfo()), __FILE__, __LINE__);
            $error_type = "db";
            include_once(APP_PATH . "offline.php");
            exit;
        }
    }


    /**
     * Method used to get the last inserted ID. This is a simple
     * wrapper to the mysql_insert_id function, as a work around to
     * the somewhat annoying implementation of PEAR::DB to create
     * separate tables to host the ID sequences.
     *
     * @access  public
     * @return  integer The last inserted ID
     */
    function get_last_insert_id()
    {
        return @mysql_insert_id($this->dbh->connection);
    }


    /**
     * Returns the escaped version of the given string.
     *
     * @access  public
     * @param   string $str The string that needs to be escaped
     * @return  string The escaped string
     */
    function escapeString($str)
    {
        static $real_escape_string;

        if (!isset($real_escape_string)) {
            $real_escape_string = function_exists('mysql_real_escape_string');
        }

        if ($real_escape_string) {
            return mysql_real_escape_string($str, $this->dbh->connection);
        } else {
            return mysql_escape_string($str);
        }
    }


    /**
     * Returns the SQL used to calculate the difference of 2 dates, not counting weekends.
     * This thing is truly a work of art, the type of art that throws lemon juice in your eye and then laughs.
     * If $end_date_field is null, the current date is used instead.
     *
     * @access  public
     * @param   string $start_date_field The name of the field the first date is.
     * @param   string $end_date_field The name of the field where the second date is.
     * @return  string The SQL used to compare the 2 dates.
     */
    function getNoWeekendDateDiffSQL($start_date_field, $end_date_field = false)
    {
        if ($end_date_field == false) {
            $end_date_field = "'" . Date_API::getCurrentDateGMT() . "'";
        }

        // this is crazy, but it does work. Anyone with a better solution email bryan@mysql.com
        $sql = "((UNIX_TIMESTAMP($end_date_field) - UNIX_TIMESTAMP($start_date_field)) - (CASE
            WHEN DAYOFWEEK($start_date_field) = 1 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field))-1)/7) * 86400 * 2)
            WHEN DAYOFWEEK($start_date_field) = 2 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field)))/7) * 86400 *2)
            WHEN DAYOFWEEK($start_date_field) = 3 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field))+1)/7) * 86400 *2)
            WHEN DAYOFWEEK($start_date_field) = 4 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field))+2)/7) * 86400 *2)
            WHEN DAYOFWEEK($start_date_field) = 5 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field))+3)/7) * 86400 *2)
            WHEN DAYOFWEEK($start_date_field) = 6 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field))+4)/7) * 86400 *2)
            WHEN DAYOFWEEK($start_date_field) = 7 THEN (floor(((TO_DAYS($end_date_field) - TO_DAYS($start_date_field))-2)/7) * 86400 *2)
        END) - (CASE
            WHEN DAYOFWEEK($start_date_field) = 7 THEN (86400 + (86400 - time_to_sec($start_date_field)))
            WHEN DAYOFWEEK($start_date_field) = 1 THEN (86400 - time_to_sec($start_date_field))
            ELSE 0
        END) - CASE
            WHEN DAYOFWEEK($end_date_field) = 7 THEN time_to_sec($end_date_field)
            WHEN DAYOFWEEK($end_date_field) = 1 THEN (86400 + time_to_sec($end_date_field))
            ELSE 0
        END)";
        return str_replace("\n", " ", $sql);
    }
}

// benchmarking the included file (aka setup time)
if (APP_BENCHMARK) {
    $GLOBALS['bench']->setMarker('Included DB_API Class');
}
?>