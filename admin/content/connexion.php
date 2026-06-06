<?php
global $pdo;

$userDAO = new UserDAO($pdo);
$erreur = '';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php?page=accueil');
    exit();
}

if (isset($_POST['btn_connecter'])) {
    $email = trim($_POST['email_connexion']);
    $mdp = $_POST['mdp_connexion'];

    if (!empty($email) && !empty($mdp)) {

        $userData = $userDAO->getUserByEmail($email);

        if ($userData && password_verify($mdp, $userData['mdp'])) {
            $_SESSION['user_id'] = $userData['id_user'];
            $_SESSION['user_nom'] = $userData['nom'];
            $_SESSION['user_role'] = $userData['role'];
            $_SESSION['user_email'] = $userData['email'];

            if ($userData['role'] === 'admin') {
                header('Location: index.php?page=admin_catalogue');
            } else {
                header('Location: index.php?page=accueil');
            }
            exit();

        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Veuillez remplir vos identifiants.";
    }
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <h2 class="text-center mb-4 text-dark fw-bold">Connexion</h2>

            <?php if ($erreur): ?>
                <div class="alert tech-alert-danger text-center shadow-sm py-2"><?php echo $erreur; ?></div>
            <?php endif; ?>

            <div class="card shadow-sm border-light bg-light">
                <div class="card-body p-4 p-md-5">
                    <form action="index.php?page=connexion" method="POST">
                        <div class="mb-3">
                            <label class="form-label text-secondary fw-bold small">Adresse Email</label>
                            <input type="email" name="email_connexion" class="form-control" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-secondary fw-bold small">Mot de passe</label>
                            <input type="password" name="mdp_connexion" class="form-control" required>
                        </div>

                        <button type="submit" name="btn_connecter" class="btn btn-primary w-100 fw-bold shadow-sm">Se connecter</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0">Pas encore de compte ? <a href="index.php?page=inscription" class="text-info fw-bold text-decoration-none">S'inscrire</a></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>