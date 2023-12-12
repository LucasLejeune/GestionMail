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
$jours = array(
  1 => "Monday",
  2 => "Tuesday",
  3 => "Wednesday",
  4 => "Thursday",
  5 => "Friday",
  6 => "Saturday"
);

$jour = $jours[$_GET["jour"]];
$jour = date('Y-m-d', strtotime($jour.' this week'));


  $getMails = "SELECT MA_id, MA_Expediteur, MA_Object, MA_Contenu, MA_Oa FROM mail WHERE fk_EV =".$_GET["event"]." AND MA_date LIKE '".$jour."%'";
  $bdd = getConnexion();
  $queryGMA = $bdd->prepare($getMails);
  $queryGMA->execute();
  $Mails = $queryGMA->fetchAll();

  echo json_encode($Mails);

 ?>
