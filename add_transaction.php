<?php
include __DIR__ . '/db_connect.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: text/plain');

// Validate input
$boothId   = isset($_POST['boothID']) ? (int)$_POST['boothID'] : 0;
$serviceId = isset($_POST['serviceID']) ? (int)$_POST['serviceID'] : 0;
$amount    = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;

if ($boothId <= 0 || $serviceId <= 0 || $amount <= 0) {
    http_response_code(400);
    echo 'Invalid input.';
    exit;
}

// Fetch revenue per kwacha for the selected service
$stmt = $conn->prepare('SELECT Revenue_per_kwacha FROM services WHERE Service_id = ?');
$stmt->bind_param('i', $serviceId);
$stmt->execute();
$stmt->bind_result($revenuePerKwacha);
if (!$stmt->fetch()) {
    http_response_code(404);
    echo 'Service not found.';
    exit;
}
$stmt->close();

// Insert transaction
// Generate next Transaction_id like WB0000001
$res = $conn->query("SELECT Transaction_id FROM transactions ORDER BY Transaction_id DESC LIMIT 1");
$nextId = 'WB0000001';
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $num = (int)substr($row['Transaction_id'], 2);
    $nextId = 'WB' . str_pad((string)($num + 1), 7, '0', STR_PAD_LEFT);
}

$insert = $conn->prepare('INSERT INTO transactions (Transaction_id, Booth_id, Service_id, Transaction_amount, Revenue_per_kwacha) VALUES (?, ?, ?, ?, ?)');
$insert->bind_param('siidd', $nextId, $boothId, $serviceId, $amount, $revenuePerKwacha);
$insert->execute();
$insert->close();

echo 'Transaction saved successfully.';
?>


