<?php
require '../db.php';

if (!isset($_GET['id'])) {
    die("Invalid request.");
}

$id = intval($_GET['id']);

// Delete the medical record
$stmt = $conn->prepare("DELETE FROM medical_records WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Redirect back to the medical records list
header("Location: medical_records_list.php");
exit;
?>
