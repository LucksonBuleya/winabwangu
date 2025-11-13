<?php
session_start();
include __DIR__ . '/db_connect.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
// Booth may be optional for admins; read raw, validate later
$booth_id = isset($_POST['booth_id']) && $_POST['booth_id'] !== '' ? (int)$_POST['booth_id'] : null;

// Validate basic inputs
if (empty($username) || empty($password)) {
    header('Location: login.php?error=Please enter username and password');
    exit();
}

// Check user credentials
$stmt = $conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: login.php?error=Invalid username or password');
    exit();
}

$user = $result->fetch_assoc();

// Verify password
if (!password_verify($password, $user['password'])) {
    header('Location: login.php?error=Invalid username or password');
    exit();
}

// Verify booth exists
// If manager, booth is required and must exist; if admin, booth is optional
if ($user['role'] === 'manager') {
    if ($booth_id === null) {
        header('Location: login.php?error=Please select your booth');
        exit();
    }
    $booth_check = $conn->prepare("SELECT Booth_id, BoothName FROM booths WHERE Booth_id = ?");
    $booth_check->bind_param("i", $booth_id);
    $booth_check->execute();
    $booth_result = $booth_check->get_result();
    if ($booth_result->num_rows === 0) {
        header('Location: login.php?error=Invalid booth selected');
        exit();
    }
    $booth = $booth_result->fetch_assoc();
}

// Store session data
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
if ($user['role'] === 'manager') {
    $_SESSION['booth_id'] = $booth_id;
    $_SESSION['booth_name'] = $booth['BoothName'];
} else {
    unset($_SESSION['booth_id'], $_SESSION['booth_name']);
}

// Redirect based on role - both admin and manager go to dashboard
if ($user['role'] === 'admin' || $user['role'] === 'manager') {
    header('Location: dashboard.php');
} else {
    header('Location: transactions.php');
}
exit();
?>
