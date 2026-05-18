<?php
global $pdo;

// 1. On vérifie qu'on a bien un ID dans l'URL (ex: ?page=detail&id=3)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Aucun PC sélectionné.</div></div>";
    return; // On arrête l'affichage de la page ici
}

$id_pc = intval($_GET['id']); // On sécurise l'ID en s'assurant que c'est un nombre

// 2. On instancie le DAO et on récupère le PC spécifique
$catalogueDAO = new CatalogueDAO($pdo);
$pc = $catalogueDAO->getCatalogueById($id_pc);

// 3. Si l'utilisateur tape un ID qui n'existe pas dans la base
if (!$pc) {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Ce PC n'existe pas ou n'est plus disponible.</div></div>";
    return;
}
?>

<div class="container mt-5 mb-5">
    <a href="index.php?page=catalogue" class="btn btn-outline-secondary mb-4">&larr; Retour au catalogue</a>

    <div class="row bg-white p-4 shadow-sm rounded border">

        <div class="col-md-6 text-center d-flex align-items-center justify-content-center bg-light rounded p-4 mb-4 mb-md-0 border">
            <img src="admin/assets/img/<?php echo htmlspecialchars($pc->getImageUrl()); ?>"
                 alt="<?php echo htmlspecialchars($pc->getNomModele()); ?>"
                 class="img-fluid rounded"
                 style="max-height: 500px; object-fit: contain;">
        </div>

        <div class="col-md-6 d-flex flex-column pl-md-4">
            <h1 class="display-5 fw-bold text-primary mb-3"><?php echo htmlspecialchars($pc->getNomModele()); ?></h1>

            <p class="lead text-secondary mb-4">
                <?php echo nl2br(htmlspecialchars($pc->getDescription())); ?>
            </p>

            <h4 class="fw-bold mb-3 border-bottom pb-2">Caractéristiques Techniques</h4>

            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item px-0"><strong class="text-dark">Processeur :</strong> <?php echo htmlspecialchars($pc->getProcesseur()); ?></li>
                <li class="list-group-item px-0"><strong class="text-dark">Carte Mère :</strong> <?php echo htmlspecialchars($pc->getCarteMere()); ?></li>
                <li class="list-group-item px-0"><strong class="text-dark">Carte Graphique :</strong> <?php echo htmlspecialchars($pc->getCarteGraphique()); ?></li>
                <li class="list-group-item px-0"><strong class="text-dark">Mémoire (RAM) :</strong> <?php echo htmlspecialchars($pc->getMemoire()); ?></li>
                <li class="list-group-item px-0"><strong class="text-dark">Stockage (SSD) :</strong> <?php echo htmlspecialchars($pc->getStockage()); ?></li>
            </ul>

            <div class="mt-auto bg-light p-4 rounded border d-flex flex-wrap justify-content-between align-items-center">
                <span class="display-6 fw-bold text-dark"><?php echo number_format($pc->getPrix(), 2, ',', ' '); ?> €</span>

                <a href="index.php?page=panier&action=add&id=<?php echo $pc->getIdPc(); ?>" class="btn btn-success btn-lg px-4 shadow-sm mt-3 mt-md-0">
                    <i class="bi bi-cart-plus"></i> Ajouter au panier
                </a>
            </div>
        </div>

    </div>
</div>