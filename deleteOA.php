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


$mail = $_GET["ma"];
$return = $_GET["return"];

$getOAid = "SELECT fk_OA FROM mail WHERE MA_id =".$mail;
$bdd = getConnexion();
$queryOAid= $bdd->prepare($getOAid);
$queryOAid->execute();
$OAid = $queryOAid->fetch();

$updateMA = "UPDATE mail SET MA_Oa = NULL,fk_OA = NULL WHERE MA_id =".$mail;
$bdd = getConnexion();
$queryUpMa = $bdd->prepare($updateMA);
$queryUpMa->execute();


header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$return);

 ?>
