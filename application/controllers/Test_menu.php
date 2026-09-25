<?php
/**
 * Dynamic Menu Test Page
 * This page demonstrates how to use the role-based dynamic menu system
 */

class Test_menu extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Menu_model', 'menu_model');
    }

    /**
     * Display menu test page
     */
    public function index()
    {
        // Get user role from session
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        if (!$role_id) {
            redirect(base_url('auth/login'));
            return;
        }

        // Get menu hierarchy
        $menu_hierarchy = $this->menu_model->get_menu_hierarchy($role_id, $username);

        $data = array(
            'title' => 'Menu Test - Role Based Loading',
            'role_id' => $role_id,
            'username' => $username,
            'menu_hierarchy' => $menu_hierarchy,
            'top_menus' => $this->menu_model->get_top_menus($role_id),
            'user_email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'admin'
        );

        $this->load->view('test/menu_test', $data);
    }

    /**
     * Get menus as JSON (AJAX endpoint)
     */
    public function get_menus_json()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        if (!$role_id) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Unauthorized'));
            exit;
        }

        $hierarchy = $this->menu_model->get_menu_hierarchy($role_id);
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'role_id' => $role_id,
            'menus' => $hierarchy
        ));
        exit;
    }

    /**
     * Get submenus for a main menu
     */
    public function get_submenus($main_menu = '')
    {
        $main_menu = $main_menu ?: (isset($_GET['main_menu']) ? $_GET['main_menu'] : '');
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;

        if (!$role_id || empty($main_menu)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'Missing parameters'));
            exit;
        }

        $submenus = $this->menu_model->get_submenus($main_menu, $role_id);
        
        header('Content-Type: application/json');
        echo json_encode(array(
            'success' => true,
            'main_menu' => $main_menu,
            'submenus' => $submenus
        ));
        exit;
    }
}
?>
