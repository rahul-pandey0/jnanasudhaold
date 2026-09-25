<?php
/**
 * System Controller - System utilities and tests
 */

class System extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
    }

    /**
     * Database Connection Test
     */
    public function db_test()
    {
        $this->load->view('system/db_test');
    }
}
