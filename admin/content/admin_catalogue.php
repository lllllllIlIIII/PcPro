<?php
global $pdo;

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container mt-5'><div class='alert tech-alert-danger'>Accès réservé aux administrateurs.</div></div>";
    return;
}

$catalogueDAO = new CatalogueDAO($pdo);
$compDAO      = new ComposantDAO($pdo);
$adminDAO     = new AdminDAO($pdo);
$message      = "";

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($catalogueDAO->deletePC($_GET['id'])) {
        $message = "<div class='alert tech-alert-success shadow-sm'>PC supprimé avec succès (via PL/pgSQL).</div>";
        header("Location: index.php?page=admin_catalogue");
        exit;
    }
}

if (isset($_POST['btn_valider'])) {
    $id    = $_POST['id_pc']      ?? null;
    $nom   = $_POST['nom']         ?? '';
    $desc  = $_POST['description'] ?? '';
    $cpu   = $_POST['cpu']         ?? '';
    $mb    = $_POST['mb']          ?? '';
    $gpu   = $_POST['gpu']         ?? '';
    $ram   = $_POST['ram']         ?? '';
    $stock = $_POST['stock']       ?? 0;
    $img   = $_POST['image_url']   ?? '';

    $prix = str_replace(',', '.', $_POST['prix'] ?? '0');

    if (!is_numeric($prix) || floatval($prix) < 0 || floatval($prix) > 99999999.99) {
        $message = "<div class='alert tech-alert-danger shadow-sm'>Prix invalide (max 99 999 999,99 €).</div>";
    } elseif ($id) {
        $resultat = $catalogueDAO->updatePC($id, $nom, $desc, $cpu, $mb, $gpu, $ram, $stock, floatval($prix), $img);
        $message = $resultat === true
            ? "<div class='alert tech-alert-success shadow-sm'>PC mis à jour avec succès.</div>"
            : "<div class='alert tech-alert-danger shadow-sm'><b>ERREUR PostgreSQL :</b> " . htmlspecialchars($resultat) . "</div>";
    } else {
        $catalogueDAO->addPC($nom, $desc, $cpu, $mb, $gpu, $ram, $stock, floatval($prix), $img);
        $message = "<div class='alert tech-alert-success shadow-sm'>Nouveau PC ajouté au catalogue.</div>";
    }
}

$pcs = $catalogueDAO->getAllcatalogue();

$pc_a_modifier = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $pc_a_modifier = $catalogueDAO->getCatalogueById($_GET['id']);
}

$historique_global = [];

foreach ($adminDAO->getCatalogueOrders() as $order) {
    $historique_global[] = [
        'id'         => $order['id_commande'],
        'date'       => $order['date_commande'],
        'client_nom' => $order['client_nom'],
        'email'      => $order['email'],
        'type'       => 'catalogue',
        'details'    => $order['nom_modele'] . " (Qté: " . $order['quantite'] . ")",
        'prix'       => $order['prix_total']
    ];
}

foreach ($adminDAO->getCustomOrders() as $order) {
    $historique_global[] = [
        'id'         => $order['id_commande'],
        'date'       => $order['date_commande'],
        'client_nom' => $order['client_nom'],
        'email'      => $order['email'],
        'type'       => 'custom',
        'details'    => $order['description'],
        'prix'       => $order['prix_total']
    ];
}

usort($historique_global, fn($a, $b) => strtotime($b['date']) - strtotime($a['date']));
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-dark">Gestion du Catalogue</h1>
        <a href="index.php?page=admin_catalogue" class="btn btn-outline-primary btn-sm">Réinitialiser le formulaire</a>
    </div>

    <?php echo $message; ?>

    <div class="card shadow-sm border-light bg-light mb-5">
        <div class="card-body p-4">
            <h4 class="fw-bold mb-4 text-primary"><?php echo $pc_a_modifier ? "Modifier le PC #".$pc_a_modifier->getIdPc() : "Ajouter une nouvelle configuration"; ?></h4>
            <form action="index.php?page=admin_catalogue" method="POST">
                <?php if ($pc_a_modifier): ?>
                    <input type="hidden" name="id_pc" value="<?php echo $pc_a_modifier->getIdPc(); ?>">
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Nom du modèle</label>
                        <input type="text" name="nom" class="form-control" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getNomModele()) : ''; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Prix (€)</label>
                        <input type="number" step="0.01" name="prix" class="form-control" value="<?php echo $pc_a_modifier ? $pc_a_modifier->getPrix() : ''; ?>" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Nom de l'image (ex: pc1.png)</label>
                        <input type="text" name="image_url" class="form-control" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getImageUrl()) : 'pc1.png'; ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Description</label>
                    <textarea name="description" class="form-control" rows="2" required><?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getDescription()) : ''; ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Processeur</label>
                        <select name="cpu" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach($compDAO->getComposantsByCategorie(1) as $c): ?>
                                <?php $selected = ($pc_a_modifier && trim($pc_a_modifier->getProcesseur()) === trim($c->getNom())) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($c->getNom()) ?>" <?= $selected ?>><?= htmlspecialchars($c->getNom()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Carte Graphique</label>
                        <select name="gpu" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach($compDAO->getComposantsByCategorie(2) as $c): ?>
                                <?php $selected = ($pc_a_modifier && trim($pc_a_modifier->getCarteGraphique()) === trim($c->getNom())) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($c->getNom()) ?>" <?= $selected ?>><?= htmlspecialchars($c->getNom()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label small fw-bold">Carte Mère</label>
                        <select name="mb" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach($compDAO->getComposantsByCategorie(4) as $c): ?>
                                <?php $selected = ($pc_a_modifier && trim($pc_a_modifier->getCarteMere()) === trim($c->getNom())) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($c->getNom()) ?>" <?= $selected ?>><?= htmlspecialchars($c->getNom()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold">Mémoire RAM</label>
                        <select name="ram" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach($compDAO->getComposantsByCategorie(3) as $c): ?>
                                <?php $selected = ($pc_a_modifier && trim($pc_a_modifier->getMemoire()) === trim($c->getNom())) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($c->getNom()) ?>" <?= $selected ?>><?= htmlspecialchars($c->getNom()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label small fw-bold">Stockage SSD</label>
                        <select name="stock" class="form-select form-select-sm" required>
                            <option value="">-- Choisir --</option>
                            <?php foreach($compDAO->getComposantsByCategorie(5) as $c): ?>
                                <?php $selected = ($pc_a_modifier && trim($pc_a_modifier->getStockage()) === trim($c->getNom())) ? 'selected' : ''; ?>
                                <option value="<?= htmlspecialchars($c->getNom()) ?>" <?= $selected ?>><?= htmlspecialchars($c->getNom()) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <button type="submit" name="btn_valider" class="btn btn-primary fw-bold px-4 mt-2">
                    <?php echo $pc_a_modifier ? "Mettre à jour" : "Ajouter au catalogue"; ?>
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-light mb-5">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-secondary">
                <tr>
                    <th class="px-4 py-3">Image</th>
                    <th class="py-3">Nom du modèle</th>
                    <th class="py-3">Prix</th>
                    <th class="py-3 text-end px-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($pcs as $pc): ?>
                    <tr>
                        <td class="px-4">
                            <img src="admin/assets/img/<?php echo htmlspecialchars($pc->getImageUrl()); ?>" class="tech-img-sm" alt="PC">
                        </td>
                        <td class="fw-bold" contenteditable="true" data-champ="nom_modele" id="<?php echo $pc->getIdPc(); ?>">
                            <?php echo htmlspecialchars($pc->getNomModele()); ?>
                        </td>
                        <td><?php echo number_format($pc->getPrix(), 2, ',', ' '); ?> €</td>
                        <td class="text-end px-4">
                            <a href="index.php?page=admin_catalogue&action=edit&id=<?php echo $pc->getIdPc(); ?>" class="btn btn-sm btn-outline-info me-2">Modifier</a>
                            <a href="index.php?page=admin_catalogue&action=delete&id=<?php echo $pc->getIdPc(); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce PC définitivement ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-4">
        <h3 class="fw-bold font-monospace text-primary text-uppercase m-0">Historique des Transactions</h3>

        <div class="btn-group shadow-sm" role="group">
            <button type="button" class="btn btn-outline-info active btn-filter" data-filter="all">Tout afficher</button>
            <button type="button" class="btn btn-outline-info btn-filter" data-filter="catalogue">Série uniquement</button>
            <button type="button" class="btn btn-outline-info btn-filter" data-filter="custom">Sur Mesure uniquement</button>
        </div>
    </div>

    <div class="card shadow-sm border-light mb-5">
        <div class="card-body p-0">
            <?php if (empty($historique_global)): ?>
                <div class="p-4 text-center text-muted fw-bold">Aucune transaction détectée dans la base de données.</div>
            <?php else: ?>
                <table class="table table-hover align-middle mb-0" id="table-historique">
                    <thead class="bg-dark text-info font-monospace text-uppercase">
                    <tr>
                        <th class="px-4 py-3">Type</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Client</th>
                        <th class="py-3">Détails de la commande</th>
                        <th class="py-3 text-end px-4">Prix Payé</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($historique_global as $order): ?>
                        <tr class="row-order" data-type="<?php echo $order['type']; ?>">
                            <td class="px-4">
                                <?php if ($order['type'] == 'catalogue'): ?>
                                    <span class="badge bg-secondary text-light font-monospace">CATALOGUE</span>
                                <?php else: ?>
                                    <span class="badge bg-primary text-white">CUSTOM</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-monospace text-muted"><?php echo date('d/m/Y H:i', strtotime($order['date'])); ?></td>
                            <td>
                                <strong><?php echo htmlspecialchars($order['client_nom']); ?></strong><br>
                                <small class="text-secondary"><?php echo htmlspecialchars($order['email']); ?></small>
                            </td>
                            <td>
                                <small class="d-block tech-desc-custom">
                                    <?php echo htmlspecialchars($order['details']); ?>
                                </small>
                            </td>
                            <td class="text-end px-4 fw-bold font-monospace text-success">
                                <?php echo number_format($order['prix'], 2, ',', ' '); ?> €
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>

</div>