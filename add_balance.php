<?php
session_start();
require 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_email'])) {
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

    if ($amount > 0) {
        $stmt = $pdo->prepare("UPDATE users SET balance = balance + :amount WHERE email = :email");
        $stmt->execute(['amount' => $amount, 'email' => $_SESSION['user_email']]);
        echo "Баланс пополнен на {$amount} ₽"; // Сообщение для модального окна
    } else {
        echo 'Ошибка: сумма пополнения должна быть больше 0.'; // Сообщение об ошибке
    }
} else {
    echo 'Ошибка: пользователь не авторизован или запрос неверный.';
}
?>
