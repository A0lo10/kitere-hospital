<?php
require '../db.php';
require '../csrf.php';

// Fetch all patients for the dropdown
$patients = $conn->query("SELECT id, name FROM patients ORDER BY name ASC");
?>

<form action="patient_medical_record_add_process.php" method="POST">

    <label>Select Patient:</label>
    <select name="patient_id" required>
        <option value="">-- Select Patient --</option>
        <?php while ($p = $patients->fetch_assoc()): ?>
            <option value="<?= $p['id']; ?>"><?= $p['name']; ?></option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label>Visit Date:</label>
    <input type="date" name="visit_date" required>
    <br><br>

    <label>Diagnosis:</label>
    <input type="text" name="diagnosis" required>
    <br><br>

    <label>Treatment:</label>
    <input type="text" name="treatment" required>
    <br><br>

    <label>Doctor:</label>
    <input type="text" name="doctor" required>
    <br><br>

    <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">

    <button type="submit">Save Medical Record</button>
</form>
