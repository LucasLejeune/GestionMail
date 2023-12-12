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
  "Monday" => 0,
  "Tuesday" => 1,
  "Wednesday" => 2,
  "Thursday" => 3,
  "Friday" => 4,
  "Saturday" => 5,
);

$getDebut = "SELECT EV_DateCreation FROM event WHERE EV_id = ".$_GET["ev"];
$bdd = getConnexion();
$queryGDe = $bdd->prepare($getDebut);
$queryGDe->execute();
$debut = $queryGDe->fetch();
$dateDebut = date('Y-m-d', strtotime($debut["EV_DateCreation"]));

$lundiTw = date('Y-m-d', strtotime("monday this week"));
$lundi6 = date('Y-m-d', strtotime($dateDebut."monday +5 week"));

if ($lundiTw < $lundi6) {
  $s1 = $dateDebut;
  $s2 = date('Y-m-d', strtotime($dateDebut."monday next week"));
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

$tabLundiSemaine = array(1=>$s1, 2=>$s2, 3=>$s3, 4=>$s4, 5=>$s5);


$getMails = "SELECT MA_date FROM mail WHERE fk_EV = ".$_GET["ev"];
$bdd = getConnexion();
$queryGMa = $bdd->prepare($getMails);
$queryGMa->execute();
$Mails = $queryGMa->fetchAll();

$semaine1 = 0;
$semaine2 = 0;
$semaine3 = 0;
$semaine4 = 0;
$semaine5 = 0;

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

$getFreq = "SELECT EV_Frequence, EV_Jour FROM event WHERE EV_id =".$_GET["ev"];
$bdd = getConnexion();
$queryGFq = $bdd->prepare($getFreq);
$queryGFq->execute();
$freq = $queryGFq->fetch();

$frequence;
if ($freq["EV_Jour"] == "semaine") {
  $frequence = $freq["EV_Frequence"];
  $freqTotale = $frequence * 7 * 5;
} else {
  $frequence = $freq["EV_Frequence"] * 7;
  $freqTotale = $frequence * 5;
}


$tabSemaine = array("s1"=>$semaine1,"s2"=>$semaine2,"s3"=>$semaine3,"s4"=>$semaine4,"s5"=>$semaine5);

for ($i=1; $i <6 ; $i++) {
  $id = strval($i);
  if ($i == 1) {
    if (date('l', strtotime($s1))=="Monday") {
      if (($tabSemaine["s".$i] == 0 || $frequence > $tabSemaine["s".$i]) && $tabLundiSemaine[$i] >= $lundiTw) {
        $tabSemaine += ["sc".$id => "orange"];
      } elseif ($frequence == $tabSemaine["s".$i]) {
        $tabSemaine += ["sc".$id =>"vert"];
      } elseif ($frequence < $tabSemaine["s".$i]) {
        $tabSemaine += ["sc".$id =>"bleu"];
      } elseif ($tabSemaine["s".$i] == 0 || $tabSemaine["s".$i] < $frequence) {
          $tabSemaine += ["sc".$id =>"rouge"];
      }
    } else {
      if ($freq["EV_Jour"] == "jour") {
        $nvFreq = $frequence - ($jours[date('l', strtotime($s1))] * $freq["EV_Frequence"]);
        if (($tabSemaine["s".$i] == 0 || $nvFreq > $tabSemaine["s".$i]) && $tabLundiSemaine[$i+1] >= $lundiTw) {
          $tabSemaine += ["sc".$id => "orange"];
        } elseif ($nvFreq == $tabSemaine["s".$i]) {
          $tabSemaine += ["sc".$id =>"vert"];
        } elseif ($lundiTw != date('Y-m-d',strtotime($s1."monday this week")) && $nvFreq > $tabSemaine["s".$i]) {
          $tabSemaine += ["sc".$id =>"rouge"];
        } elseif ($lundiTw != date('Y-m-d',strtotime($s1."monday this week")) && $nvFreq < $tabSemaine["s".$i]) {
          $tabSemaine += ["sc".$id =>"bleu"];
        }
        else {
          $tabSemaine += ["sc".$id =>"gris"];
        }
      } else {
        if (($tabSemaine["s".$i] == 0 || $frequence > $tabSemaine["s".$i]) && $tabLundiSemaine[$i+1] >= $lundiTw) {
          $tabSemaine += ["sc".$id => "orange"];
        } elseif ($frequence == $tabSemaine["s".$i] ||$frequence == $tabSemaine["s".$i]) {
          $tabSemaine += ["sc".$id =>"vert"];
        } elseif ($lundiTw != date('Y-m-d',strtotime($s1."monday this week")) && $frequence > $tabSemaine["s".$i]) {
          $tabSemaine += ["sc".$id =>"rouge"];
        } elseif ($lundiTw != date('Y-m-d',strtotime($s1."monday this week")) && $frequence < $tabSemaine["s".$i]) {
          $tabSemaine += ["sc".$id =>"bleu"];
        }
        else {
          $tabSemaine += ["sc".$id =>"gris"];
        }
      }

    }
  } else {
    if ($freq["EV_Jour"] == "semaine") {
      if ($frequence > $tabSemaine["s".$i] && $tabLundiSemaine[$i] >= $lundiTw) {
        $tabSemaine += ["sc".$id => "orange"];
      } elseif ($frequence == $tabSemaine["s".$i]) {
        $tabSemaine += ["sc".$id =>"vert"];
      } elseif ($frequence < $tabSemaine["s".$i]) {
        $tabSemaine += ["sc".$id =>"bleu"];
      } elseif ($tabSemaine["s".$i] == 0 || $tabSemaine["s".$i] < $frequence) {
          $tabSemaine += ["sc".$id =>"rouge"];
      }
    }
    else {
      if ($frequence > $tabSemaine["s".$i] && $tabLundiSemaine[$i] >= $lundiTw) {
        $tabSemaine += ["sc".$id => "orange"];
      } elseif ($frequence == $tabSemaine["s".$i]) {
        $tabSemaine += ["sc".$id =>"vert"];
      } elseif ($frequence < $tabSemaine["s".$i]) {
        $tabSemaine += ["sc".$id =>"bleu"];
      } elseif ($tabSemaine["s".$i] == 0 || $tabSemaine["s".$i] < $frequence) {
          $tabSemaine += ["sc".$id =>"rouge"];
      }
    }

  }
}


$total = $semaine1 + $semaine2 + $semaine3 + $semaine4 + $semaine5;
if ($total == $freqTotale) {
  $tabSemaine += ["sct" =>"vert"];
} elseif ($total > $freqTotale) {
  $tabSemaine += ["sct" =>"bleu"];
} elseif ($total < $freqTotale) {
  $tabSemaine += ["sct" =>"rouge"];
} else {
  $tabSemaine += ["sct" =>"orange"];
}

$tabSemaine += ["total"=>$total];

$dateS1 = date('d-m-Y', strtotime($s1.'+ 6 days'));
$dateS2 = date('d-m-Y', strtotime($s2.'+ 6 days'));
$dateS3 = date('d-m-Y', strtotime($s3.'+ 6 days'));
$dateS4 = date('d-m-Y', strtotime($s4.'+ 6 days'));
$dateS5 = date('d-m-Y', strtotime($s5.'+ 6 days'));
$tabSemaine += ["dateS1" => $dateS1, "dateDS1"=>date('d-m-Y',strtotime($s1)), "dateS2" => $dateS2, "dateDS2"=>date('d-m-Y',strtotime($s2)), "dateS3" => $dateS3, "dateDS3"=>date('d-m-Y',strtotime($s3)), "dateS4" => $dateS4,"dateDS4"=>date('d-m-Y',strtotime($s4)), "dateS5" => $dateS5, "dateDS5"=>date('d-m-Y',strtotime($s5)) ];


echo json_encode($tabSemaine);



?>
