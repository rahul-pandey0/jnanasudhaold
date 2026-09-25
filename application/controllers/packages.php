<?php
class Packages extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';

        $this->load->model('Package_model', 'package_model');
        $this->load->model('Menu_model', 'menu_model');
    }

    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusParam = isset($_GET['status']) ? trim($_GET['status']) : '';
        $type = isset($_GET['type']) ? trim($_GET['type']) : 'offline';

        $status = null;
        if ($statusParam === 'active') { $status = 1; }
        else if ($statusParam === 'inactive') { $status = 0; }

        $limit = 10;
        $offset = ($page - 1) * $limit;

        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        $total_packages = $this->package_model->get_total_packages($search, $status,$type);
        $packages = $this->package_model->get_packages($limit, $offset, $search, $status,$type);

        $data = [
            'title' => 'Package Management',
            'user_email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin',
            'packages' => db_result($packages),
            'total' => $total_packages,
            'current_page' => $page,
            'limit' => $limit,
            'search' => $search,
            'status' => $statusParam,
            'stats' => $this->package_model->get_status_stats(),
            'package_type' => $type, 
            'total_pages' => ceil($total_packages / $limit),
            'user_role' => $role_id,
            'user_name' => $username,
            'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
        ];

        $this->load->view('packages/list', $data);
    }

    public function view($id)
    {
        $package = $this->package_model->get_package($id);
        if (!$package) redirect(base_url('packages'));
        
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        $data = [
            'title' => 'View Package - ' . $package['package_name'],
            'user_email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin',
            'package' => $package,
            'user_role' => $role_id,
            'user_name' => $username,
            'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
        ];

        $this->load->view('packages/view', $data);
    }
    public function edit($id)
{
    $package = $this->package_model->get_package($id);
    if (!$package) redirect(base_url('packages'));
$role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
$username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        
        $update_data = [
            'package_name'  => trim(isset($_POST['package_name']) ? $_POST['package_name'] : ''),
            'start_date'    => trim(isset($_POST['start_date']) ? $_POST['start_date'] : '') ?: NULL,
            'end_date'      => trim(isset($_POST['end_date']) ? $_POST['end_date'] : '') ?: NULL,
            'packageamount' => trim(isset($_POST['packageamount']) ? $_POST['packageamount'] : ''),
            'sgst_amt'      => trim(isset($_POST['sgst_amt']) ? $_POST['sgst_amt'] : ''),
            'cgst_amt'      => trim(isset($_POST['cgst_amt']) ? $_POST['cgst_amt'] : ''),
            'igst_amt'      => trim(isset($_POST['igst_amt']) ? $_POST['igst_amt'] : ''),
            'price'         => trim(isset($_POST['price']) ? $_POST['price'] : ''),
            'package_info'  => trim(isset($_POST['package_info']) ? $_POST['package_info'] : ''),
            'subject_name'  => trim(isset($_POST['subject_name']) ? $_POST['subject_name'] : ''),
            'url'           => trim(isset($_POST['url']) ? $_POST['url'] : ''),
            'type'          => trim(isset($_POST['type']) ? $_POST['type'] : ''),
            'status'        => isset($_POST['status']) ? (int)$_POST['status'] : 0,
           
        ];

        if ($this->package_model->update_package($id, $update_data)) {
            $_SESSION['success'] = 'Package updated successfully!';
            redirect(base_url('packages/view/' . $id));
        } else {
            $_SESSION['error'] = 'Failed to update package!';
        }
    }

    $data = [
        'title'      => 'Edit Package - ' . $package['package_name'],
        'user_email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin',
        'package'    => $package,
        'user_role' => $role_id,
        'user_name' => $username,
        'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
    ];

    $this->load->view('packages/edit', $data);
}

    public function enable($id)
    {
        if ($this->package_model->enable_package($id)) {
            $_SESSION['success'] = 'Package enabled successfully!';
        } else {
            $_SESSION['error'] = 'Failed to enable package!';
        }
        redirect(base_url('packages'));
    }

    public function disable($id)
    {
        if ($this->package_model->disable_package($id)) {
            $_SESSION['success'] = 'Package disabled successfully!';
        } else {
            $_SESSION['error'] = 'Failed to disable package!';
        }
        redirect(base_url('packages'));
    }
        public function online() {
        $_GET['type'] = 'online';
        $this->index();
    }

        public function offline() {
            $_GET['type'] = 'offline';
            $this->index();
        }

}
