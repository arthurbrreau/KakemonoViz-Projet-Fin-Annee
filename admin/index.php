<?php
session_start();
define('BASE_ADMIN', '.');
require_once '../fonctions.php';
admin_requis();

$filtre_statut = $_GET['statut'] ?? '';
$statuts_valides = ['', 'nouveau', 'en_cours', 'termine', 'annule'];
if (!in_array($filtre_statut, $statuts_valides, true)) {
    $filtre_statut = '';
}

$devis = get_devis($filtre_statut ?: null);

// Compteurs pour les cartes du dashboard
$tous    = get_devis();
$compteurs = [
    'total'    => count($tous),
    'nouveau'  => count(array_filter($tous, fn($d) => $d['statut'] === 'nouveau')),
    'en_cours' => count(array_filter($tous, fn($d) => $d['statut'] === 'en_cours')),
    'termine'  => count(array_filter($tous, fn($d) => $d['statut'] === 'termine')),
];

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
    <title>Tableau de bord — Admin KakémonoViz</title>
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

        <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-<?= htmlspecialchars($_SESSION['flash']['type'], ENT_QUOTES, 'UTF-8') ?>">
            <?= htmlspecialchars($_SESSION['flash']['msg'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
        <?php endif; ?>

        <!-- Cartes stats -->
        <div class="dash-cards">
            <div class="dash-card">
                <div class="dash-card-val"><?= $compteurs['total'] ?></div>
                <div class="dash-card-lbl">Total devis</div>
            </div>
            <div class="dash-card ambre">
                <div class="dash-card-val"><?= $compteurs['nouveau'] ?></div>
                <div class="dash-card-lbl">Nouveaux</div>
            </div>
            <div class="dash-card">
                <div class="dash-card-val"><?= $compteurs['en_cours'] ?></div>
                <div class="dash-card-lbl">En cours</div>
            </div>
            <div class="dash-card vert">
                <div class="dash-card-val"><?= $compteurs['termine'] ?></div>
                <div class="dash-card-lbl">Terminés</div>
            </div>
        </div>

        <!-- Entête + filtre -->
        <div class="admin-page-header">
            <h1>Gestion des devis</h1>
            <div style="display:flex;gap:.5rem;flex-wrap:wrap;">
                <?php foreach ([''=>'Tous', 'nouveau'=>'Nouveaux', 'en_cours'=>'En cours', 'termine'=>'Terminés', 'annule'=>'Annulés'] as $val => $label): ?>
                <a href="index.php<?= $val ? '?statut=' . $val : '' ?>"
                   class="btn btn-sm <?= $filtre_statut === $val ? 'btn-bleu' : 'btn-outline' ?>"
                   style="<?= $filtre_statut !== $val ? 'border-color:var(--bleu);color:var(--bleu);' : '' ?>">
                    <?= $label ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Table -->
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Contact</th>
                        <th>Support</th>
                        <th>Dimensions</th>
                        <th>Qté</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($devis)): ?>
                    <tr><td colspan="9" style="text-align:center;padding:2rem;color:var(--gris-mid);">Aucun devis.</td></tr>
                <?php else: ?>
                    <?php foreach ($devis as $d): ?>
                    <tr>
                        <td><?= $d['id'] ?></td>
                        <td><strong><?= htmlspecialchars($d['nom'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td>
                            <a href="mailto:<?= htmlspecialchars($d['email'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($d['email'], ENT_QUOTES, 'UTF-8') ?></a><br>
                            <small><?= htmlspecialchars($d['telephone'], ENT_QUOTES, 'UTF-8') ?></small>
                        </td>
                        <td><?= htmlspecialchars($d['type_support'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $d['largeur'] ?> × <?= $d['hauteur'] ?> m</td>
                        <td><?= (int)$d['quantite'] ?></td>
                        <td><span class="badge badge-<?= $d['statut'] ?>"><?= $labels_statut[$d['statut']] ?></span></td>
                        <td><?= date('d/m/Y', strtotime($d['cree_le'])) ?></td>
                        <td style="white-space:nowrap;">
                            <a href="devis_modifier.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-bleu">Modifier</a>
                            <a href="devis_supprimer.php?id=<?= $d['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer ce devis ?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</main>

</body>
</html>
