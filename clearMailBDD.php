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

$getTS = "SELECT EV_id, EV_TempSauvegarde FROM event";
$bdd = getConnexion();
$queryGTS = $bdd->prepare($getTS);
$queryGTS->execute();
$TS = $queryGTS->fetchAll();

foreach ($TS as $tpS) {
  $date = date('Y-m-d', strtotime("today -".$tpS["EV_TempSauvegarde"]." weeks"));

  $deleteMail = "DELETE FROM mail WHERE MA_date LIKE '".$date."%' AND fk_EV = ".$tpS["EV_id"];
  $bdd = getConnexion();
  $queryDelMa = $bdd->prepare($deleteMail);
  $queryDelMa->execute();
  var_dump($deleteMail);
  echo "<br>";
}

 ?>
