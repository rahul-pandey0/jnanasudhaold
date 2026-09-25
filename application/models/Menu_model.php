<?php
/**
 * Menu Model - Dynamic menu loading based on user role
 * Replaces legacy Login_model menu methods with secure, modern approach
 */

class Menu_model extends CI_Model {

    private $menu_table = 'menu_details_admin';
    private $user_table = 'user_details';

    public function __construct()
    {
        parent::__construct();
        // Ensure database helper is loaded
        require_once APPPATH . 'helpers/database_helper.php';
    }

    /**
     * Get top-level menus based on user role
     * @param int $role_id User role ID
     * @return array Menu items or empty array
     */
    public function get_top_menus($role_id)
    {
        if (!$role_id && isset($_SESSION['user_role'])) {
            $role_id = $_SESSION['user_role'];
        }

        if (!$role_id) {
            return array();
        }

        $role_id = (int)$role_id;

        // Build role-based query
        if ($role_id == 1) {
            // Admin - see ALL menus maintained for all roles
            $sql = "SELECT DISTINCT MAIN_MENU FROM " . $this->menu_table . 
                   " WHERE AVAILABLE = 'YES' ORDER BY MAIN_MENU ASC";
        } else if ($role_id == 6) {
            // Role 6 - specific menu access
            $sql = "SELECT DISTINCT MAIN_MENU FROM " . $this->menu_table . 
                   " WHERE AVAILABLE = 'YES' AND ROLE_ID IN (5) ORDER BY MAIN_MENU ASC";
        } else if ($role_id == 3) {
            // Role 3 - specific menu access
            $sql = "SELECT DISTINCT MAIN_MENU FROM " . $this->menu_table . 
                   " WHERE AVAILABLE = 'YES' AND ROLE_ID IN (3) ORDER BY MAIN_MENU ASC";
        } else if ($role_id == 4) {
            // Role 4 - specific menu access (Payment Receipts)
            $sql = "SELECT DISTINCT MAIN_MENU FROM " . $this->menu_table . 
                   " WHERE AVAILABLE = 'YES' AND ROLE_ID IN (4) ORDER BY MAIN_MENU ASC";
        } else {
            // Other roles - limited access
            $sql = "SELECT DISTINCT MAIN_MENU FROM " . $this->menu_table . 
                   " WHERE AVAILABLE = 'YES' AND ROLE_ID IN (2, 4, 5) ORDER BY MAIN_MENU ASC";
        }

        $result = get_db()->query($sql);
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return !empty($rows) ? $rows : array();
        }
        return array();
    }

    /**
     * Get submenu items for a main menu
     * @param string $main_menu Main menu name
     * @param int $role_id User role ID
     * @param string $username Current username (admin gets all items)
     * @return array Submenu items or empty array
     */
    public function get_submenus($main_menu, $role_id = null, $username = null)
    {
        if (!$role_id && isset($_SESSION['user_role'])) {
            $role_id = $_SESSION['user_role'];
        }

        if (!$username && isset($_SESSION['user_name'])) {
            $username = $_SESSION['user_name'];
        }

        if (!$role_id) {
            return array();
        }

        $main_menu = get_db()->escape($main_menu);
        $role_id = (int)$role_id;

        // Build role-based submenu query
        if ($role_id == 1) {
            $sql = "SELECT * FROM " . $this->menu_table . 
                   " WHERE MAIN_MENU = " . $main_menu . 
                   " AND AVAILABLE = 'YES'";
            
            // Admin user gets all items including Quiz Package
            if ($username && $username !== 'admin') {
                $sql .= " AND MENUTEXT NOT IN ('Quiz Package')";
            }
        } else if ($role_id == 4) {
            $sql = "SELECT * FROM " . $this->menu_table . 
                   " WHERE MAIN_MENU = " . $main_menu . 
                   " AND AVAILABLE = 'YES' AND ROLE_ID = 4";
        } else {
            $sql = "SELECT DISTINCT * FROM " . $this->menu_table . 
                   " WHERE MAIN_MENU = " . $main_menu . 
                   " AND ORDER_NO NOT IN (4) AND AVAILABLE = 'YES'";
        }

        $sql .= " ORDER BY ORDER_NO ASC";

        $result = get_db()->query($sql);
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return !empty($rows) ? $rows : array();
        }
        return array();
    }

    /**
     * Get menu by role ID - returns all accessible menus for a role
     * @param int $role_id User role ID
     * @return array Menu items
     */
    public function get_menus_by_role($role_id)
    {
        $role_id = (int)$role_id;

        $sql = "SELECT * FROM " . $this->menu_table . 
               " WHERE ROLE_ID = " . $role_id . 
               " AND AVAILABLE = 'YES' " .
               " ORDER BY ORDER_NO ASC";

        $result = get_db()->query($sql);
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return !empty($rows) ? $rows : array();
        }
        return array();
    }

    /**
     * Get all menus for public/guest access
     * @return array Menu items excluding admin roles
     */
    public function get_public_menus()
    {
        $sql = "SELECT DISTINCT MAIN_MENU FROM " . $this->menu_table . 
               " WHERE AVAILABLE = 'YES' AND ROLE_ID NOT IN (1, 15, 16, 17) " .
               " ORDER BY MAIN_MENU ASC";

        $result = get_db()->query($sql);
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return !empty($rows) ? $rows : array();
        }
        return array();
    }

    /**
     * Get all submenu items for a main menu (public version)
     * @param string $main_menu Main menu name
     * @return array Submenu items
     */
    public function get_public_submenus($main_menu)
    {
        $main_menu = get_db()->escape($main_menu);

        $sql = "SELECT * FROM " . $this->menu_table . 
               " WHERE MAIN_MENU = " . $main_menu . 
               " AND ROLE_ID NOT IN (1) AND AVAILABLE = 'YES' " .
               " ORDER BY ORDER_NO ASC";

        $result = get_db()->query($sql);
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return !empty($rows) ? $rows : array();
        }
        return array();
    }

    /**
     * Check if user has access to a specific menu item
     * @param int $role_id User role ID
     * @param int $menu_id Menu ID
     * @return bool
     */
    public function has_menu_access($role_id, $menu_id)
    {
        $role_id = (int)$role_id;
        $menu_id = (int)$menu_id;

        $sql = "SELECT id FROM " . $this->menu_table . 
               " WHERE id = " . $menu_id . 
               " AND (ROLE_ID = " . $role_id . " OR ROLE_ID = 1) " .
               " AND AVAILABLE = 'YES'";

        $result = get_db()->query($sql);
        if ($result) {
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            return count($rows) > 0;
        }
        return false;
    }

    /**
     * Get menu hierarchy for current user
     * Returns nested array of menus and submenus
     * @param int $role_id User role ID
     * @param string $username Current username
     * @return array
     */
    public function get_menu_hierarchy($role_id = null, $username = null)
    {
        $top_menus = $this->get_top_menus($role_id);
        $hierarchy = array();

        foreach ($top_menus as $menu) {
            $submenu = $this->get_submenus($menu['MAIN_MENU'], $role_id, $username);
            
            if (!empty($submenu)) {
                $hierarchy[$menu['MAIN_MENU']] = $submenu;
            }
        }

        return $hierarchy;
    }
}
?>