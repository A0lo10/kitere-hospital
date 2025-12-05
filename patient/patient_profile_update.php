<?php
session_start();
require 'db.php';

if (empty($_SESSION['patient_id'])) {
    header('Location: patient_login.php');
    exit;
}

$id = intval($_POST['id'] ?? 0);

if ($id !== intval($_SESSION['patient_id'])) {
    $_SESSION['error'] = 'Invalid action';
    header('Location: patient_dashboard.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$dob = $_POST['dob'] ?? null;
$village = trim($_POST['village'] ?? null);
$gender = $_POST['gender'] ?? 'Male';
$email = trim($_POST['email'] ?? null);
$phone = trim($_POST['phone'] ?? null); // Added phone
$password = $_POST['password'] ?? '';

if (!$name || !$dob) {
    $_SESSION['error'] = 'Name and DOB required';
    header('Location: patient_profile_edit.php');
    exit;
}

// Update query
if ($password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare('UPDATE patients SET name=?, dob=?, village=?, gender=?, email=?, phone=?, password=? WHERE id=?');
    $stmt->bind_param('sssssssi', $name, $dob, $village, $gender, $email, $phone, $hash, $id);
} else {
    $stmt = $conn->prepare('UPDATE patients SET name=?, dob=?, village=?, gender=?, email=?, phone=? WHERE id=?');
    $stmt->bind_param('ssssssi', $name, $dob, $village, $gender, $email, $phone, $id);
}

if ($stmt->execute()) {
    $_SESSION['notice'] = 'Profile updated';
    header('Location: patient_dashboard.php');
} else {
    $_SESSION['error'] = 'Update failed: ' . $conn->error;
    header('Location: patient_profile_edit.php');
}
exit;
