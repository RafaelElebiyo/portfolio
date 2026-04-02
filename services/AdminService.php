<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseService.php';

class AdminService extends BaseService
{
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOCKOUT_DURATION = 900; // 15 minutes

    public function findByEmail(string $email): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM admin_users WHERE email = :email AND is_active = 1',
            [':email' => $email]
        );
    }

    public function verifyPassword(string $email, string $password): ?array
    {
        $user = $this->findByEmail($email);
        
        if (!$user) {
            return null;
        }

        // Check if account is locked
        if ($user['locked_until'] && strtotime($user['locked_until']) > time()) {
            return null;
        }

        // Verify password
        if (password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return null;
    }

    public function recordFailedLogin(string $email): void
    {
        $sql = 'UPDATE admin_users 
                SET login_attempts = login_attempts + 1,
                    locked_until = CASE 
                        WHEN login_attempts + 1 >= :max_attempts 
                        THEN DATE_ADD(NOW(), INTERVAL :duration SECOND)
                        ELSE NULL 
                    END
                WHERE email = :email';
        
        $this->execute($sql, [
            ':email' => $email,
            ':max_attempts' => self::MAX_LOGIN_ATTEMPTS,
            ':duration' => self::LOCKOUT_DURATION
        ]);
    }

    public function recordSuccessfulLogin(int $userId, string $ipAddress, string $userAgent): string
    {
        // Reset failed attempts
        $this->execute(
            'UPDATE admin_users SET login_attempts = 0, locked_until = NULL, last_login = NOW() WHERE id = :id',
            [':id' => $userId]
        );

        // Create session
        $sessionId = $this->generateSessionId();
        $expiresAt = date('Y-m-d H:i:s', time() + 86400); // 24 hours

        $this->execute(
            'INSERT INTO admin_sessions (id, user_id, ip_address, user_agent, expires_at) 
             VALUES (:id, :user_id, :ip, :ua, :expires)',
            [
                ':id' => $sessionId,
                ':user_id' => $userId,
                ':ip' => $ipAddress,
                ':ua' => $userAgent,
                ':expires' => $expiresAt
            ]
        );

        return $sessionId;
    }

    public function validateSession(string $sessionId): ?array
    {
        $sql = 'SELECT s.*, u.email, u.full_name, u.role 
                FROM admin_sessions s
                JOIN admin_users u ON s.user_id = u.id
                WHERE s.id = :id AND s.expires_at > NOW() AND u.is_active = 1';
        
        return $this->fetchOne($sql, [':id' => $sessionId]);
    }

    public function destroySession(string $sessionId): void
    {
        $this->execute('DELETE FROM admin_sessions WHERE id = :id', [':id' => $sessionId]);
    }

    public function destroyUserSessions(int $userId): void
    {
        $this->execute('DELETE FROM admin_sessions WHERE user_id = :user_id', [':user_id' => $userId]);
    }

    public function cleanExpiredSessions(): int
    {
        return $this->execute('DELETE FROM admin_sessions WHERE expires_at <= NOW()');
    }

    public function isLocked(string $email): bool
    {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }
        return $user['locked_until'] && strtotime($user['locked_until']) > time();
    }

    public function getRemainingLockTime(string $email): int
    {
        $user = $this->findByEmail($email);
        if (!$user || !$user['locked_until']) {
            return 0;
        }
        $remaining = strtotime($user['locked_until']) - time();
        return $remaining > 0 ? $remaining : 0;
    }

    private function generateSessionId(): string
    {
        return bin2hex(random_bytes(32));
    }

    // Audit log methods
    public function logAction(?int $userId, string $action, ?string $tableName, ?int $recordId, ?string $oldValues, ?string $newValues, ?string $ipAddress): void
    {
        $this->execute(
            'INSERT INTO admin_audit_log (user_id, action, table_name, record_id, old_values, new_values, ip_address) 
             VALUES (:user_id, :action, :table, :record, :old, :new, :ip)',
            [
                ':user_id' => $userId,
                ':action' => $action,
                ':table' => $tableName,
                ':record' => $recordId,
                ':old' => $oldValues,
                ':new' => $newValues,
                ':ip' => $ipAddress
            ]
        );
    }

    public function getAuditLog(?int $limit = 50): array
    {
        return $this->fetchAll(
            'SELECT l.*, u.email as user_email, u.full_name as user_name
             FROM admin_audit_log l
             LEFT JOIN admin_users u ON l.user_id = u.id
             ORDER BY l.created_at DESC
             LIMIT :limit',
            [':limit' => $limit]
        );
    }
}