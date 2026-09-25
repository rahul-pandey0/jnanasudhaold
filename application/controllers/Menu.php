<?php
/**
 * Menu Controller - Handle dynamic menu loading and rendering
 */

class Menu extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model', 'menu_model');
    }

    /**
     * Get top-level menus for current user
     */
    public function get_top_menus()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        
        if (!$role_id) {
            $this->output_json(array('success' => false, 'message' => 'User role not set'), 403);
            return;
        }

        $menus = $this->menu_model->get_top_menus($role_id);
        $this->output_json(array('success' => true, 'menus' => $menus));
    }

    /**
     * Get submenu items for a main menu
     */
    public function get_submenus($main_menu = '')
    {
        $main_menu = $main_menu ?: (isset($_POST['main_menu']) ? $_POST['main_menu'] : '');
        
        if (empty($main_menu)) {
            $this->output_json(array('success' => false, 'message' => 'Main menu not specified'), 400);
            return;
        }

        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;

        $submenus = $this->menu_model->get_submenus($main_menu, $role_id, $username);
        $this->output_json(array('success' => true, 'submenus' => $submenus));
    }

    /**
     * Get complete menu hierarchy
     */
    public function get_hierarchy()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;

        if (!$role_id) {
            $this->output_json(array('success' => false, 'message' => 'User role not set'), 403);
            return;
        }

        $hierarchy = $this->menu_model->get_menu_hierarchy($role_id, $username);
        $this->output_json(array('success' => true, 'hierarchy' => $hierarchy));
    }

    /**
     * Check if user has access to a menu item
     */
    public function check_access($menu_id = '')
    {
        $menu_id = $menu_id ?: (isset($_POST['menu_id']) ? $_POST['menu_id'] : '');
        
        if (empty($menu_id)) {
            $this->output_json(array('success' => false, 'message' => 'Menu ID not specified'), 400);
            return;
        }

        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $has_access = $this->menu_model->has_menu_access($role_id, $menu_id);

        $this->output_json(array('success' => true, 'has_access' => $has_access));
    }

    /**
     * Output JSON response
     */
    private function output_json($data, $http_code = 200)
    {
        http_response_code($http_code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
?>
