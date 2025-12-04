<?php
session_start();

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function validate_csrf($token) {
    return isset($_SESSION['csrf']) && $token && hash_equals($_SESSION['csrf'], $token);
}
