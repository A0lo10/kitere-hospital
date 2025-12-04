<?php
require '../db.php';
require '../csrf.php';
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Admin Login - Kitere Health System</title>
<style>
    body {
    background-color: #172c86ff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    font-family: Arial, sans-serif;
    color: #fff;
}

/* Card container */
.login-card {
    background-color: #111;
    padding: 40px 50px;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    width: 400px;
    text-align: center;
}

/* Heading */
.login-card h2 {
    font-size: 2.5rem;
    color: #00cfff;
    margin-bottom: 30px;
}

/* Error message */
.error {
    background: #ffdddd;
    color: #a33;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 8px;
    text-align: center;
}

/* Form groups */
.form-group {
    margin-bottom: 20px;
    text-align: left;
}

/* Labels */
.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #ccc;
}

/* Inputs */
input {
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
    width: 100%;
    padding: 12px 0;
    border-radius: 12px;
    background-color: #00cfff;
    color: #fff;
    font-weight: bold;
    font-size: 1.1rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn:hover {
    background-color: #009ecf;
    transform: scale(1.05);
}

/* Link below form */
p a {
    color: #00cfff;
    text-decoration: none;
}

p a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<div class="login-container">
    <div class="login-card">
        <h2>Admin Login</h2>

        <?php if(!empty($_SESSION['error'])): ?>
            <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form method="post" action="admin_login_process.php">
            <input type="hidden" name="csrf" value="<?= csrf_token(); ?>">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>

            <button class="btn">Login</button>
        </form>
    </div>
</div>
</body>
</html>
