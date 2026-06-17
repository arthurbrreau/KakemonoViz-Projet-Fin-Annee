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

$erreur  = '';
$succes  = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveau_statut = $_POST['statut'] ?? '';
    $ok = modifier_statut($id, $nouveau_statut);
    if ($ok) {
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Statut mis à jour avec succès.'];
        header('Location: index.php');
        exit;
    } else {
        $erreur = 'Statut invalide ou erreur lors de la mise à jour.';
    }
}

$labels_statut = [
    'nouveau'  => 'Nouveau',
    'en_cours' => 'En cours',
    'termine'  => 'Terminé',
    'annule'   => 'Annulé',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le devis #<?= $id ?> — Admin</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

<nav class="admin-nav">
    <div class="container">
        <span class="admin-brand">Kakémono<span>Viz</span> Admin</span>
        <div class="admin-nav-links">
            <a href="index.php" class="active">Devis</a>
            <a href="portfolio.php">Portfolio</a>
            <a href="../index.php" target="_blank">Voir le site</a>
            <a href="logout.php" class="logout">Déconnexion</a>
        </div>
    </div>
</nav>

<main class="admin-main">
    <div class="container">
        <div class="admin-page-header">
            <h1>Devis #<?= $id ?> — <?= htmlspecialchars($devis['nom'], ENT_QUOTES, 'UTF-8') ?></h1>
            <a href="index.php" class="btn btn-sm btn-outline" style="border-color:var(--bleu);color:var(--bleu);">← Retour</a>
        </div>

        <?php if ($erreur): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;align-items:start;">

            <!-- Détails du devis -->
            <div class="card" style="padding:1.5rem;">
                <h3 style="margin-bottom:1rem;">Détails de la demande</h3>
                <table style="box-shadow:none;font-size:.9rem;">
                    <tbody>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);width:40%;"><strong>Nom</strong></td><td><?= htmlspecialchars($devis['nom'], ENT_QUOTES, 'UTF-8') ?></td></tr>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);"><strong>E-mail</strong></td><td><a href="mailto:<?= htmlspecialchars($devis['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($devis['email'], ENT_QUOTES, 'UTF-8') ?></a></td></tr>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);"><strong>Téléphone</strong></td><td><?= htmlspecialchars($devis['telephone'], ENT_QUOTES, 'UTF-8') ?></td></tr>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);"><strong>Support</strong></td><td><?= htmlspecialchars($devis['type_support'], ENT_QUOTES, 'UTF-8') ?></td></tr>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);"><strong>Dimensions</strong></td><td><?= $devis['largeur'] ?> × <?= $devis['hauteur'] ?> m</td></tr>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);"><strong>Quantité</strong></td><td><?= (int)$devis['quantite'] ?></td></tr>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);"><strong>Reçu le</strong></td><td><?= date('d/m/Y à H:i', strtotime($devis['cree_le'])) ?></td></tr>
                        <?php if ($devis['description']): ?>
                        <tr><td style="padding:.5rem;color:var(--gris-mid);vertical-align:top;"><strong>Description</strong></td><td><?= nl2br(htmlspecialchars($devis['description'], ENT_QUOTES, 'UTF-8')) ?></td></tr>
                        <?php endif; ?>
                        <?php if ($devis['fichier']): ?>
                        <tr>
                            <td style="padding:.5rem;color:var(--gris-mid);"><strong>Fichier</strong></td>
                            <td><a href="../uploads/<?= htmlspecialchars($devis['fichier'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">Télécharger</a></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Formulaire de modification statut -->
            <div class="card" style="padding:1.5rem;">
                <h3 style="margin-bottom:1rem;">Modifier le statut</h3>
                <p style="margin-bottom:1rem;">
                    Statut actuel : <span class="badge badge-<?= $devis['statut'] ?>"><?= $labels_statut[$devis['statut']] ?></span>
                </p>
                <form method="POST">
                    <div class="form-group">
                        <label for="statut">Nouveau statut</label>
                        <select id="statut" name="statut" required>
                            <?php foreach ($labels_statut as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $devis['statut'] === $val ? 'selected' : '' ?>>
                                <?= $lbl ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-bleu" style="width:100%;justify-content:center;">
                        Enregistrer
                    </button>
                </form>
            </div>

        </div>
    </div>
</main>

</body>
</html>
