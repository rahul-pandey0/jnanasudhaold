<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_menu extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();

        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';

        $this->load->model('Main_menu_model');
        $this->load->model('Menu_model', 'menu_model');
        $this->load->model('Role_model');
        $this->load->model('Screen_model');
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
        'title'        => 'Main Menu List',
        'menus'        => db_result( $this->Main_menu_model->get_menus($limit, $offset)),
        'total'        => $this->Main_menu_model->get_total_menus(),
        'current_page' => $page,
        'limit'        => $limit,
        'user_role'    => $role_id,
        'user_name'    => $username,
        'top_menus'    => $role_id ? $this->menu_model->get_top_menus($role_id) : []
    ];

    $this->load->view('mainmenu/menu_list', $data);
}


   /* public function add()
    {
         $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
         $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $input_role_id   = trim($_POST['role_id'] ?? '');
            $main_menu       = trim($_POST['main_menu'] ?? '');
            $navigation      = trim($_POST['navigation'] ?? '');
            $screen_name     = trim($_POST['screen_name'] ?? '');
            $available       = trim($_POST['available'] ?? '');
            $order_no        = trim($_POST['order_no'] ?? '');
            $errors = [];
            $screen_id = trim($_POST['screen_id'] ?? '');
            $screen_name = '';
            $data['main_menu'] = $this->Screen_model->get_main_menus();

            if ($screen_id !== '') {
                $screen = $this->Screen_model->get_screen_by_id($screen_id);
                if ($screen) {
                    $screen_name = $screen['screen_name'];
                }
            }


            if ($input_role_id === '' || !ctype_digit($input_role_id)) {
                $errors[] = 'Valid Role ID is required';
            }

            if ($main_menu === '') {
                $errors[] = 'Main Menu is required';
            }

            if ($screen_id === '' || !ctype_digit($screen_id)) {
              $errors[] = 'Menu Text is required';
            }


            if ($order_no === '' || !ctype_digit($order_no)) {
                $errors[] = 'Order No must be a number';
            }

            if (!empty($errors)) {
                $data = [
                    'errors'     => $errors,
                    'menu_data'  => $_POST,
                    'user_role'  => $role_id,
                    'user_name'  => $username,
                    'top_menus'  => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
                    'roles'      => $this->Role_model->get_all_roles(),
                    'screen_name'  => $this->Screen_model->get_all_screens(),
                    'main_menu'  => $this->Main_menu_model->get_all_screens('main_menu')
    
                ];
                $this->load->view('mainmenu/add', $data);
                return;
            }

            $insert_data = [
                'ROLE_ID'    => (int)$input_role_id,
                'main_menu'  => $main_menu,
                'NAVIGATION' => $navigation,
                'MENUTEXT'   => $screen_name,
                'AVAILABLE'  => $available,
                'ORDER_NO'   => (int)$order_no
            ];

            $insert = $this->Main_menu_model->insert_menu($insert_data);

            if ($insert) {
                $_SESSION['success'] = 'Menu added successfully!';
                redirect(base_url('Main_menu/add'));
            } else {
                $_SESSION['error'] = 'Failed to add menu!';
                redirect(base_url('Main_menu/add'));
            }

        } else {

            $data = [
                'title'        => 'Add Menu',
                'user_role'    => $role_id,
                'user_name'    => $username,
                'top_menus'    => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
                'roles'        =>$this->Role_model->get_all_roles(),
                'navigations'  => $this->Main_menu_model->get_distinct_column('NAVIGATION'),
                'screen_name'  => $this->Screen_model->get_all_screens(),
                'main_menu'    => $this->Main_menu_model->get_all_screens('main_menu')


            ];

            $this->load->view('mainmenu/add', $data);
        }
    }*/
    public function add()
{ 
    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
 
    $main_menus  = $this->Screen_model->get_main_menus();
    $screens     = $this->Screen_model->get_all_screens();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input_role_id = trim($_POST['role_id'] ?? '');
        $main_menu     = trim($_POST['main_menu'] ?? '');
        $navigation    = trim($_POST['navigation'] ?? '');
        $available     = trim($_POST['available'] ?? '');
        $order_no      = trim($_POST['order_no'] ?? '');
        $screen_id     = trim($_POST['screen_id'] ?? '');

        $errors = [];
        $screen_name = '';

        if ($screen_id !== '' && ctype_digit($screen_id)) {
            $screen = $this->Screen_model->get_screen_by_id($screen_id);
            if ($screen) {
                $screen_name = $screen['screen_name'];
            }
        }

        if ($input_role_id === '' || !ctype_digit($input_role_id)) {
            $errors[] = 'Valid Role ID is required';
        }

        if ($main_menu === '') {
            $errors[] = 'Main Menu is required';
        }

        if ($screen_id === '' || !ctype_digit($screen_id)) {
            $errors[] = 'Menu Text is required';
        }

        if ($order_no === '' || !ctype_digit($order_no)) {
            $errors[] = 'Order No must be a number';
        }

        if (!empty($errors)) {
            $data = [
                'errors'      => $errors,
                'menu_data'   => $_POST,
                'user_role'   => $role_id,
                'user_name'   => $username,
                'top_menus'   => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
                'roles'       => $this->Role_model->get_all_roles(),
                'screen_name' => $screens,
                'main_menu'   => $main_menus
            ];
            $this->load->view('mainmenu/add', $data);
            return;
        }

        $insert_data = [
            'ROLE_ID'    => (int)$input_role_id,
            'main_menu'  => $main_menu,
            'NAVIGATION' => $navigation,
            'MENUTEXT'   => $screen_name,
            'AVAILABLE'  => $available,
            'ORDER_NO'   => (int)$order_no
        ];

        if ($this->Main_menu_model->insert_menu($insert_data)) {
            $_SESSION['success'] = 'Menu added successfully!';
        } else {
            $_SESSION['error'] = 'Failed to add menu!';
        }

        redirect(base_url('Main_menu/add'));

    } else {

        $data = [
            'title'       => 'Add Menu',
            'user_role'   => $role_id,
            'user_name'   => $username,
            'top_menus'   => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
            'roles'       => $this->Role_model->get_all_roles(),
            'screen_name' => $screens,
            'main_menu'   => $main_menus
        ];

        $this->load->view('mainmenu/add', $data);
    }
}

/* public function add_new_menu()
{
    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $input_role_id = trim($_POST['role_id'] ?? '');
        $main_menu     = trim($_POST['main_menu'] ?? '');
        $navigation    = trim($_POST['navigation'] ?? '');
        $menu_text     = trim($_POST['menu_text'] ?? '');
        $available     = trim($_POST['available'] ?? 'Yes');
        $order_no      = trim($_POST['order_no'] ?? '');
        $errors = [];

        if ($input_role_id === '' || !ctype_digit($input_role_id)) {
            $errors[] = 'Valid Role ID is required';
        }

        if ($main_menu === '') {
            $errors[] = 'Main Menu is required';
        }

        if ($menu_text === '') {
            $errors[] = 'Menu Text is required';
        }

        if ($order_no === '' || !ctype_digit($order_no)) {
            $errors[] = 'Order No must be a number';
        }

        if (!empty($errors)) {
            $data = [
                'errors'     => $errors,
                'menu_data'  => $_POST,
                'user_role'  => $role_id,
                'user_name'  => $username,
                'top_menus'  => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
                'roles'      => $this->Role_model->get_all_roles(),
                'main_menu'  => $this->Main_menu_model->get_distinct_column('MAIN_MENU'),
            ];
            $this->load->view('mainmenu/addnew', $data);
            return;
        }

       
        // data for menu_details_admin table
         $menu_data = [
            'ROLE_ID'    => (int)$input_role_id,
            'MAIN_MENU'  => $main_menu,
            'NAVIGATION' => $navigation,
            'MENUTEXT'   => $menu_text,
            'AVAILABLE'  => $available,
            'ORDER_NO'   => (int)$order_no
        ];

        // data for screen table
        $screen_data = [
            'screen_name' => $menu_text,
            'link'  => $navigation,
              
        ];

        // insert into BOTH tables
        $insert = $this->Main_menu_model->insert_menu_and_screen($menu_data, $screen_data);


      

        if ($insert) {
            $_SESSION['success'] = 'Menu added successfully!';
            redirect(base_url('Main_menu/add_new_menu'));
        } else {
            $_SESSION['error'] = 'Failed to add menu!';
            redirect(base_url('Main_menu/add_new_menu'));
        }

    } else {

        $data = [
            'title'       => 'Add New Menu',
            'user_role'   => $role_id,
            'user_name'   => $username,
            'top_menus'   => $role_id ? $this->menu_model->get_top_menus($role_id) : [],
            'roles'       => $this->Role_model->get_all_roles(),
            'main_menu'   => $this->Main_menu_model->get_distinct_column('MAIN_MENU'),
        ];

        $this->load->view('mainmenu/addnew', $data);
    }
    
}*/
/*public function edit($id)
{
    $role_id  = $_SESSION['user_role'] ?? null;
    $username = $_SESSION['user_name'] ?? 'Guest';

    $menu = $this->Main_menu_model->get_menu_by_id($id);

    if (!$menu) {
        $_SESSION['error'] = 'Menu not found';
        redirect(base_url('Main_menu'));
        return;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $update_data = [
            'ROLE_ID'    => (int)$_POST['role_id'],
            'MAIN_MENU'  => trim($_POST['main_menu']),
            'NAVIGATION' => trim($_POST['navigation']),
            'MENUTEXT'   => trim($_POST['menu_text']),
            'AVAILABLE'  => trim($_POST['available']),
            'ORDER_NO'   => (int)$_POST['order_no']
        ];

        if ($this->Main_menu_model->update_menu($id, $update_data)) {
            $_SESSION['success'] = 'Menu updated successfully';
            redirect(base_url('Main_menu/edit'));
        } else {
            $_SESSION['error'] = 'Failed to update menu';
        }
    }

    // Load edit form
    $data = [
        'title'      => 'Edit Menu',
        'menu'       => $menu,
        'roles'      => $this->Role_model->get_all_roles(),
        'user_role'  => $role_id,
        'user_name'  => $username,
        'top_menus'  => $role_id ? $this->menu_model->get_top_menus($role_id) : []
    ];

    $this->load->view('mainmenu/edit_menu', $data);
}*/


    public function enable($id)
{
    if ($this->Main_menu_model->enable_menu($id)) {
        $_SESSION['success'] = 'Menu enabled successfully';
    } else {
        $_SESSION['error'] = 'Failed to enable menu';
    }
    redirect(base_url('Main_menu/index'));
}

    public function disable($id)
{
    if ($this->Main_menu_model->disable_menu($id)) {
        $_SESSION['success'] = 'Menu disabled successfully';
    } else {
        $_SESSION['error'] = 'Failed to disable menu';
    }
    redirect(base_url('Main_menu/index'));
}
/*public function update()
{
    $id = (int)$_POST['id'];

    $this->Main_menu_model->update_menu($id, [
        'MAIN_MENU' => $_POST['main_menu'],
        'NAVIGATION' => $_POST['navigation'],
        'MENUTEXT' => $_POST['menu_text'],
        'ORDER_NO' => $_POST['order_no'],
        'AVAILABLE' => $_POST['available']
    ]);

    redirect('Main_menu/index');
}*/

}




