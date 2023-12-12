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

$insertUser = "INSERT INTO participe (fk_US, fk_PR) VALUES (:User, :Projet)";
$bdd = getConnexion();
$queryIu = $bdd->prepare($insertUser);

if ($_POST["projet1"] != "") {
  $getIdProj = "SELECT PR_id FROM projet WHERE PR_Libelle ='".$_POST["projet1"]."';";
  $bdd = getConnexion();
  $queryGidP = $bdd->prepare($getIdProj);
  $queryGidP->execute();
  $idProjet = $queryGidP->fetch();
  $idProjet = $idProjet["PR_id"];

  $queryIu->execute(array(
    ":User" => $_GET["user"],
    ":Projet" => $idProjet
));}

if ($_POST["projet2"] != "") {
  $getIdProj = "SELECT PR_id FROM projet WHERE PR_Libelle ='".$_POST["projet2"]."';";
  $bdd = getConnexion();
  $queryGidP = $bdd->prepare($getIdProj);
  $queryGidP->execute();
  $idProjet = $queryGidP->fetch();
  $idProjet = $idProjet["PR_id"];

  $queryIu->execute(array(
    ":User" => $_GET["user"],
    ":Projet" => $idProjet
));}

if ($_POST["projet3"] != "") {
  $getIdProj = "SELECT PR_id FROM projet WHERE PR_Libelle ='".$_POST["projet3"]."';";
  $bdd = getConnexion();
  $queryGidP = $bdd->prepare($getIdProj);
  $queryGidP->execute();
  $idProjet = $queryGidP->fetch();
  $idProjet = $idProjet["PR_id"];

  $queryIu->execute(array(
    ":User" => $_GET["user"],
    ":Projet" => $idProjet
));}

if ($_POST["projet4"] != "") {
  $getIdProj = "SELECT PR_id FROM projet WHERE PR_Libelle ='".$_POST["projet4"]."';";
  $bdd = getConnexion();
  $queryGidP = $bdd->prepare($getIdProj);
  $queryGidP->execute();
  $idProjet = $queryGidP->fetch();
  $idProjet = $idProjet["PR_id"];

  $queryIu->execute(array(
    ":User" => $_GET["user"],
    ":Projet" => $idProjet
));}

$getUserNom = "SELECT US_Nom FROM user WHERE US_id =".$_GET["user"];
$bdd = getConnexion();
$queryGUsN = $bdd->prepare($getUserNom);
$queryGUsN->execute();
$UserNom = $queryGUsN->fetch();


header("refresh:0; http://localhost/GestionMail/Utilisateur.php?user=".$UserNom["US_Nom"]);


 ?>
