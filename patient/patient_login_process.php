<?php
session_start();
require '../db.php';
require '../csrf.php';

if (!validate_csrf($_POST['csrf'])) {
    $_SESSION['error'] = "Invalid request!";
    header("Location: patient_login.php");
    exit;
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM patients WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $patient = $result->fetch_assoc();

    if (password_verify($password, $patient['password'])) {
        $_SESSION['patient_id'] = $patient['id'];
        $_SESSION['patient_name'] = $patient['name'];

        header("Location: patient_dashboard.php");
        exit;
    }
}

$_SESSION['error'] = "Invalid email or password!";
header("Location: patient_login.php");
exit;
