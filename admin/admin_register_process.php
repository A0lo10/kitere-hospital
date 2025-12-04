<?php
session_start();
require '../db.php';
require '../csrf.php';
if(!csrf_validate($_POST['csrf'])) die('Invalid CSRF');
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
if(!$username || !$password){ $_SESSION['error']='Fill all fields'; header('Location: admin_register.php'); exit; }
$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare('INSERT INTO admin (username,password) VALUES (?,?)');
$stmt->bind_param('ss',$username,$hash);
$stmt->execute();
$_SESSION['notice']='Admin registered';
exit;