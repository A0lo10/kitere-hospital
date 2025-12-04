<?php
require '../db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Record ID missing");
}

// Fetch record
$stmt = $conn->prepare("SELECT * FROM medical_records WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();

if (!$record) {
    die("Record not found");
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $date = $_POST['date'];

    $stmt = $conn->prepare("UPDATE medical_records SET diagnosis=?, treatment=?, date=? WHERE id=?");
    $stmt->bind_param("sssi", $diagnosis, $treatment, $date, $id);
    if ($stmt->execute()) {
        $message = "Record updated successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<h2>Edit Medical Record</h2>

<?php if(!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>

<form method="post">
    <label>Diagnosis</label><br>
    <input type="text" name="diagnosis" value="<?= $record['diagnosis']; ?>" required><br><br>

    <label>Treatment</label><br>
    <input type="text" name="treatment" value="<?= $record['treatment']; ?>" required><br><br>

    <label>Date</label><br>
    <input type="date" name="date" value="<?= $record['date']; ?>" required><br><br>

    <button type="submit">Update Record</button>
</form>
