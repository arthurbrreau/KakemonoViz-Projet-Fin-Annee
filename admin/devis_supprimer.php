<?php
session_start();
define('BASE_ADMIN', '.');
require_once '../fonctions.php';
admin_requis();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: index.php');
    exit;
}

$devis = get_un_devis($id);
if (!$devis) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Devis introuvable.'];
    header('Location: index.php');
    exit;
}

$ok = supprimer_devis($id);
if ($ok) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Devis #' . $id . ' supprimé.'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de la suppression.'];
}

header('Location: index.php');
exit;
