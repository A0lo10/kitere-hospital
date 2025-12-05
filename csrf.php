<?php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Generate CSRF token
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

// Validate CSRF token
function validate_csrf($token) {
    return isset($_SESSION['csrf']) && $token && hash_equals($_SESSION['csrf'], $token);
}
