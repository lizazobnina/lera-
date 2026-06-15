<?php
require 'config/db.php';
require 'includes/auth.php';
require_admin();
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM policies WHERE id=?')->execute([$_GET['delete']]); header('Location: policies.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare('INSERT INTO policies (client_id, policy_number, program_name, start_date, end_date, price, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$_POST['client_id'], $_POST['policy_number'], $_POST['program_name'], $_POST['start_date'], $_POST['end_date'], $_POST['price'], $_POST['status']]);
    $message = 'Договор ДМС успешно оформлен';
}
$clients = $pdo->query('SELECT id, full_name FROM clients ORDER BY full_name')->fetchAll();
$policies = $pdo->query('SELECT p.*, c.full_name FROM policies p JOIN clients c ON c.id = p.client_id ORDER BY p.id DESC')->fetchAll();
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Договоры ДМС</h1><p>Оформление программ добровольного медицинского страхования и контроль сроков действия.</p></div></section>
<section class="container">
<?php if (!empty($message)): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
<form method="post" class="form"><h2>Оформить договор</h2><div class="form-row">
<div><label>Клиент</label><select name="client_id" required><?php foreach ($clients as $client): ?><option value="<?= $client['id'] ?>"><?= e($client['full_name']) ?></option><?php endforeach; ?></select></div>
<div><label>Номер договора</label><input name="policy_number" value="DMS-<?= date('Ymd-His') ?>" required></div>
<div><label>Программа</label><select name="program_name"><option>Базовая ДМС</option><option>Расширенная ДМС</option><option>Семейная ДМС</option><option>Премиум ДМС</option><option>Корпоративная ДМС</option></select></div>
<div><label>Дата начала</label><input type="date" name="start_date" required></div><div><label>Дата окончания</label><input type="date" name="end_date" required></div><div><label>Стоимость, ₽</label><input type="number" step="0.01" name="price" required></div>
<div><label>Статус</label><select name="status"><option value="draft">Черновик</option><option value="active">Активен</option><option value="expired">Истёк</option></select></div></div><br><button class="btn" type="submit">Сохранить договор</button></form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>Клиент</th><th>Номер</th><th>Программа</th><th>Период</th><th>Стоимость</th><th>Статус</th><th></th></tr>
<?php foreach ($policies as $p): ?><tr><td><?= $p['id'] ?></td><td><?= e($p['full_name']) ?></td><td><?= e($p['policy_number']) ?></td><td><?= e($p['program_name']) ?></td><td><?= e($p['start_date']) ?> — <?= e($p['end_date']) ?></td><td><?= e(number_format($p['price'], 2, ',', ' ')) ?> ₽</td><td><span class="status <?= e($p['status']) ?>"><?= e(status_label($p['status'])) ?></span></td><td><a class="btn danger" onclick="return confirm('Удалить договор?')" href="?delete=<?= $p['id'] ?>">Удалить</a></td></tr><?php endforeach; ?>
<?php if (!$policies): ?><tr><td colspan="8" class="empty">Договоры пока не оформлены</td></tr><?php endif; ?></table></div></section>
<?php include 'includes/footer.php'; ?>
