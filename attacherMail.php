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

$evId = substr($_POST["evId"], 1);
$updateMail = "UPDATE mail SET fk_EV = ".$evId." WHERE MA_id = ".$_POST["mailId"];
$bdd = getConnexion();
$queryUpMa = $bdd->prepare($updateMail);
$queryUpMa->execute();

header("refresh:0; http://localhost/GestionMail/MailsNA.php")


 ?>
