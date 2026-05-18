<?php
global $pdo;

// 1. SÉCURITÉ : On vérifie si l'utilisateur est admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Accès réservé aux administrateurs.</div></div>";
    return;
}

$catalogueDAO = new CatalogueDAO($pdo);
$message = "";

// ---------------------------------------------------------
// TRAITEMENT DES ACTIONS (CREATE / UPDATE / DELETE)
// ---------------------------------------------------------

// ACTION : SUPPRIMER
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    if ($catalogueDAO->deletePC($_GET['id'])) {
        $message = "<div class='alert alert-success shadow-sm'>PC supprimé avec succès (via PL/pgSQL).</div>";
    }
}

// ACTION : AJOUTER OU MODIFIER
if (isset($_POST['btn_valider'])) {
    $id = $_POST['id_pc'] ?? null;
    $nom = $_POST['nom'];
    $desc = $_POST['description'];
    $cpu = $_POST['cpu'];
    $mb = $_POST['mb'];
    $gpu = $_POST['gpu'];
    $ram = $_POST['ram'];
    $stock = $_POST['stock'];
    $prix = $_POST['prix'];
    $img = $_POST['image_url'];

    if ($id) {
        // UPDATE
        $catalogueDAO->updatePC($id, $nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img);
        $message = "<div class='alert alert-success shadow-sm'>PC mis à jour avec succès (via PL/pgSQL).</div>";
    } else {
        // CREATE
        $catalogueDAO->addPC($nom, $desc, $cpu, $mb, $gpu, $ram, $stock, $prix, $img);
        $message = "<div class='alert alert-success shadow-sm'>Nouveau PC ajouté au catalogue (via PL/pgSQL).</div>";
    }
}

// ---------------------------------------------------------
// PRÉPARATION DE L'AFFICHAGE
// ---------------------------------------------------------
$pcs = $catalogueDAO->getAllcatalogue();

// Si on clique sur "Modifier", on récupère les infos du PC pour remplir le formulaire
$pc_a_modifier = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $pc_a_modifier = $catalogueDAO->getCatalogueById($_GET['id']);
}
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
                    <div class="col-md-4 mb-3"><input type="text" name="cpu" placeholder="Processeur" class="form-control form-control-sm" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getProcesseur()) : ''; ?>" required></div>
                    <div class="col-md-4 mb-3"><input type="text" name="gpu" placeholder="Carte Graphique" class="form-control form-control-sm" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getCarteGraphique()) : ''; ?>" required></div>
                    <div class="col-md-4 mb-3"><input type="text" name="mb" placeholder="Carte Mère" class="form-control form-control-sm" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getCarteMere()) : ''; ?>" required></div>
                    <div class="col-md-6 mb-3"><input type="text" name="ram" placeholder="Mémoire RAM" class="form-control form-control-sm" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getMemoire()) : ''; ?>" required></div>
                    <div class="col-md-6 mb-3"><input type="text" name="stock" placeholder="Stockage SSD" class="form-control form-control-sm" value="<?php echo $pc_a_modifier ? htmlspecialchars($pc_a_modifier->getStockage()) : ''; ?>" required></div>
                </div>

                <button type="submit" name="btn_valider" class="btn btn-dark fw-bold px-4">
                    <?php echo $pc_a_modifier ? "Mettre à jour" : "Ajouter au catalogue"; ?>
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-light">
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
                            <img src="assets/img/<?php echo htmlspecialchars($pc->getImageUrl()); ?>" style="width: 50px; height: 50px; object-fit: contain;">
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($pc->getNomModele()); ?></td>
                        <td><?php echo number_format($pc->getPrix(), 2, ',', ' '); ?> €</td>
                        <td class="text-end px-4">
                            <a href="index.php?page=admin_catalogue&action=edit&id=<?php echo $pc->getIdPc(); ?>" class="btn btn-sm btn-outline-secondary me-2">Modifier</a>
                            <a href="index.php?page=admin_catalogue&action=delete&id=<?php echo $pc->getIdPc(); ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Supprimer ce PC définitivement ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>