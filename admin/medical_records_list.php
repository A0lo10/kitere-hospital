<?php
require '../db.php';

// Fetch all medical records with patient names
$result = $conn->query("
    SELECT mr.id, p.name AS patient_name, mr.diagnosis, mr.treatment, mr.visit_date
    FROM medical_records mr
    JOIN patients p ON mr.patient_id = p.id
    ORDER BY mr.visit_date DESC
");
?>

<h2>All Medical Records</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Diagnosis</th>
        <th>Treatment</th>
        <th>Date</th>
        <th>Action</th>
    </tr>

   <?php while($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['patient_name']; ?></td>
    <td><?= $row['diagnosis']; ?></td>
    <td><?= $row['treatment']; ?></td>
    <td><?= $row['visit_date']; ?></td>
    <td>
        <a href="medical_records_edit.php?id=<?= $row['id']; ?>">Edit</a> |
        <a href="medical_records_delete.php?id=<?= $row['id']; ?>" 
           onclick="return confirm('Are you sure you want to delete this record?');">
           Delete
        </a>
    </td>
</tr>
<?php endwhile; ?>

</table>
