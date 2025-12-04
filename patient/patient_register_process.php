<?php
session_start();
require '../db.php';

$national_id = trim($_POST['national_id'] ?? '');
$name = trim($_POST['name'] ?? '');
$dob = $_POST['dob'] ?? null;
$village = trim($_POST['village'] ?? null);
$gender = $_POST['gender'] ?? 'Male';
$email = trim($_POST['email'] ?? null);
$password = $_POST['password'] ?? '';

if (!$national_id || !$name || !$dob || !$password) {
    $_SESSION['error'] = 'Please fill required fields.';
    header('Location: patient_register.php');
    exit;
}

// check unique national id
$stmt = $conn->prepare('SELECT id FROM patients WHERE national_id = ?');
$stmt->bind_param('s', $national_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'National ID already registered.';
    header('Location: patient_register.php');
    exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO patients (national_id,name,dob,village,gender,email,password) VALUES (?,?,?,?,?,?,?)');
$stmt->bind_param('sssssss', $national_id, $name, $dob, $village, $gender, $email, $hash);

if ($stmt->execute()) {
    $_SESSION['notice'] = 'Registration successful. Please login.';
    header('Location: patient_login.php');
} else {
    $_SESSION['error'] = 'Registration failed: ' . $conn->error;
    header('Location: patient_register.php');
}
exit;
