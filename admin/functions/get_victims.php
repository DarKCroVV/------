<?php
include 'db_connect.php';

$sql = "SELECT * FROM victims";
$result = $conn->query($sql);

$victims = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $victims[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($victims);

$conn->close();
?>