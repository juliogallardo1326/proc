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
// @(#) $Id: s.class.category.php 1.12 03/12/31 17:29:00-00:00 jpradomaia $
//

include_once(APP_INC_PATH . "class.error_handler.php");
include_once(APP_INC_PATH . "class.misc.php");
include_once(APP_INC_PATH . "class.validation.php");

/**
 * Class to handle project category related issues.
 *
 * @version 1.0
 * @author Jo�o Prado Maia <jpm@mysql.com>
 */

class Category
{
    /**
     * Method used to get the full details of a category.
     *
     * @access  public
     * @param   integer $prc_id The category ID
     * @return  array The information about the category provided
     */
    function getDetails($prc_id)
    {
        $stmt = "SELECT
                    *
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 WHERE
                    prc_id=" . Misc::escapeInteger($prc_id);
        $res = $GLOBALS["db_api"]->dbh->getRow($stmt, DB_FETCHMODE_ASSOC);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return "";
        } else {
            return $res;
        }
    }


    /**
     * Method used to remove all categories related to a set of
     * specific projects.
     *
     * @access  public
     * @param   array $ids The project IDs to be removed
     * @return  boolean Whether the removal worked or not
     */
    function removeByProjects($ids)
    {
        $items = @implode(", ", Misc::escapeInteger($ids));
        $stmt = "DELETE FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 WHERE
                    prc_prj_id IN ($items)";
        $res = $GLOBALS["db_api"]->dbh->query($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        } else {
            return true;
        }
    }


    /**
     * Method used to remove user-selected categories from the 
     * database.
     *
     * @access  public
     * @return  boolean Whether the removal worked or not
     */
    function remove()
    {
        global $HTTP_POST_VARS;

        $items = @implode(", ", Misc::escapeInteger($HTTP_POST_VARS["items"]));
        $stmt = "DELETE FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 WHERE
                    prc_id IN ($items)";
        $res = $GLOBALS["db_api"]->dbh->query($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return false;
        } else {
            return true;
        }
    }


    /**
     * Method used to update the values stored in the database. 
     * Typically the user would modify the title of the category in 
     * the application and this method would be called.
     *
     * @access  public
     * @return  integer 1 if the update worked properly, any other value otherwise
     */
    function update()
    {
        global $HTTP_POST_VARS;

        if (Validation::isWhitespace($HTTP_POST_VARS["title"])) {
            return -2;
        }
        $stmt = "UPDATE
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 SET
                    prc_title='" . Misc::escapeString($HTTP_POST_VARS["title"]) . "'
                 WHERE
                    prc_prj_id=" . Misc::escapeInteger($HTTP_POST_VARS["prj_id"]) . " AND
                    prc_id=" . Misc::escapeInteger($HTTP_POST_VARS["id"]);
        $res = $GLOBALS["db_api"]->dbh->query($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return -1;
        } else {
            return 1;
        }
    }


    /**
     * Method used to add a new category to the application.
     *
     * @access  public
     * @return  integer 1 if the update worked properly, any other value otherwise
     */
    function insert()
    {
        global $HTTP_POST_VARS;

        if (Validation::isWhitespace($HTTP_POST_VARS["title"])) {
            return -2;
        }
        $stmt = "INSERT INTO
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 (
                    prc_prj_id,
                    prc_title
                 ) VALUES (
                    " . Misc::escapeInteger($HTTP_POST_VARS["prj_id"]) . ",
                    '" . Misc::escapeString($HTTP_POST_VARS["title"]) . "'
                 )";
        $res = $GLOBALS["db_api"]->dbh->query($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return -1;
        } else {
            return 1;
        }
    }


    /**
     * Method used to get the full list of categories associated with
     * a specific project.
     *
     * @access  public
     * @param   integer $prj_id The project ID
     * @return  array The full list of categories
     */
    function getList($prj_id)
    {
        $stmt = "SELECT
                    prc_id,
                    prc_title
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 WHERE
                    prc_prj_id=" . Misc::escapeInteger($prj_id) . "
                 ORDER BY
                    prc_title ASC";
        $res = $GLOBALS["db_api"]->dbh->getAll($stmt, DB_FETCHMODE_ASSOC);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return "";
        } else {
            return $res;
        }
    }


    /**
     * Method used to get an associative array of the list of 
     * categories associated with a specific project.
     *
     * @access  public
     * @param   integer $prj_id The project ID
     * @return  array The associative array of categories
     */
    function getAssocList($prj_id)
    {
        static $list;

        if (!empty($list[$prj_id])) {
            return $list[$prj_id];
        }

        $stmt = "SELECT
                    prc_id,
                    prc_title
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 WHERE
                    prc_prj_id=" . Misc::escapeInteger($prj_id) . "
                 ORDER BY
                    prc_title ASC";
        $res = $GLOBALS["db_api"]->dbh->getAssoc($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return "";
        } else {
            $list[$prj_id] = $res;
            return $res;
        }
    }


    /**
     * Method used to get the title for a specific project category.
     *
     * @access  public
     * @param   integer $prc_id The category ID
     * @return  string The category title
     */
    function getTitle($prc_id)
    {
        $stmt = "SELECT
                    prc_title
                 FROM
                    " . APP_DEFAULT_DB . "." . APP_TABLE_PREFIX . "project_category
                 WHERE
                    prc_id=" . Misc::escapeInteger($prc_id);
        $res = $GLOBALS["db_api"]->dbh->getOne($stmt);
        if (PEAR::isError($res)) {
            Error_Handler::logError(array($res->getMessage(), $res->getDebugInfo()), __FILE__, __LINE__);
            return "";
        } else {
            return $res;
        }
    }
}

// benchmarking the included file (aka setup time)
if (APP_BENCHMARK) {
    $GLOBALS['bench']->setMarker('Included Category Class');
}
?>