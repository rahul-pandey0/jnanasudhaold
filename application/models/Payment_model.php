<?php
/**
 * Payment Gateway Model
 * Handles payment gateway receipts and transactions
 */

class Payment_model extends CI_Model {

    protected $table = 'payment_gateway_status';

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/database_helper.php';
    }

    /**
     * Get all payment records with pagination
     */
    public function get_payments($limit = 20, $offset = 0, $search = '', $status = '')
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table;

        $conditions = array();
        if (!empty($search)) {
            $pattern = "'%" . $db->escape($search) . "%'";
            $conditions[] = "(cust_name LIKE " . $pattern . " 
                     OR email_id LIKE " . $pattern . " 
                     OR mobile_no LIKE " . $pattern . " 
                     OR order_no LIKE " . $pattern . ")";
        }
        if (!empty($status)) {
            $conditions[] = "status = '" . $db->escape($status) . "'";
        }
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY datetime DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        
        $result = $db->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }

    /**
     * Get total payment records count
     */
    public function get_payments_count($search = '', $status = '')
    {
        $db = get_db();
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;

        $conditions = array();
        if (!empty($search)) {
            $pattern = "'%" . $db->escape($search) . "%'";
            $conditions[] = "(cust_name LIKE " . $pattern . " 
                     OR email_id LIKE " . $pattern . " 
                     OR mobile_no LIKE " . $pattern . " 
                     OR order_no LIKE " . $pattern . ")";
        }
        if (!empty($status)) {
            $conditions[] = "status = '" . $db->escape($status) . "'";
        }
        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }
        
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return (int)$row['total'];
        }
        return 0;
    }

    /**
     * Get single payment by ID
     */
    public function get_payment($id)
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table . " WHERE id = " . (int)$id . " LIMIT 1";
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    /**
     * Get payment by order number
     */
    public function get_payment_by_order($order_no)
    {
        $db = get_db();
        $order_no_escaped = $db->escape($order_no);
        $sql = "SELECT * FROM " . $this->table . " WHERE order_no = '" . $order_no_escaped . "' LIMIT 1";
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    /**
     * Get print data by order number, joining with package_info or package_info_offline
     */
    public function get_print_data_by_order($order_no)
    {
        $db = get_db();
        $order_no_escaped = $db->escape($order_no);
        // Fetch payment first to detect type and packageid
        $payment = $this->get_payment_by_order($order_no);
        if (!$payment) { return null; }

        $packageId = isset($payment['packageid']) ? (int)$payment['packageid'] : 0;
        // package type field comes from payment as package_type
        $type = isset($payment['package_type']) ? $payment['package_type'] : 'online';

        if ($packageId <= 0) {
            // No package id; return payment as-is
            return $payment;
        }

        // Join package info and project key financial fields explicitly
        if ($type === 'offline') {
            // Use actual column names: packageamount, sgst_amt, cgst_amt; hardcode SAC; alias for controller usage
            $sql = "SELECT a.*, '999299' as sac, b.packageamount AS package_amount, b.sgst_amt AS sgst_amount, b.cgst_amt AS cgst_amount, 
                           (b.packageamount + IFNULL(b.sgst_amt,0) + IFNULL(b.cgst_amt,0)) AS total_amount 
                    FROM " . $this->table . " a 
                    JOIN package_info_offline b ON a.packageid = b.id 
                    WHERE a.order_no = '" . $order_no_escaped . "' LIMIT 1";
        } else {
            // Online package query with correct column names and aliases
            $sql = "SELECT a.*, '999299' as sac, b.packageamount AS package_amount, b.sgst_amt AS sgst_amount, b.cgst_amt AS cgst_amount, 
                           (b.packageamount + IFNULL(b.sgst_amt,0) + IFNULL(b.cgst_amt,0)) AS total_amount 
                    FROM " . $this->table . " a 
                    JOIN package_info b ON a.packageid = b.id 
                    WHERE a.order_no = '" . $order_no_escaped . "' LIMIT 1";
        }
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return $payment; // fallback to payment-only data
    }

    /**
     * Get payments by customer mobile number
     */
    public function get_payments_by_mobile($mobile_no, $limit = 10)
    {
        $db = get_db();
        $mobile_escaped = $db->escape($mobile_no);
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE mobile_no = '" . $mobile_escaped . "' 
                ORDER BY datetime DESC LIMIT " . (int)$limit;
        $result = $db->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }

    /**
     * Get payment statistics
     */
    public function get_payment_stats()
    {
        $db = get_db();
        
        $sql = "SELECT 
                COUNT(*) as total_transactions,
                SUM(amount) as total_amount,
                COUNT(CASE WHEN status = 'Success' THEN 1 END) as successful,
                COUNT(CASE WHEN status = 'Failed' THEN 1 END) as failed,
                COUNT(CASE WHEN status = 'Pending' THEN 1 END) as pending
                FROM " . $this->table;
        
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    /**
     * Get payments by status
     */
    public function get_payments_by_status($status, $limit = 100)
    {
        $db = get_db();
        $status_escaped = $db->escape($status);
        $sql = "SELECT * FROM " . $this->table . " 
                WHERE status = '" . $status_escaped . "' 
                ORDER BY datetime DESC LIMIT " . (int)$limit;
        $result = $db->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }

    /**
     * Get payment revenue by date range
     */
    public function get_revenue_by_date_range($from_date, $to_date)
    {
        $db = get_db();
        $from_date_escaped = $db->escape($from_date);
        $to_date_escaped = $db->escape($to_date);
        
        $sql = "SELECT 
                DATE(datetime) as payment_date,
                COUNT(*) as transaction_count,
                SUM(amount) as daily_revenue
                FROM " . $this->table . " 
                WHERE DATE(datetime) BETWEEN '" . $from_date_escaped . "' AND '" . $to_date_escaped . "'
                AND status = 'Success'
                GROUP BY DATE(datetime)
                ORDER BY payment_date DESC";
        
        $result = $db->query($sql);
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return array();
    }

    /**
     * Insert payment record
     */
    public function insert_payment($data = array())
    {
        if (empty($data)) {
            return false;
        }
        
        $db = get_db();
        
        // Escape all string values
        $escaped_data = array();
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $escaped_data[$key] = "'" . $db->escape($value) . "'";
            } else if (is_numeric($value)) {
                $escaped_data[$key] = $value;
            } else if (is_null($value)) {
                $escaped_data[$key] = "NULL";
            } else {
                $escaped_data[$key] = "'" . $db->escape((string)$value) . "'";
            }
        }
        
        $columns = implode(', ', array_keys($escaped_data));
        $values = implode(', ', array_values($escaped_data));
        
        $sql = "INSERT INTO " . $this->table . " (" . $columns . ") VALUES (" . $values . ")";
        
        if ($db->query($sql)) {
            return $db->insert_id();
        }
        return false;
    }

    /**
     * Update payment record
     */
    public function update_payment($id, $data = array())
    {
        if (empty($data) || !$id) {
            return false;
        }
        
        $db = get_db();
        
        $set_clauses = array();
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $set_clauses[] = $key . " = '" . $db->escape($value) . "'";
            } else if (is_numeric($value)) {
                $set_clauses[] = $key . " = " . $value;
            } else if (is_null($value)) {
                $set_clauses[] = $key . " = NULL";
            } else {
                $set_clauses[] = $key . " = '" . $db->escape((string)$value) . "'";
            }
        }
        
        $sql = "UPDATE " . $this->table . " SET " . implode(', ', $set_clauses) . " WHERE id = " . (int)$id;
        
        return $db->query($sql) ? true : false;
    }

    /**
     * Delete payment record
     */
    public function delete_payment($id)
    {
        $db = get_db();
        $sql = "DELETE FROM " . $this->table . " WHERE id = " . (int)$id;
        return $db->query($sql) ? true : false;
    }

    /**
     * Ensure receipt number for an order with null receipt_no.
     * Inserts into receipt_no(order_no) and updates payment_gateway_status.receipt_no with inserted id.
     * Generates regardless of status (used when transitioning to Success or regenerating).
     */
    public function ensure_receipt_no_for_order($order_no)
    {
        $db = get_db();
        $order = $this->get_payment_by_order($order_no);
        if (!$order) { return false; }
        // Only proceed if receipt_no is empty
        if (!empty($order['receipt_no'])) { return false; }

        $order_no_escaped = $db->escape($order_no);
        // Insert into receipt_no
        $sqlInsert = "INSERT INTO receipt_no (order_no) VALUES ('" . $order_no_escaped . "')";
        if (!$db->query($sqlInsert)) { return false; }
        $insertId = $db->insert_id();
        if (!$insertId) { return false; }

        // Update payment_gateway_status with new receipt_no
        $sqlUpdate = "UPDATE " . $this->table . " SET receipt_no = " . (int)$insertId . " WHERE order_no = '" . $order_no_escaped . "'";
        return $db->query($sqlUpdate) ? $insertId : false;
    }

    /**
     * Get payment by receipt number
     */
    public function get_payment_by_receipt($receipt_no)
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table . " WHERE receipt_no = " . (int)$receipt_no . " LIMIT 1";
        $result = $db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return $row;
        }
        return null;
    }

    /**
     * Get payment details with formatted values
     */
    public function get_payment_details($id)
    {
        $payment = $this->get_payment($id);
        
        if (!$payment) {
            return null;
        }
        
        // Format the payment data
        $payment['amount_formatted'] = number_format($payment['amount'], 2);
        $payment['datetime_formatted'] = date('d M Y H:i', strtotime($payment['datetime']));
        $payment['status_badge'] = $this->get_status_badge($payment['status']);
        
        return $payment;
    }

    /**
     * Get status badge HTML
     */
    public function get_status_badge($status)
    {
        $badges = array(
            'Success' => '<span class="badge bg-success">Success</span>',
            'Failed' => '<span class="badge bg-danger">Failed</span>',
            'Pending' => '<span class="badge bg-warning">Pending</span>',
            'Cancelled' => '<span class="badge bg-secondary">Cancelled</span>'
        );
        
        return isset($badges[$status]) ? $badges[$status] : '<span class="badge bg-info">' . htmlspecialchars($status) . '</span>';
    }
/**
 * Update only customer name
 */
public function update_customer_name($id, $name)
{
    if (!$id || $name === '') {
        return false;
    }

    $db = get_db();

    // escape WITHOUT quotes
    $escaped = $db->escape($name);

    $sql = "UPDATE {$this->table}
            SET cust_name = '" . $escaped . "'
            WHERE id = " . (int)$id . "
            LIMIT 1";

    $result = $db->query($sql);

    return ($result ? true : false);
}

/**
 * Update offline_assigned_package status to 'paid' when receipt is generated
 * This is called when a receipt is created/regenerated for an offline payment
 * 
 * @param string $order_no The order number from payment_gateway_status
 * @return bool Success/failure
 */
public function update_offline_package_receipt($order_no)
{
    $db = get_db();
    $order_no_escaped = $db->escape($order_no);
    
    // Get payment details to find mobile_no and packageid
    $payment = $this->get_payment_by_order($order_no);
    if (!$payment || !isset($payment['mobile_no']) || !isset($payment['packageid'])) {
        return false;
    }
    
    $mobile_no_escaped = $db->escape($payment['mobile_no']);
    $package_id_escaped = $db->escape($payment['packageid']);
    
    // Update offline_assigned_package: set status to 'paid' (no receipt_time update)
        $sql = "UPDATE `offline_assigned_package` 
            SET `status` = 'paid' 
            WHERE `user_name` = '" . $mobile_no_escaped . "' 
            AND `package_id` = '" . $package_id_escaped . "'";
    print_r($sql);
    //die;
    return $db->query($sql) ? true : false;
}

/**
 * Batch update offline packages by mobile number and package IDs
 * Used for CSV import or multiple assignment scenarios
 * Marks packages as 'paid' and records receipt time
 * 
 * @param string $user_name Mobile number
 * @param array $package_ids Array of package IDs to mark as paid
 * @return bool Success/failure
 */
public function mark_offline_packages_paid($user_name, $package_ids = [])
{
    if (empty($user_name) || empty($package_ids)) {
        return false;
    }
    
    $db = get_db();
    $user_name_escaped = $db->escape($user_name);
    
    // Build IN clause for package IDs
    $ids_escaped = array_map(function($id) use ($db) {
        return "'" . $db->escape($id) . "'";
    }, $package_ids);
    $ids_list = implode(',', $ids_escaped);
    
    // Update all packages for this user with specified package IDs
    $sql = "UPDATE `offline_assigned_package` 
            SET `status` = 'paid' 
            WHERE `user_name` = " . $user_name_escaped . " 
            AND `package_id` IN (" . $ids_list . ")";
    
    return $db->query($sql) ? true : false;
}

/**
 * Get offline package details with receipt information
 * Useful for tracking receipts for offline packages
 * 
 * @param string $user_name Mobile number
 * @param string $package_id Package ID
 * @return array|null Package details or null if not found
 */
public function get_offline_package_receipt($user_name, $package_id = null)
{
    $db = get_db();
    $user_name_escaped = $db->escape($user_name);
    
    if (empty($package_id)) {
        // Get all packages for user
        $sql = "SELECT * FROM `offline_assigned_package` 
                WHERE `user_name` = " . $user_name_escaped . "
                ORDER BY `receipt_time` DESC, `creation_time` DESC";
    } else {
        // Get specific package
        $package_id_escaped = $db->escape($package_id);
        $sql = "SELECT * FROM `offline_assigned_package` 
                WHERE `user_name` = " . $user_name_escaped . " 
                AND `package_id` = " . $package_id_escaped;
    }
    
    $result = $db->query($sql);
    if (!$result) {
        return null;
    }
    
    if (empty($package_id)) {
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return !empty($rows) ? $rows : null;
    } else {
        return $result->fetch_assoc();
    }
}



}
?>