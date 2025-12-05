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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Medical Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #2196F3; /* Blue header */
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e0f7fa;
        }

        td {
            color: #555;
        }

        a.button {
            display: inline-block;
            padding: 6px 12px;
            background-color: #2196F3;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: 0.3s;
            font-size: 13px;
        }

        a.button.delete {
            background-color: #f44336;
        }

        a.button:hover {
            opacity: 0.9;
        }

        /* Responsive table */
        @media screen and (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<h2>All Medical Records</h2>

<table>
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
            <a href="medical_records_edit.php?id=<?= $row['id']; ?>" class="button">Edit</a>
            <a href="medical_record_delete.php?id=<?= $row['id']; ?>" 
               onclick="return confirm('Are you sure you want to delete this record?');" 
               class="button delete">
               Delete
            </a>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

</body>
</html>
