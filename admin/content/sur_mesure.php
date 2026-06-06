<?php
global $pdo;

if (!isset($_SESSION['user_id'])) {
    echo "<div class='container mt-5 d-flex justify-content-center'>";
    echo "<div class='card p-5 shadow-lg text-center restricted-card w-100'>";
    echo "<i class='bi bi-shield-lock-fill text-primary display-1 mb-3'></i>";
    echo "<h2 class='fw-bold mb-3'>Accès Restreint</h2>";
    echo "<p class='text-muted mb-4'>Vous devez être connecté à votre compte pour accéder pour configurer votre pc.</p>";
    echo "<div class='d-flex justify-content-center'>";
    echo "<a href='index.php?page=connexion' class='btn btn-primary fw-bold px-5 py-2'>S'identifier</a>";
    echo "</div>";
    echo "</div></div>";
    return;
}

$compDAO = new ComposantDAO($pdo);
$message_panier = "";

if (isset($_POST['btn_ajouter_sur_mesure'])) {
    $cpu = $_POST['cpu'] ?? '';
    $mb = $_POST['mb'] ?? '';
    $gpu = $_POST['gpu'] ?? '';
    $ram = $_POST['ram'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $case = $_POST['case'] ?? '';
    $prix_total = $_POST['prix_total'] ?? 0;

    if (empty($cpu) || empty($mb) || empty($gpu) || empty($ram) || empty($stock) || empty($case)) {
        $message_panier = "<div class='alert mt-3 shadow-sm font-monospace rounded-0 tech-alert-danger'>ERREUR SYSTÈME : Une configuration incomplète ne peut pas être validée.</div>";
    } else {
        $description = "Processeur : " . htmlspecialchars($cpu) . "\nCarte Mère : " . htmlspecialchars($mb) . "\nCarte Graphique : " . htmlspecialchars($gpu) . "\nRAM : " . htmlspecialchars($ram) . "\nStockage : " . htmlspecialchars($stock) . "\nBoîtier : " . htmlspecialchars($case);

        if (!isset($_SESSION['panier_custom'])) {
            $_SESSION['panier_custom'] = [];
        }

        $_SESSION['panier_custom'][] = [
            'description' => $description,
            'prix' => floatval($prix_total),
            'quantite' => 1
        ];

        $message_panier = "<div class='alert alert-success mt-3 shadow-sm font-monospace rounded-0 tech-alert-success'>Configuration validée et ajoutée au Panier</div>";
    }
}
?>

<div class="container mt-5">
    <h2 class="border-bottom pb-3 mb-4 font-monospace text-primary text-uppercase">Configurateur Matériel</h2>

    <?php echo $message_panier; ?>

    <form action="index.php?page=sur_mesure" method="POST">
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label class="form-label fw-bold font-monospace text-secondary">Processeur</label>
                    <select id="select-cpu" name="cpu" class="form-select composant-select rounded-0" required>
                        <option value="" data-id="0">-- Obligatoire --</option>
                        <?php foreach($compDAO->getComposantsByCategorie(1) as $c): ?>
                            <option value="<?= htmlspecialchars($c->getNom()) ?>" data-id="<?= $c->getIdComp() ?>"><?= $c->getNom() ?> (<?= $c->getPrix() ?>€)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-monospace text-secondary">Carte Graphique</label>
                    <select id="select-gpu" name="gpu" class="form-select composant-select rounded-0" required>
                        <option value="" data-id="0">-- Obligatoire --</option>
                        <?php foreach($compDAO->getComposantsByCategorie(2) as $c): ?>
                            <option value="<?= htmlspecialchars($c->getNom()) ?>" data-id="<?= $c->getIdComp() ?>"><?= $c->getNom() ?> (<?= $c->getPrix() ?>€)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-monospace text-secondary">Mémoire vive (RAM)</label>
                    <select id="select-ram" name="ram" class="form-select composant-select rounded-0" required>
                        <option value="" data-id="0">-- Obligatoire --</option>
                        <?php foreach($compDAO->getComposantsByCategorie(3) as $c): ?>
                            <option value="<?= htmlspecialchars($c->getNom()) ?>" data-id="<?= $c->getIdComp() ?>"><?= $c->getNom() ?> (<?= $c->getPrix() ?>€)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-monospace text-secondary">Carte Mère</label>
                    <select id="select-mb" name="mb" class="form-select composant-select rounded-0" required>
                        <option value="" data-id="0">-- Obligatoire --</option>
                        <?php foreach($compDAO->getComposantsByCategorie(4) as $c): ?>
                            <option value="<?= htmlspecialchars($c->getNom()) ?>" data-id="<?= $c->getIdComp() ?>"><?= $c->getNom() ?> (<?= $c->getPrix() ?>€)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-monospace text-secondary">Stockage (SSD)</label>
                    <select id="select-ssd" name="stock" class="form-select composant-select rounded-0" required>
                        <option value="" data-id="0">-- Obligatoire --</option>
                        <?php foreach($compDAO->getComposantsByCategorie(5) as $c): ?>
                            <option value="<?= htmlspecialchars($c->getNom()) ?>" data-id="<?= $c->getIdComp() ?>"><?= $c->getNom() ?> (<?= $c->getPrix() ?>€)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold font-monospace text-secondary">Boîtier</label>
                    <select id="select-case" name="case" class="form-select composant-select rounded-0" required>
                        <option value="" data-id="0">-- Obligatoire --</option>
                        <?php foreach($compDAO->getComposantsByCategorie(7) as $c): ?>
                            <option value="<?= htmlspecialchars($c->getNom()) ?>" data-id="<?= $c->getIdComp() ?>"><?= $c->getNom() ?> (<?= $c->getPrix() ?>€)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm rounded-0 sticky-offset">
                    <div class="card-body text-center p-4">
                        <h5 class="card-title text-uppercase font-monospace text-secondary">Total estimé</h5>
                        <hr class="opacity-25">
                        <h2 class="text-info font-monospace my-4"><span id="prix-total">0.00</span> €</h2>

                        <input type="hidden" name="prix_total" id="prix_total_cache" value="0">

                        <button type="submit" name="btn_ajouter_sur_mesure" class="btn btn-outline-info w-100 fw-bold mt-2 shadow-sm rounded-0 text-uppercase tech-letter-spacing">
                            <i class="bi bi-cart-plus me-2"></i> Ajouter au panier
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>