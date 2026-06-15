<?php
require 'config/db.php';
require 'includes/auth.php';
require_client();

$service_id = isset($_GET['service_id']) ? (int)$_GET['service_id'] : 0;
$stmt = $pdo->prepare('SELECT * FROM client_users WHERE id=?');
$stmt->execute([$_SESSION['client_user_id']]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO service_applications (service_id, user_id, full_name, phone, email, birth_date, comment, status) VALUES (?,?,?,?,?,?,?,?)');
    $stmt->execute([
        $_POST['service_id'],
        $_SESSION['client_user_id'],
        $user['full_name'],
        $user['phone'],
        $user['email'],
        $user['birth_date'],
        $_POST['comment'],
        'new'
    ]);
    $message = 'Заявка отправлена. Администратор свяжется с вами для оформления договора.';
}

$services = $pdo->query('SELECT * FROM services WHERE is_active=1 ORDER BY price')->fetchAll();
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Заявка на оформление ДМС</h1><p>Выберите услугу, а данные клиента будут взяты из личного кабинета.</p></div></section>
<section class="container">
<?php if(!empty($message)): ?><div class="alert"><?= e($message) ?> <a href="profile.php">Перейти в личный кабинет</a></div><?php endif; ?>
<form method="post" class="form">
    <h2>Оформление заявки</h2>
    <div class="client-note"><b><?= e($user['full_name']) ?></b><br><?= e($user['phone']) ?> · <?= e($user['email']) ?></div>
    <div class="form-row single">
        <div><label>Выбранная услуга</label><select name="service_id" required><?php foreach($services as $s): ?><option value="<?= $s['id'] ?>" <?= $service_id === (int)$s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?> — <?= number_format($s['price'],0,',',' ') ?> ₽</option><?php endforeach; ?></select></div>
    </div>
    <label>Комментарий</label>
    <textarea name="comment" placeholder="Например: нужна консультация по программе, оформление для себя или семьи"></textarea>
    <br><button class="btn" type="submit">Отправить заявку</button>
</form>
</section>
<?php include 'includes/footer.php'; ?>
