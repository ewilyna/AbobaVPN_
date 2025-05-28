<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :password)");
    $stmt->execute(['email' => $email, 'password' => $password]);

    echo 'Регистрация прошла успешно';
}
?>
