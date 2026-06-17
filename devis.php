<?php
require_once 'fonctions.php';

$erreurs = [];
$succes  = false;
$champs  = [
    'nom' => '', 'email' => '', 'telephone' => '',
    'type_support' => '', 'largeur' => '', 'hauteur' => '',
    'quantite' => '1', 'description' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération & nettoyage
    foreach ($champs as $k => $_) {
        $champs[$k] = trim($_POST[$k] ?? '');
    }

    // Validations
    if (empty($champs['nom'])) {
        $erreurs['nom'] = 'Le nom est obligatoire.';
    } elseif (strlen($champs['nom']) > 100) {
        $erreurs['nom'] = 'Le nom ne doit pas dépasser 100 caractères.';
    }

    if (empty($champs['email'])) {
        $erreurs['email'] = "L'adresse e-mail est obligatoire.";
    } elseif (!filter_var($champs['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "L'adresse e-mail n'est pas valide.";
    }

    if (empty($champs['telephone'])) {
        $erreurs['telephone'] = 'Le téléphone est obligatoire.';
    } elseif (!preg_match('/^[0-9\s\+\-\.]{7,20}$/', $champs['telephone'])) {
        $erreurs['telephone'] = 'Le numéro de téléphone n\'est pas valide.';
    }

    if (empty($champs['type_support'])) {
        $erreurs['type_support'] = 'Veuillez choisir un type de support.';
    }

    if ($champs['largeur'] === '' || !is_numeric($champs['largeur']) || (float)$champs['largeur'] <= 0) {
        $erreurs['largeur'] = 'La largeur doit être un nombre positif.';
    }

    if ($champs['hauteur'] === '' || !is_numeric($champs['hauteur']) || (float)$champs['hauteur'] <= 0) {
        $erreurs['hauteur'] = 'La hauteur doit être un nombre positif.';
    }

    if (!is_numeric($champs['quantite']) || (int)$champs['quantite'] < 1) {
        $erreurs['quantite'] = 'La quantité doit être au moins 1.';
    }

    // Upload fichier (optionnel)
    $nom_fichier = null;
    if (isset($_FILES['fichier']) && $_FILES['fichier']['error'] !== UPLOAD_ERR_NO_FILE) {
        $nom_fichier = upload_fichier($_FILES['fichier']);
        if ($nom_fichier === false) {
            $erreurs['fichier'] = 'Fichier invalide. Formats acceptés : JPG, PNG, PDF, AI, EPS, SVG. Taille max : 10 Mo.';
        }
    }

    // Enregistrement si pas d'erreurs
    if (empty($erreurs)) {
        $ok = creer_devis($champs, $nom_fichier);
        if ($ok) {
            $succes = true;
            $champs = array_map(fn($v) => '', $champs); // Vider le formulaire
            $champs['quantite'] = '1';
        } else {
            $erreurs['global'] = 'Une erreur est survenue. Veuillez réessayer.';
        }
    }
}

$types_support = ['Kakémono', 'Banderole', 'Roll-up', 'Toile tendue', 'Affiche', 'Vitrophanie', 'Autre'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande de devis — KakémonoViz</title>
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
            <li><a href="contact.php">Contact</a></li>
            <li><a href="devis.php" class="btn-nav active">Devis gratuit</a></li>
        </ul>
    </div>
</nav>

<header class="hero" style="padding:3rem 0;">
    <div class="container">
        <h1>Demande de devis</h1>
        <p>Remplissez le formulaire ci-dessous. Nous vous répondons sous 24 h ouvrées.</p>
    </div>
</header>

<section class="section">
    <div class="container">
        <div class="form-card">

            <?php if ($succes): ?>
            <div class="alert alert-success">
                ✅ <strong>Votre demande a bien été envoyée !</strong><br>
                Notre équipe vous contactera dans les 24 h ouvrées pour finaliser votre devis.
            </div>
            <div class="text-center mt-2">
                <a href="index.php" class="btn btn-bleu">Retour à l'accueil</a>
            </div>

            <?php else: ?>

            <?php if (!empty($erreurs['global'])): ?>
            <div class="alert alert-error">⚠️ <?= htmlspecialchars($erreurs['global'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php endif; ?>

            <?php if (!empty($erreurs)): ?>
            <div class="alert alert-error">⚠️ Veuillez corriger les erreurs ci-dessous avant d'envoyer.</div>
            <?php endif; ?>

            <form method="POST" action="devis.php" enctype="multipart/form-data" novalidate>

                <h3 style="margin-bottom:1.25rem;padding-bottom:.5rem;border-bottom:2px solid var(--gris-light);">
                    Vos coordonnées
                </h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom complet <span class="req">*</span></label>
                        <input type="text" id="nom" name="nom" maxlength="100"
                               value="<?= htmlspecialchars($champs['nom'], ENT_QUOTES, 'UTF-8') ?>"
                               class="<?= isset($erreurs['nom']) ? 'form-error' : '' ?>"
                               placeholder="Ex : Sophie Martin" required>
                        <?php if (isset($erreurs['nom'])): ?>
                        <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['nom'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse e-mail <span class="req">*</span></label>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($champs['email'], ENT_QUOTES, 'UTF-8') ?>"
                               class="<?= isset($erreurs['email']) ? 'form-error' : '' ?>"
                               placeholder="sophie@exemple.fr" required>
                        <?php if (isset($erreurs['email'])): ?>
                        <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['email'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="telephone">Téléphone <span class="req">*</span></label>
                    <input type="tel" id="telephone" name="telephone"
                           value="<?= htmlspecialchars($champs['telephone'], ENT_QUOTES, 'UTF-8') ?>"
                           class="<?= isset($erreurs['telephone']) ? 'form-error' : '' ?>"
                           placeholder="06 12 34 56 78" required>
                    <?php if (isset($erreurs['telephone'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['telephone'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <h3 style="margin:1.75rem 0 1.25rem;padding-bottom:.5rem;border-bottom:2px solid var(--gris-light);">
                    Votre commande
                </h3>

                <div class="form-group">
                    <label for="type_support">Type de support <span class="req">*</span></label>
                    <select id="type_support" name="type_support"
                            class="<?= isset($erreurs['type_support']) ? 'form-error' : '' ?>" required>
                        <option value="">-- Choisissez un support --</option>
                        <?php foreach ($types_support as $t): ?>
                        <option value="<?= htmlspecialchars($t, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $champs['type_support'] === $t ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($erreurs['type_support'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['type_support'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="largeur">Largeur (m) <span class="req">*</span></label>
                        <input type="number" id="largeur" name="largeur" min="0.1" step="0.01"
                               value="<?= htmlspecialchars($champs['largeur'], ENT_QUOTES, 'UTF-8') ?>"
                               class="<?= isset($erreurs['largeur']) ? 'form-error' : '' ?>"
                               placeholder="Ex : 0.85" required>
                        <?php if (isset($erreurs['largeur'])): ?>
                        <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['largeur'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="hauteur">Hauteur (m) <span class="req">*</span></label>
                        <input type="number" id="hauteur" name="hauteur" min="0.1" step="0.01"
                               value="<?= htmlspecialchars($champs['hauteur'], ENT_QUOTES, 'UTF-8') ?>"
                               class="<?= isset($erreurs['hauteur']) ? 'form-error' : '' ?>"
                               placeholder="Ex : 2.00" required>
                        <?php if (isset($erreurs['hauteur'])): ?>
                        <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['hauteur'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="quantite">Quantité <span class="req">*</span></label>
                    <input type="number" id="quantite" name="quantite" min="1"
                           value="<?= htmlspecialchars($champs['quantite'], ENT_QUOTES, 'UTF-8') ?>"
                           class="<?= isset($erreurs['quantite']) ? 'form-error' : '' ?>"
                           required>
                    <?php if (isset($erreurs['quantite'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['quantite'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Description du projet</label>
                    <textarea id="description" name="description"
                              placeholder="Décrivez votre projet : couleurs souhaitées, textes, utilisation prévue, date de besoin..."><?= htmlspecialchars($champs['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="form-group">
                    <label for="fichier">Fichier graphique (optionnel)</label>
                    <input type="file" id="fichier" name="fichier"
                           accept=".jpg,.jpeg,.png,.gif,.pdf,.ai,.eps,.svg"
                           class="<?= isset($erreurs['fichier']) ? 'form-error' : '' ?>">
                    <div class="error-msg" style="color:var(--gris-mid);margin-top:.35rem;font-size:.82rem;">
                        Formats acceptés : JPG, PNG, PDF, AI, EPS, SVG — Taille max : 10 Mo
                    </div>
                    <?php if (isset($erreurs['fichier'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['fichier'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;font-size:1rem;padding:.85rem;">
                        Envoyer ma demande de devis
                    </button>
                </div>
                <p style="text-align:center;margin-top:.75rem;font-size:.82rem;color:var(--gris-mid);">
                    Les champs marqués <span style="color:var(--rouge);">*</span> sont obligatoires.
                </p>

            </form>
            <?php endif; ?>
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
