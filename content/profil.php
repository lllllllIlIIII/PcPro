<?php
global $pdo;

// 1. SÉCURITÉ : Il faut être connecté pour voir son profil
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=connexion');
    exit();
}

$catalogueDAO = new CatalogueDAO($pdo);
$id_user = $_SESSION['user_id'];

// Infos de session (ajuste si tes variables de session ont des noms différents)
$nom_user = $_SESSION['user_nom'] ?? 'Utilisateur Cyber';
$email_user = $_SESSION['user_email'] ?? 'email@non-renseigne.com';
$role_user = $_SESSION['user_role'] ?? 'client';

// 2. RÉCUPÉRATION DES COMMANDES DU CLIENT
$catalogue_orders = $catalogueDAO->getCatalogueOrdersByUser($id_user);
$custom_orders = $catalogueDAO->getCustomOrdersByUser($id_user);

$historique_global = [];

// Formatage Catalogue
foreach ($catalogue_orders as $order) {
    $historique_global[] = [
        'id' => $order['id_commande'],
        'date' => $order['date_commande'],
        'type' => 'catalogue',
        'details' => $order['nom_modele'] . " (Qté: " . $order['quantite'] . ")",
        'prix' => $order['prix_total']
    ];
}

// Formatage Sur Mesure
foreach ($custom_orders as $order) {
    $historique_global[] = [
        'id' => $order['id_commande'],
        'date' => $order['date_commande'],
        'type' => 'custom',
        'details' => $order['description'],
        'prix' => $order['prix_total']
    ];
}

// Tri par date (du plus récent au plus ancien)
usort($historique_global, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});

// Calcul des statistiques client
$total_depense = array_sum(array_column($historique_global, 'prix'));
$nb_commandes = count($historique_global);
?>

<div class="container mt-5 mb-5">

    <!-- EN-TÊTE PROFIL -->
    <h1 class="display-5 fw-bold mb-4 text-primary font-monospace text-uppercase tech-title-spacing">Interface Utilisateur</h1>

    <div class="row mb-5">
        <!-- CARTE D'IDENTITÉ -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card shadow-sm bg-light rounded-0 tech-border-secondary h-100">
                <div class="card-body p-4 text-center">
                    <div class="tech-icon-box mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; border-radius: 50%;">
                        <i class="bi bi-person-bounding-box text-info display-4"></i>
                    </div>
                    <h3 class="fw-bold font-monospace text-uppercase"><?php echo htmlspecialchars($nom_user); ?></h3>
                    <p class="text-secondary mb-1"><?php echo htmlspecialchars($email_user); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- HISTORIQUE DES COMMANDES (Réutilisation du système de l'Admin) -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold font-monospace text-primary text-uppercase m-0">Historique d'Achat</h3>

        <div class="btn-group shadow-sm" role="group">
            <button type="button" class="btn btn-outline-info active btn-filter rounded-0" data-filter="all">Tout</button>
            <button type="button" class="btn btn-outline-info btn-filter rounded-0" data-filter="catalogue">Série</button>
            <button type="button" class="btn btn-outline-info btn-filter rounded-0" data-filter="custom">Sur Mesure</button>
        </div>
    </div>

    <div class="card shadow-sm border-light rounded-0 mb-5">
        <div class="card-body p-0">
            <?php if (empty($historique_global)): ?>
                <div class="p-5 text-center text-muted font-monospace">
                    <i class="bi bi-box-seam fs-1 d-block mb-3"></i>
                    Aucune transaction.
                </div>
            <?php else: ?>
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-dark text-info font-monospace text-uppercase">
                    <tr>
                        <th class="px-4 py-3">Type</th>
                        <th class="py-3">Date</th>
                        <th class="py-3">Détails du module</th>
                        <th class="py-3 text-end px-4">Montant</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($historique_global as $order): ?>
                        <tr class="row-order" data-type="<?php echo $order['type']; ?>">
                            <td class="px-4">
                                <?php if ($order['type'] == 'catalogue'): ?>
                                    <span class="badge bg-secondary text-light font-monospace">CATALOGUE</span>
                                <?php else: ?>
                                    <span class="badge bg-dark border tech-border-secondary text-info font-monospace">CUSTOM</span>
                                <?php endif; ?>
                            </td>
                            <td class="font-monospace text-muted"><?php echo date('d/m/Y', strtotime($order['date'])); ?></td>
                            <td>
                                <small class="d-block text-light tech-desc-custom"><?php echo htmlspecialchars($order['details']); ?></small>
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

<!-- On réutilise ton script d'admin pour le filtre (optimisation au top !) -->
<script src="admin/assets/js/admin_filtre.js"></script>