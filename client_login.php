<?php
require 'config/db.php';
require 'includes/auth.php';

if (is_client()) { header('Location: profile.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM client_users WHERE email=? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['client_user_id'] = $user['id'];
        $_SESSION['client_user_name'] = $user['full_name'];
        header('Location: profile.php');
        exit;
    } else {
        $error = 'Неверный email или пароль';
    }
}
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Вход клиента</h1><p>Войдите, чтобы оформить заявку на выбранную услугу ДМС.</p></div></section>
<section class="container small-page">
<?php if (!empty($error)): ?><div class="alert danger-alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form login-form" autocomplete="off">
    <h2>Авторизация клиента</h2>
    <label>Email</label><input type="email" name="email" required autocomplete="username">
    <label>Пароль</label><input type="password" name="password" required autocomplete="current-password">
    <button class="btn login-btn" type="submit">Войти</button>
    <p class="center-link">Нет аккаунта? <a href="register.php">Зарегистрироваться</a></p>
</form>
</section>
<?php include 'includes/footer.php'; ?>
