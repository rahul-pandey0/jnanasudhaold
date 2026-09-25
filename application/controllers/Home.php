<?php
/**
 * Home Controller - Landing page replacing old dashboard at root
 */
class Home extends CI_ProtectedController {
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        $this->load->model('Menu_model', 'menu_model');
    }

    public function index()
    {
        // Require login; redirect if not authenticated
        if (empty($_SESSION['logged_in'])) {
            redirect(base_url('auth/login'));
        }
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        $data = array();
        $data['title'] = 'Home';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        $data['top_menus'] = $role_id ? $this->menu_model->get_top_menus($role_id) : array();

        $this->load->view('home/index', $data);
    }
}
