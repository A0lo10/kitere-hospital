<?php
require '../db.php';
require '../csrf.php';

// Validate CSRF token
if (!csrf_validate($_POST['csrf'])) {
    die('Invalid CSRF token');
}

// Get patient ID from form
$pid = $_POST['patient_id'];

// Insert medical record
$stmt = $conn->prepare("
    INSERT INTO medical_records (patient_id, visit_date, diagnosis, treatment, doctor) 
    VALUES (?,?,?,?,?)
");
$stmt->bind_param(
    'issss',
    $pid,
    $_POST['visit_date'],
    $_POST['diagnosis'],
    $_POST['treatment'],
    $_POST['doctor']
);

// Execute and check for errors
if (!$stmt->execute()) {
    die("Error: " . $stmt->error);
}
    header("Location: patient_medical_list.php?success=1");
    exit;