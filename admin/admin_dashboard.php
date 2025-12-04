<?php
session_start();

// Protect page
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Kitere Health System</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        /* Page background */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #000; /* black background */
            color: #fff;
        }

        /* Center dashboard card */
        .dashboard-card {
            background-color: #0d6ed6ff; /* dark blue card */
            max-width: 1000px;
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
            background-color: #14cce4ff;
        }

        /* Iframe styling */
        iframe {
            width: 100%;
            height: 70vh;
            border: none;
            background-color: #bbd5daff;
            border-radius: 8px;
        }

        /* Responsive adjustments */
        @media (max-width: 700px) {
            .menu {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h2>Welcome Aloyce, <?= $_SESSION['admin_name']; ?></h2>

    <div class="menu">
        <a href="patient_list.php" target="main">Patients</a>
        <a href="medical_records_list.php" target="main">Medical Records List</a>
        <a href="patient_medical_record_add.php" target="main">Add Medical Record</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- This loads your pages inside the dashboard -->
    <iframe name="main"></iframe>
</div>

</body>
</html>
