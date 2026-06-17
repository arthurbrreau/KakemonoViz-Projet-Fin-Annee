<?php
session_start();

// Déjà connecté → dashboard
if (!empty($_SESSION['admin_connecte'])) {
    header('Location: index.php');
    exit;
}

$erreur_blocage = false;
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 3) {
    $reste = (15 * 60) - (time() - ($_SESSION['last_attempt_time'] ?? 0));
    if ($reste > 0) {
        $erreur_blocage = ceil($reste / 60);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin — KakémonoViz</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

<div class="login-page">
    <div class="login-card">
        <div class="login-logo">
            <div class="brand">Kakémono<span>Viz</span></div>
            <p>Espace d'administration</p>
        </div>

        <?php if ($erreur_blocage): ?>
        <div class="alert alert-error">
            🔒 Trop de tentatives échouées. Réessayez dans <strong><?= $erreur_blocage ?> minute<?= $erreur_blocage > 1 ? 's' : '' ?></strong>.
        </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['login_erreur'])): ?>
        <div class="alert alert-error">
            ⚠ <?= htmlspecialchars($_SESSION['login_erreur'], ENT_QUOTES, 'UTF-8') ?>
            <?php if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 0): ?>
            <br><small>Tentative <?= (int)$_SESSION['login_attempts'] ?>/3</small>
            <?php endif; ?>
        </div>
        <?php unset($_SESSION['login_erreur']); ?>
        <?php endif; ?>

        <?php if (!$erreur_blocage): ?>
        <form method="POST" action="login_traitement.php">
            <div class="form-group">
                <label for="login">Identifiant</label>
                <input type="text" id="login" name="login"
                       value="<?= htmlspecialchars($_SESSION['login_saisie'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                       placeholder="admin" required autofocus autocomplete="username">
                <?php unset($_SESSION['login_saisie']); ?>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-bleu" style="width:100%;justify-content:center;margin-top:.5rem;">
                Se connecter
            </button>
        </form>
        <?php endif; ?>

        <div style="text-align:center;margin-top:1.25rem;">
            <a href="../index.php" style="font-size:.85rem;color:var(--gris-mid);">← Retour au site</a>
        </div>
    </div>
</div>

</body>
</html>
