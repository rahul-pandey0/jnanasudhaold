<?php
/**
 * CodeIgniter Database Class
 */

class CI_DB {
    protected $connection;
    protected $config;
    protected $last_query;

    public function __construct($config = array())
    {
        $this->config = $config;
        $this->connect();
    }

    protected function connect()
    {
        try {
            if ($this->config['dbdriver'] === 'mysqli') {
                $this->connection = new mysqli(
                    $this->config['hostname'],
                    $this->config['username'],
                    $this->config['password'],
                    $this->config['database']
                );

                if ($this->connection->connect_error) {
                    throw new Exception('Database Connection Error: ' . $this->connection->connect_error);
                }

                // Set charset
                if (isset($this->config['char_set'])) {
                    $this->connection->set_charset($this->config['char_set']);
                }
            }
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
    }

    public function query($sql, $params = array())
    {
        try {
            $this->last_query = $sql;

            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    $value = $this->connection->real_escape_string($value);
                    $sql = str_replace(':' . $key, "'" . $value . "'", $sql);
                }
            }

            $result = $this->connection->query($sql);

            if (!$result) {
                if ($this->config['db_debug']) {
                    show_error('Database Error: ' . $this->connection->error . ' | Query: ' . $sql);
                }
                return FALSE;
            }

            return $result;
        } catch (Exception $e) {
            show_error($e->getMessage());
        }
    }

    public function get($table, $where = array())
    {
        $sql = "SELECT * FROM " . $table;

        if (!empty($where)) {
            $sql .= " WHERE ";
            $conditions = array();
            foreach ($where as $key => $value) {
                $conditions[] = $key . " = '" . $this->connection->real_escape_string($value) . "'";
            }
            $sql .= implode(" AND ", $conditions);
        }

        return $this->query($sql);
    }

    public function insert($table, $data = array())
    {
        if (empty($data)) return FALSE;

        $keys = array_keys($data);
        $values = array_values($data);

        // Escape values
        $escaped_values = array();
        foreach ($values as $val) {
            $escaped_values[] = "'" . $this->connection->real_escape_string($val) . "'";
        }

        $sql = "INSERT INTO " . $table . " (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $escaped_values) . ")";

        if ($this->query($sql)) {
            return $this->connection->insert_id;
        }

        return FALSE;
    }

    public function update($table, $data = array(), $where = array())
    {
        if (empty($data) || empty($where)) return FALSE;

        $set = array();
        foreach ($data as $key => $value) {
            $set[] = $key . " = '" . $this->connection->real_escape_string($value) . "'";
        }

        $sql = "UPDATE " . $table . " SET " . implode(", ", $set);

        if (!empty($where)) {
            $sql .= " WHERE ";
            $conditions = array();
            foreach ($where as $key => $value) {
                $conditions[] = $key . " = '" . $this->connection->real_escape_string($value) . "'";
            }
            $sql .= implode(" AND ", $conditions);
        }

        return $this->query($sql);
    }

    public function delete($table, $where = array())
    {
        if (empty($where)) return FALSE;

        $sql = "DELETE FROM " . $table . " WHERE ";
        $conditions = array();
        foreach ($where as $key => $value) {
            $conditions[] = $key . " = '" . $this->connection->real_escape_string($value) . "'";
        }
        $sql .= implode(" AND ", $conditions);

        return $this->query($sql);
    }

    public function get_result($result_obj)
    {
        $results = array();
        if ($result_obj && $result_obj->num_rows > 0) {
            while ($row = $result_obj->fetch_assoc()) {
                $results[] = $row;
            }
        }
        return $results;
    }

    public function get_row($result_obj)
    {
        if ($result_obj && $result_obj->num_rows > 0) {
            return $result_obj->fetch_assoc();
        }
        return NULL;
    }

    public function escape($string)
    {
        if ($this->connection) {
            return $this->connection->real_escape_string($string);
        }
        return addslashes($string);
    }

    public function last_query()
    {
        return $this->last_query;
    }

    public function close()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    public function insert_id()
    {
        return $this->connection ? $this->connection->insert_id : 0;
    }

    public function affected_rows()
    {
        return $this->connection ? $this->connection->affected_rows : 0;
    }
}
