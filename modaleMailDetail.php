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

$id = $_GET["id"];
$getMail = "SELECT MA_Expediteur, MA_Object, MA_Contenu FROM mail WHERE MA_id = ".$id;
$bdd = getConnexion();
$query = $bdd->prepare($getMail);
$query->execute();
$mail = $query->fetch();

echo json_encode($mail)


 ?>
