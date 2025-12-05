<?php
require '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);

// Delete the patient
$stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Redirect back to the list
header("Location: patient_list.php");
exit;
?>
