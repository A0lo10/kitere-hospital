<?php
session_start();
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Kitere Health System - Home</title>
<style>
    /* Make body black and center everything */
body {
    background-color: #0e1c6bff;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    font-family: Arial, sans-serif;
}

/* Card container */
.home-card {
    text-align: center;
    padding: 50px 60px;
    border-radius: 20px;
    background-color: #111; /* Slightly lighter than black for contrast */
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
}

/* Welcome message */
.welcome-message {
    font-size: 3rem; /* Make it big */
    font-weight: bold;
    color: #00cfff; /* Sky blue */
    margin-bottom: 40px;
}

/* Buttons */
.home-buttons .btn {
    display: inline-block;
    padding: 15px 30px;
    margin: 10px;
    border-radius: 12px;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: bold;
    color: #fff;
    background-color: #00cfff; /* Sky blue */
    transition: background-color 0.3s, transform 0.2s;
}

.home-buttons .btn:hover {
    background-color: #009ecf; /* Slightly darker blue on hover */
    transform: scale(1.05); /* Slight grow on hover */
}

</style>
</head>
<body>
<div class="home-container">
    <div class="home-card">
        <!-- Welcome message scrolling -->
        <div class="welcome-message">
            Welcome to Kitere Hospital
        </div><br><br>

        <!-- Buttons -->
        <div class="home-buttons">
            <a class="btn" href="patient/patient_login.php">Patient</a>
            <a class="btn" href="admin/admin_login.php">Admin</a>
        </div>
    </div>
</div>
</body>
</html>
