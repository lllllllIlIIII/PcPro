<?php

$catalogueDAO = new catalogueDAO($pdo);

$catalogue = $catalogueDAO->getAllcatalogue();
?>

<div class="container mt-5 mb-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold text-light">Catalogue PC Gamer </h1>
    </div>

    <div class="row g-4">
        <?php if (empty($catalogue)): ?>
            <div class="col-12 text-center">
                <div class="alert alert-warning" role="alert">
                    Aucun PC n'est disponible dans le catalogue pour le moment.
                </div>
            </div>
        <?php else: ?>

            <?php foreach ($catalogue as $pc): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-lg bg-dark border-secondary text-light">
                        <div class="bg-black text-center py-4" style="border-top-left-radius: var(--bs-card-inner-border-radius); border-top-right-radius: var(--bs-card-inner-border-radius);">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($pc->getImageUrl()); ?>" alt="PC" class="img-fluid" style="max-height: 200px; object-fit: contain;">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h4 class="card-title fw-bold text-primary border-bottom pb-2 mb-3">
                                <?php echo htmlspecialchars($pc->getNomModele()); ?>
                            </h4>

                                <?php echo htmlspecialchars($pc->getDescription()); ?>


                            <div class="bg-black bg-opacity-25 p-3 rounded mb-4">
                                <div class="small mb-1"><strong class="text-info">CPU :</strong> <?php echo htmlspecialchars($pc->getProcesseur()); ?></div>
                                <div class="small mb-1"><strong class="text-info">CM :</strong> <?php echo htmlspecialchars($pc->getCarteMere()); ?></div>
                                <div class="small mb-1"><strong class="text-info">GPU :</strong> <?php echo htmlspecialchars($pc->getCarteGraphique()); ?></div>
                                <div class="small mb-1"><strong class="text-info">RAM :</strong> <?php echo htmlspecialchars($pc->getMemoire()); ?></div>
                                <div class="small"><strong class="text-info">SSD :</strong> <?php echo htmlspecialchars($pc->getStockage()); ?></div>
                            </div>

                            <div class="mt-auto pt-3 d-flex justify-content-between align-items-center border-top border-secondary">
                                <span class="fs-3 fw-bold text-warning"><?php echo number_format($pc->getPrix(), 2, ',', ' '); ?> €</span>
                                <a href="index.php?page=detail&id=<?php echo $pc->getIdPc(); ?>" class="btn btn-primary px-4">Voir détails</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endif; ?>
    </div>
</div>