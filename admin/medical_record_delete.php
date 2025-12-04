<?php
require '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$id = $_GET['id'];

// Delete the record
$stmt = $conn->prepare("DELETE FROM patient_medical_record_list WHERE id = ?");
$stmt->bind_param("i", $id);

if (!$stmt->execute()) {
    die("Error deleting record: " . $stmt->error);
}

// Redirect back to list
header("Location: patient_medical_record_list.php?deleted=1");
exit;
