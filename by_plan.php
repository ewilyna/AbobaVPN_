<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_email'])) {
    $planId = (int)$_POST['plan_id'];

    // Получаем информацию о плане
    $stmt = $pdo->prepare("SELECT price FROM vpn_plans WHERE plan_id = :plan_id");
    $stmt->execute(['plan_id' => $planId]);
    $plan = $stmt->fetch();

    // Получаем информацию о пользователе и его баланс
    $stmt = $pdo->prepare("SELECT user_id, balance FROM users WHERE email = :email");
    $stmt->execute(['email' => $_SESSION['user_email']]);
    $user = $stmt->fetch();

    if ($user && $plan) {
        if ($user['balance'] >= $plan['price']) {
            // Достаточно средств: списываем стоимость плана и добавляем подписку
            $pdo->beginTransaction();
            $pdo->prepare("UPDATE users SET balance = balance - :price WHERE user_id = :user_id")
                ->execute(['price' => $plan['price'], 'user_id' => $user['user_id']]);

            $pdo->prepare("INSERT INTO user_subscriptions (user_id, plan_id, end_date) 
                           VALUES (:user_id, :plan_id, CURRENT_DATE + interval '1 month')")
                ->execute(['user_id' => $user['user_id'], 'plan_id' => $planId]);
            $pdo->commit();

            echo 'План успешно куплен';
        } else {
            echo 'Недостаточно средств на балансе. Пожалуйста, пополните баланс.';
        }
    } else {
        echo 'Ошибка при получении информации о пользователе или плане.';
    }
} else {
    echo 'Ошибка: неверный запрос или пользователь не авторизован.';
}
?>
