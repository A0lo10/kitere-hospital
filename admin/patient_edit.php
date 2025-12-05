<?php
require '../db.php';

// Get patient ID
$id = $_GET['id'] ?? null;

if (!$id) {
    die("Patient ID missing");
}

// Fetch patient details
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

if (!$patient) {
    die("Patient not found");
}

// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $stmt = $conn->prepare("UPDATE patients SET name=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $email, $phone, $id);

    if ($stmt->execute()) {
        $message = "Patient updated successfully!";
        // Refresh patient data
        $patient['name'] = $name;
        $patient['email'] = $email;
        $patient['phone'] = $phone;
    } else {
        $message = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Patient - Kitere Health System</title>
<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7f9;
    padding: 20px;
}
form {
    max-width: 500px;
    margin: 0 auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}
h2 { text-align: center; color: #333; }
label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #555;
}
input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%;
    padding: 12px 15px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 8px;
    font-size: 14px;
    box-sizing: border-box;
}
input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus {
    border-color: #007bff;
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
}
button:hover {
    background-color: #0056b3;
}
p.message {
    text-align: center;
    color: green;
    font-weight: 500;
}
</style>
</head>
<body>

<h2>Edit Patient Details</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($patient['name']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($patient['email']); ?>" required>

    <label>Phone</label>
    <input type="tel" name="phone" value="<?= htmlspecialchars($patient['phone']); ?>" required>

    <button type="submit">Update Patient</button>
</form>

</body>
</html>
