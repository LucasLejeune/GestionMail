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
  "Sunday" => 7,
);

  $ajd = date('l', strtotime('today'));
  $idajd = $jours[$ajd];
  $mailJour = array();

  $getFreq = "SELECT EV_Frequence, EV_Jour, EV_DateCreation FROM event WHERE EV_id = ".$_GET["event"].";";
  $bdd = getConnexion();
  $queryGFq = $bdd->prepare($getFreq);
  $queryGFq->execute();
  $Freq = $queryGFq->fetch();

  for ($i=1; $i <8 ; $i++) {
    $jour = array_search($i,$jours);
    $jourX = date('Y-m-d', strtotime($jour.'this week'));
    $getMails = "SELECT MA_id FROM mail WHERE MA_date LIKE '".$jourX."%' AND fk_EV=".$_GET["event"].";";
    $bdd = getConnexion();
    $queryGMA = $bdd->prepare($getMails);
    $queryGMA->execute();
    $Mails = $queryGMA->fetchAll();

    $date = date('Y-m-d', strtotime('today'));
    if ($jourX < $Freq["EV_DateCreation"]) {
      $mailJour += [$jour => sizeof($Mails), $jour."C" => "gris"];
    }
    elseif (sizeof($Mails) < $Freq["EV_Frequence"] AND $jourX < $date){
      $mailJour += [$jour => sizeof($Mails), $jour."C" => "rouge"];

    } elseif (sizeof($Mails) == $Freq["EV_Frequence"]) {
      $mailJour += [$jour => sizeof($Mails), $jour."C" => "vert"];

    }
    elseif (sizeof($Mails) < $Freq["EV_Frequence"] AND $jourX = $date){
      $mailJour += [$jour => sizeof($Mails), $jour."C" => "orange"];

    } elseif (sizeof($Mails) > $Freq["EV_Frequence"]){
          $mailJour += [$jour => sizeof($Mails), $jour."C" => "bleu"];
    }
  }

  echo json_encode($mailJour);



 ?>
