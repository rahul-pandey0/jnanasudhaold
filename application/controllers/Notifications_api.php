<?php
class Notifications_api extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/common_helper.php';
        require_once APPPATH . 'helpers/database_helper.php';
        require_once APPPATH . 'helpers/jwt_helper.php';

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* Read JSON body, falling back to $_POST if body is empty or not valid JSON. */
    private function _input()
    {
        $raw  = file_get_contents('php://input');
        $body = $raw ? json_decode($raw, true) : null;
        return is_array($body) ? $body : $_POST;
    }

    /* Returns decoded JWT payload or sends 401 and halts. */
    private function _require_jwt()
    {
        $payload = jwt_from_request();
        if (!$payload) {
            header('Content-Type: application/json');
            http_response_code(401);
            echo json_encode(array('success' => false, 'message' => 'Unauthorized: valid JWT required'));
            exit;
        }
        return $payload;
    }

    // GET /api/fcm/debug — temporary, remove after diagnosis
    public function fcm_debug()
    {
        header('Content-Type: application/json');
        $out = array();

        $out['openssl']   = extension_loaded('openssl');
        $out['curl']      = extension_loaded('curl');
        $key_path         = APPPATH . 'config/firebase_service_account.json';
        $out['sa_exists'] = file_exists($key_path);

        if ($out['sa_exists']) {
            $sa = json_decode(file_get_contents($key_path), true);
            $out['sa_project_id']    = isset($sa['project_id'])    ? $sa['project_id']    : 'missing';
            $out['sa_client_email']  = isset($sa['client_email'])  ? $sa['client_email']  : 'missing';
            $out['sa_private_key']   = !empty($sa['private_key'])  ? 'present'            : 'missing';
        }

        if ($out['curl']) {
            $ch = curl_init('https://oauth2.googleapis.com/token');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_exec($ch);
            $out['curl_google_http'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $out['curl_error']       = curl_error($ch);
            curl_close($ch);
        }

        echo json_encode($out, JSON_PRETTY_PRINT);
    }

    // POST /api/auth/login
    public function login()
    {
        header('Content-Type: application/json');
        $body     = $this->_input();
        $phone    = trim(isset($body['phone'])    ? $body['phone']    : '');
        $password = trim(isset($body['password']) ? $body['password'] : '');

        if (!$phone || !$password) {
            echo json_encode(array('success' => false, 'message' => 'Phone and password required'));
            return;
        }

        $db  = get_db();
        $res = $db->query(
            "SELECT user_id, first_name, last_name, email, phone, role_id, password
             FROM user_details
             WHERE phone = '" . $db->escape($phone) . "' AND user_status = 1
             LIMIT 1"
        );
        $user = $res ? $db->get_row($res) : null;

        if (!$user || $user['password'] !== md5($password)) {
            echo json_encode(array('success' => false, 'message' => 'Invalid credentials'));
            return;
        }

        $name  = trim($user['first_name'] . ' ' . $user['last_name']);
        $token = jwt_encode(array(
            'user_id' => (int)$user['user_id'],
            'role'    => (int)$user['role_id'],
            'phone'   => $user['phone'],
        ));

        echo json_encode(array(
            'success' => true,
            'token'   => $token,
            'user_id' => (int)$user['user_id'],
            'name'    => $name,
            'role'    => (int)$user['role_id'],
            'phone'   => $user['phone'],
        ));
    }

    // POST /api/fcm/register
    public function register()
    {
        header('Content-Type: application/json');
        $this->_require_jwt();

        $body        = json_decode(file_get_contents('php://input'), true);
        $phone       = trim(isset($body['phone'])        ? $body['phone']        : '');
        $user_id_in  = (int)(isset($body['user_id'])     ? $body['user_id']      : 0);
        $fcm_token   = trim(isset($body['device_token']) ? $body['device_token'] : '');
        $platform    = trim(isset($body['platform'])     ? $body['platform']     : 'android');
        $app_version = trim(isset($body['app_version'])  ? $body['app_version']  : '');

        if (!$fcm_token) {
            echo json_encode(array('success' => false, 'message' => 'device_token required'));
            return;
        }

        $db = get_db();

        $user_id = $user_id_in;
        if (!$user_id && $phone) {
            $res     = $db->query("SELECT user_id FROM user_details WHERE phone = '" . $db->escape($phone) . "' LIMIT 1");
            $row     = $res ? $db->get_row($res) : null;
            $user_id = $row ? (int)$row['user_id'] : 0;
        }

        if (!$user_id) {
            echo json_encode(array('success' => false, 'message' => 'User not found'));
            return;
        }

        $db->query(
            "INSERT INTO fcm_tokens (user_id, device_token, platform, app_version, created_at, updated_at)
             VALUES (
                 $user_id,
                 '" . $db->escape($fcm_token)   . "',
                 '" . $db->escape($platform)     . "',
                 '" . $db->escape($app_version)  . "',
                 NOW(), NOW()
             )
             ON DUPLICATE KEY UPDATE
                 user_id     = VALUES(user_id),
                 platform    = VALUES(platform),
                 app_version = VALUES(app_version),
                 updated_at  = NOW()"
        );

        echo json_encode(array('success' => true, 'user_id' => $user_id));
    }

    // POST /api/fcm/unregister
    public function unregister()
    {
        header('Content-Type: application/json');
        $this->_require_jwt();

        $body      = json_decode(file_get_contents('php://input'), true);
        $fcm_token = trim(isset($body['device_token']) ? $body['device_token'] : '');

        if (!$fcm_token) {
            echo json_encode(array('success' => false, 'message' => 'device_token required'));
            return;
        }

        $db = get_db();
        $db->query("DELETE FROM fcm_tokens WHERE device_token = '" . $db->escape($fcm_token) . "'");

        echo json_encode(array('success' => true));
    }

    // GET /api/fcm_notifications/{phone}
    public function inbox($phone = '')
    {
        header('Content-Type: application/json');
        $this->_require_jwt();

        $phone = trim($phone);
        if (!$phone) {
            echo json_encode(array('success' => false, 'message' => 'Phone required'));
            return;
        }

        $db  = get_db();
        $res = $db->query("SELECT user_id FROM user_details WHERE phone = '" . $db->escape($phone) . "' LIMIT 1");
        $u   = $res ? $db->get_row($res) : null;

        if (!$u) {
            echo json_encode(array('success' => false, 'message' => 'User not found'));
            return;
        }

        $uid = (int)$u['user_id'];

        $res = $db->query(
            "SELECT id, notification_id, title, body, image_url, data_payload, is_read, created_at
             FROM user_notifications
             WHERE user_id = $uid
             ORDER BY created_at DESC
             LIMIT 50"
        );
        $rows = $res ? $db->get_result($res) : array();

        $db->query("UPDATE user_notifications SET is_read = 1 WHERE user_id = $uid AND is_read = 0");

        echo json_encode(array('success' => true, 'notifications' => $rows));
    }
}
