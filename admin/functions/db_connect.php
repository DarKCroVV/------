<?php
$servername = "localhost";
$username = "adminroot";
$password = "rE9gM2vF5o";
$dbname = "main_site";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Проблемы с соединением к БД: " . $conn->connect_error);
}
?>