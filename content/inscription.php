<?php
global $pdo;

$userDAO = new UserDAO($pdo);
$erreur = '';
$succes = '';

// Si l'utilisateur est déjà connecté, on le redirige
if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=accueil');
    exit();
}

// ---------------------------------------------------------
// TRAITEMENT DE L'INSCRIPTION
// ---------------------------------------------------------
if (isset($_POST['btn_inscrire'])) {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $mdp = $_POST['mot_de_passe'];

    if (!empty($nom) && !empty($email) && !empty($mdp)) {
        if ($userDAO->inscrireClient($nom, $email, $mdp)) {
            $succes = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $erreur = "Erreur lors de l'inscription. Cet email est peut-être déjà utilisé.";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs d'inscription.";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <h1 class="text-center mb-4 text-dark fw-bold">Créer un compte</h1>

            <?php if ($erreur): ?>
                <div class="alert alert-danger text-center shadow-sm"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <?php if ($succes): ?>
                <div class="alert alert-success text-center shadow-sm">
                    <?php echo $succes; ?><br><br>
                    <a href="index.php?page=connexion" class="btn btn-success fw-bold">Aller à la connexion</a>
                </div>
            <?php endif; ?>

            <?php if (!$succes): // On cache le formulaire si l'inscription a réussi ?>
                <div class="card shadow-sm border-light bg-light">
                    <div class="card-body p-5">
                        <form action="index.php?page=inscription" method="POST">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-bold">Nom complet</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-bold">Adresse Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label text-secondary fw-bold">Mot de passe</label>
                                <input type="password" name="mot_de_passe" class="form-control" required>
                            </div>
                            <button type="submit" name="btn_inscrire" class="btn btn-dark w-100 btn-lg fw-bold shadow-sm mb-3">S'inscrire</button>
                        </form>

                        <hr class="text-secondary my-4">

                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">Déjà un compte ? <a href="index.php?page=connexion" class="text-dark fw-bold text-decoration-none">Se connecter</a></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>