<?php

//Traitement du formulaire
if(isset($_GET['submit'])) {
    extract($_GET,EXTR_OVERWRITE);
    if(!empty($login) && !empty($password)){
        $admin = new AdminDAO($cnx);
        $adm = $admin->getAdmin($login,$password);
        //var_dump($adm);
        if($adm != null) {
            $_SESSION['admin'] = 1;
            $_SESSION['page'] = "accueil.php"; //page par défaut
            header("location:./index_.php?page=accueil.php");
            exit(); //pour arrêter l'exécution de la suite
        } else {
            print "<br>Accès réservé aux administrateurs<br>";
        }


    }
}


?>

<form method="get" action="<?= $_SERVER['PHP_SELF'] ?>">
    <!—choix d'un formulaire Bootstrap -->
    <div class="mb-3">
        <label for="login" class="form-label">Login : </label>
        <input type="text" class="form-control" id="login" name="login">
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password : </label>
        <input type="password" class="form-control" id="password" name="password">
    </div>

    <button type="submit" class="btn btn-primary" name="submit">Submit</button>
</form>

