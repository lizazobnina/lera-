<?php
require 'config/db.php';
require 'includes/auth.php';
require_admin();
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM clients WHERE id=?')->execute([$_GET['delete']]); header('Location: clients.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO clients (full_name, birth_date, phone, email, passport, address) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$_POST['full_name'], $_POST['birth_date'], $_POST['phone'], $_POST['email'], $_POST['passport'], $_POST['address']]);
    $message = 'Клиент успешно добавлен';
}
$clients = $pdo->query('SELECT * FROM clients ORDER BY id DESC')->fetchAll();
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Клиенты</h1><p>Регистрация физических лиц для оформления договоров добровольного медицинского страхования.</p></div></section>
<section class="container">
<?php if (!empty($message)): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
<form method="post" class="form">
  <h2>Добавить клиента</h2>
  <div class="form-row">
    <div><label>ФИО</label><input name="full_name" placeholder="Иванов Иван Иванович" required></div>
    <div><label>Дата рождения</label><input type="date" name="birth_date" required></div>
    <div><label>Телефон</label><input name="phone" placeholder="+7 900 000-00-00" required></div>
    <div><label>Email</label><input type="email" name="email" placeholder="client@mail.ru"></div>
    <div><label>Паспорт</label><input name="passport" placeholder="4512 123456"></div>
    <div><label>Адрес</label><input name="address" placeholder="Город, улица, дом"></div>
  </div><br><button class="btn" type="submit">Сохранить клиента</button>
</form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>ФИО</th><th>Дата рождения</th><th>Телефон</th><th>Email</th><th>Паспорт</th><th>Адрес</th><th></th></tr>
<?php foreach ($clients as $c): ?><tr><td><?= $c['id'] ?></td><td><?= e($c['full_name']) ?></td><td><?= e($c['birth_date']) ?></td><td><?= e($c['phone']) ?></td><td><?= e($c['email']) ?></td><td><?= e($c['passport']) ?></td><td><?= e($c['address']) ?></td><td><a class="btn danger" onclick="return confirm('Удалить клиента?')" href="?delete=<?= $c['id'] ?>">Удалить</a></td></tr><?php endforeach; ?>
<?php if (!$clients): ?><tr><td colspan="8" class="empty">Клиенты пока не добавлены</td></tr><?php endif; ?>
</table></div></section>
<?php include 'includes/footer.php'; ?>
