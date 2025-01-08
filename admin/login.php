<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Проверка учетных данных
    if ($username === 'your_username' && $password === 'your_password') {
        $_SESSION['loggedin'] = true;
        header("Location: admin_panel.html");
        exit;
    } else {
        echo "Неверные учетные данные";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
</head>
<body>
    <form method="post" action="">
        <label for="username">Имя пользователя:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Пароль:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Войти</button>
    </form>
</body>
</html>