<?php
global $pdo;

// 1. SÉCURITÉ : Il faut être connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=connexion');
    exit();
}

$catalogueDAO = new CatalogueDAO($pdo);
$commande_reussie = false;
$id_user = $_SESSION['user_id'];

// 2. TRAITEMENT DES PC DU CATALOGUE
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    foreach ($_SESSION['panier'] as $id_pc => $quantite) {
        $pc = $catalogueDAO->getCatalogueById($id_pc);
        if ($pc) {
            $prix_total_ligne = $pc->getPrix() * $quantite;
            // On sauvegarde via la fonction PL/pgSQL
            $catalogueDAO->saveCatalogueOrder($id_user, $id_pc, $quantite, $prix_total_ligne);
        }
    }
    unset($_SESSION['panier']); // On vide ce panier
    $commande_reussie = true;
}

// 3. TRAITEMENT DES PC SUR MESURE
if (isset($_SESSION['panier_custom']) && !empty($_SESSION['panier_custom'])) {
    foreach ($_SESSION['panier_custom'] as $custom_pc) {
        $quantite = $custom_pc['quantite'] ?? 1;
        $prix_total_ligne = $custom_pc['prix'] * $quantite;

        // On sauvegarde l'historique sur mesure (en précisant la quantité dans la description)
        $description = "Quantité : " . $quantite . "\n" . $custom_pc['description'];
        $catalogueDAO->saveCustomOrder($id_user, $description, $prix_total_ligne);
    }
    unset($_SESSION['panier_custom']); // On vide ce panier
    $commande_reussie = true;
}
?>

<div class="container mt-5 mb-5 text-center">
    <?php if ($commande_reussie): ?>
        <div class="card shadow-sm py-5">
            <div class="card-body">
                <i class="bi bi-check-circle-fill text-success display-1"></i>
                <h1 class="fw-bold mt-4">Félicitations pour votre achat, <?php echo htmlspecialchars($_SESSION['user_nom']); ?> !</h1>
                <p class="lead mt-3 text-muted">Votre commande a été enregistrée avec succès.</p>
                <a href="index.php?page=accueil" class="btn btn-primary fw-bold mt-4 px-4">Retour à l'accueil</a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">
            <h4 class="alert-heading">Information</h4>
            <p>Votre panier est vide ou la commande a déjà été traitée.</p>
            <hr>
            <a href="index.php?page=catalogue" class="btn btn-outline-dark mt-2 me-2">Retour au Catalogue</a>
            <a href="index.php?page=sur_mesure" class="btn btn-dark mt-2">Créer un PC sur mesure</a>
        </div>
    <?php endif; ?>
</div>