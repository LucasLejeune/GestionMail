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

$Libelle = $_POST["NomProj"];
$idClient = $_GET["idClient"];
$id_TP =  $_POST["TypeProj"];

$updateProj = "SELECT PR_Libelle FROM projet WHERE PR_Libelle ='".$Libelle."';";
$bdd = getConnexion();
$queryIsup = $bdd->prepare($updateProj);
$queryIsup->execute();
$isUpdate = $queryIsup->fetch();

$idTypeProj = "SELECT TP_id FROM typeprojet WHERE TP_Libelle ='".$id_TP."';";
$bdd = getConnexion();
$queryIDtp = $bdd->prepare($idTypeProj);
$queryIDtp->execute();
$idTP = $queryIDtp->fetch();

if ($isUpdate != false) {

  $update = "UPDATE projet SET fk_TP =".$idTP["TP_id"]." WHERE PR_Libelle ='".$Libelle."';";

  $bdd = getConnexion();
  $queryUpd = $bdd->prepare($update);
  $queryUpd->execute();
  header("refresh:0; http://localhost/GestionMail/MonClient.php?id=".$idClient."&maj=".$Libelle);
}

else {

  $NvProj = "INSERT INTO projet (PR_Libelle, fk_CL, fk_TP) VALUES(:Libelle, :idClient, :idTP)";
  $bdd = getConnexion();
  $query = $bdd->prepare($NvProj);
  $query->execute(array(
    ":Libelle" => $Libelle,
    ":idClient" => $idClient,
    ":idTP" => $idTP["TP_id"]
  ));
  header("refresh:0; http://localhost/GestionMail/MonClient.php?id=".$idClient);
}


?>
