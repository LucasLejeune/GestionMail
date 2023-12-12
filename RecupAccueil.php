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

$getUsRole = "SELECT US_Roles, US_id FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
$bdd = getConnexion();
$queryGUsR = $bdd->prepare($getUsRole);
$queryGUsR->execute();
$Role = $queryGUsR->fetch();

if ($Role["US_Roles"] == "Admin") {
  $getProjs = "SELECT PR_id, PR_Libelle FROM projet WHERE fk_CL=".$_GET["cl"];
  $bdd = getConnexion();
  $queryGPr = $bdd->prepare($getProjs);
  $queryGPr->execute();
  $Projet = $queryGPr->fetchAll();

} else {
  $getProjs = "SELECT PR_id, PR_Libelle FROM participe JOIN projet ON PR_id = fk_PR JOIN user ON US_id = fk_US WHERE fk_US =".$Role["US_id"];
  $bdd = getConnexion();
  $queryGPr = $bdd->prepare($getProjs);
  $queryGPr->execute();
  $Projet = $queryGPr->fetchAll();

}
  echo json_encode($Projet);

 ?>
