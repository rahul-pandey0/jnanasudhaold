<?php
/**
 * Menu Helper - Utility functions for menu operations
 */

if (!function_exists('load_user_menu')) {
    /**
     * Load menu based on user role
     * @param int $role_id User role ID
     * @param string $username Username
     * @return void
     */
    function load_user_menu($role_id = null, $username = null)
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model', 'menu_model');
        
        $role_id = $role_id ?: (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null);
        $username = $username ?: (isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest');
        
        $data = array(
            'role_id' => $role_id,
            'username' => $username
        );
        
        $CI->load->view('layout/dynamic_menu', $data);
    }
}

if (!function_exists('get_user_menus')) {
    /**
     * Get all menus for current user
     * @param int $role_id User role ID
     * @return array Menu hierarchy
     */
    function get_user_menus($role_id = null)
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model', 'menu_model');
        
        $role_id = $role_id ?: (isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null);
        
        return $CI->menu_model->get_menu_hierarchy($role_id);
    }
}

if (!function_exists('check_menu_access')) {
    /**
     * Check if user has access to a menu item
     * @param int $role_id User role ID
     * @param int $menu_id Menu ID
     * @return bool
     */
    function check_menu_access($role_id, $menu_id)
    {
        $CI = &get_instance();
        $CI->load->model('Menu_model', 'menu_model');
        
        return $CI->menu_model->has_menu_access($role_id, $menu_id);
    }
}

if (!function_exists('render_menu_html')) {
    /**
     * Render menu as HTML
     * @param array $hierarchy Menu hierarchy
     * @return string HTML
     */
    function render_menu_html($hierarchy = array())
    {
        if (empty($hierarchy)) {
            $CI = &get_instance();
            $CI->load->model('Menu_model', 'menu_model');
            $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
            $hierarchy = $CI->menu_model->get_menu_hierarchy($role_id);
        }
        
        $html = '<div class="menu-tree">';
        
        foreach ($hierarchy as $main_menu => $submenus) {
            $html .= '<div class="menu-section">';
            $html .= '<h5 class="menu-title">' . htmlspecialchars($main_menu) . '</h5>';
            $html .= '<ul class="submenu-list">';
            
            foreach ($submenus as $submenu) {
                $html .= '<li>';
                $html .= '<a href="' . htmlspecialchars($submenu['NAVIGATION']) . '">';
                $html .= htmlspecialchars($submenu['MENUTEXT']);
                $html .= '</a>';
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            $html .= '</div>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
}
?>
