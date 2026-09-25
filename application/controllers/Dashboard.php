<?php
/**
 * Dashboard Controller
 */

// Legacy Dashboard controller stub retained intentionally but disabled.
// All dashboard routes are redirected to Home via routes.php.
class Dashboard extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        $this->load->model('Menu_model', 'menu_model');
    }

    /**
     * Dashboard Home
     */
    public function index()
    {
        // Immediately redirect to new Home landing.
        redirect(base_url('home'));
    }

    /**
     * Database Connection Test
     */
    public function db_test()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        $data['title'] = 'Database Connection Test';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        
        // Load menu data
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
        
        // Load database configuration
        $db = array();
        require_once APPPATH . 'config/database.php';
        
        // Initialize db_config with default values
        $data['db_config'] = isset($db['default']) ? $db['default'] : array();
        
        try {
            if (extension_loaded('mysqli')) {
                $test_connection = new mysqli(
                    $data['db_config']['hostname'],
                    $data['db_config']['username'],
                    $data['db_config']['password'],
                    $data['db_config']['database']
                );

                if ($test_connection->connect_error) {
                    $data['connection_status'] = 'error';
                    $data['connection_message'] = 'Database Connection Failed! Error: ' . $test_connection->connect_error;
                } else {
                    $data['connection_status'] = 'success';
                    $data['connection_message'] = 'Database Connection Successful!';
                    $test_connection->close();
                }
            } else {
                $data['connection_status'] = 'error';
                $data['connection_message'] = 'MySQLi extension is not loaded in PHP. Please enable it in php.ini';
            }
        } catch (Exception $e) {
            $data['connection_status'] = 'error';
            $data['connection_message'] = 'Database Connection Error: ' . $e->getMessage();
        }
        
        $this->load->view('dashboard/db_test', $data);
    }
}
