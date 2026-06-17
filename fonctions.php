<?php
require_once __DIR__ . '/connexion.php';

// ══════════════════════════════════════════════
//  DEVIS
// ══════════════════════════════════════════════

function get_devis(?string $statut = null): array {
    $pdo = getConnexion();
    if ($statut) {
        $stmt = $pdo->prepare('SELECT * FROM devis WHERE statut = :s ORDER BY cree_le DESC');
        $stmt->execute([':s' => $statut]);
    } else {
        $stmt = $pdo->query('SELECT * FROM devis ORDER BY cree_le DESC');
    }
    return $stmt->fetchAll();
}

function get_un_devis(int $id): array|false {
    $pdo  = getConnexion();
    $stmt = $pdo->prepare('SELECT * FROM devis WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function creer_devis(array $data, ?string $fichier = null): bool {
    $pdo  = getConnexion();
    $stmt = $pdo->prepare(
        'INSERT INTO devis (nom, email, telephone, type_support, largeur, hauteur, quantite, description, fichier)
         VALUES (:nom, :email, :telephone, :type_support, :largeur, :hauteur, :quantite, :description, :fichier)'
    );
    return $stmt->execute([
        ':nom'          => htmlspecialchars(trim($data['nom']),          ENT_QUOTES, 'UTF-8'),
        ':email'        => filter_var(trim($data['email']), FILTER_SANITIZE_EMAIL),
        ':telephone'    => htmlspecialchars(trim($data['telephone']),    ENT_QUOTES, 'UTF-8'),
        ':type_support' => htmlspecialchars(trim($data['type_support']), ENT_QUOTES, 'UTF-8'),
        ':largeur'      => (float) $data['largeur'],
        ':hauteur'      => (float) $data['hauteur'],
        ':quantite'     => max(1, (int) $data['quantite']),
        ':description'  => htmlspecialchars(trim($data['description'] ?? ''), ENT_QUOTES, 'UTF-8'),
        ':fichier'      => $fichier,
    ]);
}

function modifier_statut(int $id, string $statut): bool {
    $statuts_valides = ['nouveau', 'en_cours', 'termine', 'annule'];
    if (!in_array($statut, $statuts_valides, true)) {
        return false;
    }
    $pdo  = getConnexion();
    $stmt = $pdo->prepare('UPDATE devis SET statut = :s WHERE id = :id');
    return $stmt->execute([':s' => $statut, ':id' => $id]);
}

function supprimer_devis(int $id): bool {
    $devis = get_un_devis($id);
    if ($devis && $devis['fichier']) {
        $chemin = __DIR__ . '/uploads/' . $devis['fichier'];
        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }
    $pdo  = getConnexion();
    $stmt = $pdo->prepare('DELETE FROM devis WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}

// ══════════════════════════════════════════════
//  PORTFOLIO
// ══════════════════════════════════════════════

function get_portfolio(?int $limite = null): array {
    $pdo = getConnexion();
    $sql = 'SELECT * FROM portfolio ORDER BY cree_le DESC';
    if ($limite !== null) {
        $sql .= ' LIMIT ' . (int) $limite;
    }
    return $pdo->query($sql)->fetchAll();
}

function get_une_realisation(int $id): array|false {
    $pdo  = getConnexion();
    $stmt = $pdo->prepare('SELECT * FROM portfolio WHERE id = :id LIMIT 1');
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function creer_realisation(array $data, string $image): bool {
    $pdo  = getConnexion();
    $stmt = $pdo->prepare(
        'INSERT INTO portfolio (titre, description, image, categorie)
         VALUES (:titre, :description, :image, :categorie)'
    );
    return $stmt->execute([
        ':titre'       => htmlspecialchars(trim($data['titre']),       ENT_QUOTES, 'UTF-8'),
        ':description' => htmlspecialchars(trim($data['description'] ?? ''), ENT_QUOTES, 'UTF-8'),
        ':image'       => $image,
        ':categorie'   => htmlspecialchars(trim($data['categorie']),   ENT_QUOTES, 'UTF-8'),
    ]);
}

function modifier_realisation(int $id, array $data, ?string $image = null): bool {
    $pdo = getConnexion();
    if ($image !== null) {
        // Supprimer l'ancienne image
        $ancienne = get_une_realisation($id);
        if ($ancienne && $ancienne['image']) {
            $chemin = __DIR__ . '/uploads/' . $ancienne['image'];
            if (file_exists($chemin)) {
                unlink($chemin);
            }
        }
        $stmt = $pdo->prepare(
            'UPDATE portfolio SET titre=:titre, description=:description, image=:image, categorie=:categorie WHERE id=:id'
        );
        return $stmt->execute([
            ':titre'       => htmlspecialchars(trim($data['titre']),       ENT_QUOTES, 'UTF-8'),
            ':description' => htmlspecialchars(trim($data['description'] ?? ''), ENT_QUOTES, 'UTF-8'),
            ':image'       => $image,
            ':categorie'   => htmlspecialchars(trim($data['categorie']),   ENT_QUOTES, 'UTF-8'),
            ':id'          => $id,
        ]);
    } else {
        $stmt = $pdo->prepare(
            'UPDATE portfolio SET titre=:titre, description=:description, categorie=:categorie WHERE id=:id'
        );
        return $stmt->execute([
            ':titre'       => htmlspecialchars(trim($data['titre']),       ENT_QUOTES, 'UTF-8'),
            ':description' => htmlspecialchars(trim($data['description'] ?? ''), ENT_QUOTES, 'UTF-8'),
            ':categorie'   => htmlspecialchars(trim($data['categorie']),   ENT_QUOTES, 'UTF-8'),
            ':id'          => $id,
        ]);
    }
}

function supprimer_realisation(int $id): bool {
    $realisation = get_une_realisation($id);
    if ($realisation && $realisation['image']) {
        $chemin = __DIR__ . '/uploads/' . $realisation['image'];
        if (file_exists($chemin)) {
            unlink($chemin);
        }
    }
    $pdo  = getConnexion();
    $stmt = $pdo->prepare('DELETE FROM portfolio WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}

// ══════════════════════════════════════════════
//  AUTHENTIFICATION ADMIN
// ══════════════════════════════════════════════

function verifier_admin(string $login, string $password): bool {
    // Protection force brute : max 3 tentatives, blocage 15 min
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts']   = 0;
        $_SESSION['last_attempt_time'] = 0;
    }

    $delai_blocage = 15 * 60; // 15 minutes
    if ($_SESSION['login_attempts'] >= 3) {
        if (time() - $_SESSION['last_attempt_time'] < $delai_blocage) {
            return false; // Encore bloqué
        }
        // Délai écoulé : réinitialiser
        $_SESSION['login_attempts'] = 0;
    }

    $pdo  = getConnexion();
    $stmt = $pdo->prepare('SELECT password FROM admins WHERE login = :login LIMIT 1');
    $stmt->execute([':login' => $login]);
    $row  = $stmt->fetch();

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['login_attempts']    = 0;
        $_SESSION['admin_connecte']    = true;
        $_SESSION['admin_login']       = htmlspecialchars($login, ENT_QUOTES, 'UTF-8');
        return true;
    }

    $_SESSION['login_attempts']++;
    $_SESSION['last_attempt_time'] = time();
    return false;
}

function admin_requis(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['admin_connecte'])) {
        $base = defined('BASE_ADMIN') ? BASE_ADMIN : '.';
        header('Location: ' . $base . '/login.php');
        exit;
    }
}

// ══════════════════════════════════════════════
//  UPLOAD FICHIER SÉCURISÉ
// ══════════════════════════════════════════════

function upload_fichier(array $fichier): string|false {
    $extensions_ok = ['jpg','jpeg','png','gif','pdf','ai','eps','svg'];
    $taille_max    = 10 * 1024 * 1024; // 10 Mo

    if ($fichier['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    if ($fichier['size'] > $taille_max) {
        return false;
    }

    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $extensions_ok, true)) {
        return false;
    }

    // Vérification MIME réelle
    $finfo     = new finfo(FILEINFO_MIME_TYPE);
    $mime      = $finfo->file($fichier['tmp_name']);
    $mimes_ok  = [
        'image/jpeg','image/png','image/gif','image/svg+xml',
        'application/pdf','application/postscript',
    ];
    if (!in_array($mime, $mimes_ok, true)) {
        return false;
    }

    $nouveau_nom = bin2hex(random_bytes(16)) . '.' . $extension;
    $destination = __DIR__ . '/uploads/' . $nouveau_nom;

    if (!move_uploaded_file($fichier['tmp_name'], $destination)) {
        return false;
    }
    return $nouveau_nom;
}
