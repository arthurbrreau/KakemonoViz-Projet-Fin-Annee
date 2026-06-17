<?php
require_once 'fonctions.php';
$realisations = get_portfolio(6);
$page_active  = 'accueil';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KakémonoViz — Impression grand format à Lyon</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Navigation -->
<nav class="navbar">
    <div class="container">
        <a href="index.php" class="navbar-brand">Kakémono<span>Viz</span></a>
        <button class="navbar-toggle" id="navToggle" aria-label="Menu">&#9776;</button>
        <ul class="navbar-nav" id="navMenu">
            <li><a href="index.php" class="active">Accueil</a></li>
            <li><a href="portfolio.php">Réalisations</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="devis.php" class="btn-nav">Devis gratuit</a></li>
        </ul>
    </div>
</nav>

<!-- Hero -->
<header class="hero">
    <div class="container">
        <h1>L'impression grand format<br>qui donne de l'impact</h1>
        <p>Kakémonos, banderoles, roll-ups et supports publicitaires personnalisés.
           Fabrication lyonnaise depuis 2017.</p>
        <div class="hero-btns">
            <a href="devis.php" class="btn btn-primary">Demander un devis</a>
            <a href="portfolio.php" class="btn btn-outline">Voir nos réalisations</a>
        </div>
    </div>
</header>

<!-- Chiffres clés -->
<section class="stats-bar">
    <div class="container">
        <div class="stats-grid">
            <div>
                <div class="stat-val">+1 200</div>
                <div class="stat-lbl">Clients satisfaits</div>
            </div>
            <div>
                <div class="stat-val">7 ans</div>
                <div class="stat-lbl">D'expérience</div>
            </div>
            <div>
                <div class="stat-val">48 h</div>
                <div class="stat-lbl">Délai express</div>
            </div>
            <div>
                <div class="stat-val">100 %</div>
                <div class="stat-lbl">Fabrication France</div>
            </div>
        </div>
    </div>
</section>

<!-- Services -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Nos <span class="accent">supports</span></h2>
            <div class="divider"></div>
            <p>Du kakémono de salon à la toile tendue grand format, nous réalisons tous vos supports publicitaires sur mesure.</p>
        </div>
        <div class="grid-4">
            <div class="card" style="padding:1.5rem;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">🎌</div>
                <h3>Kakémono</h3>
                <p class="mt-1">Format standard 85×200 cm, impression HD, pied inclus.</p>
            </div>
            <div class="card" style="padding:1.5rem;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">🏷️</div>
                <h3>Banderole</h3>
                <p class="mt-1">Bâches vinyle intérieur/extérieur, toutes tailles.</p>
            </div>
            <div class="card" style="padding:1.5rem;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">📋</div>
                <h3>Roll-up</h3>
                <p class="mt-1">Léger et transportable, idéal pour vos événements.</p>
            </div>
            <div class="card" style="padding:1.5rem;text-align:center;">
                <div style="font-size:2.5rem;margin-bottom:.75rem;">🖼️</div>
                <h3>Toile tendue</h3>
                <p class="mt-1">Finition premium, cadre alu, rendu photo impeccable.</p>
            </div>
        </div>
    </div>
</section>

<!-- Dernières réalisations -->
<?php if (!empty($realisations)): ?>
<section class="section" style="background:var(--gris-light);">
    <div class="container">
        <div class="section-header">
            <h2>Nos dernières <span class="accent">réalisations</span></h2>
            <div class="divider"></div>
            <p>Découvrez quelques-uns de nos projets récents réalisés pour nos clients.</p>
        </div>
        <div class="grid-3">
            <?php foreach ($realisations as $item): ?>
            <div class="card">
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
                    <p><?= htmlspecialchars(mb_substr($item['description'] ?? '', 0, 100), ENT_QUOTES, 'UTF-8') ?><?= mb_strlen($item['description'] ?? '') > 100 ? '…' : '' ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-3">
            <a href="portfolio.php" class="btn btn-bleu">Voir toutes nos réalisations</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="section">
    <div class="container text-center">
        <h2>Prêt à donner de la visibilité à votre marque&nbsp;?</h2>
        <p class="mt-1">Obtenez un devis personnalisé en quelques minutes. Réponse sous 24 h.</p>
        <div class="mt-3">
            <a href="devis.php" class="btn btn-primary">Demander un devis gratuit</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">Kakémono<span>Viz</span></div>
                <p>PME lyonnaise spécialisée dans l'impression grand format et les supports publicitaires personnalisés depuis 2017.</p>
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
                    <li>🕐 Lun–Ven : 8h–18h</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            &copy; <?= date('Y') ?> KakémonoViz — Tous droits réservés
        </div>
    </div>
</footer>

<script>
document.getElementById('navToggle').addEventListener('click', function () {
    document.getElementById('navMenu').classList.toggle('open');
});
</script>
</body>
</html>
