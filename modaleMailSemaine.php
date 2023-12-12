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

$getDebut = "SELECT MA_date FROM mail WHERE fk_EV = ".$_GET["ev"]." ORDER BY MA_date LIMIT 1";
$bdd = getConnexion();
$queryGDe = $bdd->prepare($getDebut);
$queryGDe->execute();
$debut = $queryGDe->fetch();
$dateDebut = date('Y-m-d', strtotime($debut["MA_date"]));

$s1 = $dateDebut;
$s2 = date('Y-m-d', strtotime($dateDebut."monday next week"));
$s3 = date('Y-m-d', strtotime($dateDebut."monday +1 weeks"));
$s4 = date('Y-m-d', strtotime($dateDebut."monday +2 week"));
$s5 = date('Y-m-d', strtotime($dateDebut."monday +3 week"));


$getMails = "SELECT MA_id, MA_Expediteur, MA_Object, MA_Contenu, MA_date, MA_Oa FROM mail WHERE fk_EV = ".$_GET["ev"];
$bdd = getConnexion();
$queryGMa = $bdd->prepare($getMails);
$queryGMa->execute();
$Mails = $queryGMa->fetchAll();

$semaine = $_GET["sem"];
$semaine1 = 0;
$semaine2 = 0;
$semaine3 = 0;
$semaine4 = 0;
$semaine5 = 0;

if ($semaine == 1) {
  $s = $s1;
  $sx = $s2;
} elseif ($semaine == 2) {
  $s = $s2;
  $sx = $s3;
} elseif ($semaine == 3) {
  $s = $s3;
  $sx = $s4;
} elseif ($semaine == 4) {
  $s = $s4;
  $sx = $s5;
} elseif ($semaine == 5) {
  $s = $s5;
  $sx = 0;
}
$tabMail = array();
foreach ($Mails as $mail) {
  $date = date('Y-m-d', strtotime($mail["MA_date"]));
  if ($sx !== 0) {
    if ($date < $sx && $date >= $s) {
      array_push($tabMail, $mail);
    }
  } else {
    if ($date > $s) {
      array_push($tabMail, $mail);
    }
  }
}

echo json_encode($tabMail);







 ?>
