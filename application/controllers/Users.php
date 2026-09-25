<?php
/**
 * User Details Controller
 */

class Users extends CI_ProtectedController {

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';
        
        $this->load->model('User_details_model', 'user_model');
        $this->load->model('Menu_model', 'menu_model');
    }

    /**
     * User List
     */
    public function index()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $statusParam = isset($_GET['status']) ? trim($_GET['status']) : '';
        $status = null;
        if ($statusParam === 'active') { $status = 1; }
        else if ($statusParam === 'inactive') { $status = 0; }
        // Additional filters
        $filter_college = isset($_GET['college']) ? trim($_GET['college']) : '';
        $filter_batch = isset($_GET['batch']) ? trim($_GET['batch']) : '';
        $filter_standard = isset($_GET['standard']) ? trim($_GET['standard']) : '';
        $filter_role_id = isset($_GET['role_id']) ? trim($_GET['role_id']) : '';
        $filters = array(
            'college' => $filter_college,
            'batch' => $filter_batch,
            'standard' => $filter_standard,
            'role_id'   => $filter_role_id
);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        $data['title'] = 'User Management';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['users'] = db_result($this->user_model->get_users($limit, $offset, $search, $status, $filters));
        $data['total'] = $this->user_model->get_total_users($search, $status, $filters);
        $data['current_page'] = $page;
        $data['limit'] = $limit;
        $data['search'] = $search;
        $data['status'] = $statusParam;
        $data['filter_college'] = $filter_college;
        $data['filter_batch'] = $filter_batch;
        $data['filter_standard'] = $filter_standard;
        $data['filter_role_id'] = $filter_role_id;//filtered role id.
        $data['stats'] = $this->user_model->get_status_stats();
        // Distinct values for dropdowns
        $data['colleges'] = $this->user_model->get_distinct_colleges();
        $data['batches'] = $this->user_model->get_distinct_batches();
        $data['standards'] = $this->user_model->get_distinct_standards();
        $data['role_id'] = $this->user_model->get_distinct_rollnos();//values for dropdown.
        // Add menu data for dynamic navigation
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

        $this->load->view('user/list', $data);
    }

    /**
     * View user details
     */
    public function view($user_id)
    {
        $user = $this->user_model->get_user($user_id);
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        
        if (!$user) {
            redirect(base_url('users'));
        }

        $data['title'] = 'View User - ' . $user['first_name'] . ' ' . $user['last_name'];
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user'] = $user;
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
       
        $this->load->view('user/view', $data);
    }

    /**
     * Edit user
     */
    public function edit($user_id)
    {
        $user = $this->user_model->get_user($user_id);
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        
        if (!$user) {
            redirect(base_url('users'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $update_data = array(
                'first_name' => trim(isset($_POST['first_name']) ? $_POST['first_name'] : ''),
                'last_name' => trim(isset($_POST['last_name']) ? $_POST['last_name'] : ''),
                'email' => trim(isset($_POST['email']) ? $_POST['email'] : ''),
                'phone' => trim(isset($_POST['phone']) ? $_POST['phone'] : ''),
                'address' => trim(isset($_POST['address']) ? $_POST['address'] : ''),
                'city' => trim(isset($_POST['city']) ? $_POST['city'] : ''),
                'state' => trim(isset($_POST['state']) ? $_POST['state'] : ''),
                'pincode' => trim(isset($_POST['pincode']) ? $_POST['pincode'] : ''),
                'college_name' => trim(isset($_POST['college_name']) ? $_POST['college_name'] : ''),
                'rollno' => trim(isset($_POST['rollno']) ? $_POST['rollno'] : '')
            );

            if ($this->user_model->update_user($user_id, $update_data)) {
                $_SESSION['success'] = 'User updated successfully!';
                redirect(base_url('users/view/' . $user_id));
            } else {
                $_SESSION['error'] = 'Failed to update user!';
            }
        }

        $data['title'] = 'Edit User - ' . $user['first_name'] . ' ' . $user['last_name'];
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user'] = $user;
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
    
        $this->load->view('user/edit', $data);
    }

    /**
     * Enable user
     */
    public function enable($user_id)
    {
        if ($this->user_model->enable_user($user_id)) {
            $_SESSION['success'] = 'User enabled successfully!';
        } else {
            $_SESSION['error'] = 'Failed to enable user!';
        }
        
        redirect(base_url('users'));
    }

    /**
     * Disable user
     */
    public function disable($user_id)
    {
        if ($this->user_model->disable_user($user_id)) {
            $_SESSION['success'] = 'User disabled successfully!';
        } else {
            $_SESSION['error'] = 'Failed to disable user!';
        }
        
        redirect(base_url('users'));
    }

    /**
     * Update only no_of_communication from list screen
     */
    public function update_comm_count()
    {
        $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $mobile = isset($_POST['no_of_communication']) ? trim($_POST['no_of_communication']) : '';
        if ($user_id <= 0) {
            $_SESSION['error'] = 'Invalid user ID';
            redirect(base_url('users'));
            return;
        }
        if ($mobile === '') {
            $_SESSION['error'] = 'Please provide a mobile number';
            redirect(base_url('users'));
            return;
        }

        $ok = $this->user_model->update_comm_count($user_id, $mobile);
        if ($ok) {
            $_SESSION['success'] = 'Communication mobile updated';
        } else {
            $_SESSION['error'] = 'Failed to update communication mobile';
        }
        redirect(base_url('users'));
    }

    /**
     * Update user password from list screen
     */
    public function update_password()
    {
        $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
        $password = isset($_POST['actual_password']) ? trim($_POST['actual_password']) : '';
        
        if ($user_id <= 0) {
            $_SESSION['error'] = 'Invalid user ID';
            redirect(base_url('users'));
            return;
        }
        
        if ($password === '') {
            $_SESSION['error'] = 'Please provide a password';
            redirect(base_url('users'));
            return;
        }

        $ok = $this->user_model->update_user_password($user_id, $password);
        if ($ok) {
            $_SESSION['success'] = 'Password updated successfully';
        } else {
            $_SESSION['error'] = 'Failed to update password';
        }
        redirect(base_url('users'));
    }

    /**
     * Delete user
     */
    public function delete($user_id)
    {
        if ($this->user_model->delete_user($user_id)) {
            $_SESSION['success'] = 'User deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete user!';
        }
        
        redirect(base_url('users'));
    }

    /**
     * Change user password
     */
    public function change_password($user_id)
    {
        $user = $this->user_model->get_user($user_id);
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        
        if (!$user) {
            redirect(base_url('users'));
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_password = trim(isset($_POST['new_password']) ? $_POST['new_password'] : '');
            $confirm_password = trim(isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '');

            if (empty($new_password)) {
                $_SESSION['error'] = 'Password cannot be empty!';
            } elseif ($new_password !== $confirm_password) {
                $_SESSION['error'] = 'Passwords do not match!';
            } elseif (strlen($new_password) < 6) {
                $_SESSION['error'] = 'Password must be at least 6 characters!';
            } else {
                $update_data = array('password' => md5($new_password));
                if ($this->user_model->update_user($user_id, $update_data)) {
                    $_SESSION['success'] = 'Password changed successfully!';
                    redirect(base_url('users/view/' . $user_id));
                } else {
                    $_SESSION['error'] = 'Failed to change password!';
                }
            }
        }
        $data['title'] = 'Change Password - ' . $user['first_name'] . ' ' . $user['last_name'];
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user'] = $user;
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

    
        $this->load->view('user/change_password', $data);
    }

    /**
     * Communication log
     */
    public function communication($user_id)
    {
        $user = $this->user_model->get_user($user_id);
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

        
        if (!$user) {
            redirect(base_url('users'));
        }

        $data['title'] = 'Communication History - ' . $user['first_name'] . ' ' . $user['last_name'];
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin';
        $data['user'] = $user;
         $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }
        $data['communications'] = array(
            array(
                'date' => '2025-11-25',
                'type' => 'Email',
                'subject' => 'Welcome to Quiz Master',
                'status' => 'Delivered'
            ),
            array(
                'date' => '2025-11-24',
                'type' => 'SMS',
                'subject' => 'Verification Code: 12345',
                'status' => 'Delivered'
            )
        );
       
        $this->load->view('user/communication', $data);
    }
    public function add()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $first_name       = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
        $last_name        = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
        $email            = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone            = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $status           = isset($_POST['status']) ? trim($_POST['status']) : '';
        $college          = isset($_POST['college_code']) ? trim($_POST['college_code']) : '';
        $batch            = isset($_POST['batch']) ? trim($_POST['batch']) : '';
        $acm              = isset($_POST['acm']) ? trim($_POST['acm']) : '';
        $roll_no          = isset($_POST['roll_no']) ? trim($_POST['roll_no']) : '';
        $password         = isset($_POST['password']) ? trim($_POST['password']) : '';
        $confirm_pass     = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
        $selected_role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';
        $subject          = isset($_POST['subject_name']) ? $_POST['subject_name'] : '';

        $errors = [];

        // Validation
        if ($first_name === '') {
            $errors[] = 'First Name is required';
        } elseif (!preg_match('/^[A-Za-z ]+$/', $first_name)) {
            $errors[] = 'First Name must contain only letters';
        }

        if ($last_name === '') {
            $errors[] = 'Last Name is required';
        } elseif (!preg_match('/^[A-Za-z ]+$/', $last_name)) {
            $errors[] = 'Last Name must contain only letters';
        }

        if ($email === '') {
            $errors[] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        if ($phone === '') {
            $errors[] = 'Phone is required';
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors[] = 'Phone number must be exactly 10 digits';
        }

        if ($status === '') {
            $errors[] = 'Status is required';
        } elseif (!in_array($status, ['0', '1'])) {
            $errors[] = 'Invalid status selected';
        }

        if ($college === '') {
            $errors[] = 'College is required';
        } 
        if ($batch === '') {
            $errors[] = 'Batch is required';
        } elseif (!preg_match('/^[0-9]{4}$/', $batch)) {
            $errors[] = 'Batch must be a valid year (Example: 2024)';
        }

        if ($acm === '') {
            $errors[] = 'ACM is required';
        }

        if ($roll_no === '') {
            $errors[] = 'Roll Number is required';
        }
        if ($subject === '') {
            $errors[] = 'subject is required';
        }

        if ($password === '') {
            $errors[] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if ($password !== $confirm_pass) {
            $errors[] = 'Passwords do not match';
        }

        if ($selected_role_id === '') {
            $errors[] = 'Please select Role';
        }

        // Use the loaded alias $this->user_model
        if ($this->user_model->is_duplicate($email, $phone, $roll_no)) {
            $errors[] = 'User with this Email, Phone, or Roll Number already exists.';
        }

        if (!empty($errors)) {
            $data = [
                'errors' => $errors,
                'user' => $_POST,
                'user_role' => $role_id,
                'user_name' => $username,
                'college_code' => $this->user_model->get_college_codes(),
                'roles' => $this->user_model->get_distinct_rollnos()
            ];
            $this->load->view('user/add', $data);
            return;
        }

        $insertData = [
            'org_id'        => 1,
            'user_name'     => strtolower($first_name . $last_name),
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'email'         => $email,
            'phone'         => $phone,
            'user_status'   => $status,
            'college_name'  => $college,
            'batch'         => $batch,
            'accommodation' => $acm,
            'rollno'        => $roll_no,
            'role_id'       => $selected_role_id,
            'application'   => 'web',
            'creation_date' => date('Y-m-d H:i:s'),
            'modified_date' => date('Y-m-d H:i:s'),
            'chapter_id'    => 'NA',
            'cluster_id'    => 'NA',
            'otp'           => '000000',
            'otp_confirmed' => 'N',
            'password'      => md5($password),
            'actual_password'=> $password,
            'subject_name'  => $subject,
        ];

        if ($this->user_model->insert_user($insertData, $selected_role_id)) {
            $_SESSION['success'] = 'User added successfully!';
            redirect(base_url('users'));
        } else {
            $_SESSION['error'] = 'Failed to add user.';
            $data = [
                'user_role' => $role_id,
                'user_name' => $username
            ];
            $this->load->view('user/add', $data);
        }

    } else {
        // GET request
        $data = [
            'user_role' => $role_id,
            'user_name' => $username,
            'college_code' => $this->user_model->get_college_codes(),
            'roles' => $this->user_model->get_distinct_rollnos()
        ];
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }


        $this->load->view('user/add', $data);
    }
}


/**
 * Upload user profile photo
 */
public function upload_photo($user_id)
{
     $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
     $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';

    $user = $this->user_model->get_user($user_id);
    if (!$user) {
        $_SESSION['error'] = 'User not found!';
        redirect(base_url('users'));
    }
    $hasPhoto = !empty($user['profile_photo']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($hasPhoto) {
            $_SESSION['error'] = 'Profile photo already exists for this user!';
            redirect(base_url('users/upload_photo/' . $user_id));
        }

        if (!empty($_FILES['profile_photo']['name'])) {
            $upload_path = FCPATH. 'uploads/profile_photos/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $file_name = time() . '_' . basename($_FILES['profile_photo']['name']);
            $target_file = $upload_path . $file_name;

            $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
            $file_ext = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_types)) {
                $_SESSION['error'] = 'Only JPG, JPEG, PNG, GIF files are allowed.';
            } elseif (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $target_file)) {
                if ($this->user_model->update_profile_photo($user_id, $file_name)) {
                    $_SESSION['success'] = 'Profile photo uploaded successfully!';
                } else {
                    $_SESSION['error'] = 'Failed to update profile photo in database.';
                }
            } else {
                $_SESSION['error'] = 'Failed to upload the file.';
            }
        } else {
            $_SESSION['error'] = 'Please select a file to upload.';
        }

        redirect(base_url('users/view/' . $user_id));
    }

    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';
    
    $data['top_menus'] = $this->menu_model->get_top_menus($role_id);

    $data = [
        
        'title'      => 'Upload Profile Photo - ' . $user['first_name'] . ' ' . $user['last_name'],
        'user_email' => isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'Admin',
        'user'       => $user,
        'user_role'  => $role_id,
        'user_name'  => $username,
        'top_menus'  => $role_id ? $this->menu_model->get_top_menus($role_id) : []
    ];

    
    $this->load->view('user/upload_photo', $data);
}

/**
 * Delete profile photo
 */
public function delete_profile_photo($user_id)
{
    $user = $this->user_model->get_user($user_id);
    if (!$user) {
        $_SESSION['error'] = 'User not found!';
        redirect(base_url('users'));
    }

    if (empty($user['profile_photo'])) {
        $_SESSION['error'] = 'No profile photo found to delete!';
        redirect(base_url('users/view/' . $user_id));
    }

    if ($this->user_model->delete_profile_photo($user_id)) {
        $_SESSION['success'] = 'Profile photo deleted successfully!';
    } else {
        $_SESSION['error'] = 'Failed to delete profile photo!';
    }

    redirect(base_url('users/view/' . $user_id));
}
public function student_profile($phone)
{
    $result = $this->user_model->get_student_with_package_payment($phone);
    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    if (!$result['student']) {
        $_SESSION['error'] = 'Student not found!';
        redirect(base_url('users'));
        return;
    }

    $data = array();
    $data['student']  = $result['student'];
    $data['packages'] = $result['packages'];      
    $data['payments'] = $result['payments'];      

    $data['payment']  = !empty($result['payments']) ? $result['payments'][0] : [];

    $data['title'] = 'Student Profile';
    $data['active_menu'] = 'students';
     $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

    $this->load->view('user/student_profile', $data);
}
public function package($user_id, $package_id)
{
    $db = get_db();
    $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
    $username = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Guest';


    //  quizzes + marks for this user and package
    $sqlQuizzes = "
        SELECT 
            a.package_id,
            a.quiz_id,
            a.quiz_name,
            b.user_id,
            b.physicsmark,
            b.chemistrymark,
            b.biologymark
        FROM quiz_package_link a
        INNER JOIN subscription_details s 
            ON s.package_id = a.package_id
        LEFT JOIN student_quiz_result b
            ON a.quiz_id = b.quiz_id
            AND b.user_id = s.username
        WHERE a.package_id = ".$db->escape($package_id)."
        AND s.username = ".$db->escape($user_id)."
    ";

    $resQuiz = $db->query($sqlQuizzes);

    $quizzes = [];
    while ($row = $db->get_row($resQuiz)) {
        $quizzes[] = (array)$row;
    }

    $data = [
        'student' => ['user_id' => $user_id],
        'package_id' => $package_id,
        'quizzes' => $quizzes
    ];
        $data['user_role'] = $role_id;
        $data['user_name'] = $username;
        if ($role_id) {
            $data['top_menus'] = $this->menu_model->get_top_menus($role_id);
        } else {
            $data['top_menus'] = array();
        }

    $this->load->view('user/package', $data);
}



}