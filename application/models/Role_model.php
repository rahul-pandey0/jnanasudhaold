<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role_model extends CI_Model {

    protected $table = 'role_details';

    public function __construct()
    {
        parent::__construct();
    }
    

    /**
     * Get all roles
     */
   public function get_roles($limit, $offset)
{
    $db = get_db();

    $limit  = (int)$limit;
    $offset = (int)$offset;

    $sql = "SELECT *
            FROM {$this->table}
            ORDER BY role_id  ASC
            LIMIT $limit OFFSET $offset";

    return $db->query($sql); 
}

/**
     * Get single role by role_id
     */
    public function get_role($role_id)
    {
        $db = get_db();

        
        $role_id = (int)$role_id;
        $sql = "SELECT * FROM " . $this->table . " WHERE role_id = " . $role_id;
        $result = $db->query($sql);
        return $db->get_row($result);
    }

    /**
     * Insert new role
     */
   public function insert_role($data)
{
    if (isset($data['role_id'])) {
        $data['role_id'] = (int)$data['role_id'];
    }

    
    return db_insert($this->table, $data);
}

    /**
     * Update role
     */
    public function update_role($role_id, $data)
    {
        $db = get_db();
        $role_id = (int)$role_id;
        return db_update($this->table, $data, ['role_id' => $role_id]) ? true : false;
    }

    /**
     * Delete role
     */
    public function delete_role($role_id)
    {
        $db = get_db();
        $role_id = (int)$role_id;
        return db_delete($this->table, ['role_id' => $role_id]) ? true : false;
    }

    
    public function is_duplicate($role_id)
    {
        $db = get_db();
        $role_id = (int)$role_id;
        $sql = "SELECT COUNT(*) AS total FROM " . $this->table . " WHERE role_id = " . $role_id;
        $result = $db->query($sql);
        $row = $db->get_row($result);
        return ($row['total'] > 0);
    }
    public function get_total_roles()
{
    $db = get_db();
    $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
    $query = $db->query($sql);

    $row = $query->fetch_assoc();
    return (int)$row['total'];
}
public function get_next_role_id()
{
    $db = get_db();
    $sql = "SELECT MAX(role_id) AS max_id FROM " . $this->table;
    $result = $db->query($sql);
    $row = $db->get_row($result);
    $next_id = isset($row['max_id']) ? ((int)$row['max_id'] + 1) : 1;
    return $next_id;
}
public function get_all_roles()
{
    $db = get_db();

    $sql = "SELECT role_id, role_name
            FROM {$this->table}
            ORDER BY role_id ASC";

    $result = $db->query($sql);
    return $db->get_result($result);
}



}
