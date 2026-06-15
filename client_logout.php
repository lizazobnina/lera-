<?php
require 'includes/auth.php';
unset($_SESSION['client_user_id'], $_SESSION['client_user_name']);
header('Location: index.php');
exit;
?>
