<?php
class Screen extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();

        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';

        $this->load->model('Screen_model');
        $this->load->model('Menu_model', 'menu_model');
    }
    public function index()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


        // Pagination
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10;
        $offset = ($current_page - 1) * $limit;

        // Get total records
        $total_records = $this->Screen_model->count_screens();
        
        // Get paginated screens
        $screens = $this->Screen_model->get_screens($limit, $offset);

        $data = [
            'title'        => 'Screen List',
            'screens'      => $screens,
            'total'        => $total_records,
            'current_page' => $current_page,
            'limit'        => $limit,
            'user_role'    => $role_id,
            'user_name'    => $username,
            'top_menus'    => $role_id ? $this->menu_model->get_top_menus($role_id) : []
        ];

        $this->load->view('screen/screen_list', $data);
    }

    public function add()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Get POST data
           // $input_screen_id   = isset($_POST['screen_id']) ? trim($_POST['screen_id']) : '';
            $input_screen_name = isset($_POST['screen_name']) ? trim($_POST['screen_name']) : '';
           // $input_link        = isset($_POST['link']) ? trim($_POST['link']) : '';\
           $input_link = isset($_POST['link']) && trim($_POST['link']) !== ''? trim($_POST['link']): '#';
           $input_main_menu = isset($_POST['main_menu']) ? trim($_POST['main_menu']) : '';



            $errors = [];

            // Validation
           /* if ($input_screen_id === '') {
                $errors[] = 'Screen ID is required';
            } elseif (!ctype_digit($input_screen_id)) {
                $errors[] = 'Screen ID must be a number';
            }*/

            if ($input_screen_name === '') {
                $errors[] = 'Screen Name is required';
            }
            if ($input_main_menu === '') {
                $errors[] = 'Main Menu is required';
            }


           /* if ($input_link === '') {
                $errors[] = 'Link is required';
            }*/

            if (!empty($errors)) {
                $data = [
                    'errors'     => $errors,
                    'screen_data'=> $_POST,
                    'user_role'  => $role_id,
                    'user_name'  => $username,
                    'top_menus'  => $role_id ? $this->menu_model->get_top_menus($role_id) : []
                ];
                $this->load->view('screen/addscreen', $data);
                return;
            }

            // Prepare insert data
            $insert_data = [
                //'screen_id'   => (int)$input_screen_id,
                'main_menu'   => $input_main_menu,
                'screen_name' => $input_screen_name,
                'link'        =>($input_link !== '') ? $input_link : '#'
            ];

            // Insert screen
            $insert = $this->Screen_model->insert_screen($insert_data);

            if ($insert !== false) {
                $_SESSION['success'] = 'Screen added successfully!';
                redirect(base_url('Screen/add'));
                return;
            } else {
                $_SESSION['error'] = 'Failed to add screen!';
                $data = [
                    'screen_data'=> $_POST,
                    'user_role'  => $role_id,
                    'user_name'  => $username,
                    'top_menus'  => $role_id ? $this->menu_model->get_top_menus($role_id) : []
                ];
                $this->load->view('screen/addscreen', $data);
            }

        } else {
            // GET request
            $data = [
                'title'     => 'Add Screen',
                'user_role' => $role_id,
                'user_name' => $username,
                'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
            ];

            $this->load->view('screen/addscreen', $data);
        }

    }
    public function edit($id)
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
        if (empty($id)) {
            $_SESSION['error'] = 'Invalid screen ID';
            redirect(base_url('Screen'));
            return;
        }

        $screen = $this->Screen_model->get_screen_by_id($id);

        if (!$screen) {
            $_SESSION['error'] = 'Screen not found';
            redirect(base_url('Screen'));
            return;
        }

        $data = [
            'title'     => 'Edit Screen',
            'screen'    => $screen,
            'user_role' => $role_id,
            'user_name' => $username,
            'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
        ];

        $this->load->view('screen/edit', $data);
    }
    public function update($id)
    {   
    
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(base_url('Screen'));
            return;
        }

        $input_screen_name = isset($_POST['screen_name']) ? trim($_POST['screen_name']) : '';
        $input_link        = isset($_POST['link']) ? trim($_POST['link']) : '';
        $input_main_menu = isset($_POST['main_menu']) ? trim($_POST['main_menu']) : '';


        $errors = [];

        if ($input_screen_name === '') {
            $errors[] = 'Screen Name is required';
        }
        if ($input_main_menu === '') {
        $errors[] = 'Main Menu is required';
        }

        if ($input_link === '') {
            $errors[] = 'Link is required';
        }

        if (!empty($errors)) {
            $data = [
                'title'     => 'Edit Screen',
                'errors'    => $errors,
                'screen'    => [
                'screen_id'   => $id,
                'screen_name' => $input_screen_name,
                'link'        => $input_link
                ],
                'user_role' => $role_id,
                'user_name' => $username,
                'top_menus' => $role_id ? $this->menu_model->get_top_menus($role_id) : []
            ];

            $this->load->view('screen/edit', $data);
            return;
            }

            $update_data = [
                'main_menu'   => $input_main_menu,
                'screen_name' => $input_screen_name,
                'link'        => $input_link
            ];

            $updated = $this->Screen_model->update_screen($id, $update_data);

            if ($updated) {
                $_SESSION['success'] = 'Screen updated successfully!';
                redirect(base_url('Screen'));
            } else {
                $_SESSION['error'] = 'Failed to update screen';
                redirect(base_url('Screen/edit/' . $id));
            }
    }
   // Enable screen
    public function enable($id)
    {
        $updated = $this->Screen_model->update_screen($id, ['status' => 'Active']);

        if ($updated) {
            $_SESSION['success'] = "Screen enabled successfully!";
        } else {
            $_SESSION['error'] = "Failed to enable screen!";
        }

        redirect(base_url('Screen'));
    }

    // Disable screen
    public function disable($id)
    {
        $updated = $this->Screen_model->update_screen($id, ['status' => 'Inactive']);

        if ($updated) {
            $_SESSION['success'] = "Screen disabled successfully!";
        } else {
            $_SESSION['error'] = "Failed to disable screen!";
        }

        redirect(base_url('Screen'));
    }

}
