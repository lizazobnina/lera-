<?php
require 'config/db.php';
require 'includes/auth.php';

if (is_admin()) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM admins WHERE login = ? LIMIT 1');
    $stmt->execute([$login]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        header('Location: admin.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}

include 'includes/header.php';
?>

<section class="page-hero container">
    <div class="page-title">
        <h1>Вход администрации</h1>
        <p>Доступ к заявкам, клиентам, договорам и направлениям.</p>
    </div>
</section>

<section class="container small-page">
    <?php if (!empty($error)): ?>
        <div class="alert danger-alert"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" class="form login-form" autocomplete="off">
        <h2>Авторизация</h2>

        <label for="login">Логин</label>
        <input id="login" name="login" type="text" required autocomplete="username">

        <label for="password">Пароль</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">

        <button class="btn login-btn" type="submit">Войти</button>
    </form>
</section>

<?php include 'includes/footer.php'; ?>
