<?php
require 'config/db.php';
require 'includes/auth.php';
require_admin();
if (isset($_GET['delete'])) { $pdo->prepare('DELETE FROM referrals WHERE id=?')->execute([$_GET['delete']]); header('Location: referrals.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $policy = $pdo->prepare('SELECT client_id FROM policies WHERE id = ?');
    $policy->execute([$_POST['policy_id']]);
    $policyRow = $policy->fetch();
    if ($policyRow) {
        $stmt = $pdo->prepare('INSERT INTO referrals (client_id, policy_id, clinic_id, doctor_name, service_name, referral_date, status, comment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$policyRow['client_id'], $_POST['policy_id'], $_POST['clinic_id'], $_POST['doctor_name'], $_POST['service_name'], $_POST['referral_date'], $_POST['status'], $_POST['comment']]);
        $message = 'Направление успешно создано';
    }
}
$policies = $pdo->query("SELECT p.id, p.policy_number, c.full_name FROM policies p JOIN clients c ON c.id=p.client_id WHERE p.status='active' ORDER BY p.id DESC")->fetchAll();
$clinics = $pdo->query('SELECT id, name FROM clinics ORDER BY name')->fetchAll();
$referrals = $pdo->query('SELECT r.*, c.full_name, p.policy_number, cl.name AS clinic_name FROM referrals r JOIN clients c ON c.id=r.client_id JOIN policies p ON p.id=r.policy_id JOIN clinics cl ON cl.id=r.clinic_id ORDER BY r.id DESC')->fetchAll();
include 'includes/header.php';
?>
<section class="page-hero container"><div class="page-title"><h1>Направления в ЛПУ</h1><p>Формирование направлений клиентов в медицинские учреждения по активным договорам ДМС.</p></div></section>
<section class="container">
<?php if (!empty($message)): ?><div class="alert"><?= e($message) ?></div><?php endif; ?>
<form method="post" class="form"><h2>Создать направление</h2><div class="form-row">
<div><label>Активный договор</label><select name="policy_id" required><?php foreach ($policies as $p): ?><option value="<?= $p['id'] ?>"><?= e($p['policy_number'] . ' — ' . $p['full_name']) ?></option><?php endforeach; ?></select></div>
<div><label>ЛПУ</label><select name="clinic_id" required><?php foreach ($clinics as $clinic): ?><option value="<?= $clinic['id'] ?>"><?= e($clinic['name']) ?></option><?php endforeach; ?></select></div>
<div><label>Врач</label><input name="doctor_name" placeholder="Терапевт"></div>
<div><label>Медицинская услуга</label><input name="service_name" required placeholder="Консультация, обследование"></div>
<div><label>Дата направления</label><input type="date" name="referral_date" required></div>
<div><label>Статус</label><select name="status"><option value="new">Новое</option><option value="sent">Отправлено</option><option value="completed">Выполнено</option><option value="cancelled">Отменено</option></select></div>
</div><label>Комментарий</label><textarea name="comment" placeholder="Дополнительная информация для клиники"></textarea><br><button class="btn" type="submit">Сформировать направление</button></form>
<div class="table-wrap"><table class="table"><tr><th>ID</th><th>Клиент</th><th>Договор</th><th>ЛПУ</th><th>Врач</th><th>Услуга</th><th>Дата</th><th>Статус</th><th></th></tr>
<?php foreach ($referrals as $r): ?><tr><td><?= $r['id'] ?></td><td><?= e($r['full_name']) ?></td><td><?= e($r['policy_number']) ?></td><td><?= e($r['clinic_name']) ?></td><td><?= e($r['doctor_name']) ?></td><td><?= e($r['service_name']) ?></td><td><?= e($r['referral_date']) ?></td><td><span class="status <?= e($r['status']) ?>"><?= e(status_label($r['status'])) ?></span></td><td><a class="btn danger" onclick="return confirm('Удалить направление?')" href="?delete=<?= $r['id'] ?>">Удалить</a></td></tr><?php endforeach; ?>
<?php if (!$referrals): ?><tr><td colspan="9" class="empty">Направления пока не сформированы</td></tr><?php endif; ?></table></div></section>
<?php include 'includes/footer.php'; ?>
