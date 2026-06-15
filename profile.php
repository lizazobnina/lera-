<?php
require 'config/db.php';
require 'includes/auth.php';
require_client();
$stmt = $pdo->prepare('SELECT * FROM client_users WHERE id=?');
$stmt->execute([$_SESSION['client_user_id']]);
$user = $stmt->fetch();
$stmt = $pdo->prepare('SELECT a.*, s.name service_name, s.price FROM service_applications a JOIN services s ON s.id=a.service_id WHERE a.user_id=? ORDER BY a.id DESC');
$stmt->execute([$_SESSION['client_user_id']]);
$apps = $stmt->fetchAll();
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Личный кабинет</h1><p>Здравствуйте, <?= e($user['full_name']) ?>. Здесь отображаются ваши заявки на услуги ДМС.</p></div></section>
<section class="container">
    <div class="section-title"><h2>Мои заявки</h2><a class="btn" href="services.php">Выбрать услугу</a></div>
    <div class="table-wrap"><table class="table"><tr><th>Услуга</th><th>Стоимость</th><th>Комментарий</th><th>Статус</th><th>Дата</th></tr>
    <?php foreach($apps as $a): ?><tr><td><?= e($a['service_name']) ?></td><td><?= number_format($a['price'],0,',',' ') ?> ₽</td><td><?= e($a['comment']) ?></td><td><span class="status <?= e($a['status']) ?>"><?= e(status_label($a['status'])) ?></span></td><td><?= e($a['created_at']) ?></td></tr><?php endforeach; ?>
    <?php if(!$apps): ?><tr><td colspan="5" class="empty">Вы пока не отправляли заявки</td></tr><?php endif; ?>
    </table></div>
</section>
<?php include 'includes/footer.php'; ?>
