<?php
declare(strict_types=1);

require_once __DIR__ . '/Database.php';

abstract class BaseService
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Execute a prepared statement and return all rows.
     * @param string $sql   SQL with :named placeholders
     * @param array  $params Associative array of bindings
     */
    protected function fetchAll(string $sql, array $params = []): array
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('DB fetchAll error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return [];
        }
    }

    /**
     * Execute a prepared statement and return a single row.
     */
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch();
            return $row !== false ? $row : null;
        } catch (PDOException $e) {
            error_log('DB fetchOne error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return null;
        }
    }

    /**
     * Execute a write query and return affected rows.
     */
    protected function execute(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            error_log('DB execute error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return 0;
        }
    }
}
