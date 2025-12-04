<?php
session_start();
require '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

$result = $conn->query("SELECT * FROM medical_records WHERE patient_id = $patient_id ORDER BY date DESC");
?>

<h2>Your Medical Records</h2>

<?php if ($result->num_rows == 0): ?>
<p>No records found.</p>
<?php else: ?>
<table border="1" cellpadding="10">
    <tr>
        <th>Date</th>
        <th>Diagnosis</th>
        <th>Treatment</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['date']; ?></td>
        <td><?= $row['diagnosis']; ?></td>
        <td><?= $row['treatment']; ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>
