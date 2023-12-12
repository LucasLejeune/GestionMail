<?php
function getConnexion(){
  $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

  try {
      $bdd = new PDO($dsn, "root", "");
      return $bdd;
  } catch (PDOExeption $e) {
      die('DB Error: '.$e->getMessage());
  }
}

$getProj = "SELECT PR_Libelle, PR_id FROM projet WHERE fk_CL = ".$_GET["cl"];
$bdd = getConnexion();
$queryGPr = $bdd->prepare($getProj);
$queryGPr->execute();
$Projets = $queryGPr->fetchAll();

echo json_encode($Projets);


 ?>
