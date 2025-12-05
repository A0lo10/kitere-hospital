<?php
require '../db.php';
require '../csrf.php';

// Fetch all patients for the dropdown
$patients = $conn->query("SELECT id, name FROM patients ORDER BY name ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Medical Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            max-width: 600px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="date"]:focus,
        select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Add Medical Record</h2>

<form action="patient_medical_record_add_process.php" method="POST">

    <label>Select Patient:</label>
    <select name="patient_id" required>
        <option value="">-- Select Patient --</option>
        <?php while ($p = $patients->fetch_assoc()): ?>
            <option value="<?= $p['id']; ?>"><?= $p['name']; ?></option>
        <?php endwhile; ?>
    </select>

    <label>Visit Date:</label>
    <input type="date" name="visit_date" required>

    <label>Diagnosis:</label>
    <input type="text" name="diagnosis" placeholder="Enter diagnosis" required>

    <label>Treatment:</label>
    <input type="text" name="treatment" placeholder="Enter treatment" required>

    <label>Doctor:</label>
    <input type="text" name="doctor" placeholder="Doctor's name" required>

    <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">

    <button type="submit">Save Medical Record</button>
</form>

</body>
</html>
