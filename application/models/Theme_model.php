<?php
class Theme_model extends CI_Model {
    protected $table = 'app_settings';

    public function __construct()
    {
        parent::__construct();
        require_once APPPATH . 'helpers/database_helper.php';
    }

    public function get_theme()
    {
        $db = get_db();
        $res = $db->query("SELECT setting_key, setting_value FROM {$this->table} WHERE setting_key IN ('theme_primary','theme_secondary')");
        $out = array('theme_primary' => '#667eea', 'theme_secondary' => '#764ba2');
        if ($res && $res->num_rows > 0) {
            while ($row = $res->fetch_assoc()) {
                $out[$row['setting_key']] = $row['setting_value'];
            }
        }
        return $out;
    }

    public function set_theme($primary, $secondary)
    {
        $db = get_db();
        $p = $db->escape($primary);
        $s = $db->escape($secondary);
        // Upsert-like behavior
        $db->query("DELETE FROM {$this->table} WHERE setting_key IN ('theme_primary','theme_secondary')");
        $db->query("INSERT INTO {$this->table} (setting_key, setting_value) VALUES ('theme_primary', {$p}), ('theme_secondary', {$s})");
        return true;
    }
}
?>