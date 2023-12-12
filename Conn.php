<?php
session_start();

  function getConnexion(){
    $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

    try {
        $bdd = new PDO($dsn, "root", "");
        return $bdd;
    } catch (PDOExeption $e) {
        die('DB Error: '.$e->getMessage());
    }
  }

  if (isset($_POST["Login"]) && isset($_POST["mdp"])){
    $login = $_POST["Login"];
    $mdp = $_POST["mdp"];;
    $hash = hash("sha256",$mdp);

    $verif = "SELECT US_Nom, US_Password FROM user WHERE US_Nom = '".$login."' AND US_Password = '".$hash."';";
    $bdd = getConnexion();
    $query = $bdd->prepare($verif);
    $query->execute();
    $log = $query->fetch();

    if ($log !== false) {
      $_SESSION["login"] = $log["US_Nom"];

      header("refresh:0; http://localhost/GestionMail/Accueil.php");
    } else {
      header("refresh:0; http://localhost/GestionMail/Connexion.php?conn=erreur");
    }
}




?>
