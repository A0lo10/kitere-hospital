<?php
session_start();
require '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

// Fetch patient records
$result = $conn->query("SELECT * FROM medical_records WHERE patient_id = $patient_id ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Medical Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
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

        /* Responsive table */
        @media screen and (max-width: 600px) {
            table {
                display: block;
                overflow-x: auto;
            }
        }

        p.no-records {
            color: #777;
            font-style: italic;
        }
    </style>
</head>
<body>

<h2>Your Medical Records</h2>

<?php if ($result->num_rows == 0): ?>
<p class="no-records">No records found.</p>
<?php else: ?>
<table>
    <tr>
        <th>Date</th>
        <th>Diagnosis</th>
        <th>Treatment</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['created_at']; ?></td>
        <td><?= htmlspecialchars($row['diagnosis']); ?></td>
        <td><?= htmlspecialchars($row['treatment']); ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php endif; ?>

</body>
</html>
