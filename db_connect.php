<?php
$host = "localhost";
$user = "root";   // default for XAMPP
$pass = "";       // leave empty unless you set a MySQL password
$db   = "wina_wangu_db";   // exact name of your database

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
