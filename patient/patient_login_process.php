<?php
// Start session only if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require '../db.php';
require '../csrf.php';

// Validate CSRF token
if (!isset($_POST['csrf']) || !validate_csrf($_POST['csrf'])) {
    $_SESSION['error'] = "Invalid request!";
    header("Location: patient_login.php");
    exit;
}

// Sanitize input
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Please enter email and password!";
    header("Location: patient_login.php");
    exit;
}

// Fetch patient from database
$stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $patient = $result->fetch_assoc();

    // Verify password
    if (password_verify($password, $patient['password'])) {
        $_SESSION['patient_id'] = $patient['id'];
        $_SESSION['patient_name'] = $patient['name'];

        // Redirect to dashboard
        header("Location: patient_dashboard.php");
        exit;
    }
}

// Invalid credentials
$_SESSION['error'] = "Invalid email or password!";
header("Location: patient_login.php");
exit;
