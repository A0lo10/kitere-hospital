<?php
require '../db.php';

$result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
?>

<h2>All Patients</h2>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Action</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['phone']; ?></td>
        <td>
            <a href="patient_edit.php?id=<?= $row['id']; ?>">Edit</a> |
            <a href="patient_delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Delete this patient?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
