<?php
class Notification_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    // Insert one record into fcm_notifications, return the new id
    public function log_broadcast($sent_by, $target_type, $target_value, $title, $body, $image_url, $data, $tokens_total, $tokens_success, $tokens_failed)
    {
        $db = get_db();
        $db->query(
            "INSERT INTO fcm_notifications (sent_by, target_type, target_value, title, body, image_url, data_payload, tokens_total, tokens_success, tokens_failed, status, sent_at, created_at)
             VALUES (" . (int)$sent_by . ",
                     '" . $db->escape($target_type)   . "',
                     " . ($target_value !== null ? "'" . $db->escape($target_value) . "'" : 'NULL') . ",
                     '" . $db->escape($title)         . "',
                     '" . $db->escape($body)          . "',
                     " . ($image_url ? "'" . $db->escape($image_url) . "'" : 'NULL') . ",
                     '" . $db->escape(json_encode($data)) . "',
                     " . (int)$tokens_total   . ",
                     " . (int)$tokens_success . ",
                     " . (int)$tokens_failed  . ",
                     'sent', NOW(), NOW())"
        );
        return $db->insert_id();
    }

    // Get user_ids matching a target type/value
    public function get_user_ids_for_target($target_type, $target_value)
    {
        $db = get_db();
        if ($target_type === 'broadcast') {
            $res = $db->query("SELECT user_id FROM user_details WHERE user_status = 1");
        } elseif ($target_type === 'fcm_registered') {
            $res = $db->query(
                "SELECT ud.user_id FROM user_details ud
                 INNER JOIN fcm_tokens ft ON ft.user_id = ud.user_id
                 WHERE ud.user_status = 1"
            );
        } elseif ($target_type === 'role') {
            $res = $db->query("SELECT user_id FROM user_details WHERE user_status = 1 AND role_id = '" . $db->escape($target_value) . "'");
        } elseif ($target_type === 'batch') {
            $res = $db->query("SELECT user_id FROM user_details WHERE user_status = 1 AND batch = '" . $db->escape($target_value) . "'");
        } elseif ($target_type === 'individual') {
            $v = $db->escape($target_value);
            $res = $db->query("SELECT user_id FROM user_details WHERE user_status = 1 AND (phone = '$v' OR user_name = '$v' OR user_id = " . (int)$target_value . ") LIMIT 1");
        } else {
            return array();
        }
        $rows = $res ? $db->get_result($res) : array();
        return array_column($rows, 'user_id');
    }

    // Bulk insert rows into user_notifications for all matched users
    public function fan_out_to_users(array $user_ids, $notification_id, $title, $body, $image_url, $data)
    {
        if (empty($user_ids)) return;
        $db      = get_db();
        $payload = $db->escape(json_encode($data));
        $img     = $image_url ? "'" . $db->escape($image_url) . "'" : 'NULL';
        $t       = $db->escape($title);
        $b       = $db->escape($body);
        $nid     = (int)$notification_id;

        $values = array();
        foreach ($user_ids as $uid) {
            $values[] = "(" . (int)$uid . ", $nid, '$t', '$b', $img, '$payload', NOW())";
        }
        $db->query(
            "INSERT INTO user_notifications (user_id, notification_id, title, body, image_url, data_payload, created_at)
             VALUES " . implode(',', $values)
        );
    }

    public function get_device_tokens_for_target($target_type, $target_value)
    {
        $db      = get_db();
        $user_ids = $this->get_user_ids_for_target($target_type, $target_value);
        if (empty($user_ids)) return array();
        $ids = implode(',', array_map('intval', $user_ids));
        $res = $db->query("SELECT device_token FROM fcm_tokens WHERE user_id IN ($ids)");
        return $res ? array_column($db->get_result($res), 'device_token') : array();
    }

    public function register_device_token($user_id, $device_token, $platform = 'android', $app_version = '')
    {
        $db = get_db();
        $db->query(
            "INSERT INTO fcm_tokens (user_id, device_token, platform, app_version, created_at, updated_at)
             VALUES (" . (int)$user_id . ", '" . $db->escape($device_token) . "', '" . $db->escape($platform) . "',
                     '" . $db->escape($app_version) . "', NOW(), NOW())
             ON DUPLICATE KEY UPDATE
                user_id     = VALUES(user_id),
                platform    = VALUES(platform),
                app_version = VALUES(app_version),
                updated_at  = NOW()"
        );
    }

    public function remove_device_token($device_token)
    {
        $db = get_db();
        $db->query("DELETE FROM fcm_tokens WHERE device_token = '" . $db->escape($device_token) . "'");
    }

    public function get_device_tokens_for_users(array $user_ids)
    {
        if (empty($user_ids)) return [];
        $db  = get_db();
        $ids = implode(',', array_map('intval', $user_ids));
        $res = $db->query("SELECT device_token FROM fcm_tokens WHERE user_id IN ($ids)");
        return $res ? array_column($db->get_result($res), 'device_token') : [];
    }

    public function get_all_device_tokens()
    {
        $db  = get_db();
        $res = $db->query(
            "SELECT ft.device_token FROM fcm_tokens ft
             INNER JOIN user_details ud ON ud.user_id = ft.user_id
             WHERE ud.user_status = 1"
        );
        return $res ? array_column($db->get_result($res), 'device_token') : [];
    }

    public function count_device_tokens()
    {
        $db  = get_db();
        $res = $db->query(
            "SELECT COUNT(*) as c FROM fcm_tokens ft
             INNER JOIN user_details ud ON ud.user_id = ft.user_id
             WHERE ud.user_status = 1"
        );
        $row = $res ? $db->get_row($res) : null;
        return $row ? (int)$row['c'] : 0;
    }

    public function save_notification($user_id, $notification_id, $title, $body, $image_url = null, $data = array())
    {
        $db = get_db();
        $db->query(
            "INSERT INTO user_notifications (user_id, notification_id, title, body, image_url, data_payload, created_at)
             VALUES (" . (int)$user_id . ",
                     " . (int)$notification_id . ",
                     '" . $db->escape($title) . "',
                     '" . $db->escape($body) . "',
                     " . ($image_url ? "'" . $db->escape($image_url) . "'" : 'NULL') . ",
                     '" . $db->escape(json_encode($data)) . "',
                     NOW())"
        );
    }

    public function get_inbox($user_id, $limit = 50, $offset = 0)
    {
        $db  = get_db();
        $res = $db->query(
            "SELECT id, notification_id, title, body, image_url, data_payload, is_read, created_at
             FROM user_notifications
             WHERE user_id = " . (int)$user_id . "
             ORDER BY created_at DESC
             LIMIT " . (int)$limit . " OFFSET " . (int)$offset
        );
        return $res ? $db->get_result($res) : array();
    }

    public function count_unread($user_id)
    {
        $db  = get_db();
        $res = $db->query("SELECT COUNT(*) as c FROM user_notifications WHERE user_id = " . (int)$user_id . " AND is_read = 0");
        $row = $res ? $db->get_row($res) : null;
        return $row ? (int)$row['c'] : 0;
    }

    public function mark_all_read($user_id)
    {
        $db = get_db();
        $db->query("UPDATE user_notifications SET is_read = 1 WHERE user_id = " . (int)$user_id);
    }
}
