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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Profile</title>
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
            max-width: 500px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="email"]:focus {
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

        .message {
            margin-bottom: 20px;
            color: green;
        }
    </style>
</head>
<body>

<h2>Edit Profile</h2>

<?php if(!empty($message)) echo "<p class='message'>$message</p>"; ?>

<form method="post">
    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($patient['name']); ?>" required>

    <label>Email</label>
    <input type="email" name="email" value="<?= htmlspecialchars($patient['email']); ?>" required>

<label>Phone Number</label>
<input type="tel" name="phone" value="<?= htmlspecialchars($patient['phone']); ?>" required>

    <button type="submit">Update Profile</button>
</form>

</body>
</html>
