<?php
session_start();
define('BASE_ADMIN', '.');
require_once '../fonctions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$login    = trim($_POST['login']    ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($login) || empty($password)) {
    $_SESSION['login_erreur'] = 'Identifiant et mot de passe requis.';
    header('Location: login.php');
    exit;
}

// Vérification force brute (avant appel BDD)
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts']    = 0;
    $_SESSION['last_attempt_time'] = 0;
}
if ($_SESSION['login_attempts'] >= 3) {
    $reste = (15 * 60) - (time() - $_SESSION['last_attempt_time']);
    if ($reste > 0) {
        $_SESSION['login_erreur'] = 'Compte temporairement bloqué.';
        header('Location: login.php');
        exit;
    }
    $_SESSION['login_attempts'] = 0; // Délai écoulé
}

if (verifier_admin($login, $password)) {
    session_regenerate_id(true);
    header('Location: index.php');
    exit;
} else {
    $_SESSION['login_erreur'] = 'Identifiant ou mot de passe incorrect.';
    $_SESSION['login_saisie'] = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
    header('Location: login.php');
    exit;
}
