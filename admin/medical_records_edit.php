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
    $visit_date = $_POST['visit_date']; // FIXED

    // Update with correct column name
    $stmt = $conn->prepare("
        UPDATE medical_records 
        SET diagnosis=?, treatment=?, visit_date=? 
        WHERE id=?
    ");
    $stmt->bind_param("sssi", $diagnosis, $treatment, $visit_date, $id);

    if ($stmt->execute()) {
        $message = "Record updated successfully!";
        // Refresh record after update
        $record['diagnosis'] = $diagnosis;
        $record['treatment'] = $treatment;
        $record['visit_date'] = $visit_date;
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>
<style>
    /* Page background and font */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f4f7f9;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    /* Container card */
    form {
        background-color: #fff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 500px;
        box-sizing: border-box;
    }

    h2 {
        text-align: center;
        margin-bottom: 25px;
        color: #333;
    }

    label {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
        color: #555;
    }

    input[type="text"],
    input[type="date"],
    select {
        width: 100%;
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 14px;
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input[type="text"]:focus,
    input[type="date"]:focus,
    select:focus {
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0,123,255,0.3);
        outline: none;
    }

    button {
        width: 100%;
        padding: 14px;
        background-color: #007bff;
        color: #fff;
        font-size: 16px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
    }

    button:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    p {
        text-align: center;
        font-weight: 500;
    }

    p.success {
        color: #28a745;
    }

    /* Responsive */
    @media (max-width: 600px) {
        form {
            padding: 20px;
        }
    }
</style>

<h2>Edit Medical Record</h2>

<?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>

<form method="post">
    <label>Diagnosis</label><br>
    <input type="text" name="diagnosis" value="<?= $record['diagnosis']; ?>" required><br><br>

    <label>Treatment</label><br>
    <input type="text" name="treatment" value="<?= $record['treatment']; ?>" required><br><br>

    <label>Date</label><br>
    <input type="date" name="visit_date" value="<?= $record['visit_date']; ?>" required><br><br>

    <button type="submit">Update Record</button>
</form>
