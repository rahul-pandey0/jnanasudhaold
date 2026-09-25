<?php
class Package_model extends CI_Model {

    protected $table = 'package_info';

    public function __construct() {
        parent::__construct();
    }

    public function get_packages($limit = 10, $offset = 0, $search = '', $status = null,$type='')
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table;
        $conditions = [];
        if (!empty($search)) {
            $escaped = $db->escape_like_str($search);
            $conditions[] = "(package_name LIKE '%" . $escaped . "%' OR subject_name LIKE '%" . $escaped . "%')";
        }
        if ($status === 0 || $status === 1) {
            $conditions[] = "status = " . (int)$status;
        }
     if ($type !== '') {

    if ($type === 'offline') {
        $conditions[] = "(LOWER(TRIM(`type`)) = 'offline' OR `type` = '' OR `type` IS NULL)";
    } else {
        $type_value = strtolower(trim($type));
        $conditions[] = "LOWER(TRIM(`type`)) = '".$type_value."'";
    }

     }


    

     if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY id ASC LIMIT " . $limit . " OFFSET " . $offset;

        return $db->query($sql);
    }

    public function get_total_packages($search = '', $status = null,$type='')
    {
        $db = get_db();
        $sql = "SELECT COUNT(*) as total FROM " . $this->table;

        $conditions = [];
        if (!empty($search)) {
            $escaped = $db->escape_like_str($search);
            $conditions[] = "(package_name LIKE '%" . $escaped . "%' OR subject_name LIKE '%" . $escaped . "%')";
        }
        if ($status === 0 || $status === 1) {
            $conditions[] = "status = " . (int)$status;
        }
           if ($type !== '') {

    if ($type === 'offline') {
        $conditions[] = "(LOWER(TRIM(`type`)) = 'offline' OR `type` = '' OR `type` IS NULL)";
    } else {
        $type_value = strtolower(trim($type));
        $conditions[] = "LOWER(TRIM(`type`)) = '".$type_value."'";
    }

}

        

       if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $result = $db->query($sql);
        $row = $db->get_row($result);

        return isset($row['total']) ? (int)$row['total'] : 0;
    }

    public function get_package($id)
    {
        $db = get_db();
        $sql = "SELECT * FROM " . $this->table . " WHERE id=" . (int)$id;
        $result = $db->query($sql);
        return $db->get_row($result);
    }

    public function enable_package($id)
    {
        return $this->update_package($id, ['status' => 1]);
    }

    public function disable_package($id)
    {
        return $this->update_package($id, ['status' => 0]);
    }

    public function update_package($id, $data)
    {
        $db = get_db();

        if (isset($data['start_date']) && $data['start_date'] === '') {
            $data['start_date'] = NULL;
        }
        if (isset($data['end_date']) && $data['end_date'] === '') {
            $data['end_date'] = NULL;
        }

        return $db->update($this->table, $data, ['id' => (int)$id]);
    }

    public function delete_package($id)
    {
        $db = get_db();
        return $db->delete($this->table, ['id' => (int)$id]);
    }

    public function get_status_stats()
    {
        $db = get_db();
        $stats = [];

        $result = $db->query("SELECT COUNT(*) as total FROM " . $this->table);
        $row = $db->get_row($result);
        $stats['total'] = isset($row['total']) ? $row['total'] : 0;

        $result = $db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE status=1");
        $row = $db->get_row($result);
        $stats['active'] = isset($row['total']) ? $row['total'] : 0;

        $result = $db->query("SELECT COUNT(*) as total FROM " . $this->table . " WHERE status=0");
        $row = $db->get_row($result);
        $stats['inactive'] = isset($row['total']) ? $row['total'] : 0;

        return $stats;
    }
}