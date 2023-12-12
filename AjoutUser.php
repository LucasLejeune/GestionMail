<?php session_start();
function getConnexion(){
  $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

  try {
      $bdd = new PDO($dsn, "root", "");
      return $bdd;
  } catch (PDOExeption $e) {
      die('DB Error: '.$e->getMessage());
  }
}

$projet = $_POST["projet"];

$insertUser = "INSERT INTO participe (fk_US, fk_PR) VALUES (:User,:Projet);";
$bdd = getConnexion();
$queryIu = $bdd->prepare($insertUser);

$getProjNom = "SELECT PR_Libelle FROM projet WHERE PR_id =".$projet;
$bdd = getConnexion();
$queryGPN = $bdd->prepare($getProjNom);
$queryGPN->execute();
$NomProjet = $queryGPN->fetch();


if ($_POST["users1"] != "") {
    $user1 = $_POST["users1"];
    $getUserId = "SELECT US_id FROM user WHERE US_Nom ='".$user1."';";
    $bdd = getConnexion();
    $queryGUid = $bdd->prepare($getUserId);
    $queryGUid->execute();
    $UserId = $queryGUid->fetch();

    if ($UserId != false) {
      $queryIu->execute(array(
        ":User" => $UserId["US_id"],
        ":Projet" => $projet
      ));
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$NomProjet["PR_Libelle"]);
    } else {
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?erreur=".$user1."&proj=".$NomProjet["PR_Libelle"]);
    }
}

if ($_POST["users2"] != "") {
    $user2 = $_POST["users2"];
    $getUserId = "SELECT US_id FROM user WHERE US_Nom ='".$user2."';";
    $bdd = getConnexion();
    $queryGUid = $bdd->prepare($getUserId);
    $queryGUid->execute();
    $UserId = $queryGUid->fetch();

    if ($UserId != false) {
      $queryIu->execute(array(
        ":User" => $UserId["US_id"],
        ":Projet" => $projet
      ));
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$NomProjet["PR_Libelle"]);
    } else {
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?erreur=".$user2."&proj=".$NomProjet["PR_Libelle"]);
    }
}

if ($_POST["users3"] != "") {
    $user3 = $_POST["users3"];
    $getUserId = "SELECT US_id FROM user WHERE US_Nom ='".$user3."';";
    $bdd = getConnexion();
    $queryGUid = $bdd->prepare($getUserId);
    $queryGUid->execute();
    $UserId = $queryGUid->fetch();

    if ($UserId != false) {
      $queryIu->execute(array(
        ":User" => $UserId["US_id"],
        ":Projet" => $projet
      ));
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$NomProjet["PR_Libelle"]);
    } else {
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?erreur=".$user3."&proj=".$NomProjet["PR_Libelle"]);
    }


}

if ($_POST["users4"] != "") {
    $user4 = $_POST["users4"];
    $getUserId = "SELECT US_id FROM user WHERE US_Nom ='".$user4."';";
    $bdd = getConnexion();
    $queryGUid = $bdd->prepare($getUserId);
    $queryGUid->execute();
    $UserId = $queryGUid->fetch();

    if ($UserId != false) {
      $queryIu->execute(array(
        ":User" => $UserId["US_id"],
        ":Projet" => $projet
      ));
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$NomProjet["PR_Libelle"]);
    } else {
      header("refresh:0; http://localhost/GestionMail/MonProjet.php?erreur=".$user4."&proj=".$NomProjet["PR_Libelle"]);
    }
}











 ?>
