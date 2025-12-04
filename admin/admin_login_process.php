<?php
session_start(); // Start session for CSRF and login

require '../db.php';
require '../csrf.php'; // Make sure csrf.php defines csrf_token() and validate_csrf()

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Check CSRF token
    if (!isset($_POST['csrf']) || !validate_csrf($_POST['csrf'])) {
        $_SESSION['error'] = "Invalid request!";
        header("Location: admin_login.php");
        exit;
    }

    // Get username and password from POST
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare and execute query to fetch admin
    $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $admin['password'])) {

            // Set session variables for admin
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];

            // Redirect to dashboard
            header("Location: admin_dashboard.php");
            exit;
        }
    }

    // If login failed
    $_SESSION['error'] = "Invalid username or password!";
    header("Location: admin_login.php");
    exit;
} else {
    // Block direct access
    $_SESSION['error'] = "Invalid request method!";
    header("Location: admin_login.php");
    exit;
}
