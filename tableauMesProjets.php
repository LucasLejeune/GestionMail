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
$EventTab = array();

$event = "SELECT EV_id, EV_Libelle, EV_Frequence, fk_PR, fk_TE FROM event WHERE fk_PR =".$_GET["proj"];
$bdd = getConnexion();
$queryEvent = $bdd->prepare($event);
$queryEvent->execute();
$Evenement = $queryEvent->fetchAll();

foreach ($Evenement as $ev) {
  $tableau = array();
  $typeTache = "SELECT TE_Libelle FROM typeevent WHERE Te_id =".$ev["fk_TE"];
  $bdd = getConnexion();
  $queryTt = $bdd->prepare($typeTache);
  $queryTt->execute();
  $typeEvent = $queryTt->fetch();

  $Recep = "SELECT EV_Frequence, EV_Jour, EV_DateCreation, EV_Etat FROM event WHERE EV_id =".$ev["EV_id"];
  $bdd = getConnexion();
  $queryRec = $bdd->prepare($Recep);
  $queryRec->execute();
  $frequence = $queryRec->fetch();

  $output = file_get_contents('http://localhost/GestionMail/RecapSemaine.php?event='.$ev["EV_id"]);

  $tableau += ["Nom" => $ev["EV_Libelle"],
  "Type" => $typeEvent["TE_Libelle"],
  "Recap" => $output, "Couleur" => $frequence["EV_Etat"]];

  if ($frequence["EV_Jour"] == "jour") {
    $tableau += ["Jour" => $frequence["EV_Frequence"],
    "Semaine" => intval($frequence["EV_Frequence"]) * 6];
  }
  else {
    $tableau += ["Jour" => "X",
    "Semaine" => $frequence["EV_Frequence"]];
  }
  array_push($EventTab, $tableau);
}

echo json_encode($EventTab);

 ?>
