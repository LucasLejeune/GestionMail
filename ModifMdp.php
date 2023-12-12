<?php session_start();
function getConnexion(){
  $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

  try {
      $bdd = new PDO($dsn, "root", "");
      return $bdd;
  } catch (PDOExeption $e) {
      die('DB Error: '.$e->getMessage());
  }
}
$AncienMdp = hash("sha256",$_POST["Amdp"]);
$Nmdp = $_POST["Nmdp"];
$Cmdp = $_POST["CNmdp"];

$getMdp = "SELECT US_id FROM user WHERE US_Nom='".$_SESSION["login"]."' AND US_Password ='".$AncienMdp."';";
$bdd = getConnexion();
$queryGMdp = $bdd->prepare($getMdp);
$queryGMdp->execute();
$Mdp = $queryGMdp->fetch();
var_dump($Mdp);

if ($Mdp !== false) {
  if (hash("sha256",$Nmdp) == $AncienMdp) {
    header("refresh:0; http://localhost/GestionMail/MonCompte.php?err=1");
  } elseif ($Nmdp != $Cmdp) {
    header("refresh:0; http://localhost/GestionMail/MonCompte.php?err=2");
  }
  else {
    $UpdateMdp = "UPDATE user SET US_Password ='".hash("sha256",$Nmdp)."' WHERE US_id =".$Mdp["US_id"];
    $bdd = getConnexion();
    $queryUpMdp = $bdd->prepare($UpdateMdp);
    $queryUpMdp->execute();
    var_dump($UpdateMdp);
    header("refresh:0; http://localhost/GestionMail/MonCompte.php?err=none");
  }
} else {
  header("refresh:0; http://localhost/GestionMail/MonCompte.php?err=3");
}







 ?>
