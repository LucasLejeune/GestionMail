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

$deleteMail = "DELETE FROM mail WHERE MA_id =".$_GET["mail"];
$bdd = getConnexion();
$queryDelMa = $bdd->prepare($deleteMail);
$queryDelMa->execute();

header("refresh:0; http://localhost/GestionMail/MailsNA.php")


 ?>
