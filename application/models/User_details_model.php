<?php
/**
 * User Details Model
 */

class User_details_model extends CI_Model {

    protected $table = 'user_details';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all users with pagination
     */
    public function get_users($limit = 10, $offset = 0, $search = '', $status = null, $filters = array())
    {
        $db = get_db();
        
        $sql = "SELECT * FROM " . $this->table;

        $conditions = array();
        if (!empty($search)) {
            $pattern = "'%" . $db->escape($search) . "%'";
            $conditions[] = "(user_name LIKE " . $pattern . " 
                    OR first_name LIKE " . $pattern . "
                    OR email LIKE " . $pattern . ")";
        }
        if ($status === 0 || $status === 1 || $status === '0' || $status === '1') {
            $conditions[] = "user_status = " . (int)$status;
        }
        // Additional field filters (optional): college_name, batch, standard
        if (!empty($filters) && is_array($filters)) {
            if (!empty($filters['college'])) {
                $patternCollege = "'%" . $db->escape($filters['college']) . "%'";
                $conditions[] = "college_name LIKE " . $patternCollege;
            }
            if (!empty($filters['batch'])) {
                $patternBatch = "'%" . $db->escape($filters['batch']) . "%'";
                $conditions[] = "batch LIKE " . $patternBatch;
            }
            if (!empty($filters['standard'])) {
                $patternStandard = "'%" . $db->escape($filters['standard']) . "%'";
                $conditions[] = "standard LIKE " . $patternStandard;
            }
             if (!empty($filters['role_id'])) {
                $patternrole_id = "'%" . $db->escape($filters['role_id']) . "%'";
                $conditions[] = "role_id LIKE " . $patternrole_id;
            }
        }
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY user_id DESC LIMIT " . $limit . " OFFSET " . $offset;
        
        return $db->query($sql);
    }

    /**
     * Get total user count
     */
    public function get_total_users($search = '', $status = null, $filters = array())
    {
        $db = get_db();
        
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;
        
        $conditions = array();
        if (!empty($search)) {
            $pattern = "'%" . $db->escape($search) . "%'";
            $conditions[] = "(user_name LIKE " . $pattern . " 
                    OR first_name LIKE " . $pattern . "
                    OR email LIKE " . $pattern . ")";
        }
        if ($status === 0 || $status === 1 || $status === '0' || $status === '1') {
            $conditions[] = "user_status = " . (int)$status;
        }
        // Additional field filters for count as well
        if (!empty($filters) && is_array($filters)) {
            if (!empty($filters['college'])) {
                $patternCollege = "'%" . $db->escape($filters['college']) . "%'";
                $conditions[] = "college_name LIKE " . $patternCollege;
            }
            if (!empty($filters['batch'])) {
                $patternBatch = "'%" . $db->escape($filters['batch']) . "%'";
                $conditions[] = "batch LIKE " . $patternBatch;
            }
            if (!empty($filters['standard'])) {
                $patternStandard = "'%" . $db->escape($filters['standard']) . "%'";
                $conditions[] = "standard LIKE " . $patternStandard;
            }
            if (!empty($filters['role_id'])) {
                $patternrole_id = "'%" . $db->escape($filters['role_id']) . "%'";
                $conditions[] = "role_id LIKE " . $patternrole_id;
            }
        }
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        $result = $db->query($sql);
        $row = $db->get_row($result);
        
        return isset($row['total']) ? (int)$row['total'] : 0;
    }

    /**
     * Get single user by ID
     */
    public function get_user($user_id)
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table . " WHERE user_id = " . (int)$user_id;
        $result = $db->query($sql);
        return $db->get_row($result);
    }

    /**
     * Enable user
     */
    public function enable_user($user_id)
    {
        $db = get_db();
        $data = array('user_status' => 1);
        $where = array('user_id' => (int)$user_id);
        return $db->update($this->table, $data, $where);
    }

    /**
     * Disable user
     */
    public function disable_user($user_id)
    {
        $db = get_db();
        $data = array('user_status' => 0);
        $where = array('user_id' => (int)$user_id);
        return $db->update($this->table, $data, $where);
    }

    /**
     * Update user
     */
    public function update_user($user_id, $data)
    {
        $db = get_db();
        $data['modified_date'] = date('Y-m-d H:i:s');
        $where = array('user_id' => (int)$user_id);
        return $db->update($this->table, $data, $where);
    }

    /**
     * Update only no_of_communication field
     */
    public function update_comm_count($user_id, $mobile)
    {
        $db = get_db();
        $data = array(
            'no_of_communication' => trim((string)$mobile),
            'modified_date' => date('Y-m-d H:i:s'),
        );
        $where = array('user_id' => (int)$user_id);
        return $db->update($this->table, $data, $where);
    }

    /**
     * Update user password
     */
    public function update_user_password($user_id, $password)
    {
        $db = get_db();
        $hashed_password = md5($password); // Using MD5 to match existing auth system
        
        $data = array(
            'actual_password' => trim((string)$password),
            'password' => $hashed_password,
            'modified_date' => date('Y-m-d H:i:s'),
        );
        $where = array('user_id' => (int)$user_id);
        return $db->update($this->table, $data, $where);
    }

    /**
     * Delete user
     */
    public function delete_user($user_id)
    {
        $db = get_db();
        $where = array('user_id' => (int)$user_id);
        return $db->delete($this->table, $where);
    }

    /**
     * Get status stats
     */
    public function get_status_stats()
    {
        $db = get_db();
        
        $stats = array();
        
        // Total users
        $result = $db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $row = $db->get_row($result);
        $stats['total'] = isset($row['total']) ? $row['total'] : 0;
        
        // Active users
        $result = $db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_status = 1");
        $row = $db->get_row($result);
        $stats['active'] = isset($row['total']) ? $row['total'] : 0;
        
        // Inactive users
        $result = $db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE user_status = 0");
        $row = $db->get_row($result);
        $stats['inactive'] = isset($row['total']) ? $row['total'] : 0;
        
        return $stats;
    }

    /**
     * Get distinct filter values
     */
    public function get_distinct_colleges()
    {
        $db = get_db();
        $sql = "SELECT DISTINCT TRIM(college_name) AS college_name FROM " . $this->table . " WHERE college_name IS NOT NULL AND TRIM(college_name) <> '' ORDER BY college_name";
        $result = $db->query($sql);
        $rows = $db->get_result($result);
        $out = array();
        foreach ($rows as $r) { if (isset($r['college_name'])) { $out[] = $r['college_name']; } }
        return $out;
    }

    public function get_distinct_batches()
    {
        $db = get_db();
        $sql = "SELECT DISTINCT TRIM(batch) AS batch FROM " . $this->table . " WHERE batch IS NOT NULL AND TRIM(batch) <> '' ORDER BY batch";
        $result = $db->query($sql);
        $rows = $db->get_result($result);
        $out = array();
        foreach ($rows as $r) { if (isset($r['batch'])) { $out[] = $r['batch']; } }
        return $out;
    }

    public function get_distinct_standards()
    {
        $db = get_db();
        $sql = "SELECT DISTINCT TRIM(standard) AS standard FROM " . $this->table . " WHERE standard IS NOT NULL AND TRIM(standard) <> '' ORDER BY standard";
        $result = $db->query($sql);
        $rows = $db->get_result($result);
        $out = array();
        foreach ($rows as $r) { if (isset($r['standard'])) { $out[] = $r['standard']; } }
        return $out;
    }
     public function get_distinct_rollnos()
    {
        $db = get_db();
        $sql = "SELECT DISTINCT TRIM(role_id) AS role_id FROM " . $this->table . " WHERE role_id IS NOT NULL AND TRIM(role_id) <> '' ORDER BY role_id";
        $result = $db->query($sql);
        $rows = $db->get_result($result);
        $out = array();
        foreach ($rows as $r) { if (isset($r['role_id'])) { $out[] = $r['role_id']; } }
        return $out;
    }
    
    //public function insert_user($data,$role_id) {
   // $db = get_db();
   // return db_insert($this->table, $data);
   public function insert_user($data, $role_id)
{
    $db = get_db();

    // Start transaction
    $db->query("START TRANSACTION");

    //  Insert Into user_details
    $insertUser = db_insert('user_details', $data);

    if (!$insertUser) {
        $db->query("ROLLBACK");
        return false;
    }

    

    //  If Teacher-> insert into quiz_teacher
    if ($role_id == 3) {

      $teacherData = array(
            'org_id'               => 1, // set org_id appropriately
            'name'                 => $data['first_name'].' '.$data['last_name'],
            'user_name'            => $data['user_name'],      // or same as email 
            'email'                => $data['email'],
            'phone_no'             => $data['phone'],
            'college_code'        => $data['college_name'],                     
            'subject_name'         => $data['subject_name'],                       
            'subject_information'  => '', 
);


        $insertTeacher = db_insert('quiz_teacher', $teacherData);

        if (!$insertTeacher) {
            $db->query("ROLLBACK");
            return false;
        }
    }

    // Commit final
    $db->query("COMMIT");
    return true;
}

public function get_user_by_email($email) {
    $db = get_db();
    $result = db_query("SELECT * FROM " . $this->table . " WHERE email = ?", [$email]);
    return db_row($result);
}
public function is_duplicate($email, $phone, $roll_no)
{
    $db = get_db();

    // Make sure all values are quoted properly
    $email   = "'" . addslashes($email) . "'";
    $phone   = "'" . addslashes($phone) . "'";   
    $roll_no = "'" . addslashes($roll_no) . "'"; 

    $sql = "SELECT COUNT(*) AS total 
            FROM " . $this->table . " 
            WHERE email = $email 
               OR phone = $phone 
               OR rollno = $roll_no";

    $result = db_query($sql);
    $row = db_row($result);

    return ($row['total'] > 0);
}
/**
 * Update user profile photo
 */
public function update_profile_photo($user_id, $filename)
{
    $db = get_db();
    $data = array(
        'profile_photo' => $filename,
        'modified_date' => date('Y-m-d H:i:s')
    );
    $where = array('user_id' => (int)$user_id);
    return $db->update($this->table, $data, $where);
}
/**
 * Delete user profile photo
 */
public function delete_profile_photo($user_id)
{
    $db = get_db();

    // Get current profile photo
    $user = $this->get_user($user_id);
    if (!empty($user['profile_photo'])) {
        $file_path = FCPATH . 'uploads/profile_photos/' . $user['profile_photo'];
        if (file_exists($file_path)) {
            unlink($file_path); // Delete file from server
        }
    }

    // Update database
    $data = array(
        'profile_photo' => null,
        'modified_date' => date('Y-m-d H:i:s')
    );
    $where = array('user_id' => (int)$user_id);
    return $db->update($this->table, $data, $where);
}
public function get_college_codes(){
    $db = get_db();
    $sql = "SELECT DISTINCT college_code FROM quiz_teacher ORDER by college_code ASC";
    $result = $db->query($sql);
    return $db->get_result($result);
}
public function get_student_with_package_payment($phone)
{
    $db = get_db();

    // Get Student
    $sqlStudent = "
        SELECT *
        FROM user_details
        WHERE phone = ".$db->escape($phone)."
        LIMIT 1
    ";

    $resStudent = $db->query($sqlStudent);
    $student = $db->get_row($resStudent);

    if(!$student){
        return [
            'student'  => null,
            'packages' => [],
            'payments' => []
        ];
    }

    $sql = "
        SELECT 
            p.order_no,
            p.receipt_no,
            p.amount,
            p.currency,
            p.datetime,
            p.status,
            p.packageid,

            s.package_id,
            s.package_name,
            s.package_amount,
            s.subscribed_on,
            s.end_date

        FROM payment_gateway_status p
        
        LEFT JOIN subscription_details s
            ON s.username = p.mobile_no
        
        WHERE p.mobile_no = ".$db->escape($student['phone'])."
        AND p.status NOT IN ('INITIATED','Failure')

        ORDER BY p.datetime ASC
    ";

    $result = $db->query($sql);
    $rows = $db->get_result($result);

    $payments = [];
    $packages = [];

    foreach($rows as $row){

        $payments[] = $row;

        if(!empty($row['package_id'])){
            $packages[$row['package_id']] = [
                'package_id'     => $row['package_id'],
                'package_name'   => $row['package_name'],
                'package_amount' => $row['package_amount'],
                'subscribed_on'  => $row['subscribed_on'],
                'end_date'       => $row['end_date'],
            ];
        }
    }

    return [
        'student'  => $student,
        'packages' => array_values($packages),
        'payments' => $payments
    ];
}
}