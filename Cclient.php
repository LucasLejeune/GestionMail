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

  $Nom = $_POST["NomClient"];
  $InsertClient = "INSERT INTO client (CL_Nom) VALUES ('".$Nom."')";

  $bdd = getConnexion();
  $query = $bdd->prepare($InsertClient);
  $query->execute();

  header("refresh:0; http://localhost/GestionMail/NosClients.php")







?>
