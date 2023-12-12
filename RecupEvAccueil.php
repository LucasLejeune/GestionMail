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

$getEvents = "SELECT EV_Libelle, EV_Etat FROM event WHERE fk_PR=".$_GET["pr"];
$bdd = getConnexion();
$queryGEv = $bdd->prepare($getEvents);
$queryGEv->execute();
$Events = $queryGEv->fetchAll();


echo json_encode($Events);





 ?>
