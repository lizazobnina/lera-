<?php
require 'config/db.php';
require 'includes/auth.php';
require_admin();
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM clinics WHERE id=?')->execute([$_GET['delete']]); header('Location: clinics.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO clinics (name, address, phone, email, specialization) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$_POST['name'], $_POST['address'], $_POST['phone'], $_POST['email'], $_POST['specialization']]);
    $message = 'Лечебное учреждение добавлено';
}
$clinics = $pdo->query('SELECT * FROM clinics ORDER BY id DESC')->fetchAll();
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Лечебные учреждения</h1><p>Справочник клиник, медицинских центров и профильных специалистов.</p></div></section>
<section class="container">
<?php if (!empty($message)): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
<form method="post" class="form"><h2>Добавить ЛПУ</h2><div class="form-row">
<div><label>Название</label><input name="name" required></div><div><label>Адрес</label><input name="address" required></div><div><label>Телефон</label><input name="phone"></div><div><label>Email</label><input type="email" name="email"></div><div><label>Специализация</label><input name="specialization" placeholder="Терапия, диагностика"></div></div><br><button class="btn" type="submit">Сохранить ЛПУ</button></form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>Название</th><th>Адрес</th><th>Телефон</th><th>Email</th><th>Специализация</th><th></th></tr>
<?php foreach ($clinics as $clinic): ?><tr><td><?= $clinic['id'] ?></td><td><?= e($clinic['name']) ?></td><td><?= e($clinic['address']) ?></td><td><?= e($clinic['phone']) ?></td><td><?= e($clinic['email']) ?></td><td><?= e($clinic['specialization']) ?></td><td><a class="btn danger" onclick="return confirm('Удалить ЛПУ?')" href="?delete=<?= $clinic['id'] ?>">Удалить</a></td></tr><?php endforeach; ?>
<?php if (!$clinics): ?><tr><td colspan="7" class="empty">ЛПУ пока не добавлены</td></tr><?php endif; ?>
</table></div></section>
<?php include 'includes/footer.php'; ?>
