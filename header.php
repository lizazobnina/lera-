<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/auth.php';
$current = basename($_SERVER['PHP_SELF']);

$clientNav = [
    'index.php' => 'Главная',
    'services.php' => 'Услуги ДМС',
    'application.php' => 'Оставить заявку',
    'about.php' => 'О компании'
];

$adminNav = [
    'admin.php' => 'Админ-панель',
    'applications.php' => 'Заявки',
    'clients.php' => 'Клиенты',
    'policies.php' => 'Договоры',
    'clinics.php' => 'ЛПУ',
    'referrals.php' => 'Направления'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>МедГарант — ДМС</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
    <div class="container header-wrap">
        <a href="index.php" class="brand">
            <span class="brand-icon">✚</span>
            <span><b>МедГарант</b><small>добровольное медицинское страхование</small></span>
        </a>
        <nav class="menu">
            <?php if (is_admin()): ?>
                <?php foreach ($adminNav as $file => $title): ?>
                    <a class="admin-link <?= $current === $file ? 'active' : '' ?>" href="<?= $file ?>"><?= $title ?></a>
                <?php endforeach; ?>
                <a class="admin-link" href="logout.php">Выйти</a>
            <?php else: ?>
                <?php foreach ($clientNav as $file => $title): ?>
                    <a class="<?= $current === $file ? 'active' : '' ?>" href="<?= $file ?>"><?= $title ?></a>
                <?php endforeach; ?>
                <a class="admin-entry <?= $current === 'login.php' ? 'active' : '' ?>" href="login.php">Админ-панель</a>
                <?php if (is_client()): ?>
                    <a class="client-link <?= $current === 'profile.php' ? 'active' : '' ?>" href="profile.php">Личный кабинет</a>
                    <a class="client-link" href="client_logout.php">Выйти</a>
                <?php else: ?>
                    <a class="client-link <?= $current === 'client_login.php' ? 'active' : '' ?>" href="client_login.php">Вход</a>
                    <a class="client-link <?= $current === 'register.php' ? 'active' : '' ?>" href="register.php">Регистрация</a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
