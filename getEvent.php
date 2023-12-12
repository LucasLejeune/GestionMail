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

$prId = substr($_GET["pr"],1);

$getEvent = "SELECT EV_id, EV_Libelle FROM event WHERE fk_PR =".$prId;
$bdd = getConnexion();
$queryGEv = $bdd->prepare($getEvent);
$queryGEv->execute();
$Events = $queryGEv->fetchAll();

echo json_encode($Events);


 ?>
