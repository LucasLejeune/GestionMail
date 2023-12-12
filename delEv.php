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

$deleteOA = "DELETE FROM optionavance WHERE fk_EV =".$_GET["ev"];
$bdd = getConnexion();
$queryDelOa = $bdd->prepare($deleteOA);
$queryDelOa->execute();

$deleteEnv = "DELETE FROM envoie WHERE fk_EV =".$_GET["ev"];
$bdd = getConnexion();
$queryDelEnv = $bdd->prepare($deleteEnv);
$queryDelEnv->execute();

$deleteCont = "DELETE FROM contient WHERE fk_EV =".$_GET["ev"];
$bdd = getConnexion();
$queryDelCont = $bdd->prepare($deleteCont);
$queryDelCont->execute();

$deleteMail = "DELETE FROM mail WHERE fk_EV =".$_GET["ev"];
$bdd = getConnexion();
$queryDelMail = $bdd->prepare($deleteMail);
$queryDelMail->execute();

$sql = "DELETE FROM event WHERE EV_id=".$_GET["ev"];
$bdd = getConnexion();
$query = $bdd->prepare($sql);
$query->execute();




 ?>
