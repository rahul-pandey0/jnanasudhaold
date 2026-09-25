<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_menu_model extends CI_Model {

    protected $table = 'menu_details_admin';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert menu
     */
    public function insert_menu($data)
    {
        return db_insert($this->table, $data);
    }

    /**
     * Get menus by role
     */
    public function get_menus_by_role($role_id)
    {
        $db = get_db();
        $role_id = (int)$role_id;

        $sql = "SELECT * FROM {$this->table} 
                WHERE ROLE_ID = {$role_id} 
                ORDER BY ORDER_NO ASC";

        $result = $db->query($sql);
        return $db->get_result($result);
    }
   


 
   public function get_menus($limit, $offset)
{
    $db = get_db();

    
    $limit = (int)$limit;
    $offset = (int)$offset;

    $sql = "SELECT id,ROLE_ID, MAIN_MENU, NAVIGATION, MENUTEXT, AVAILABLE, ORDER_NO
            FROM {$this->table}
            ORDER BY ORDER_NO ASC
            LIMIT {$offset}, {$limit}";

    return $db->query($sql);
}

    
   public function get_total_menus()
{
    $db = get_db();
    $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
    $query = $db->query($sql);

   
    $row = $query->fetch_assoc(); 
    return (int)$row['total'];
}
public function get_distinct_column($column)
    {
        $allowed = ['ROLE_ID', 'NAVIGATION', 'MENUTEXT','MAIN_MENU'];

        if (!in_array($column, $allowed)) {
            return [];
        }

        $db = get_db();

        $sql = "SELECT DISTINCT {$column}
            FROM {$this->table}
            WHERE {$column} != ''
            ORDER BY {$column} ASC";
         $result = $db->query($sql);
        return $db->get_result($result); 
    }
  /*  public function insert_menu_and_screen($menu_data, $screen_data)
{
    $db = get_db();

    // Start transaction
    $db->query("START TRANSACTION");

    try {

        $menu_insert = $db->insert($this->table, $menu_data);
        if ($menu_insert === false) {
            throw new Exception('Menu insert failed');
        }

        $screen_insert = $db->insert('screen', $screen_data);
        if ($screen_insert === false) {
            throw new Exception('Screen insert failed');
        }

        
        $db->query("COMMIT");
        return true;

    } catch (Exception $e) {

        
        $db->query("ROLLBACK");
        return false;
    }
}*/
public function get_menu_by_id($id)
{
    $db = get_db();
    $id = (int)$id;

    $sql = "SELECT * FROM {$this->table} WHERE id= {$id}";
    $result = $db->query($sql);

    return $result ? $result->fetch_assoc() : null;
}

public function update_menu($id, $data)
{
    $db = get_db();
    $id = (int)$id;

    return $db->update($this->table, $data, ['id' => $id]);
}

public function enable_menu($id)
{
    $db = get_db();
    $id = (int)$id;
    return $db->query("UPDATE {$this->table} SET AVAILABLE='YES' WHERE id={$id}"
    );
}

public function disable_menu($id)
{
    $db = get_db();
    $id = (int)$id;
    return $db->query("UPDATE {$this->table} SET AVAILABLE='NO' WHERE id={$id}"
    );
}

 }

