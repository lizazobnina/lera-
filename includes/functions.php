<?php
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function status_label($status) {
    $labels = [
        'active' => 'Активен', 'expired' => 'Истёк', 'draft' => 'Черновик',
        'new' => 'Новая', 'sent' => 'Отправлено', 'completed' => 'Выполнено', 'cancelled' => 'Отменено', 'processing' => 'В обработке', 'approved' => 'Одобрена', 'rejected' => 'Отклонена'
    ];
    return $labels[$status] ?? $status;
}
?>
