<?php
/**
 * Database Helper Functions
 */

if (!function_exists('get_db')) {
    /**
     * Get Database Instance
     */
    function get_db()
    {
        if (!isset($GLOBALS['db_instance'])) {
            $config = array();
            require_once APPPATH . 'config/database.php';
            
            require_once BASEPATH . 'core/DB.php';
            $GLOBALS['db_instance'] = new CI_DB($db['default']);
        }
        
        return $GLOBALS['db_instance'];
    }
}

if (!function_exists('db_query')) {
    /**
     * Execute a database query
     */
    function db_query($sql, $params = array())
    {
        return get_db()->query($sql, $params);
    }
}

if (!function_exists('db_get')) {
    /**
     * Get records from database
     */
    function db_get($table, $where = array())
    {
        return get_db()->get($table, $where);
    }
}

if (!function_exists('db_insert')) {
    /**
     * Insert record into database
     */
    function db_insert($table, $data = array())
    {
        return get_db()->insert($table, $data);
    }
}

if (!function_exists('db_update')) {
    /**
     * Update record in database
     */
    function db_update($table, $data = array(), $where = array())
    {
        return get_db()->update($table, $data, $where);
    }
}

if (!function_exists('db_delete')) {
    /**
     * Delete record from database
     */
    function db_delete($table, $where = array())
    {
        return get_db()->delete($table, $where);
    }
}

if (!function_exists('db_result')) {
    /**
     * Get results from database query
     */
    function db_result($result_obj)
    {
        return get_db()->get_result($result_obj);
    }
}

if (!function_exists('db_row')) {
    /**
     * Get single row from database query
     */
    function db_row($result_obj)
    {
        return get_db()->get_row($result_obj);
    }
}
