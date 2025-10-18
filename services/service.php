<?php
require_once 'connection.php';

abstract class BaseService {
    protected $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    protected function fetchAll($query) {
        $result = $this->db->query($query);
        $data = [];
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $result->free();
        }
        
        return $data;
    }

    protected function fetchOne($query) {
        $result = $this->db->query($query);
        return $result ? $result->fetch_assoc() : null;
    }
}
?>