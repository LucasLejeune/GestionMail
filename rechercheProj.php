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

$client = $_GET["client"];

$sql = "SELECT PR_Libelle,PR_id FROM projet WHERE fk_CL =".$client;
$bdd = getConnexion();
$query = $bdd->prepare($sql);
$query->execute();
$result = $query->fetchAll();

echo json_encode($result);

 ?>
