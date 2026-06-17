<?php require_once 'fonctions.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — KakémonoViz</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar">
    <div class="container">
        <a href="index.php" class="navbar-brand">Kakémono<span>Viz</span></a>
        <button class="navbar-toggle" id="navToggle" aria-label="Menu">&#9776;</button>
        <ul class="navbar-nav" id="navMenu">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="portfolio.php">Réalisations</a></li>
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="devis.php" class="btn-nav">Devis gratuit</a></li>
        </ul>
    </div>
</nav>

<header class="hero" style="padding:3rem 0;">
    <div class="container">
        <h1>Nous contacter</h1>
        <p>Notre équipe est disponible du lundi au vendredi, de 8 h à 18 h.</p>
    </div>
</header>

<section class="section">
    <div class="container">
        <div class="contact-grid">

            <!-- Informations de contact -->
            <div>
                <h2 style="margin-bottom:1.75rem;">Nos coordonnées</h2>

                <div class="contact-item">
                    <div class="contact-icon">📍</div>
                    <div class="contact-text">
                        <strong>Adresse</strong>
                        12 rue de la Fabrique<br>69003 Lyon, France
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <div class="contact-text">
                        <strong>Téléphone</strong>
                        <a href="tel:0472000000">04 72 00 00 00</a>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">✉️</div>
                    <div class="contact-text">
                        <strong>E-mail</strong>
                        <a href="mailto:contact@kakemonoviz.fr">contact@kakemonoviz.fr</a>
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">🕐</div>
                    <div class="contact-text">
                        <strong>Horaires</strong>
                        Lundi – Vendredi : 8 h – 18 h<br>
                        Samedi : 9 h – 12 h (urgences)<br>
                        Dimanche : fermé
                    </div>
                </div>

                <div class="contact-item">
                    <div class="contact-icon">🚗</div>
                    <div class="contact-text">
                        <strong>Accès</strong>
                        Métro D — Gare Part-Dieu<br>
                        Parking Vivier-Merle (350 m)
                    </div>
                </div>

                <div style="margin-top:2rem;">
                    <a href="devis.php" class="btn btn-primary">Demander un devis en ligne</a>
                </div>
            </div>

            <!-- Encart infos pratiques -->
            <div>
                <div class="card" style="padding:2rem;margin-bottom:1.5rem;">
                    <h3 style="margin-bottom:1rem;">Pourquoi nous choisir ?</h3>
                    <ul style="list-style:none;display:flex;flex-direction:column;gap:.75rem;">
                        <li>✅ <strong>Délai express</strong> — livraison en 48 h possible</li>
                        <li>✅ <strong>Qualité HD</strong> — impression sur traceurs grand format</li>
                        <li>✅ <strong>Conseils personnalisés</strong> — nos infographistes vous guident</li>
                        <li>✅ <strong>Devis gratuit</strong> — réponse sous 24 h ouvrées</li>
                        <li>✅ <strong>Fabrication locale</strong> — 100 % réalisé à Lyon</li>
                    </ul>
                </div>

                <div class="card" style="padding:1.5rem;background:var(--ambre-clair);border:2px solid var(--ambre);">
                    <h3 style="color:var(--gris-dark);margin-bottom:.75rem;">Besoin urgent ?</h3>
                    <p style="color:var(--gris-dark);">
                        Pour les commandes express (livraison sous 24–48 h), appelez-nous directement
                        au <strong><a href="tel:0472000000" style="color:var(--bleu-fonce);">04 72 00 00 00</a></strong>.
                        Des majorations peuvent s'appliquer.
                    </p>
                </div>
            </div>

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
</script>
</body>
</html>
