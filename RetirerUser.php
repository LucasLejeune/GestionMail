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

$user = $_GET["user"];
$idProj = $_GET["proj"];
$delUser = "DELETE FROM participe WHERE fk_US=".$user." AND fk_PR=".$idProj;
$bdd = getConnexion();
$queryDu = $bdd->prepare($delUser);
$queryDu->execute();

$getProjNom = "SELECT PR_Libelle FROM projet WHERE PR_id =".$idProj;
$bdd = getConnexion();
$queryGPN = $bdd->prepare($getProjNom);
$queryGPN->execute();
$NomProjet = $queryGPN->fetch();

header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$NomProjet["PR_Libelle"]);





 ?>
