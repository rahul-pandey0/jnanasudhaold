<?php
/**
 * Welcome Controller
 */

class Welcome extends CI_ProtectedController {

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        // Load common helpers
        require_once APPPATH . 'helpers/common_helper.php';
    }

    /**
     * Index Page for this controller.
     */
    public function index()
    {
        $data['title'] = 'Welcome to CodeIgniter';
        $this->load->view('welcome_view', $data);
    }

    /**
     * Hello method
     */
    public function hello($name = 'Guest')
    {
        echo "Hello, " . htmlspecialchars($name) . "!";
    }
}
