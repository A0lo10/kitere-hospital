<?php
require '../db.php';
require '../csrf.php';
?>
<h2>Admin Registration</h2>
<form method="post" action="admin_register_process.php">
<input type="hidden" name="csrf" value="<?= csrf_token(); ?>">
<div><label>Username</label><input type="text" name="username" required></div>
<div><label>Password</label><input type="password" name="password" required></div>
<button class="btn">Register</button>
</form>
?>