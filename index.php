<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AbobaVPN</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js" defer></script>
    <style>
        .hero-bg {
            background: linear-gradient(to right, #4a90e2, #9013fe);
        }

        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
            z-index: 50;
        }

            .mobile-menu.open {
                transform: translateX(0);
            }
    </style>

</head>
<body class="bg-gray-50 text-gray-900">
<!-- Модальное окно Вход -->
<div id="loginModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Вход</h2>
        <form id="loginForm">
            <div id="loginError" class="text-red-500 mb-4 hidden"></div>
            <div class="mb-4">
                <label for="loginEmail" class="block text-gray-700">Email</label>
                <input type="email" id="loginEmail" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <div class="mb-4">
                <label for="loginPassword" class="block text-gray-700">Пароль</label>
                <input type="password" id="loginPassword" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full hover:bg-blue-700">Войти</button>
        </form>
    </div>
</div>

<!-- Модальное окно Регистрация -->
<div id="registerModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Регистрация</h2>
        <form id="registerForm">
            <div id="registerError" class="text-red-500 mb-4 hidden"></div>
            <div class="mb-4">
                <label for="registerEmail" class="block text-gray-700">Email</label>
                <input type="email" id="registerEmail" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <div class="mb-4">
                <label for="registerPassword" class="block text-gray-700">Пароль</label>
                <input type="password" id="registerPassword" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <div class="mb-4">
                <label for="registerConfirmPassword" class="block text-gray-700">Подтвердите пароль</label>
                <input type="password" id="registerConfirmPassword" name="confirm_password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full hover:bg-blue-700">Зарегистрироваться</button>
        </form>
    </div>
</div>
    <!-- Header -->
    <header class="bg-white shadow-md">
        <?php session_start(); ?>
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1> <a href="index.php" class="text-3xl font-extrabold text-blue-600">AbobaVPN</a></h1>
            <nav class="flex items-center space-x-6">
                <ul class="flex space-x-6">
                    <li><a href="#about" class="text-gray-700 hover:text-blue-600">О нас</a></li>
                    <li><a href="#features" class="text-gray-700 hover:text-blue-600">Особенности</a></li>
                    <li><a href="#testimonials" class="text-gray-700 hover:text-blue-600">Отзывы</a></li>
                    <li><a href="#pricing" class="text-gray-700 hover:text-blue-600">Тарифы</a></li>
                    <li><a href="#contact" class="text-gray-700 hover:text-blue-600">Контакты</a></li>
                </ul>

                <!-- Управление отображением кнопок на основе авторизации -->
                <div class="flex items-center space-x-4 ml-6">
                    <?php if (isset($_SESSION['user_email'])):?>
                        <!-- Кнопка с email пользователя и выпадающее меню -->
                        <div id="profileMenu" class="relative inline-block">
                            <button class="flex items-center text-gray-700 hover:text-blue-600" id="userEmailDisplay">
                                <?= htmlspecialchars($_SESSION['user_email']) ?>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden" id="dropdownMenu">
                                <a href="profile.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Профиль</a>
                                <a href="javascript:void(0);" onclick="openModal('balanceModal')" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Пополнение баланса</a>
                                <a href="logout.php" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Выход</a>
                            </div>
                        </div>
                    <?php else:  ?>
                        <!-- Кнопки Вход / Регистрация -->
                        <div id="authButtons" class="flex items-center space-x-4">
                            <a href="javascript:void(0);" onclick="openModal('loginModal')" class="text-gray-700 hover:text-blue-600">Вход</a>
                            <span class="text-gray-700">/</span>
                            <a href="javascript:void(0);" onclick="openModal('registerModal')" class="text-gray-700 hover:text-blue-600">Регистрация</a>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-bg text-white h-screen flex flex-col justify-center items-center text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-5xl font-bold mb-6 leading-tight">Защитите свои данные с AbobaVPN</h2>
            <p class="text-xl mb-8">
                Присоединяйтесь к тысячам пользователей, которые доверяют нашей платформе для безопасного и анонимного доступа в интернет.
            </p>
            <a href="#contact" class="bg-white text-blue-600 py-3 px-6 rounded-full font-semibold hover:bg-gray-100 transition duration-300">Начать использовать</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-16">
        <div class="container md:flex block mx-auto px-6">
            <div class="md:w-1/2 pr-4">
                <h2 class="text-4xl font-bold mb-8 text-gray-900">Почему выбирают наш VPN?</h2>
                <p class="text-lg mb-6 text-gray-700">
                    Наш VPN помогает пользователям из России и стран СНГ сохранять свободу и анонимность в интернете. В условиях растущей интернет-цензуры и контроля, VPN становится важным инструментом для доступа к информации без ограничений. Наша платформа предлагает надёжную защиту от блокировок, позволяет анонимно просматривать веб-сайты и использовать интернет без страха быть отслеженным. Выбирая наш VPN, вы получаете быстрый, безопасный и стабильный доступ к любым ресурсам, где бы вы ни находились.
                </p>
            </div>
            <div class="md:w-1/2">
                <img src="https://via.placeholder.com/800x500" alt="About Us" class="mx-auto rounded-lg shadow-lg" />
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="bg-gray-100 py-16">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-12 text-gray-900">Особенности нашего VPN</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-2xl transition duration-300">
                    <img src="https://via.placeholder.com/100x100" alt="Анонимность" class="mx-auto rounded-lg shadow-lg my-16" />
                    <h3 class="text-2xl font-semibold mb-4 text-blue-600">Анонимность</h3>
                    <p class="text-gray-600">
                        Ваш IP-адрес остаётся скрытым, что гарантирует полную приватность при работе в интернете.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-2xl transition duration-300">
                    <img src="https://via.placeholder.com/100x100" alt="Высокая скорость" class="mx-auto rounded-lg shadow-lg my-16" />
                    <h3 class="text-2xl font-semibold mb-4 text-blue-600">Высокая скорость</h3>
                    <p class="text-gray-600">
                        Наш VPN-сервис оптимизирован для максимальной скорости, чтобы вы могли стримить видео, загружать файлы и пользоваться интернетом без замедлений.
                    </p>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg hover:shadow-2xl transition duration-300">
                    <img src="https://via.placeholder.com/100x100" alt="Доступ к заблокированным ресурсам" class="mx-auto rounded-lg shadow-lg my-16" />
                    <h3 class="text-2xl font-semibold mb-4 text-blue-600">Доступ к заблокированным ресурсам</h3>
                    <p class="text-gray-600">
                        Наш сервис обеспечивает доступ к сайтам и сервисам, которые могут быть заблокированы в вашей стране.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- Информационное окно -->
    <div id="infoModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full text-center">
            <h2 class="text-2xl font-bold text-blue-600 mb-4">AbobaHelp</h2>
            <p id="infoModalMessage" class="text-gray-700 mb-4"></p>
            <button onclick="closeInfoModal()" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">Закрыть</button>
        </div>
    </div>
    <!-- Pricing Section -->
    <section id="pricing" class="py-16">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl font-bold mb-12 text-gray-900">Тарифные планы VPN</h2>
            <div class="flex flex-col md:flex-row justify-center items-center space-y-8 md:space-y-0 md:space-x-8">
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 flex flex-col justify-between h-full border border-gray-200">
                    <div class="text-center">
                        <img src="https://via.placeholder.com/130" alt="Базовый план" class="w-16 h-16 mx-auto mb-4 rounded-full" />
                        <h3 class="text-2xl font-bold mb-4 text-blue-700">

                            <h3 class="text-2xl font-bold mb-4 text-blue-700">Базовый план</h3>
                            <p class="text-gray-800 text-lg mb-4">299 ₽/месяц</p>
                            <p class="text-gray-600 mb-6">
                                Идеально подходит для простого и безопасного серфинга в интернете. Подключение до 5 устройств, 10 серверов в 5 странах.
                            </p>
                    </div>
                    <ul class="text-left text-gray-600 mb-6 space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Подключение 5 устройств
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            10 серверов в 5 странах
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Безлимитный трафик
                        </li>
                    </ul>
                    <a href="javascript:void(0);" onclick="selectPlan(1)" class="bg-blue-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-blue-700">Выбрать план</a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 flex flex-col justify-between h-full border border-gray-200">
                    <div class="text-center">
                        <img src="https://via.placeholder.com/130" alt="Продвинутый план" class="w-16 h-16 mx-auto mb-4 rounded-full" />
                        <h3 class="text-2xl font-bold mb-4 text-blue-700">Продвинутый план</h3>
                        <p class="text-gray-800 text-lg mb-4">599 ₽/месяц</p>
                        <p class="text-gray-600 mb-6">
                            Для пользователей, которые хотят больше. Подключение до 10 устройств, 50 серверов в 20 странах, приоритетная поддержка.
                        </p>
                    </div>
                    <ul class="text-left text-gray-600 mb-6 space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Подключение 10 устройств
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            50 серверов в 20 странах
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Приоритетная поддержка
                        </li>
                    </ul>
                    <a href="javascript:void(0);" onclick="selectPlan(2)" class="bg-blue-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-blue-700">Выбрать план</a>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-2xl transition duration-300 flex flex-col justify-between h-full border border-gray-200">
                    <div class="text-center">
                        <img src="https://via.placeholder.com/130" alt="Корпоративный план" class="w-16 h-16 mx-auto mb-4 rounded-full" />
                        <h3 class="text-2xl font-bold mb-4 text-blue-700">Корпоративный план</h3>
                        <p class="text-gray-800 text-lg mb-4">1499 ₽/месяц</p>
                        <p class="text-gray-600 mb-6">
                            Максимальная защита и безлимитные возможности. Неограниченное количество устройств, доступ к 100 серверам, выделенный IP.
                        </p>
                    </div>
                    <ul class="text-left text-gray-600 mb-6 space-y-2">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Неограниченное количество устройств
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Доступ к 100 серверам по всему миру
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Выделенный IP-адрес
                        </li>
                    </ul>
                    <a href="javascript:void(0);" onclick="selectPlan(3)" class="bg-blue-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-blue-700">Выбрать план</a>

                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="bg-gray-50 py-16">
        <div class="container mx-auto px-6 flex justify-center">
            <div class="flex w-full max-w-4xl shadow-lg rounded-lg overflow-hidden">
                <form action="#" method="POST" class="bg-white p-8 w-full lg:w-1/3">
                    <h2 class="text-4xl font-bold mb-8 text-gray-900">Возникли проблемы? Свяжись с нами</h2>
                    <div class="mb-6">
                        <label for="name" class="block text-gray-700 mb-2">Имя</label>
                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required />
                    </div>
                    <div class="mb-6">
                        <label for="email" class="block text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required />
                    </div>
                    <div class="mb-6">
                        <label for="message" class="block text-gray-700 mb-2">Сообщение</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required></textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-blue-700 transition duration-300 w-full">Отправить</button>
                </form>

                <div class="hidden lg:block w-2/3">
                    <img src="https://via.placeholder.com/400" alt="Связаться с нами" class="w-full h-full object-cover rounded-r-lg" />
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-blue-600 text-white py-6">
        <div class="container mx-auto text-center">
            <p>&copy; 2024 AbobaVPN. Все права защищены.</p>
        </div>
    </footer>
<!-- Модальное окно для пополнения баланса -->
<div id="balanceModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden" onclick="closeOnClickOutside(event, 'balanceModalContent')">
    <div id="balanceModalContent" class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Пополнение баланса</h2>
        <form id="balanceForm">
            <label for="balanceAmount" class="block text-gray-700">Сумма</label>
            <input type="number" id="balanceAmount" name="amount" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600" required>
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg w-full mt-4 hover:bg-blue-700">Пополнить</button>
        </form>
    </div>
</div>

<script>
    function closeOnClickOutside(event, contentId) {
        const content = document.getElementById(contentId);
        if (event.target === content || content.contains(event.target)) {
            return; // Клик произошел внутри окна, ничего не делаем
        }
        closeModal('balanceModal'); // Закрываем модальное окно, если клик вне его содержимого
    }
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
                alert(data);
                closeModal('balanceModal');
            })
            .catch(error => console.error('Ошибка:', error));
    };

    window.onclick = function(event) {
        if (event.target.classList.contains('bg-black')) {
            closeModal('loginModal');
            closeModal('registerModal');
        }
    };

    // AJAX для формы входа с обновлением интерфейса
        document.getElementById('loginForm').onsubmit = function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('login.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data.includes('успешно')) {
                    // Скрыть модальное окно и обновить интерфейс
                    closeModal('loginModal');
                    document.getElementById('authButtons').classList.add('hidden');
                    document.getElementById('profileMenu').classList.remove('hidden');
                    document.getElementById('userEmailDisplay').innerText = formData.get('email');
                } else {
                    document.getElementById('loginError').innerText = data;
                    document.getElementById('loginError').classList.remove('hidden');
                }
            })
            .catch(error => console.error('Ошибка:', error));
    };


    // AJAX для формы регистрации
    document.getElementById('registerForm').onsubmit = function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                if (data.includes('успешно')) {
                    alert('Регистрация успешна!');
                    closeModal('registerModal');
                } else {
                    document.getElementById('registerError').innerText = data;
                    document.getElementById('registerError').classList.remove('hidden');
                }
            })
            .catch(error => console.error('Ошибка:', error));
    };

    // Открытие и закрытие выпадающего меню при клике на email
    document.getElementById('userEmailDisplay').addEventListener('click', function (event) {
        event.stopPropagation(); // Остановка всплытия события для предотвращения закрытия при клике на email
        const dropdownMenu = document.getElementById('dropdownMenu');
        dropdownMenu.classList.toggle('hidden'); // Переключение видимости меню
    });

    // Закрытие меню при клике вне его области
    window.addEventListener('click', function(event) {
        const dropdownMenu = document.getElementById('dropdownMenu');
        if (!dropdownMenu.contains(event.target) && !document.getElementById('userEmailDisplay').contains(event.target)) {
            dropdownMenu.classList.add('hidden'); // Закрываем меню
        }
    });
    document.addEventListener('DOMContentLoaded', function () {
        const userEmail = '<?php echo $_SESSION["user_email"] ?? ""; ?>';
        const authButtons = document.getElementById('authButtons');
        const profileMenu = document.getElementById('profileMenu');

        if (userEmail) {
            console.log("User is authenticated:", userEmail); // Проверка
            authButtons.classList.add('hidden');
            profileMenu.classList.remove('hidden');
            document.getElementById('userEmailDisplay').innerText = userEmail;
        } else {
            authButtons.classList.remove('hidden');
            profileMenu.classList.add('hidden');
        }
    });


    function selectPlan(planId, price) {
        const userEmail = '<?php echo $_SESSION["user_email"] ?? ""; ?>';
        if (!userEmail) {
            alert("Сначала авторизуйтесь, чтобы купить VPN-план.");
            openModal('loginModal');
        } else {
            openModal('balanceModal');
            document.getElementById('balanceAmount').value = price;
        }
    }
    // Функция для открытия информационного окна с динамическим сообщением
    function openInfoModal(message) {
        document.getElementById('infoModalMessage').innerText = message;
        document.getElementById('infoModal').classList.remove('hidden');
    }

    // Функция для закрытия информационного окна
    function closeInfoModal() {
        document.getElementById('infoModal').classList.add('hidden');
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

    function selectPlan(planId) {
        fetch('by_plan.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `plan_id=${planId}`
        })
            .then(response => response.text())
            .then(data => {
                if (data.includes('успешно')) {
                    openInfoModal('План успешно куплен');
                } else if (data.includes('Недостаточно средств')) {
                    openInfoModal(data); // Показываем сообщение об ошибке
                    openModal('balanceModal'); // Открываем окно пополнения
                } else {
                    openInfoModal(data); // Показываем другие сообщения об ошибке
                }
            })
            .catch(error => console.error('Ошибка:', error));
    }

</script>
</body>
</html>