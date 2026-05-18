<?php
// On calcule le nombre total d'articles (Catalogue + Sur Mesure)
$nb_articles_panier = 0;

// 1. On compte les PC du catalogue
if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
    $catalogueMenuDAO = new CatalogueDAO($pdo);
    foreach ($_SESSION['panier'] as $id_pc => $quantite) {
        $pc_verif = $catalogueMenuDAO->getCatalogueById($id_pc);
        if ($pc_verif) {
            $nb_articles_panier += $quantite;
        } else {
            unset($_SESSION['panier'][$id_pc]); // Nettoyage des fantômes
        }
    }
}

// 2. NOUVEAU : On compte aussi les PC sur mesure !
if (isset($_SESSION['panier_custom']) && !empty($_SESSION['panier_custom'])) {
    foreach ($_SESSION['panier_custom'] as $custom_pc) {
        // On sécurise au cas où la quantité n'est pas définie
        $qte_custom = isset($custom_pc['quantite']) ? $custom_pc['quantite'] : 1;
        $nb_articles_panier += $qte_custom;
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php?page=accueil">PcPro</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=catalogue">Catalogue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=sur_mesure">PC sur mesure</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=contact">Contact</a>
                </li>
                <li class="nav-item ms-3 d-flex align-items-center">
                    <a href="index.php?page=panier" class="nav-link text-secondary fw-bold d-flex align-items-center">
                        <i class="bi bi-cart3 me-1"></i> Panier

                        <?php if ($nb_articles_panier > 0): ?>
                            <span class="badge bg-danger rounded-pill ms-2">
                <?php echo $nb_articles_panier; ?>
            </span>
                        <?php endif; ?>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                <li class="nav-item ms-3 d-flex align-items-center">
                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <a href="index.php?page=admin_catalogue" class="btn btn-sm btn-dark me-3">Dashboard Admin</a>
                    <?php endif; ?>
                    <a class="nav-link text-info fw-bold" href="index.php?page=profil">
                        <i class="bi bi-person-circle"></i> Mon Profil
                    </a>
                    <span class="text-secondary me-3 small">Bonjour, <strong><?php echo $_SESSION['user_nom']; ?></strong></span>
                    <a href="index.php?page=deconnexion" class="btn btn-sm btn-outline-danger">Déconnexion</a>
                </li>
                <?php else: ?>
                    <li class="nav-item ms-3">
                        <a href="index.php?page=connexion" class="nav-link text-secondary fw-bold">Connexion</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
