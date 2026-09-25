<?php
class Api_docs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->load->view('api_docs/index');
    }
}
