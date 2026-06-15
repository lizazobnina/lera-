<?php require 'config/db.php'; $services=$pdo->query('SELECT * FROM services WHERE is_active=1 ORDER BY price')->fetchAll(); include 'includes/header.php'; ?>
<section class="page-hero container"><div class="page-title"><h1>Выберите услугу ДМС</h1><p>На сайте доступно 9 услуг добровольного медицинского страхования. Выберите подходящую программу и отправьте заявку.</p></div></section>
<section class="section container"><div class="grid three">
<?php foreach($services as $s): ?><div class="card service-card"><div class="icon">🛡️</div><h3><?= e($s['name']) ?></h3><p><?= e($s['description']) ?></p><div class="price"><?= number_format($s['price'],0,',',' ') ?> ₽</div><a class="btn" href="application.php?service_id=<?= $s['id'] ?>">Выбрать услугу</a></div><?php endforeach; ?>
</div></section><?php include 'includes/footer.php'; ?>
