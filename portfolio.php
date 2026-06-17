<?php
require_once 'fonctions.php';
$realisations = get_portfolio();

// Catégories uniques pour les filtres
$categories = array_unique(array_column($realisations, 'categorie'));
sort($categories);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réalisations — KakémonoViz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="index.php" class="navbar-brand">Kakémono<span>Viz</span></a>
        <button class="navbar-toggle" id="navToggle" aria-label="Menu">&#9776;</button>
        <ul class="navbar-nav" id="navMenu">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="portfolio.php" class="active">Réalisations</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="devis.php" class="btn-nav">Devis gratuit</a></li>
        </ul>
    </div>
</nav>

<header class="hero" style="padding:3rem 0;">
    <div class="container">
        <h1>Nos réalisations</h1>
        <p>Découvrez l'ensemble de nos projets réalisés pour nos clients en France.</p>
    </div>
</header>

<section class="section">
    <div class="container">

        <?php if (!empty($categories)): ?>
        <div class="filter-bar">
            <button class="filter-btn active" data-cat="tous">Tous (<?= count($realisations) ?>)</button>
            <?php foreach ($categories as $cat): ?>
            <button class="filter-btn" data-cat="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
            </button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($realisations)): ?>
            <div class="alert alert-info">Aucune réalisation pour le moment. Revenez bientôt !</div>
        <?php else: ?>
        <div class="grid-3" id="portfolioGrid">
            <?php foreach ($realisations as $item): ?>
            <div class="card portfolio-item" data-cat="<?= htmlspecialchars($item['categorie'], ENT_QUOTES, 'UTF-8') ?>">
                <?php
                $img_path = 'uploads/' . htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8');
                if ($item['image'] && file_exists($img_path)):
                ?>
                    <img src="<?= $img_path ?>" alt="<?= htmlspecialchars($item['titre'], ENT_QUOTES, 'UTF-8') ?>" class="card-img">
                <?php else: ?>
                    <div class="card-img-placeholder">🖼️</div>
                <?php endif; ?>
                <div class="card-body">
                    <span class="card-cat"><?= htmlspecialchars($item['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
                    <h3><?= htmlspecialchars($item['titre'], ENT_QUOTES, 'UTF-8') ?></h3>
                    <?php if ($item['description']): ?>
                    <p><?= htmlspecialchars($item['description'], ENT_QUOTES, 'UTF-8') ?></p>
                    <?php endif; ?>
                    <p class="mt-1" style="font-size:.8rem;color:var(--gris-mid);">
                        <?= date('d/m/Y', strtotime($item['cree_le'])) ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="text-center mt-3">
            <a href="devis.php" class="btn btn-primary">Demander un devis pour votre projet</a>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">Kakémono<span>Viz</span></div>
                <p>PME lyonnaise spécialisée dans l'impression grand format depuis 2017.</p>
            </div>
            <div>
                <h4>Navigation</h4>
                <ul class="footer-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="portfolio.php">Réalisations</a></li>
                    <li><a href="devis.php">Devis gratuit</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4>Contact</h4>
                <ul class="footer-links">
                    <li>📍 12 rue de la Fabrique, 69003 Lyon</li>
                    <li>📞 <a href="tel:0472000000">04 72 00 00 00</a></li>
                    <li>✉️ <a href="mailto:contact@kakemonoviz.fr">contact@kakemonoviz.fr</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">&copy; <?= date('Y') ?> KakémonoViz — Tous droits réservés</div>
    </div>
</footer>

<script>
document.getElementById('navToggle').addEventListener('click', function () {
    document.getElementById('navMenu').classList.toggle('open');
});

// Filtre portfolio côté client
const filterBtns = document.querySelectorAll('.filter-btn');
const items = document.querySelectorAll('.portfolio-item');

filterBtns.forEach(btn => {
    btn.addEventListener('click', function () {
        filterBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const cat = this.dataset.cat;
        items.forEach(item => {
            item.style.display = (cat === 'tous' || item.dataset.cat === cat) ? '' : 'none';
        });
    });
});
</script>
</body>
</html>
