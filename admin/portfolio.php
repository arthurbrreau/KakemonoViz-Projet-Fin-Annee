<?php
session_start();
define('BASE_ADMIN', '.');
require_once '../fonctions.php';
admin_requis();

$realisations = get_portfolio();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio — Admin KakémonoViz</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body class="admin-body">

<nav class="admin-nav">
    <div class="container">
        <span class="admin-brand">Kakémono<span>Viz</span> Admin</span>
        <div class="admin-nav-links">
            <a href="index.php">Devis</a>
            <a href="portfolio.php" class="active">Portfolio</a>
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

        <div class="admin-page-header">
            <h1>Gestion du portfolio</h1>
            <a href="portfolio_ajouter.php" class="btn btn-primary">+ Ajouter une réalisation</a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Image</th>
                        <th>Titre</th>
                        <th>Catégorie</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($realisations)): ?>
                    <tr><td colspan="7" style="text-align:center;padding:2rem;color:var(--gris-mid);">Aucune réalisation. <a href="portfolio_ajouter.php">Ajouter la première</a></td></tr>
                <?php else: ?>
                    <?php foreach ($realisations as $r): ?>
                    <tr>
                        <td><?= $r['id'] ?></td>
                        <td>
                            <?php
                            $img = '../uploads/' . htmlspecialchars($r['image'], ENT_QUOTES, 'UTF-8');
                            if ($r['image'] && file_exists($img)):
                            ?>
                                <img src="<?= $img ?>" alt="" style="width:60px;height:45px;object-fit:cover;border-radius:4px;">
                            <?php else: ?>
                                <span style="font-size:1.5rem;">🖼️</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= htmlspecialchars($r['titre'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td><?= htmlspecialchars($r['categorie'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <?= htmlspecialchars(mb_substr($r['description'] ?? '', 0, 80), ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td><?= date('d/m/Y', strtotime($r['cree_le'])) ?></td>
                        <td style="white-space:nowrap;">
                            <a href="portfolio_modifier.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-bleu">Modifier</a>
                            <a href="portfolio_supprimer.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer cette réalisation ?')">Supprimer</a>
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
