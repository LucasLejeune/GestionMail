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

  $evID = $_GET["ev"];

  $event = array();

  $getEv = "SELECT EV_Libelle, TE_Libelle, EV_Frequence, EV_Jour, EV_TempSauvegarde, EV_MailEvent FROM event JOIN typeevent ON fk_TE = TE_id WHERE EV_id = ".$evID.";";
  $bdd = getConnexion();
  $queryGEv = $bdd->prepare($getEv);
  $queryGEv->execute();
  $resultGEv = $queryGEv->fetch();
  $event += ["EV_Libelle" => $resultGEv["EV_Libelle"]];
  $event += ["TE_Libelle" => $resultGEv["TE_Libelle"]];
  $event += ["EV_Frequence" => $resultGEv["EV_Frequence"]];
  $event += ["EV_Jour" => $resultGEv["EV_Jour"]];
  $event += ["EV_TempSauvegarde" => $resultGEv["EV_TempSauvegarde"]];
  $event += ["EV_MailEvent" => $resultGEv["EV_MailEvent"]];


  $getEx = "SELECT EX_adresse FROM expediteurs JOIN envoie ON EX_id = fk_EX WHERE fk_EV = ".$evID.";";
  $bdd = getConnexion();
  $queryGEx = $bdd->prepare($getEx);
  $queryGEx->execute();
  $resultGEx = $queryGEx->fetchAll();
  $i = 0;
  $event += ["Exp" => array()];
  foreach ($resultGEx as $Exp) {
    $i += 1;
    $event["Exp"] += [$i => $Exp["EX_adresse"]];
  }

  $getMc = "SELECT MC_Libelle FROM motscles JOIN contient ON fk_MA = MC_id WHERE fk_EV = ".$evID.";";
  $bdd = getConnexion();
  $queryGMc = $bdd->prepare($getMc);
  $queryGMc->execute();
  $resultGMc = $queryGMc->fetchAll();
  $j = 0;
  $event += ["Mc" => array()];
  foreach ($resultGMc as $Mc) {
    $j += 1;
    $event["Mc"] += [$j => $Mc["MC_Libelle"]];
  }

  $getOA = "SELECT OA_Conditions, OA_Status FROM optionavance WHERE fk_EV = ".$evID.";";
  $bdd = getConnexion();
  $queryGOa = $bdd->prepare($getOA);
  $queryGOa->execute();
  $resultGOa = $queryGOa->fetchAll();
  $k = 0;
  $event += ["Oa" => array()];
  $event += ["OaS" => array()];
  foreach ($resultGOa as $OA) {
    $k += 1;
    $event["Oa"] += [$k => $OA["OA_Conditions"]];
    $event["OaS"] += [$k => $OA["OA_Status"]];
  }

  echo json_encode($event);


 ?>
