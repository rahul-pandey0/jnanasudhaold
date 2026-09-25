<?php
class Notifications extends CI_ProtectedController
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';
        require_once APPPATH . 'helpers/fcm_helper.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->load->model('Notification_model', 'notification_model');
        $this->load->model('Menu_model', 'menu_model');
        $this->load->model('Admin_Model', 'admin_model');
    }

    public function index()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $db      = get_db();

        $br = $db->query("SELECT DISTINCT batch FROM user_details WHERE batch IS NOT NULL AND batch != '' AND user_status = 1 ORDER BY batch");
        $rr = $db->query("SELECT DISTINCT role_id FROM user_details WHERE user_status = 1 ORDER BY role_id");

        $data['title']       = 'Send Notification';
        $data['user_name']   = isset($_SESSION['user_name'])  ? $_SESSION['user_name']  : 'Guest';
        $data['user_email']  = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
        $data['user_role']   = $role_id;
        $data['top_menus']   = $role_id ? $this->menu_model->get_top_menus($role_id) : array();
        $data['token_count'] = $this->notification_model->count_device_tokens();
        $data['batches']     = $br ? array_column($db->get_result($br), 'batch')    : array();
        $data['roles']       = $rr ? array_column($db->get_result($rr), 'role_id')  : array();

        $this->load->view('notifications/compose', $data);
    }

    public function send()
    {
        $title        = trim(isset($_POST['title'])        ? $_POST['title']        : '');
        $body         = trim(isset($_POST['body'])         ? $_POST['body']         : '');
        $target_type  = trim(isset($_POST['target_type'])  ? $_POST['target_type']  : 'broadcast');
        $target_value = trim(isset($_POST['target_value']) ? $_POST['target_value'] : '');
        $image        = trim(isset($_POST['image_url'])    ? $_POST['image_url']    : '');
        $send_mode    = trim(isset($_POST['send_mode'])    ? $_POST['send_mode']    : 'fcm');
        $sent_by      = (int)(isset($_SESSION['user_id'])  ? $_SESSION['user_id']   : 0);

        if ($title === '' || $body === '') {
            header('Location: ' . base_url('notifications') . '?error=Title+and+body+are+required');
            exit;
        }

        // broadcast / fcm_registered always push via FCM
        $no_value_types = array('broadcast', 'fcm_registered');
        if (in_array($target_type, $no_value_types)) {
            $send_mode = 'fcm';
        }

        $data  = array('type' => 'admin_message', 'target_type' => $target_type, 'target_value' => $target_value);
        $image = $image !== '' ? $image : null;
        $tv    = in_array($target_type, $no_value_types) ? null : $target_value;

        // Fan-out user list (always needed for inbox rows)
        $user_ids = $this->notification_model->get_user_ids_for_target($target_type, $target_value);

        $tokens_total = 0;
        $fcm_success  = 0;
        $fcm_failed   = 0;
        $fcm_errors   = array();

        if ($send_mode === 'fcm') {
            $tokens       = $this->notification_model->get_device_tokens_for_target($target_type, $target_value);
            $result       = fcm_send_multicast($tokens, $title, $body, $data, $image);
            $tokens_total = count($tokens);
            $fcm_success  = $result['success'];
            $fcm_failed   = $result['failed'];
            $fcm_errors   = isset($result['errors']) ? $result['errors'] : array();
        }

        // Log one record in fcm_notifications
        $notification_id = $this->notification_model->log_broadcast(
            $sent_by, $target_type, $tv, $title, $body, $image, $data,
            $tokens_total, $fcm_success, $fcm_failed
        );
        error_log('[NOTIFY] log_broadcast returned notification_id=' . $notification_id);

        // Fan out per-user inbox rows
        error_log('[NOTIFY] fan_out user_ids count=' . count($user_ids));
        $this->notification_model->fan_out_to_users($user_ids, $notification_id, $title, $body, $image, $data);

        if ($send_mode === 'fcm') {
            $message = 'Sent (FCM). Success: ' . $fcm_success . ', Failed: ' . $fcm_failed;
        } else {
            $message = 'Saved to inbox only (Normal mode).';
        }
        $message .= ' | DB: notif_id=' . $notification_id . ' users=' . count($user_ids);
        if (!empty($fcm_errors)) {
            $message .= ' | ' . implode('; ', $fcm_errors);
        }

        header('Location: ' . base_url('notifications') . '?status=' . urlencode($message));
        exit;
    }

    // ---------------------------------------------------------------
    // Quiz rank notification flow
    // ---------------------------------------------------------------

    public function quiz_rank()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $db      = get_db();

        $qr = $db->query("SELECT id, Quiz_name FROM quiz_info ORDER BY id DESC");
        $br = $db->query("SELECT DISTINCT batch FROM user_details WHERE batch IS NOT NULL AND batch != '' AND user_status = 1 ORDER BY batch");

        $data['title']      = 'Quiz Rank Notification';
        $data['user_name']  = isset($_SESSION['user_name'])  ? $_SESSION['user_name']  : 'Guest';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
        $data['user_role']  = $role_id;
        $data['top_menus']  = $role_id ? $this->menu_model->get_top_menus($role_id) : array();
        $data['quizzes']    = $qr ? array_column($db->get_result($qr), null, 'id') : array();
        $data['batches']    = $br ? array_column($db->get_result($br), 'batch')    : array();

        $this->load->view('notifications/quiz_rank_notify', $data);
    }

    // AJAX: fetch rank rows for preview
    public function quiz_rank_preview()
    {
        $cat        = isset($_POST['quiz_id'])    ? trim($_POST['quiz_id'])    : '';
        $batch      = isset($_POST['batch'])      ? trim($_POST['batch'])      : 'all';
        $quiz_type  = isset($_POST['quiz_type'])  ? trim($_POST['quiz_type'])  : 'general';
        if ($batch === '') $batch = 'all';

        if ($cat === '') {
            header('Content-Type: application/json');
            echo json_encode(array('error' => 'quiz_id required'));
            exit;
        }

        if ($quiz_type === 'jee') {
            $rows = $this->admin_model->getjeerank($cat, $batch);
        } elseif ($quiz_type === 'subjectmath') {
            $rows = $this->admin_model->getsubjectmathresult($cat);
        } else {
            $rows = $this->admin_model->getallrank($cat, $batch);
        }

        header('Content-Type: application/json');
        echo json_encode($rows ? $rows : array());
        exit;
    }

    // POST: send rank notifications to selected users
    public function quiz_rank_send()
    {
        $quiz_id        = trim(isset($_POST['quiz_id'])        ? $_POST['quiz_id']        : '');
        $batch          = trim(isset($_POST['batch'])           ? $_POST['batch']           : 'all');
        $quiz_type      = trim(isset($_POST['quiz_type'])       ? $_POST['quiz_type']       : 'general');
        $title          = trim(isset($_POST['title'])           ? $_POST['title']           : '');
        $body_tpl       = trim(isset($_POST['body'])            ? $_POST['body']            : '');
        $send_mode      = trim(isset($_POST['send_mode'])       ? $_POST['send_mode']       : 'fcm');
        $selected_users = trim(isset($_POST['selected_users'])  ? $_POST['selected_users']  : '');
        $sent_by        = (int)(isset($_SESSION['user_id'])     ? $_SESSION['user_id']      : 0);

        if ($batch === '') $batch = 'all';

        if ($quiz_id === '' || $title === '' || $body_tpl === '') {
            header('Location: ' . base_url('notifications/quiz_rank') . '?error=Quiz%2C+title+and+body+are+required');
            exit;
        }

        $selected = $selected_users ? json_decode($selected_users, true) : array();
        $selected = is_array($selected) ? array_filter(array_map('trim', $selected)) : array();

        if (empty($selected)) {
            header('Location: ' . base_url('notifications/quiz_rank') . '?error=No+users+selected');
            exit;
        }

        if ($quiz_type === 'jee') {
            $all_rows = $this->admin_model->getjeerank($quiz_id, $batch);
        } elseif ($quiz_type === 'subjectmath') {
            $all_rows = $this->admin_model->getsubjectmathresult($quiz_id);
        } else {
            $all_rows = $this->admin_model->getallrank($quiz_id, $batch);
        }
        if (empty($all_rows)) {
            header('Location: ' . base_url('notifications/quiz_rank') . '?error=No+results+found+for+that+quiz+%2F+batch');
            exit;
        }

        // Index rows by user_id for fast lookup
        $row_map = array();
        foreach ($all_rows as $row) {
            $row_map[$row['user_id']] = $row;
        }

        $db           = get_db();
        $tokens_total = 0;
        $fcm_success  = 0;
        $fcm_failed   = 0;
        $sent_count   = 0;

        foreach ($selected as $username) {
            $row = isset($row_map[$username]) ? $row_map[$username] : null;
            if (!$row) continue;

            // Use db_user_id if already resolved (subjectmath), otherwise look up by username
            if (!empty($row['db_user_id'])) {
                $uid = (int)$row['db_user_id'];
            } else {
                $uid_res = $db->query(
                    "SELECT user_id FROM user_details WHERE user_name = '" . $db->escape($username) . "' AND user_status = 1 LIMIT 1"
                );
                $uid_row = $uid_res ? $db->get_row($uid_res) : null;
                if (!$uid_row) continue;
                $uid = (int)$uid_row['user_id'];
            }

            $body = str_replace(
                array('{name}', '{rank}', '{physics}', '{chemistry}', '{biology}', '{total}', '{quiz}', '{mark}', '{attempted}', '{correct}', '{wrong}'),
                array(
                    isset($row['name'])          ? $row['name']          : '',
                    isset($row['rank'])          ? $row['rank']          : '',
                    isset($row['physicsmark'])   ? $row['physicsmark']   : '0',
                    isset($row['chemistrymark']) ? $row['chemistrymark'] : '0',
                    isset($row['biologymark'])   ? $row['biologymark']   : '0',
                    isset($row['total'])         ? $row['total']         : '',
                    isset($row['Quiz_name'])     ? $row['Quiz_name']     : '',
                    isset($row['mark'])          ? $row['mark']          : '0',
                    isset($row['attempted'])     ? $row['attempted']     : '0',
                    isset($row['correct'])       ? $row['correct']       : '0',
                    isset($row['wrong'])         ? $row['wrong']         : '0',
                ),
                $body_tpl
            );

            $payload = array('type' => 'quiz_rank', 'quiz_id' => $quiz_id, 'rank' => isset($row['rank']) ? $row['rank'] : '');

            $notification_id = $this->notification_model->log_broadcast(
                $sent_by, 'individual', (string)$uid, $title, $body, null, $payload, 0, 0, 0
            );

            $this->notification_model->fan_out_to_users(array($uid), $notification_id, $title, $body, null, $payload);

            if ($send_mode === 'fcm') {
                $tokens = $this->notification_model->get_device_tokens_for_users(array($uid));
                if (!empty($tokens)) {
                    $result = fcm_send_multicast($tokens, $title, $body, $payload, null);
                    $tokens_total += count($tokens);
                    $fcm_success  += $result['success'];
                    $fcm_failed   += $result['failed'];
                }
            }

            $sent_count++;
        }

        $msg = 'Rank notifications sent to ' . $sent_count . ' users.';
        if ($send_mode === 'fcm') {
            $msg .= ' FCM: success=' . $fcm_success . ' failed=' . $fcm_failed;
        } else {
            $msg .= ' (Inbox only)';
        }

        header('Location: ' . base_url('notifications/quiz_rank') . '?status=' . urlencode($msg));
        exit;
    }

    // ---------------------------------------------------------------
    // User notify — by batch or by role, with personalised message
    // ---------------------------------------------------------------

    public function user_notify()
    {
        $role_id = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : null;
        $db      = get_db();

        $br = $db->query("SELECT DISTINCT batch FROM user_details WHERE batch IS NOT NULL AND batch != '' AND user_status = 1 ORDER BY batch");
        $rr = $db->query("SELECT rd.role_id, rd.role_name FROM role_details rd INNER JOIN user_details ud ON ud.role_id = rd.role_id WHERE ud.user_status = 1 GROUP BY rd.role_id, rd.role_name ORDER BY rd.role_id");

        $data['title']      = 'User Notify';
        $data['user_name']  = isset($_SESSION['user_name'])  ? $_SESSION['user_name']  : 'Guest';
        $data['user_email'] = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
        $data['user_role']  = $role_id;
        $data['top_menus']  = $role_id ? $this->menu_model->get_top_menus($role_id) : array();
        $data['batches']    = $br ? $db->get_result($br) : array();
        $data['roles']      = $rr ? $db->get_result($rr) : array();

        $this->load->view('notifications/user_notify', $data);
    }

    // AJAX: fetch users for preview
    public function user_notify_preview()
    {
        $target_type  = isset($_POST['target_type'])  ? trim($_POST['target_type'])  : '';
        $target_value = isset($_POST['target_value']) ? trim($_POST['target_value']) : '';

        $db = get_db();

        if ($target_type === 'batch') {
            $res = $db->query(
                "SELECT ud.user_id, ud.user_name, CONCAT(ud.first_name,' ',ud.last_name) AS name,
                        ud.phone, ud.batch, rd.role_name
                 FROM user_details ud
                 LEFT JOIN role_details rd ON rd.role_id = ud.role_id
                 WHERE ud.user_status = 1
                   AND ud.batch = '" . $db->escape($target_value) . "'
                 ORDER BY ud.first_name, ud.last_name"
            );
        } elseif ($target_type === 'role') {
            $res = $db->query(
                "SELECT ud.user_id, ud.user_name, CONCAT(ud.first_name,' ',ud.last_name) AS name,
                        ud.phone, ud.batch, rd.role_name
                 FROM user_details ud
                 LEFT JOIN role_details rd ON rd.role_id = ud.role_id
                 WHERE ud.user_status = 1
                   AND ud.role_id = " . (int)$target_value . "
                 ORDER BY ud.first_name, ud.last_name"
            );
        } elseif ($target_type === 'staff') {
            $res = $db->query(
                "SELECT ud.user_id, ud.user_name, CONCAT(ud.first_name,' ',ud.last_name) AS name,
                        ud.phone, ud.batch, rd.role_name
                 FROM user_details ud
                 LEFT JOIN role_details rd ON rd.role_id = ud.role_id
                 WHERE ud.user_status = 1
                   AND ud.role_id = 3
                 ORDER BY ud.first_name, ud.last_name"
            );
        } else {
            header('Content-Type: application/json');
            echo json_encode(array());
            exit;
        }

        $rows = $res ? $db->get_result($res) : array();
        header('Content-Type: application/json');
        echo json_encode($rows);
        exit;
    }

    // POST: send personalised messages to selected users
    public function user_notify_send()
    {
        $title          = trim(isset($_POST['title'])           ? $_POST['title']           : '');
        $body_tpl       = trim(isset($_POST['body'])            ? $_POST['body']            : '');
        $send_mode      = trim(isset($_POST['send_mode'])       ? $_POST['send_mode']       : 'fcm');
        $selected_users = trim(isset($_POST['selected_users'])  ? $_POST['selected_users']  : '');
        $sent_by        = (int)(isset($_SESSION['user_id'])     ? $_SESSION['user_id']      : 0);

        if ($title === '' || $body_tpl === '') {
            header('Location: ' . base_url('notifications/user_notify') . '?error=Title+and+body+are+required');
            exit;
        }

        $selected = $selected_users ? json_decode($selected_users, true) : array();
        $selected = is_array($selected) ? array_filter(array_map('intval', $selected)) : array();

        if (empty($selected)) {
            header('Location: ' . base_url('notifications/user_notify') . '?error=No+users+selected');
            exit;
        }

        $db           = get_db();
        $tokens_total = 0;
        $fcm_success  = 0;
        $fcm_failed   = 0;
        $sent_count   = 0;

        foreach ($selected as $uid) {
            $uid = (int)$uid;
            $ur  = $db->query(
                "SELECT ud.user_id, CONCAT(ud.first_name,' ',ud.last_name) AS name,
                        ud.phone, ud.batch, rd.role_name
                 FROM user_details ud
                 LEFT JOIN role_details rd ON rd.role_id = ud.role_id
                 WHERE ud.user_id = $uid AND ud.user_status = 1 LIMIT 1"
            );
            $user = $ur ? $db->get_row($ur) : null;
            if (!$user) continue;

            $body = str_replace(
                array('{name}', '{phone}', '{batch}', '{role}'),
                array(
                    isset($user['name'])      ? $user['name']      : '',
                    isset($user['phone'])     ? $user['phone']     : '',
                    isset($user['batch'])     ? $user['batch']     : '',
                    isset($user['role_name']) ? $user['role_name'] : '',
                ),
                $body_tpl
            );

            $payload = array('type' => 'admin_message');

            $notification_id = $this->notification_model->log_broadcast(
                $sent_by, 'individual', (string)$uid, $title, $body, null, $payload, 0, 0, 0
            );

            $this->notification_model->fan_out_to_users(array($uid), $notification_id, $title, $body, null, $payload);

            if ($send_mode === 'fcm') {
                $tokens = $this->notification_model->get_device_tokens_for_users(array($uid));
                if (!empty($tokens)) {
                    $result = fcm_send_multicast($tokens, $title, $body, $payload, null);
                    $tokens_total += count($tokens);
                    $fcm_success  += $result['success'];
                    $fcm_failed   += $result['failed'];
                }
            }
            $sent_count++;
        }

        $msg = 'Notifications sent to ' . $sent_count . ' users.';
        if ($send_mode === 'fcm') {
            $msg .= ' FCM: success=' . $fcm_success . ' failed=' . $fcm_failed;
        } else {
            $msg .= ' (Inbox only)';
        }

        header('Location: ' . base_url('notifications/user_notify') . '?status=' . urlencode($msg));
        exit;
    }
}
