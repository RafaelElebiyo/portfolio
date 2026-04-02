<?php
declare(strict_types=1);

require_once __DIR__ . '/../services/AdminService.php';

class AdminAuthMiddleware
{
    private static ?AdminService $adminService = null;
    private static ?array $currentUser = null;

    public static function getAdminService(): AdminService
    {
        if (!self::$adminService) {
            self::$adminService = new AdminService();
        }
        return self::$adminService;
    }

    public static function requireAuth(): void
    {
        if (self::isAuthenticated()) {
            return;
        }

        self::redirectToLogin();
    }

    public static function isAuthenticated(): bool
    {
        if (self::$currentUser !== null) {
            return true;
        }

        $sessionId = $_COOKIE['admin_session'] ?? $_SESSION['admin_session'] ?? null;

        if (!$sessionId) {
            return false;
        }

        // Clean expired sessions periodically
        if (rand(1, 100) === 1) {
            self::getAdminService()->cleanExpiredSessions();
        }

        $session = self::getAdminService()->validateSession($sessionId);

        if (!$session) {
            self::clearSession();
            return false;
        }

        self::$currentUser = $session;
        return true;
    }

    public static function getCurrentUser(): ?array
    {
        if (!self::isAuthenticated()) {
            return null;
        }

        // self::$currentUser already has user_id from the session data
        return [
            'id' => (int) self::$currentUser['user_id'],
            'email' => self::$currentUser['email'],
            'full_name' => self::$currentUser['full_name'],
            'role' => self::$currentUser['role']
        ];
    }

    public static function requireRole(string $role): void
    {
        $user = self::getCurrentUser();

        if (!$user) {
            self::redirectToLogin();
        }

        $roles = ['superadmin' => 2, 'admin' => 1];
        $userLevel = $roles[$user['role']] ?? 0;
        $requiredLevel = $roles[$role] ?? 0;

        if ($userLevel < $requiredLevel) {
            http_response_code(403);
            exit('Access denied');
        }
    }

    public static function login(string $email, string $password): array
    {
        $adminService = self::getAdminService();

        // Check if account is locked
        if ($adminService->isLocked($email)) {
            $remaining = $adminService->getRemainingLockTime($email);
            return [
                'success' => false,
                'error' => 'account_locked',
                'message' => 'Account locked. Try again in ' . ceil($remaining / 60) . ' minutes.',
                'locked' => true,
                'remaining' => $remaining
            ];
        }

        $user = $adminService->verifyPassword($email, $password);

        if (!$user) {
            $adminService->recordFailedLogin($email);
            return [
                'success' => false,
                'error' => 'invalid_credentials',
                'message' => 'Invalid email or password.'
            ];
        }

        // Successful login
        $sessionId = $adminService->recordSuccessfulLogin(
            $user['id'],
            $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        );

        self::setSession($sessionId);
        self::$currentUser = $user;

        return ['success' => true, 'user' => $user];
    }

    public static function logout(): void
    {
        $sessionId = $_COOKIE['admin_session'] ?? $_SESSION['admin_session'] ?? null;

        if ($sessionId) {
            self::getAdminService()->destroySession($sessionId);
        }

        self::clearSession();
    }

    private static function setSession(string $sessionId): void
    {
        $options = [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Strict'
        ];

        setcookie('admin_session', $sessionId, $options);
        $_SESSION['admin_session'] = $sessionId;
    }

    private static function clearSession(): void
    {
        setcookie('admin_session', '', time() - 3600, '/');
        unset($_SESSION['admin_session']);
        self::$currentUser = null;
    }

    public static function redirectToLogin(): void
    {
        header('Location: login.php');
        exit;
    }

    public static function redirectToDashboard(): void
    {
        header('Location: dashboard.php');
        exit;
    }
}