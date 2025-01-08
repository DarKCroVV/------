<?php
include 'admin/db_connect.php';

function scan_ports($ip) {
    $nm = new \Nmap\Nmap();
    $nm->scan([$ip], '1-1024');
    $open_ports = [];
    foreach ($nm[$ip] as $port => $port_info) {
        if ($port_info['state'] == 'open') {
            $open_ports[] = $port;
        }
    }
    return json_encode($open_ports);
}

function check_vulnerabilities($ip) {
    $nm = new \Nmap\Nmap();
    $nm->scan([$ip], '1-1024', '-sV --script vuln');
    $vulnerabilities = [];
    foreach ($nm[$ip] as $port => $port_info) {
        if (isset($port_info['scripts'])) {
            foreach ($port_info['scripts'] as $script) {
                if (strpos($script['id'], 'vuln') !== false) {
                    $vulnerabilities[] = $script['id'];
                }
            }
        }
    }
    return json_encode($vulnerabilities);
}

// Получаем IP-адрес из базы данных
$sql = "SELECT id, realIP FROM victims";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $ip = $row['realIP'];
        $openPorts = scan_ports($ip);
        $vulnerabilities = check_vulnerabilities($ip);

        $sql_update = "UPDATE victims SET openPorts = ?, vulnerabilities = ? WHERE id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("ssi", $openPorts, $vulnerabilities, $id);

        if ($stmt->execute()) {
            echo "Record updated successfully for IP: " . $ip . "\n";
        } else {
            echo "Error updating record for IP: " . $ip . "\n";
        }
    }
}

$stmt->close();
$conn->close();
?>