<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_email'])) {
    header('Location: login.php');
    exit;
}

$email = $_SESSION['user_email'];

// Получаем информацию о пользователе и его баланс
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

// Проверка на наличие баланса (установка значения по умолчанию)
$balance = $user['balance'] ?? 0;

// Получаем список купленных VPN-планов
$plans = $pdo->prepare("SELECT vpn_plans.name, vpn_plans.price, user_subscriptions.start_date, user_subscriptions.end_date 
                        FROM user_subscriptions 
                        JOIN vpn_plans ON vpn_plans.plan_id = user_subscriptions.plan_id 
                        WHERE user_id = :user_id");
$plans->execute(['user_id' => $user['user_id']]);
$subscriptions = $plans->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет | AbobaVPN</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<!-- Шапка сайта -->
<header class="bg-white shadow-md">
    <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <h1> <a href="index.php" class="text-3xl font-extrabold text-blue-600">AbobaVPN</a></h1>
        <!-- Навигация -->
        <nav class="flex items-center space-x-6">
            <ul class="flex space-x-6">
                <li><a href="index.php#about" class="text-gray-700 hover:text-blue-600">О нас</a></li>
                <li><a href="index.php#features" class="text-gray-700 hover:text-blue-600">Особенности</a></li>
                <li><a href="index.php#testimonials" class="text-gray-700 hover:text-blue-600">Отзывы</a></li>
                <li><a href="index.php#pricing" class="text-gray-700 hover:text-blue-600">Тарифы</a></li>
                <li><a href="index.php#contact" class="text-gray-700 hover:text-blue-600">Контакты</a></li>
            </ul>

            <!-- Кнопка с почтой пользователя и выпадающее меню -->
            <div id="profileMenu" class="relative inline-block text-left">
                <button class="flex items-center text-gray-700 hover:text-blue-600" id="userEmailDisplay">
                    <?= htmlspecialchars($_SESSION['user_email'] ?? 'Пользователь') ?>
                </button>
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden" id="dropdownMenu">
                    <a href="index.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Главная страница</a>
                    <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Выход</a>
                </div>
            </div>
        </nav>
    </div>
</header>

<script>
    // Открытие и закрытие выпадающего меню при клике на почту
    document.getElementById('userEmailDisplay').addEventListener('click', function () {
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden');
    });

    // Закрытие меню при клике вне его области
    window.addEventListener('click', function(event) {
        const profileMenu = document.getElementById('profileMenu');
        const dropdownMenu = document.getElementById('dropdownMenu');
        if (!profileMenu.contains(event.target)) {
            dropdownMenu.classList.add('hidden');
        }
    });
</script>

<!-- Основной контент профиля -->
<div class="container mx-auto my-10 p-5 bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold text-blue-600 mb-6">Личный кабинет</h1>

    <div class="mb-4">
        <h2 class="text-xl font-semibold">Информация о пользователе</h2>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Баланс:</strong> <?= htmlspecialchars($balance) ?> ₽</p>
    </div>

    <div class="mb-6">
        <h2 class="text-xl font-semibold">Ваши подписки</h2>
        <?php if ($subscriptions): ?>
            <ul class="space-y-4">
                <?php foreach ($subscriptions as $plan): ?>
                    <li class="p-4 border border-gray-200 rounded-lg">
                        <h3 class="text-lg font-semibold"><?= htmlspecialchars($plan['name']) ?></h3>
                        <p>Цена: <?= htmlspecialchars($plan['price']) ?> ₽</p>
                        <p>Дата окончания: <?= date("d.m.Y", strtotime($plan['end_date'])) ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-600">Нет активных подписок.</p>
        <?php endif; ?>
    </div>

    <div class="flex justify-end">
        <a href="javascript:void(0);" onclick="openModal('balanceModal')" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700 transition duration-300">Пополнить баланс</a>
    </div>
</div>

<!-- Модальное окно для пополнения баланса -->
<div id="balanceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Пополнение баланса</h2>
        <form id="balanceForm">
            <label for="balanceAmount" class="block text-gray-700">Сумма</label>
            <input type="number" id="balanceAmount" name="amount" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full mt-4 hover:bg-blue-700">Пополнить</button>
        </form>
    </div>
</div>
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    document.getElementById('balanceForm').onsubmit = function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('add_balance.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                openInfoModal(data); // Выводим сообщение в модальном окне
                closeModal('balanceModal'); // Закрываем модальное окно пополнения баланса
                location.reload(); // Обновляем страницу для отображения нового баланса
            })
            .catch(error => {
                console.error('Ошибка:', error);
                openInfoModal('Произошла ошибка при попытке пополнения баланса. Пожалуйста, попробуйте еще раз.');
            });
    };

</script>
</body>
</html>
