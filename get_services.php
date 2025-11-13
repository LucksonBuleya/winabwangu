<?php
include __DIR__ . '/db_connect.php';

$boothID = isset($_GET['boothID']) ? (int)$_GET['boothID'] : 0;

// Return services available for a given booth via boothservices mapping.
if ($boothID > 0) {
  $sql = "SELECT s.Service_id, s.Service_name, s.Revenue_per_kwacha
          FROM services s
          INNER JOIN boothservices bs ON bs.Service_id = s.Service_id
          WHERE bs.Booth_id = $boothID";
} else {
  $sql = "SELECT Service_id, Service_name, Revenue_per_kwacha FROM services";
}

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
