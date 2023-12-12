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

$sql = "SELECT US_Nom FROM user";
$bdd = getConnexion();
$query = $bdd->prepare($sql);
$query->execute();
$result = $query->fetch();
echo $result["US_Nom"][0];
$str = "2022-06-07";

$date = date('Y-m-d', strtotime('-7 days'));
$date2 = date('Y-m-d', strtotime($str));

if ($date > $date2) {
  echo "??";
} else {
  echo "oui";
}

echo "<br><br>";
$tab = array();
$tab += [["jour" => "lundi", "couleur" => "bleu"]];
$tab += [["jour" => "mardi", "couleur" => "rouge"]];
var_dump($tab);

echo "<br><br>";

$getExp = "SELECT fk_EX, fk_EV FROM envoie";
$bdd = getConnexion();
$queryGEx = $bdd->prepare($getExp);
$queryGEx->execute();
$Expediteurs = $queryGEx->fetchAll();

var_dump($Expediteurs);

echo "<br><br>";

echo date('Y-m-d', strtotime("2022-06-11 monday -2 week"));

echo "<br><br>";

$tab = array("Test"=>1);
foreach ($tab as $t) {
  echo "oui";
}

echo "<br><br>";

$array = array();
for ($i=0; $i < 2; $i++) {
  $array += ["EOF" => "e"];
  $array += ["DFV" => 'f'];
}

var_dump($array);

echo "<br><br>";

$esa = date('Y-m-d', strtotime('monday this week'));
echo date('d-m-Y', strtotime($esa));

echo "<br><br>";

$getUserProj = "SELECT fk_US FROM participe WHERE fk_PR = 1";
$bdd = getConnexion();
$queryGUP = $bdd->prepare($getUserProj);
$queryGUP->execute();
$usersProj = $queryGUP->fetchAll();

$list = "";
foreach ($usersProj as $usP) {
  if ($list == "") {
    $str = strval($usP["fk_US"]);
    $list.=$str;
  } else {
    $str = ",".strval($usP["fk_US"]).",";
    $list.=$str;
  }

}
var_dump($list,$usersProj);

echo "<br><br>";
$getExpEv = "SELECT fk_EX FROM envoie WHERE fk_EV= 22";
$bdd = getConnexion();
$queryGExEv = $bdd->prepare($getExpEv);
$queryGExEv->execute();
$ExpEvent = $queryGExEv->fetchAll();

var_dump($ExpEvent[0]);

echo "<br><br>";
$date = date('Y-m-d', strtotime("today -5 weeks"));
echo $date;

echo "<br><br>";

var_dump(intval("5"));

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div id="1P">
      <p>text</p>
      <button type="button" name="button"onclick="test()">test</button>
    </div>
    <div id="1P">
      <p>test</p>
    </div>
    <button type="button" onclick="Tslice()" name="button">slice</button>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
    function test(){
      $("#1P").css('display','none');

    }
    function Tslice(){
      var strUser = "test";
      console.log(strUser.slice(0,strUser.length - 1));
    }



    </script>
  </body>
</html>
