<?php
require_once __DIR__ . '/' . 'utils.class.php';
$t411 = new Utils;
$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? $_GET['id'] : $t411->home();

$t411->deleteSerie($id);
header('Location: /suivi/');
exit;
?>
