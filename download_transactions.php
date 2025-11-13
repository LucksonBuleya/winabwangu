<?php
require __DIR__ . '/auth_check.php';
include __DIR__ . '/db_connect.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit('Unauthorized');
}

if (isset($_GET['all']) && $_SESSION['role'] === 'admin') {
    // Admin: Export all transactions with booth and user info
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="all_transactions_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Transaction ID', 'Booth', 'Service', 'Amount (ZMW)', 'Revenue/kwacha', 'Revenue Earned', 'Date']);

    $query = "
      SELECT t.Transaction_id, b.BoothName, s.Service_name, t.Transaction_amount, t.Revenue_per_kwacha, (t.Transaction_amount*t.Revenue_per_kwacha) AS Revenue, t.Date_created
      FROM transactions t
      JOIN booths b ON b.Booth_id = t.Booth_id
      JOIN services s ON s.Service_id = t.Service_id
      ORDER BY t.Date_created DESC
    ";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
      fputcsv($out, [
        $row['Transaction_id'],
        $row['BoothName'],
        $row['Service_name'],
        $row['Transaction_amount'],
        $row['Revenue_per_kwacha'],
        $row['Revenue'],
        $row['Date_created']
      ]);
    }
    fclose($out);
    exit();
}

if ($_SESSION['role'] === 'manager') {
    $booth_id = (int)$_SESSION['booth_id'];
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="transactions_booth_' . $booth_id . '_' . date('Ymd_His') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, ['Transaction ID', 'Service', 'Amount (ZMW)', 'Revenue/kwacha', 'Revenue Earned', 'Date']);

    $query = "
      SELECT t.Transaction_id, s.Service_name, t.Transaction_amount, t.Revenue_per_kwacha, (t.Transaction_amount*t.Revenue_per_kwacha) AS Revenue, t.Date_created
      FROM transactions t
      JOIN services s ON s.Service_id = t.Service_id
      WHERE t.Booth_id = ?
      ORDER BY t.Date_created DESC
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $booth_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      fputcsv($out, [
        $row['Transaction_id'],
        $row['Service_name'],
        $row['Transaction_amount'],
        $row['Revenue_per_kwacha'],
        $row['Revenue'],
        $row['Date_created']
      ]);
    }
    fclose($out);
    exit();
}

http_response_code(403);
echo 'Unauthorized.';
exit();
