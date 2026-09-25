<?php
/**
 * Auth Controller - Handles login/logout
 */

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';
        require_once APPPATH . 'helpers/fcm_helper.php';
        
        // Start session if not already started
        if (!isset($_SESSION)) {
            session_start();
        }
        
        // Load auth model
        $this->load->model('Auth_model', 'auth_model');
    }

    /**
     * Login Page
     */
    public function login()
    {
        // If already logged in, redirect to home landing (new dashboard)
        if (!empty($_SESSION['logged_in'])) {
            redirect(base_url('home'));
        }
        
        $data['title'] = 'Admin Login';
        $data['error'] = '';
        $this->load->view('auth/login', $data);
    }

    /**
     * Handle Login Post
     */
    public function do_login()
    {
        if (!isset($_SESSION)) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $login = trim(isset($_POST['login']) ? $_POST['login'] : '');
                $password = trim(isset($_POST['password']) ? $_POST['password'] : '');

                // Validate inputs
                if (empty($login) || empty($password)) {
                    $data['error'] = 'User ID/Email and password are required.';
                    $data['title'] = 'Admin Login';
                    $this->load->view('auth/login', $data);
                    return;
                }

                // Authenticate against database
                $user = $this->auth_model->authenticate($login, $password);

                if ($user) {
                    // Login successful
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_email'] = isset($user['email']) ? $user['email'] : '';
                    $_SESSION['user_id'] = isset($user['user_id']) ? $user['user_id'] : '';
                    $_SESSION['user_name'] = (isset($user['first_name']) ? $user['first_name'] : '') . ' ' . (isset($user['last_name']) ? $user['last_name'] : '');
                    $_SESSION['user_role'] = isset($user['role_id']) ? $user['role_id'] : '';

                    $deviceToken = isset($_POST['device_token']) ? trim($_POST['device_token']) : '';
                    if ($deviceToken !== '') {
                        fcm_store_device_token($_SESSION['user_id'], $deviceToken, 'web');
                    }
                    
                    redirect(base_url('home'));
                } else {
                    // Login failed - invalid credentials or user not active
                    $data['error'] = 'Invalid user ID/email, password, or account is not active. (Role 2 users cannot login)';
                    $data['title'] = 'Admin Login';
                    $this->load->view('auth/login', $data);
                }
            } catch (Exception $e) {
                $data['error'] = 'Error during login: ' . htmlspecialchars($e->getMessage());
                $data['title'] = 'Admin Login';
                $this->load->view('auth/login', $data);
            }
        } else {
            redirect(base_url('auth/login'));
        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
        redirect(base_url('auth/login'));
    }

    /**
     * Test database connection
     */
    public function test_db()
    {
        try {
            $db = get_db();
            
            if ($db) {
                echo "Database object created successfully!<br>";
                
                // Try a simple query
                $result = $db->query("SELECT COUNT(*) as total FROM user_details LIMIT 1");
                
                if ($result) {
                    $row = $db->get_row($result);
                    echo "Total users in database: " . (isset($row['total']) ? $row['total'] : 'N/A') . "<br>";
                    
                    // Try to fetch a test user
                    $test_result = $db->query("SELECT user_id, user_name, email, password FROM user_details WHERE user_name = 'admin' OR email = 'admin@test.com' LIMIT 1");
                    if ($test_result) {
                        $user = $db->get_row($test_result);
                        if ($user) {
                            echo "Test user found: " . htmlspecialchars($user['user_name']) . " (" . htmlspecialchars($user['email']) . ")<br>";
                            echo "Password hash: " . htmlspecialchars($user['password']) . "<br>";
                        } else {
                            echo "No test user found with user_name='admin' or email='admin@test.com'<br>";
                        }
                    } else {
                        echo "Query failed!<br>";
                    }
                } else {
                    echo "Initial query failed!<br>";
                }
            } else {
                echo "Database connection failed!<br>";
            }
        } catch (Exception $e) {
            echo "Error: " . htmlspecialchars($e->getMessage());
        }
    }
}