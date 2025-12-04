<?php
session_start();

// Block access if not logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pat<?php
session_start();

// Block access if not logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard - Kitere Health System</title>
    <style>
        /* Page background */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #000; /* black background */
            color: #fff;
        }

        /* Center the dashboard card */
        .dashboard-card {
            background-color: #116dcfff; /* dark blue card */
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.5);
        }

        /* Welcome heading */
        .dashboard-card h2 {
            text-align: center;
            color: #fff;
            margin-top: 0;
        }

        /* Menu styling */
        .menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 20px 0;
            flex-wrap: wrap;
        }

        .menu a {
            background-color: #1b2a47;
            padding: 10px 20px;
            border-radius: 8px;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .menu a:hover {
            background-color: #05f0d0ff;
        }

        /* Iframe styling */
        iframe {
            width: 100%;
            height: 60vh;
            border: none;
            background-color: #b06bc5ff;
            border-radius: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 600px) {
            .menu {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h2>Welcome, <?= $_SESSION['patient_name']; ?></h2>

    <div class="menu">
        <a href="patient_profile_edit.php" target="main">Edit Profile</a>
        <a href="medical_record.php" target="main">Medical Records</a>
        <a href="logout.php">Logout</a>
    </div>

    <iframe name="main"></iframe>
</div>

</body>
</html>
