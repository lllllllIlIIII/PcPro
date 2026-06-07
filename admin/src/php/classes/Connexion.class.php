<?php
class Connexion
{
    public static function getInstance($dsn, $user, $pass){
        try{
            $pdo = new PDO($dsn, $user, $pass);
            return $pdo;
        }catch(PDOException $e){
            print "Erreur : ".$e->getMessage();
        }
    }
}
?>