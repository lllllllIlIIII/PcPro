<?php
$catalogueDAO = new CatalogueDAO($pdo);

$toutLeCatalogue = $catalogueDAO->getAllcatalogue();

$topsVentes = array_slice($toutLeCatalogue, 0, 3);
?>

<section class="py-5 text-center mt-4 mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="badge tech-border-secondary text-primary px-3 py-2 mb-3">Performances Extrêmes</span>
                <h1 class="display-3 mb-4">Bienvenue chez <span class="text-primary">PcPro</span></h1>
                <p class="lead text-muted mb-5">Découvrez nos configurations conçues pour l'esport, le streaming et le jeu en ultra. Choisissez parmi nos modèles de série perfectionnés ou créez la machine de vos rêves de A à Z.</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="index.php?page=catalogue" class="btn btn-primary btn-lg px-4">
                        <i class="bi bi-controller me-2"></i> Nos PC Gamers
                    </a>
                    <a href="index.php?page=sur_mesure" class="btn btn-outline-info btn-lg px-4">
                        <i class="bi bi-tools me-2"></i> Créer sur mesure
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 mb-5 border-top border-bottom tech-border-secondary bg-flowup-light">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-4">
                <div class="tech-icon-box mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                    <i class="bi bi-lightning-charge-fill text-primary fs-3"></i>
                </div>
                <h5 class="fw-bold">Montage Express</h5>
                <p class="text-muted small">Votre machine est assemblée et testée par nos experts en 48h chrono.</p>
            </div>
            <div class="col-md-4">
                <div class="tech-icon-box mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                    <i class="bi bi-shield-check text-primary fs-3"></i>
                </div>
                <h5 class="fw-bold">Garantie Premium</h5>
                <p class="text-muted small">Tous nos composants sont garantis 2 ans avec remplacement à neuf.</p>
            </div>
            <div class="col-md-4">
                <div class="tech-icon-box mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle">
                    <i class="bi bi-headset text-primary fs-3"></i>
                </div>
                <h5 class="fw-bold">Support Dédié</h5>
                <p class="text-muted small">Une équipe de passionnés à votre écoute 7j/7 pour vous conseiller.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5 mb-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Nos Best-Sellers</h2>
            <p class="text-muted">Les configurations les plus plébiscitées par notre communauté.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($topsVentes as $pc): ?>
                <div class="col-md-4">
                    <div class="card h-100 p-3">

                        <div class="p-3 text-center rounded mb-3 bg-glass">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($pc->getImageUrl()); ?>"
                                 class="home-product-img"
                                 alt="<?php echo htmlspecialchars($pc->getNomModele()); ?>">
                        </div>

                        <div class="card-body d-flex flex-column p-0">
                            <h4 class="card-title fw-bold text-center mb-3"><?php echo htmlspecialchars($pc->getNomModele()); ?></h4>

                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-2 text-muted small">
                                    <i class="bi bi-cpu text-primary me-2 fs-5"></i>
                                    <span><?php echo htmlspecialchars($pc->getProcesseur()); ?></span>
                                </div>
                                <div class="d-flex align-items-center mb-2 text-muted small">
                                    <i class="bi bi-gpu-card text-primary me-2 fs-5"></i>
                                    <span><?php echo htmlspecialchars($pc->getCarteGraphique()); ?></span>
                                </div>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-memory text-primary me-2 fs-5"></i>
                                    <span><?php echo htmlspecialchars($pc->getMemoire()); ?> RAM</span>
                                </div>
                            </div>

                            <div class="mt-auto text-center border-top tech-border-secondary pt-3">
                                <p class="fs-3 fw-bold text-primary mb-3"><?php echo number_format($pc->getPrix(), 2, ',', ' '); ?> €</p>
                                <a href="index.php?page=catalogue" class="btn btn-outline-info w-100 fw-bold">Découvrir</a>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-5">
            <a href="index.php?page=catalogue" class="btn btn-primary btn-lg px-5 fw-bold">Voir tout le catalogue</a>
        </div>
    </div>
</section>