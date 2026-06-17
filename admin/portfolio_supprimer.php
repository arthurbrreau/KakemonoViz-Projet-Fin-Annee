<?php
session_start();
define('BASE_ADMIN', '.');
require_once '../fonctions.php';
admin_requis();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: portfolio.php');
    exit;
}

$realisation = get_une_realisation($id);
if (!$realisation) {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Réalisation introuvable.'];
    header('Location: portfolio.php');
    exit;
}

$ok = supprimer_realisation($id);
if ($ok) {
    $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Réalisation supprimée.'];
} else {
    $_SESSION['flash'] = ['type' => 'error', 'msg' => 'Erreur lors de la suppression.'];
}

header('Location: portfolio.php');
exit;
