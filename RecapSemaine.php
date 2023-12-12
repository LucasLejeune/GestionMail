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
  "Monday" => 1,
  "Tuesday" => 2,
  "Wednesday" => 3,
  "Thursday" => 4,
  "Friday" => 5,
  "Saturday" => 6,
);

  $ajd = date('l', strtotime('today'));
  $idajd = $jours[$ajd];
  $mailJour = array();
  $count = 0;
  for ($i=1; $i <7 ; $i++) {
    $jour = array_search($i,$jours);
    $jourX = date('Y-m-d', strtotime($jour.'this week'));
    $getMails = "SELECT MA_id FROM mail WHERE MA_date LIKE '".$jourX."%' AND fk_EV=".$_GET["event"].";";
    $bdd = getConnexion();
    $queryGMA = $bdd->prepare($getMails);
    $queryGMA->execute();
    $Mails = $queryGMA->fetchAll();
    $count += sizeof($Mails);
  }

  echo json_encode($count);



 ?>
