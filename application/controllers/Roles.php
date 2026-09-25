<?php
class Roles extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();

        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';

        $this->load->model('Role_model');
        $this->load->model('Menu_model', 'menu_model');
    }
  public function index()
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = ($page > 0) ? $page : 1;

    $limit  = 10;
    $offset = ($page - 1) * $limit;

    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    $data = [
        'title' => 'Role List',

        
        'roles' => db_result(
            $this->Role_model->get_roles($limit, $offset)
        ),

        
        'total' => $this->Role_model->get_total_roles(),

        'current_page' => $page,
        'limit'        => $limit,

        'user_role' => $role_id,
        'user_name' => $username,
        'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
    ];

    $this->load->view('role/role_list', $data);
}
 public function add()
{
    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    // For displaying next role_id
    $next_role_id = $this->Role_model->get_next_role_id();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input_role_name = isset($_POST['role_name']) ? trim($_POST['role_name']) : '';

        $errors = [];
        if ($input_role_name === '') {
            $errors[] = 'Role Name is required';
        }

        if (!empty($errors)) {
            $data = [
                'errors'        => $errors,
                'role_data'     => $_POST,
                'next_role_id'  => $next_role_id,
                'user_role'     => $role_id,
                'user_name'     => $username,
                'top_menus'     => $role_id ? $this->menu_model->get_top_menus($role_id) : []
            ];
            $this->load->view('role/roles', $data);
            return;
        }

        $insert_data = [
            'role_name' => $input_role_name
        ];

        $insert_id = $this->Role_model->insert_role($insert_data);

        if ($insert_id !== false) {
            $_SESSION['success'] = 'Role added successfully!';
            redirect(base_url('Roles/add'));
        } else {
            $_SESSION['error'] = 'Failed to add role!';
            $data = [
                'role_data'     => $_POST,
                'next_role_id'  => $next_role_id,
                'user_role'     => $role_id,
                'user_name'     => $username,
                'top_menus'     => $role_id ? $this->menu_model->get_top_menus($role_id) : []
            ];
            $this->load->view('role/roles', $data);
        }

    } else {
        $data = [
            'title'         => 'Add Role',
            'next_role_id'  => $next_role_id,
            'user_role'     => $role_id,
            'user_name'     => $username,
            'top_menus'     => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
        ];
        $this->load->view('role/roles', $data);
    }
}



    }
