<?php
// Параметры подключения к базе данных
$host = 'localhost';
$dbname = 'vpn_website';
$user = 'postgres';
$password = 'lplp';

try {
    // Создание подключения к базе данных PostgreSQL
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    // Обработка ошибок подключения
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

