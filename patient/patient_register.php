<?php
session_start();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Patient Register</title>
<style>
    /* Body styling */
body {
    background-color: #09205fff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    font-family: Arial, sans-serif;
    color: #fff;
}

/* Container card */
.container {
    background-color: #111;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    width: 400px;
}

/* Heading */
.container h2 {
    text-align: center;
    font-size: 2.5rem;
    color: #00cfff;
    margin-bottom: 30px;
}

/* Form rows */
.form-row {
    margin-bottom: 20px;
}

/* Labels */
.form-row label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #ccc;
}

/* Inputs and select */
input, select {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #555;
    background-color: #222;
    color: #fff;
    font-size: 1rem;
}

/* Placeholder color */
input::placeholder {
    color: #aaa;
}

/* Button */
.btn {
    display: inline-block;
    padding: 12px 25px;
    margin-top: 10px;
    border-radius: 12px;
    background-color: #00cfff;
    color: #fff;
    font-weight: bold;
    font-size: 1.1rem;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn:hover {
    background-color: #009ecf;
    transform: scale(1.05);
}

/* Link next to button */
a {
    color: #00cfff;
    text-decoration: none;
    margin-left: 10px;
}

a:hover {
    text-decoration: underline;
}

</style>
</head>
<body>
<div class="container">
<h2>Patient Register</h2>
<form action="patient_register_process.php" method="post">
<div class="form-row"><label>National ID</label><input name="national_id" required></div><br><br>
<div class="form-row"><label>Full Name</label><input name="name" required></div><br><br>
<div class="tel-row"><label>Phone Number</label><input name="tel" required></div><br><br>
<div class="form-row"><label>Date of Birth</label><input type="date" name="dob" required></div><br><br>
<div class="form-row"><label>Village</label><input name="village"></div><br><br>
<div class="form-row"><label>Gender</label><br><br>
<select name="gender"><option>Male</option><option>Female</option><option>Other</option></select><br><br>
</div>
<div class="form-row"><label>Email</label><input type="email" name="email"></div><br><br>
<div class="form-row"><label>Password</label><input type="password" name="password" required></div><br><br>
<div><button class="btn" type="submit">Register</button> <a href="patient_login.php">Already have an account?</a></div>
</form>
</div>
</body>
</html>