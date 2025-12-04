<?php
// db.php - database connection used by all files
$DB_HOST = '127.0.0.1:3308';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'health_system';


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
die('Database connection failed: ' . $conn->connect_error);
}
// set charset
$conn->set_charset('utf8mb4');


// helper: simple function to escape output
function e($str){ return htmlspecialchars($str, ENT_QUOTES|ENT_SUBSTITUTE, 'UTF-8'); }