<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

$error = '';
$email = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = Sanitizer::email($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = t('admin.login_error') ?? 'Please enter email and password.';
    } else {
        require_once __DIR__ . '/middleware/AdminAuthMiddleware.php';
        $result = AdminAuthMiddleware::login($email, $password);

        if ($result['success']) {
            header('Location: admin/dashboard.php');
            exit;
        } else {
            $error = $result['message'];
            
            // If account is locked, show remaining time
            if (isset($result['locked']) && $result['locked']) {
                $error .= ' (' . ceil($result['remaining'] / 60) . ' min remaining)';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="<?= current_lang() ?>" data-theme="dark">
<head>
    <?php include __DIR__ . '/includes/head.php'; ?>
    <link href="assets/css/main.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root { --header-h: 0px; --nav-h: 0px; }
        body { 
            background: var(--bg-alt); 
            color: var(--text);
            font-family: var(--font-body);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            width: 100%;
            max-width: 420px;
            padding: var(--space-6);
        }
        .login-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            padding: var(--space-8);
            box-shadow: var(--shadow-lg);
        }
        .login-header {
            text-align: center;
            margin-bottom: var(--space-8);
        }
        .login-logo {
            width: 64px;
            height: 64px;
            background: var(--accent);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto var(--space-4);
            font-size: var(--text-2xl);
            color: var(--text-inverse);
        }
        .login-title {
            font-family: var(--font-display);
            font-size: var(--text-2xl);
            font-weight: 700;
            color: var(--text);
            margin: 0;
        }
        .login-subtitle {
            color: var(--text-muted);
            font-size: var(--text-sm);
            margin-top: var(--space-2);
        }
        .form-group {
            margin-bottom: var(--space-5);
        }
        .form-label {
            display: block;
            font-size: var(--text-sm);
            font-weight: 500;
            color: var(--text-secondary);
            margin-bottom: var(--space-2);
        }
        .form-input {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            color: var(--text);
            font-size: var(--text-base);
            transition: all var(--t-fast) var(--ease);
        }
        .form-input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-subtle);
        }
        .btn-login {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            background: var(--accent);
            color: var(--text-inverse);
            border: none;
            border-radius: var(--radius-md);
            font-size: var(--text-base);
            font-weight: 600;
            cursor: pointer;
            transition: all var(--t-fast) var(--ease);
        }
        .btn-login:hover {
            background: var(--accent-dark);
            transform: translateY(-1px);
        }
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid var(--error);
            color: var(--error);
            padding: var(--space-3) var(--space-4);
            border-radius: var(--radius-md);
            font-size: var(--text-sm);
            margin-bottom: var(--space-5);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }
        .form-footer {
            text-align: center;
            margin-top: var(--space-5);
            font-size: var(--text-sm);
            color: var(--text-muted);
        }
        .form-footer a {
            color: var(--accent);
            text-decoration: none;
        }
        .form-footer a:hover {
            text-decoration: underline;
        }
        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid var(--text-inverse);
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin-right: var(--space-2);
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h1 class="login-title"><?= t('admin.login_title') ?? 'Admin Panel' ?></h1>
                <p class="login-subtitle"><?= t('admin.login_subtitle') ?? 'Sign in to manage your portfolio' ?></p>
            </div>

            <?php if ($error): ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= Sanitizer::output($error) ?>
            </div>
            <?php endif; ?>

            <form id="login-form" method="POST" action="login.php">
                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="fa-solid fa-envelope me-2"></i><?= t('admin.email') ?? 'Email' ?>
                    </label>
                    <input type="email" id="email" name="email" class="form-input" 
                           value="<?= Sanitizer::output($email) ?>" required autocomplete="email"
                           placeholder="admin@example.com">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fa-solid fa-lock me-2"></i><?= t('admin.password') ?? 'Password' ?>
                    </label>
                    <input type="password" id="password" name="password" class="form-input" 
                           required autocomplete="current-password"
                           placeholder="••••••••">
                </div>

                <button type="submit" class="btn-login" id="login-btn">
                    <span class="btn-text"><i class="fa-solid fa-sign-in-alt me-2"></i><?= t('admin.sign_in') ?? 'Sign In' ?></span>
                </button>
            </form>

            <div class="form-footer">
                <a href="index.php"><i class="fa-solid fa-arrow-left me-1"></i><?= t('admin.back_to_site') ?? 'Back to site' ?></a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            const btn = document.getElementById('login-btn');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading-spinner"></span><span class="btn-text"><?= t('admin.logging_in') ?? 'Signing in...' ?></span>';
        });
    </script>
</body>
</html>