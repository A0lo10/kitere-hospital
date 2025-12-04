<?php
session_start();
require '../db.php';

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit;
}

$patient_id = $_SESSION['patient_id'];

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE patients SET name=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $name, $email, $patient_id);
    $stmt->execute();
    $message = "Profile updated successfully!";
}

// Fetch patient info
$stmt = $conn->prepare("SELECT * FROM patients WHERE id=?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();
?>

<h2>Edit Profile</h2>

<?php if(!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>

<form method="post">
    <label>Name</label><br>
    <input type="text" name="name" value="<?= $patient['name']; ?>" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" value="<?= $patient['email']; ?>" required><br><br>

    <button type="submit">Update Profile</button>
</form>
