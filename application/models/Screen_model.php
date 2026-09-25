<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Screen_model extends CI_Model {

    protected $table = 'screen';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Insert new screen
     */
    public function insert_screen($data)
    {
        return db_insert($this->table, $data);
    }
   
/**
 * Get single screen by ID
 */
public function get_screen_by_id($id)
{
    $db = get_db();
    $id = (int)$id; 

    $sql = "SELECT screen_id, screen_name, link, status,main_menu
            FROM screen
            WHERE screen_id = $id";

    $result = $db->query($sql);
    return $db->get_row($result);
}

/**
 * Get screen
 */
public function update_screen($id, $data)
{
    return db_update($this->table, $data, ['screen_id' => (int)$id]);
}
public function get_screens($limit = 10, $offset = 0)
{
    $db = get_db();
    $limit = (int)$limit;
    $offset = (int)$offset;

    $sql = "SELECT screen_id,main_menu,screen_name, link, status 
            FROM screen 
            ORDER BY screen_id ASC 
            LIMIT $limit OFFSET $offset";

    $result = $db->query($sql);
    return $db->get_result($result);
}

/**
 * Get total number of screens
 */
public function count_screens()
{
    $db = get_db();
    $sql = "SELECT COUNT(*) AS total FROM screen";
    $result = $db->query($sql);
    $row = $db->get_row($result);
    return $row ? (int)$row['total'] : 0;
}
public function get_all_screens()
{
    $db = get_db();
    $sql = "SELECT screen_id, screen_name,link
            FROM screen
            WHERE status = 'Active'
            ORDER BY screen_id ASC";
    $result = $db->query($sql);
    return $db->get_result($result);
}
public function insert_menu_screen($data)
{
    return db_insert('screen', $data);
}
public function get_main_menus()
{
    $db = get_db();
    $sql = "SELECT DISTINCT main_menu 
            FROM screen 
            WHERE status = 'Active' AND 
            main_menu IS NOT NULL 
              AND main_menu != ''
            ORDER BY main_menu ASC";

    $result = $db->query($sql);
    return $db->get_result($result);
}


}
