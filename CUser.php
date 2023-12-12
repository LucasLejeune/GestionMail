<?php
session_start();
function getConnexion(){
  $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

  try {
      $bdd = new PDO($dsn, "root", "");
      return $bdd;
  } catch (PDOExeption $e) {
      die('DB Error: '.$e->getMessage());
  }
}
if(strlen($_POST["Nom"])>0 && strlen($_POST["Contact"])>0 && strlen($_POST["RoleUser"])>0 && strlen($_POST["TypeUser"])>0 && strlen($_POST["mdp"])>0){
  $nom = $_POST["Nom"];
  $contact = $_POST["Contact"];
  $role = $_POST["RoleUser"];
  $type = $_POST["TypeUser"];
  $mdp = $_POST["mdp"];
  $hash = hash("sha256",$mdp);

  $getID = "SELECT TU_id FROM typeuser WHERE TU_Libelle = '".$type."';";
  $bdd = getConnexion();
  $queryID = $bdd->prepare($getID);
  $queryID->execute();
  $typeId = $queryID->fetch();

  $isUpdate = "SELECT US_Roles, fk_TU FROM user WHERE US_Mail ='".$contact."';";
  $bdd = getConnexion();
  $queryIsup = $bdd->prepare($isUpdate);
  $queryIsup->execute();
  $update = $queryIsup->fetch();

  if ($update != false) {
    $msg = 0;
    if ($update["US_Roles"] != $role) {
      $upUserRole = "UPDATE user SET US_Roles ='".$role."';";
      $bdd = getConnexion();
      $queryUpRole = $bdd->prepare($upUserRole);
      $queryUpRole->execute();
      $msg += 1;
    }
    if ($update["fk_TU"] != $typeId["TU_id"]) {
      $upUserType = "UPDATE user SET fk_TU ='".$typeId["TU_id"]."';";
      $bdd = getConnexion();
      $queryUpType = $bdd->prepare($upUserType);
      $queryUpType->execute();
      $msg += 2;
    }

    $NomTU="SELECT TU_Libelle FROM typeuser WHERE TU_id =".$typeId["TU_id"];
    $bdd = getConnexion();
    $queryNomTU = $bdd->prepare($NomTU);
    $queryNomTU->execute();
    $NomTypeUser = $queryNomTU->fetch();


    if ($msg == 1) {
      header("refresh:0; http://localhost/GestionMail/PageCreationUser.php?role=".$role."&user=".$nom);
    }
    elseif ($msg == 2) {
      header("refresh:0; http://localhost/GestionMail/PageCreationUser.php?type=".$NomTypeUser["TU_Libelle"]."&user=".$nom);
    }
    else {
      header("refresh:0; http://localhost/GestionMail/PageCreationUser.php?type=".$NomTypeUser["TU_Libelle"]."&role=".$role."&user=".$nom);
    }

  } else {
    $insert = "INSERT INTO user (US_Nom, US_Mail, US_password, US_roles, fk_TU) VALUES (:Nom, :Mail, :Mdp, :Role, :Type)";
    $query = $bdd->prepare($insert);
    $query->execute(array(
      ":Nom" => $nom,
      ":Mail" => $contact,
      ":Mdp" => $hash,
      ":Role" => $role,
      ":Type" => $typeId["TU_id"]
    ));
    header("refresh:0; http://localhost/GestionMail/PageCreationUser.php?Cuser=ok");
  }
} else {
  header("refresh:0; http://localhost/GestionMail/PageCreationUser.php?Cuser=erreur");
}



 ?>
