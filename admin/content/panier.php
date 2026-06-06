<?php
global $pdo;

if (!isset($_SESSION['panier'])) { $_SESSION['panier'] = []; }
if (!isset($_SESSION['panier_custom'])) { $_SESSION['panier_custom'] = []; }

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_item = $_GET['id'];
    $action = $_GET['action'];

    if ($action == 'add' || $action == 'plus') {
        $id_item = intval($id_item);
        if (isset($_SESSION['panier'][$id_item])) { $_SESSION['panier'][$id_item]++; }
        else { $_SESSION['panier'][$id_item] = 1; }
    }
    elseif ($action == 'minus') {
        $id_item = intval($id_item);
        if (isset($_SESSION['panier'][$id_item])) {
            $_SESSION['panier'][$id_item]--;
            if ($_SESSION['panier'][$id_item] <= 0) { unset($_SESSION['panier'][$id_item]); }
        }
    }
    elseif ($action == 'remove') {
        $id_item = intval($id_item);
        if (isset($_SESSION['panier'][$id_item])) { unset($_SESSION['panier'][$id_item]); }
    }
    elseif ($action == 'plus_custom') {
        $id_item = intval($id_item);
        if (isset($_SESSION['panier_custom'][$id_item])) { $_SESSION['panier_custom'][$id_item]['quantite']++; }
    }
    elseif ($action == 'minus_custom') {
        $id_item = intval($id_item);
        if (isset($_SESSION['panier_custom'][$id_item])) {
            $_SESSION['panier_custom'][$id_item]['quantite']--;
            if ($_SESSION['panier_custom'][$id_item]['quantite'] <= 0) {
                unset($_SESSION['panier_custom'][$id_item]);
                $_SESSION['panier_custom'] = array_values($_SESSION['panier_custom']);
            }
        }
    }
    elseif ($action == 'remove_custom') {
        $id_item = intval($id_item);
        if (isset($_SESSION['panier_custom'][$id_item])) {
            unset($_SESSION['panier_custom'][$id_item]);
            $_SESSION['panier_custom'] = array_values($_SESSION['panier_custom']);
        }
    }

    header('Location: index.php?page=panier');
    exit();
}

$catalogueDAO = new CatalogueDAO($pdo);
$total_panier = 0;
$nb_articles = 0;

if (!empty($_SESSION['panier'])) { $nb_articles += array_sum($_SESSION['panier']); }
if (!empty($_SESSION['panier_custom'])) {
    foreach ($_SESSION['panier_custom'] as $custom_pc) {
        $nb_articles += $custom_pc['quantite'];
    }
}

$panier_vide = empty($_SESSION['panier']) && empty($_SESSION['panier_custom']);
?>

<div class="container mt-5 mb-5">
    <h1 class="display-5 fw-bold mb-4 text-primary font-monospace text-uppercase tech-title-spacing">Votre Panier</h1>

    <?php if ($panier_vide): ?>
        <div class="alert bg-light border text-center p-5 shadow-sm rounded-0 tech-border-secondary">
            <h4 class="text-secondary font-monospace">Panier vide.</h4>
            <p class="text-muted">Découvrez nos configurations et trouvez la machine de vos rêves !</p>
            <a href="index.php?page=catalogue" class="btn btn-outline-info mt-3 fw-bold rounded-0">retour au catalogue</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-light rounded-0">
                    <div class="card-body p-0">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-secondary font-monospace text-uppercase">
                            <tr>
                                <th class="px-4 py-3 border-bottom-0">Produit</th>
                                <th class="py-3 text-center border-bottom-0">Quantité</th>
                                <th class="py-3 text-end border-bottom-0">Sous-total</th>
                                <th class="py-3 border-bottom-0"></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php foreach ($_SESSION['panier'] as $id_pc => $quantite):
                                $pc = $catalogueDAO->getCatalogueById($id_pc);
                                if ($pc):
                                    $sous_total = $pc->getPrix() * $quantite;
                                    $total_panier += $sous_total;
                                    ?>
                                    <tr>
                                        <td class="px-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <img src="admin/assets/img/<?php echo htmlspecialchars($pc->getImageUrl()); ?>" alt="PC" class="me-3 tech-img-sm">
                                                <div>
                                                    <span class="fw-bold d-block font-monospace"><?php echo htmlspecialchars($pc->getNomModele()); ?></span>
                                                    <small class="text-muted"><?php echo number_format($pc->getPrix(), 2, ',', ' '); ?> € l'unité</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="d-flex justify-content-center align-items-center">
                                                <a href="index.php?page=panier&action=minus&id=<?php echo $id_pc; ?>" class="btn btn-sm btn-outline-secondary px-2 py-0 fs-5 text-decoration-none rounded-0 tech-btn-qty">-</a>
                                                <span class="mx-3 fw-bold fs-5 font-monospace"><?php echo $quantite; ?></span>
                                                <a href="index.php?page=panier&action=plus&id=<?php echo $id_pc; ?>" class="btn btn-sm btn-outline-secondary px-2 py-0 fs-5 text-decoration-none rounded-0 tech-btn-qty">+</a>
                                            </div>
                                        </td>
                                        <td class="py-3 text-end fw-bold font-monospace text-info">
                                            <?php echo number_format($sous_total, 2, ',', ' '); ?> €
                                        </td>
                                        <td class="py-3 text-center px-4">
                                            <a href="index.php?page=panier&action=remove&id=<?php echo $id_pc; ?>" class="text-danger fs-5">
                                                <i class="bi bi-trash3"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; endforeach; ?>

                            <?php foreach ($_SESSION['panier_custom'] as $index => $item):
                                $quantite_custom = isset($item['quantite']) ? $item['quantite'] : 1;
                                $sous_total_custom = $item['prix'] * $quantite_custom;
                                $total_panier += $sous_total_custom;
                                ?>
                                <tr class="tech-row-custom">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="me-3 d-flex align-items-center justify-content-center tech-icon-box">
                                                <i class="bi bi-cpu text-info fs-4"></i>
                                            </div>
                                            <div>
                                                <span class="fw-bold d-block font-monospace text-info">CONFIG CUSTOM #<?php echo $index + 1; ?></span>
                                                <small class="text-muted d-block mt-1 tech-desc-custom"><?php echo htmlspecialchars($item['description']); ?></small>
                                                <small class="text-secondary d-block mt-2"><?php echo number_format($item['prix'], 2, ',', ' '); ?> € l'unité</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <a href="index.php?page=panier&action=minus_custom&id=<?php echo $index; ?>" class="btn btn-sm btn-outline-info px-2 py-0 fs-5 text-decoration-none rounded-0 tech-btn-qty">-</a>
                                            <span class="mx-3 fw-bold fs-5 font-monospace"><?php echo $quantite_custom; ?></span>
                                            <a href="index.php?page=panier&action=plus_custom&id=<?php echo $index; ?>" class="btn btn-sm btn-outline-info px-2 py-0 fs-5 text-decoration-none rounded-0 tech-btn-qty">+</a>
                                        </div>
                                    </td>
                                    <td class="py-3 text-end fw-bold font-monospace text-info">
                                        <?php echo number_format($sous_total_custom, 2, ',', ' '); ?> €
                                    </td>
                                    <td class="py-3 text-center px-4">
                                        <a href="index.php?page=panier&action=remove_custom&id=<?php echo $index; ?>" class="text-danger fs-5">
                                            <i class="bi bi-trash3"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="card shadow-sm bg-light rounded-0 tech-border-secondary">
                    <div class="card-body p-4">
                        <h4 class="fw-bold mb-4 font-monospace text-uppercase text-primary border-bottom pb-2">Rapport Data</h4>

                        <div class="d-flex justify-content-between mb-3 font-monospace small">
                            <span class="text-secondary">Unités (<?php echo $nb_articles; ?>)</span>
                            <span><?php echo number_format($total_panier, 2, ',', ' '); ?> €</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 font-monospace small">
                            <span class="text-secondary">Fret spatial (Livraison)</span>
                            <span class="fw-bold text-success">OFFERT</span>
                        </div>

                        <hr class="tech-hr">

                        <div class="d-flex justify-content-between mb-4 align-items-center">
                            <span class="fw-bold font-monospace text-uppercase">Total <small>TTC</small></span>
                            <span class="fs-3 fw-bold font-monospace text-info tech-text-glow"><?php echo number_format($total_panier, 2, ',', ' '); ?> €</span>
                        </div>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="index.php?page=valider_commande" class="btn btn-outline-info btn-lg w-100 fw-bold shadow-sm font-monospace text-uppercase rounded-0 tech-letter-spacing">
                                <i class="bi bi-shield-lock me-2"></i> Initialiser l'achat
                            </a>
                        <?php else: ?>
                            <a href="index.php?page=connexion" class="btn btn-outline-warning btn-lg w-100 fw-bold shadow-sm font-monospace text-uppercase rounded-0 tech-letter-spacing">
                                <i class="bi bi-person me-2"></i> Connexion requise
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>