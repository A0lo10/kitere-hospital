<?php
require '../db.php';

$result = $conn->query("SELECT * FROM patients ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Patients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #a4e6dbff;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #ffffffff;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
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
            background-color: #4CAF50;
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

        /* Responsive table for small screens */
        @media screen and (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>

<h2>All Patients</h2>

<table>
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
            <a href="patient_edit.php?id=<?= $row['id']; ?>" class="button">Edit</a>
            <a href="patient_delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Delete this patient?')" class="button delete">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

</body>
</html>
