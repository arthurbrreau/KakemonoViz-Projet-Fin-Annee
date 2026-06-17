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

$erreurs = [];
$champs = [
    'titre'       => $realisation['titre'],
    'description' => $realisation['description'] ?? '',
    'categorie'   => $realisation['categorie'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $champs['titre']       = trim($_POST['titre']       ?? '');
    $champs['description'] = trim($_POST['description'] ?? '');
    $champs['categorie']   = trim($_POST['categorie']   ?? '');

    if (empty($champs['titre'])) {
        $erreurs['titre'] = 'Le titre est obligatoire.';
    }
    if (empty($champs['categorie'])) {
        $erreurs['categorie'] = 'La catégorie est obligatoire.';
    }

    // Nouvelle image (optionnel)
    $nom_image = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $nom_image = upload_fichier($_FILES['image']);
        if ($nom_image === false) {
            $erreurs['image'] = 'Image invalide. Formats : JPG, PNG, GIF, SVG. Max 10 Mo.';
        }
    }

    if (empty($erreurs)) {
        $ok = modifier_realisation($id, $champs, $nom_image);
        if ($ok) {
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Réalisation modifiée avec succès.'];
            header('Location: portfolio.php');
            exit;
        } else {
            $erreurs['global'] = 'Erreur lors de la modification.';
        }
    }
}

$categories = ['Kakémono', 'Banderole', 'Roll-up', 'Toile tendue', 'Affiche', 'Vitrophanie', 'Autre'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la réalisation #<?= $id ?> — Admin</title>
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
        <div class="admin-page-header">
            <h1>Modifier la réalisation #<?= $id ?></h1>
            <a href="portfolio.php" class="btn btn-sm btn-outline" style="border-color:var(--bleu);color:var(--bleu);">← Retour</a>
        </div>

        <?php if (!empty($erreurs['global'])): ?>
        <div class="alert alert-error">⚠ <?= htmlspecialchars($erreurs['global'], ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data">

                <div class="form-group">
                    <label for="titre">Titre <span class="req">*</span></label>
                    <input type="text" id="titre" name="titre" maxlength="150"
                           value="<?= htmlspecialchars($champs['titre'], ENT_QUOTES, 'UTF-8') ?>"
                           class="<?= isset($erreurs['titre']) ? 'form-error' : '' ?>" required>
                    <?php if (isset($erreurs['titre'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['titre'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="categorie">Catégorie <span class="req">*</span></label>
                    <select id="categorie" name="categorie"
                            class="<?= isset($erreurs['categorie']) ? 'form-error' : '' ?>" required>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>"
                            <?= $champs['categorie'] === $cat ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($erreurs['categorie'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['categorie'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description"><?= htmlspecialchars($champs['description'], ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <!-- Image actuelle -->
                <?php if ($realisation['image']): ?>
                <div class="form-group">
                    <label>Image actuelle</label>
                    <?php $img = '../uploads/' . htmlspecialchars($realisation['image'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php if (file_exists($img)): ?>
                    <img src="<?= $img ?>" alt="Image actuelle"
                         style="max-width:200px;border-radius:var(--radius);margin-bottom:.5rem;display:block;">
                    <?php else: ?>
                    <p style="color:var(--gris-mid);font-size:.9rem;">Fichier image non trouvé.</p>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="image">Nouvelle image (laisser vide pour conserver l'actuelle)</label>
                    <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.gif,.svg"
                           class="<?= isset($erreurs['image']) ? 'form-error' : '' ?>">
                    <div style="font-size:.82rem;color:var(--gris-mid);margin-top:.3rem;">
                        Formats : JPG, PNG, GIF, SVG — Max 10 Mo
                    </div>
                    <?php if (isset($erreurs['image'])): ?>
                    <div class="error-msg">⚠ <?= htmlspecialchars($erreurs['image'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>

                <div class="mt-3" style="display:flex;gap:1rem;">
                    <button type="submit" class="btn btn-bleu">Enregistrer les modifications</button>
                    <a href="portfolio.php" class="btn btn-outline" style="border-color:var(--gris-mid);color:var(--gris-mid);">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</main>

</body>
</html>
