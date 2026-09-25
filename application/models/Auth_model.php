<?php
/**
 * Auth Model - Handles authentication
 */

class Auth_model extends CI_Model {

    protected $table = 'user_details';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Authenticate user against database
     * Accepts either user_name or email
     * Checks: user exists, password matches (MD5), role != 2, user_status = 1
     */
    public function authenticate($login, $password)
    {
        $db = get_db();
        
        // MD5 encrypt the password for comparison
        $encrypted_password = md5($password);
        
        // Query user with conditions - accept either user_name or email
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE (user_name = '" . $db->escape($login) . "' 
                OR email = '" . $db->escape($login) . "')
                AND password = '" . $db->escape($encrypted_password) . "'
                AND role_id != 2
                AND user_status = 1
                LIMIT 1";
        
        $result = $db->query($sql);
        return $db->get_row($result);
    }

    /**
     * Get user by ID
     */
    public function get_user_by_id($user_id)
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table . " WHERE user_id = " . (int)$user_id . " LIMIT 1";
        $result = $db->query($sql);
        return $db->get_row($result);
    }
}
