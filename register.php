<?php
require 'config/db.php';
require 'includes/auth.php';

if (is_client()) { header('Location: profile.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $birth_date = $_POST['birth_date'] ?: null;
    $password = $_POST['password'] ?? '';

    if ($full_name === '' || $phone === '' || $email === '' || strlen($password) < 4) {
        $error = 'Заполните все обязательные поля. Пароль должен быть не короче 4 символов.';
    } else {
        try {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO client_users (full_name, phone, email, password_hash, birth_date) VALUES (?,?,?,?,?)');
            $stmt->execute([$full_name, $phone, $email, $hash, $birth_date]);
            $_SESSION['client_user_id'] = $pdo->lastInsertId();
            $_SESSION['client_user_name'] = $full_name;
            header('Location: profile.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Пользователь с таким email уже зарегистрирован.';
        }
    }
}
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Регистрация клиента</h1><p>Создайте личный кабинет, чтобы выбирать услуги ДМС и отправлять заявки.</p></div></section>
<section class="container small-page">
<?php if (!empty($error)): ?><div class="alert danger-alert"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form login-form" autocomplete="off">
    <h2>Данные клиента</h2>
    <label>ФИО</label><input name="full_name" required>
    <label>Телефон</label><input name="phone" required>
    <label>Email</label><input type="email" name="email" required>
    <label>Дата рождения</label><input type="date" name="birth_date">
    <label>Пароль</label><input type="password" name="password" required autocomplete="new-password">
    <button class="btn login-btn" type="submit">Зарегистрироваться</button>
    <p class="center-link">Уже есть аккаунт? <a href="client_login.php">Войти</a></p>
</form>
</section>
<?php include 'includes/footer.php'; ?>
