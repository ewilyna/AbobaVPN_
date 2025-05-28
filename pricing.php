<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $planId = $_POST['plan_id'];
    $userEmail = $_SESSION['user_email'];

    // Проверка наличия пользователя
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email");
    $stmt->execute(['email' => $userEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $pdo->prepare("INSERT INTO user_subscriptions (user_id, plan_id, end_date)
                               VALUES (:user_id, :plan_id, CURRENT_TIMESTAMP + interval '1 month' * duration_months)");
        $stmt->execute(['user_id' => $user['user_id'], 'plan_id' => $planId]);
        echo 'Подписка оформлена успешно';
    } else {
        echo 'Ошибка при оформлении подписки';
    }
}
?>

