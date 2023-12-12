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



$getEvents = "SELECT EV_id, EV_Frequence, EV_Jour, EV_DateCreation, EV_Etat, EV_MailEvent FROM event";
$bdd = getConnexion();
$queryGEv = $bdd->prepare($getEvents);
$queryGEv->execute();
$events = $queryGEv->fetchAll();
$tabCsemaine = array();

$jours = array(
  "Monday" => 1,
  "Tuesday" => 2,
  "Wednesday" => 3,
  "Thursday" => 4,
  "Friday" => 5,
  "Saturday" => 6,
);

foreach ($events as $ev) {

  $getDebut = "SELECT EV_DateCreation FROM event WHERE EV_id = ".$ev["EV_id"];
  $bdd = getConnexion();
  $queryGDe = $bdd->prepare($getDebut);
  $queryGDe->execute();
  $debut = $queryGDe->fetch();
  $dateDebut = date('Y-m-d', strtotime($debut["EV_DateCreation"]));

  $lundiTw = date('Y-m-d', strtotime("monday this week"));
  $lundi6 = date('Y-m-d', strtotime($dateDebut."monday +5 week"));

  if ($lundiTw < $lundi6) {
    $s1 = date('Y-m-d', strtotime($dateDebut." monday this week"));
    $s2 = date('Y-m-d', strtotime($dateDebut." monday next week"));
    $s3 = date('Y-m-d', strtotime($dateDebut."monday +1 weeks"));
    $s4 = date('Y-m-d', strtotime($dateDebut."monday +2 week"));
    $s5 = date('Y-m-d', strtotime($dateDebut."monday +3 week"));
  } else {
    $s1 = date('Y-m-d', strtotime($lundi."monday -5 week"));
    $s2 = date('Y-m-d', strtotime($lundi."monday -4 week"));
    $s3 = date('Y-m-d', strtotime($lundiTw."monday -3 week"));
    $s4 = date('Y-m-d',strtotime($lundiTw."monday last week"));
    $s5 = date('Y-m-d', strtotime($lundiTw));
  }

  $semaine1 = 0;
  $semaine2 = 0;
  $semaine3 = 0;
  $semaine4 = 0;
  $semaine5 = 0;

    $getMails = "SELECT MA_date FROM mail WHERE fk_EV = ".$ev["EV_id"];
    $bdd = getConnexion();
    $queryGMa = $bdd->prepare($getMails);
    $queryGMa->execute();
    $Mails = $queryGMa->fetchAll();
    foreach ($Mails as $mail) {
      $date = date('Y-m-d', strtotime($mail["MA_date"]));
      if ($date < $s2) {
        $semaine1 += 1;
      } elseif ($date >= $s2 && $date < $s3){
        $semaine2 += 1;
      } elseif ($date >= $s3 && $date < $s4){
        $semaine3 += 1;
      } elseif ($date >= $s4 && $date < $s5){
        $semaine4 += 1;
      } elseif ($date >= $s5){
        $semaine5 += 1;
      }
    }


    if ($ev["EV_Jour"] == "jour") {
      $freq = $ev["EV_Frequence"];
      $freqS = strval($ev["EV_Frequence"]) * 6;
      $ajd = date('l',strtotime('today'));
      $idAjd = $jours[$ajd] + 1;
      $Cjour = array();

      for ($i=1; $i < $idAjd ; $i++) {
        $jour = array_search($i,$jours);
        $jourX = date('Y-m-d', strtotime($jour.'this week'));
        $getMails = "SELECT MA_id FROM mail WHERE MA_date LIKE '".$jourX."%' AND fk_EV=".$ev["EV_id"].";";
        $bdd = getConnexion();
        $queryGMA = $bdd->prepare($getMails);
        $queryGMA->execute();
        $Mails = $queryGMA->fetchAll();

        $date = date('Y-m-d', strtotime('today'));
        if (sizeof($Mails) < $freq && $jourX < $date && $jourX >$dateDebut){
          array_push($Cjour, "rouge");

        } elseif (sizeof($Mails) == $freq) {
          array_push($Cjour, "vert");

        }
        elseif (sizeof($Mails) < $freq && $jourX = $date){
          array_push($Cjour, "orange");

        }
        elseif (sizeof($Mails) > $freq){
          array_push($Cjour, "bleu");
        }
      }
      if ($ev["EV_id"] == 22) {
        var_dump($Cjour);
      }

        if ($lundiTw == $s1) {
          elseif (array_search("rouge",$Cjour) === false && array_search("bleu",$Cjour) === false && array_search("orange",$Cjour) === false) {
            $couleur = "vert";
          }
          elseif (array_search("rouge",$Cjour) !== false) {
            $couleur = "rouge";
          }
          elseif (array_search("bleu",$Cjour) !== false) {
            $couleur = "bleu";
          }
          elseif (array_search("orange",$Cjour) !== false && array_search("rouge",$Cjour) === false) {
            $couleur = "orange";
          }
        }
        elseif ($lundiTw == $s2) {
          if ($semaine1 == $freqS && array_search("rouge",$Cjour) === false && array_search("bleu",$Cjour) === false && array_search("orange",$Cjour) === false) {
            $couleur = "vert";
          }
          elseif ($semaine1 < $freqS || array_search("rouge",$Cjour) !== false) {
            $couleur = "rouge";
          }
          elseif ($semaine1 > $freqS || array_search("bleu",$Cjour) !== false) {
            $couleur = "bleu";
          }
          elseif (array_search("orange",$Cjour) != false) {
            $couleur = "orange";
          }
        }
        elseif ($lundiTw == $s3) {
          if ($semaine1 == $freqS && $semaine2 == $freqS && array_search("rouge",$Cjour) === false && array_search("bleu",$Cjour) === false && array_search("orange",$Cjour) === false) {
            $couleur = "vert";
          }
          elseif ($semaine1 < $freqS || $semaine2 < $freqS || array_search("rouge",$Cjour) !== false) {
            $couleur = "rouge";
          }
          elseif ($semaine1 > $freqS || $semaine2 > $freqS || array_search("bleu",$Cjour) !== false) {
            $couleur = "bleu";
          }
          elseif (array_search("orange",$Cjour) !== false) {
            $couleur = "orange";
          }
        }
        elseif ($lundiTw == $s4) {
          if ($semaine1 == $freqS && $semaine2 == $freqS && $semaine3 == $freqS && array_search("rouge",$Cjour) === false && array_search("bleu",$Cjour) === false && array_search("orange",$Cjour) === false) {
            $couleur = "vert";
          }
          elseif ($semaine1 < $freqS || $semaine2 < $freqS || $semaine3 < $freqS || array_search("rouge",$Cjour) !== false) {
            $couleur = "rouge";
          }
          elseif ($semaine1 > $freqS || $semaine2 > $freqS || $semaine3 > $freqS || array_search("bleu",$Cjour) !== false) {
            $couleur = "bleu";
          }
          elseif (array_search("orange",$Cjour) !== false) {
            $couleur = "orange";
          }
        }
        elseif ($lundiTw == $s5) {
          if ($semaine1 == $freqS && $semaine2 == $freqS && $semaine3 == $freqS && $semaine4 == $freqS && array_search("rouge",$Cjour) === false && array_search("bleu",$Cjour) === false && array_search("orange",$Cjour) === false) {
            $couleur = "vert";
          }
          elseif ($semaine1 < $freqS || $semaine2 < $freqS || $semaine3 < $freqS || $semaine4 < $freqS || array_search("rouge",$Cjour) !== false) {
            $couleur = "rouge";
          }
          elseif ($semaine1 > $freqS || $semaine2 > $freqS || $semaine3 > $freqS || $semaine4 > $freqS || array_search("bleu",$Cjour) !== false) {
            $couleur = "bleu";
          }
          elseif (array_search("orange",$Cjour) !== false) {
            $couleur = "orange";
          }
        }

    } else {
      $freq = $ev["EV_Frequence"];
      if ($lundiTw == $s1) {
        $couleur = "orange";
      }
      elseif ($lundiTw == $s2) {
        if ($semaine1 < $freq) {
          $couleur = "rouge";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq) {
          $couleur = "vert";
        }
        elseif ($semaine1 == $freq){
          $couleur = "orange";
        }
        elseif ($semaine1 > $freq) {
          $couleur = "bleu";
        }
      }
      elseif ($lundiTw == $s3) {
        if ($semaine1 < $freq || $semaine2 < $freq) {
          $couleur = "rouge";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq && $semaine3 == $freq) {
          $couleur = "vert";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq){
          $couleur = "orange";
        }
        elseif ($semaine1 > $freq || $semaine2 > $freq) {
          $couleur = "bleu";
        }
      }
      elseif ($lundiTw == $s4) {
        if ($semaine1 < $freq || $semaine2 < $freq || $semaine3 < $freq) {
          $couleur = "rouge";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq && $semaine3 == $freq && $semaine4 == $freq) {
          $couleur = "vert";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq && $semaine3 == $freq){
          $couleur = "orange";
        }
        elseif ($semaine1 > $freq || $semaine2 > $freq || $semaine3 > $freq) {
          $couleur = "bleu";
        }
      }
      elseif ($lundiTw == $s5) {
        if ($semaine1 < $freq || $semaine2 < $freq || $semaine3 < $freq || $semaine4 < $freq) {
          $couleur = "rouge";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq && $semaine3 == $freq && $semaine4 == $freq && $semaine5 == $freq) {
          $couleur = "vert";
        }
        elseif ($semaine1 == $freq && $semaine2 == $freq && $semaine3 == $freq && $semaine4 == $freq){
          $couleur = "orange";
        }
        elseif ($semaine1 > $freq || $semaine2 > $freq || $semaine3 > $freq || $semaine4 > $freq) {
          $couleur = "bleu";
        }
      }
    }

    $isUpdateC = "SELECT EV_Etat FROM event WHERE EV_Etat ='".$couleur."' AND EV_id =".$ev["EV_id"];
    $bdd = getConnexion();
    $queryIsUpC = $bdd->prepare($isUpdateC);
    $queryIsUpC->execute();
    $UpdateC = $queryIsUpC->fetch();

    if ($UpdateC == false) {

      $upCouleur = "UPDATE event SET EV_Etat ='".$couleur."' WHERE EV_id =".$ev["EV_id"];
      $bdd = getConnexion();
      $queryUpC = $bdd->prepare($upCouleur);
      $queryUpC->execute();

      $insertLCD = "INSERT INTO logchangementdetat (LC_Mail, LC_AncienneEtat, LC_NouvelleEtat, LC_Evenement, LC_Date) VALUES(:Mail, :Aetat, :Netat, :Event, :DateU)";
      $bdd = getConnexion();
      $queryILcd = $bdd->prepare($insertLCD);
      $queryILcd->execute(array(
        ":Mail" => $ev["EV_MailEvent"],
        ":Aetat" => $ev["EV_Etat"],
        ":Netat" => $couleur,
        ":Event" => $ev["EV_id"],
        ":DateU" => date('Y-m-d H:i:s',strtotime("today"))
      ));
    }

}
 ?>
