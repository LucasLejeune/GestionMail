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

if ($_POST["NomProj"] !="" && $_POST["Nom"]!="" && $_POST["Type"]!="" && $_POST["Frequence"]!="" && $_POST["semaine"]!="" && $_POST["Mail"]!="" && $_POST["Status"]!="") {

  $Proj = $_POST["NomProj"]; //id du projet
  $Nom = $_POST["Nom"];
  $Type = $_POST["Type"];
  $Freq = $_POST["Frequence"];
  $Par = $_POST["semaine"];
  if ($_POST["TempsSauv"] == "" || intval($_POST["TempsSauv"]) < 5) {
    $TempSave = 5;
  } else {
    $TempSave = $_POST["TempsSauv"];
  }
  $Mail = $_POST["Mail"];
  $Status = $_POST["Status"];
  if ($Status == "on") {
    $Status = 1;
  } else {
    $Status = 0;
  }
  if ($_POST["crea"] == 'off') {

    $updateEv = "UPDATE event SET EV_Libelle='".$Nom."', EV_Frequence =".$Freq.", EV_Jour ='".$Par."', EV_TempSauvegarde=".$TempSave.", EV_MailEvent='".$Mail."', EV_Actif =".$Status." WHERE EV_id = '".$_POST["idEvent"]."';";
    $bdd = getConnexion();
    $queryUEv = $bdd->prepare($updateEv);
    $queryUEv->execute();
    $valI = intval($_POST["i"])+1;
    $valJ = intval($_POST["j"])+1;

    for ($i=1; $i <$valI ; $i++) {
      if ($_POST["addExp".$i] != "") {
        $getExpEv = "SELECT fk_EX FROM envoie WHERE fk_EV=".$_POST["idEvent"];
        $bdd = getConnexion();
        $queryGExEv = $bdd->prepare($getExpEv);
        $queryGExEv->execute();
        $ExpEvent = $queryGExEv->fetchAll();

        $isNewExp = "SELECT EX_id FROM expediteurs WHERE EX_Adresse ='".$_POST["addExp".$i]."';";
        $bdd = getConnexion();
        $queryIsNEv = $bdd->prepare($isNewExp);
        $queryIsNEv->execute();
        $NewExp = $queryIsNEv->fetch();
        $aExp = $NewExp["EX_id"];

        if ($NewExp === FALSE) {
            $createExp = "INSERT INTO expediteurs(EX_Adresse) VALUES('".$_POST["addExp".$i]."')";
            $bdd = getConnexion();
            $queryCExp = $bdd->prepare($createExp);
            $queryCExp->execute();

            $getNewExp = "SELECT EX_id FROM expediteurs WHERE EX_Adresse ='".$_POST["addExp".$i]."';";
            $bdd = getConnexion();
            $queryGNExp = $bdd->prepare($getNewExp);
            $queryGNExp->execute();
            $NewExp = $queryGNExp->fetch();
        }

        $isNewEnvoie = "SELECT fk_EX, fk_EV FROM envoie WHERE fk_EX =".$ExpEvent[$i-1]["fk_EX"]." AND fk_EV=".$_POST["idEvent"].";";
        $bdd = getConnexion();
        $queryIsNEnv = $bdd->prepare($isNewEnvoie);
        $queryIsNEnv->execute();
        $NewEnv = $queryIsNEnv->fetch();

        $insertExp = "UPDATE envoie SET fk_EX = ".$NewExp["EX_id"].", fk_EV=".$_POST["idEvent"]." WHERE fk_EX =".$ExpEvent[$i-1]["fk_EX"]."";
        $bdd = getConnexion();
        $queryInExp = $bdd->prepare($insertExp);
        $queryInExp->execute();

      }
    }
        for ($j=1; $j <$valJ ; $j++) {
          if ($_POST["addMc".$j] != "") {

            $getMcEv = "SELECT fk_MA FROM contient WHERE fk_EV=".$_POST["idEvent"];
            $bdd = getConnexion();
            $queryGMcEv = $bdd->prepare($getMcEv);
            $queryGMcEv->execute();
            $McEvent = $queryGMcEv->fetchAll();

            $isNewMc = "SELECT MC_id FROM motscles WHERE MC_Libelle = '".$_POST["addMc".$j]."';";
            $bdd = getConnexion();
            $queryIsNMc = $bdd->prepare($isNewMc);
            $queryIsNMc->execute();
            $NewMc = $queryIsNMc->fetch();

            if ($NewMc === FALSE) {
              $createMc = "INSERT INTO motscles(MC_Libelle) VALUES('".$_POST["addMc".$j]."')";
              $bdd = getConnexion();
              $queryCMc = $bdd->prepare($createMc);
              $queryCMc->execute();

              $getNewMc = "SELECT MC_id FROM motscles WHERE MC_Libelle ='".$_POST["addMc".$j]."';";
              $bdd = getConnexion();
              $queryGNMc = $bdd->prepare($getNewMc);
              $queryGNMc->execute();
              $NewMc = $queryGNMc->fetch();
            }

            $isNewContient = "SELECT fk_MA, fk_EV FROM contient WHERE fk_MA =".$McEvent[$j-1]["fk_MA"]." AND fk_EV=".$_POST["idEvent"].";";
            $bdd = getConnexion();
            $queryIsNCont = $bdd->prepare($isNewContient);
            $queryIsNCont->execute();
            $NewCont = $queryIsNCont->fetch();

            if ($NewCont == false) {
              $insertMc = "INSERT INTO contient(fk_EV,fk_MA) VALUES(:EV,:MC)";
              $bdd = getConnexion();
              $queryInMc = $bdd->prepare($insertMc);
              $queryInMc->execute(array(
                ":EV" => $_POST["idEvent"],
                ":MC" => $NewMc["MC_id"]
              ));
            } else {
              $updateMc = "UPDATE contient SET fk_EV =".$_POST["idEvent"].", fk_MA= ".$NewMc["MC_id"]." WHERE fk_MA = ".$McEvent[$j-1]["fk_MA"];
              $bdd = getConnexion();
              $queryUpMc = $bdd->prepare($updateMc);
              $queryUpMc->execute();
            }
          }
        }

        $valIOA = intval($_POST["iOA"]) + 1;

        $getOaEv = "SELECT OA_id FROM optionavance WHERE fk_EV = ".$_POST["idEvent"];
        $bdd = getConnexion();
        $queryGOaEv = $bdd->prepare($getOaEv);
        $queryGOaEv->execute();
        $OaEv = $queryGOaEv->fetchAll();


        for ($iOA=1; $iOA < $valIOA ; $iOA++) {

          $isUpdateOA = "SELECT OA_id, OA_Conditions, OA_Status FROM optionavance WHERE OA_Conditions ='".$_POST["OAsi".$iOA]."' AND OA_Status='".$_POST["OAalors".$iOA]."' AND fk_EV=".$_POST["idEvent"];
          $bdd = getConnexion();
          $queryUpOa = $bdd->prepare($isUpdateOA);
          $queryUpOa->execute();
          $updateOA = $queryUpOa->fetchAll();
          $boucle = $valIOA - sizeof($OaEv) - 1;

          if ($_POST["OAsi".$iOA] != "" && $_POST["OAalors".$iOA] != "" && $updateOA == false) {
            if (sizeof($OaEv) == $valIOA-1 || $iOA + $boucle != $valIOA-1) {
              $upOA = "UPDATE optionavance SET OA_Conditions ='".$_POST["OAsi".$iOA]."', OA_Status='".$_POST["OAalors".$iOA]."' WHERE OA_id =".$OaEv[$iOA-1]["OA_id"];
              $bdd = getConnexion();
              $queryUpdOa = $bdd->prepare($upOA);
              $queryUpdOa->execute();
            } else {

              for ($i=0; $i < $boucle ; $i++) {
                $insertOA = "INSERT INTO optionavance (OA_Conditions, OA_Status, fk_EV) VALUES (:Cond, :Status, :ev)";
                $bdd = getConnexion();
                $queryInsOa = $bdd->prepare($insertOA);
                $queryInsOa->execute(array(
                  ":Cond" => $_POST["OAsi".$iOA],
                  ":Status" => $_POST["OAalors".$iOA],
                  ":ev" => $_POST["idEvent"]
                ));
              }
            }



        }
      }
  } else {

  $isNewTache = "SELECT TE_id FROM typeevent WHERE TE_Libelle ='".$Type."';";
  $bdd = getConnexion();
  $queryNT = $bdd->prepare($isNewTache);
  $queryNT->execute();
  $Tache = $queryNT->fetch();

  if ($Tache === FALSE) {
    $Ctache = "INSERT INTO typeevent (TE_Libelle) VALUES ('".$Type."')";
    $bdd = getConnexion();
    $queryCT = $bdd->prepare($Ctache);
    $queryCT->execute();

    $getNewTache = "SELECT TE_id FROM typeevent WHERE TE_Libelle ='".$Type."';";
    $bdd = getConnexion();
    $queryGNT = $bdd->prepare($getNewTache);
    $queryGNT->execute();
    $Tache = $queryGNT->fetch();

  }


  $isUpdate = "SELECT EV_id FROM event WHERE EV_Libelle ='".$Nom."' AND (EV_Frequence =".$Freq." OR EV_TempSauvegarde =".$TempSave." OR EV_Actif =".$Status." OR EV_MailEvent ='".$Mail."' OR fk_PR =".$Proj." OR fk_TE =".$Tache["TE_id"].");";
  $bdd = getConnexion();
  $queryIsUp = $bdd->prepare($isUpdate);
  $queryIsUp->execute();
  $Update = $queryIsUp->fetch();

  if ($Update != false) {
    $UpEvent = "UPDATE event SET EV_Libelle ='".$Nom."', EV_Frequence=".$Freq.",EV_Jour=".$Par." EV_TempSauvegarde=".$TempSave.", EV_Actif=".$Status.", EV_MailEvent='".$Mail."', fk_PR=".$Proj.", fk_TE=".$Tache["TE_id"]." WHERE EV_id=".$Update["EV_id"].";";
    $bdd = getConnexion();
    $queryUpEv = $bdd->prepare($UpEvent);
    $queryUpEv->execute();
  } else {
    $newEvent = "INSERT INTO event (EV_Libelle, EV_Frequence, EV_Jour, EV_TempSauvegarde, EV_Actif, EV_MailEvent,EV_Etat, fk_PR, fk_TE, EV_DateCreation)
        VALUES (:Nom, :Freq, :jour, :TempSave, :Actif, :Mail, :Etat, :Proj, :Type, :dateCreation);";
    $bdd = getConnexion();
    $queryNE = $bdd->prepare($newEvent);
    $queryNE->execute(array(
      ":Nom" => $Nom,
      ":Freq" => $Freq,
      ":jour" => $Par,
      ":TempSave" => $TempSave,
      ":Actif" => $Status,
      ":Mail" => $Mail,
      ":Etat" => "orange",
      ":Proj" => $Proj,
      ":Type" => $Tache["TE_id"],
      ":dateCreation" => date('Y-m-d', strtotime("today"))
    ));
  }

  $getNewEvent = "SELECT EV_id FROM event WHERE EV_Libelle ='".$Nom."'";
  $bdd = getConnexion();
  $queryGNEv = $bdd->prepare($getNewEvent);
  $queryGNEv->execute();
  $evId = $queryGNEv->fetch();
  $valI = intval($_POST["i"])+1;
  $valJ = intval($_POST["j"])+1;

  for ($i=1; $i <$valI ; $i++) {
    if ($_POST["addExp".$i] != "") {
      $isNewExp = "SELECT EX_id FROM expediteurs WHERE EX_Adresse ='".$_POST["addExp".$i]."';";
      $bdd = getConnexion();
      $queryIsNEv = $bdd->prepare($isNewExp);
      $queryIsNEv->execute();
      $NewExp = $queryIsNEv->fetch();

      if ($NewExp === FALSE) {
          $createExp = "INSERT INTO expediteurs(EX_Adresse) VALUES('".$_POST["addExp".$i]."')";
          $bdd = getConnexion();
          $queryCExp = $bdd->prepare($createExp);
          $queryCExp->execute();

          $getNewExp = "SELECT EX_id FROM expediteurs WHERE EX_Adresse ='".$_POST["addExp".$i]."';";
          $bdd = getConnexion();
          $queryGNExp = $bdd->prepare($getNewExp);
          $queryGNExp->execute();
          $NewExp = $queryGNExp->fetch();
      }

      $insertExp = "INSERT INTO envoie (fk_EX, fk_EV) VALUES (:Ex, :Ev)";
      $bdd = getConnexion();
      $queryInExp = $bdd->prepare($insertExp);
      $queryInExp->execute(array(
        ":Ex" => $NewExp["EX_id"],
        ":Ev" => $evId["EV_id"]
      ));
    }
  }
  for ($j=1; $j <$valJ ; $j++) {
    if ($_POST["addMc".$j] != "") {
      $isNewMc = "SELECT MC_id FROM motscles WHERE MC_Libelle = '".$_POST["addMc".$j]."';";
      $bdd = getConnexion();
      $queryIsNMc = $bdd->prepare($isNewMc);
      $queryIsNMc->execute();
      $NewMc = $queryIsNMc->fetch();

      if ($NewMc === FALSE) {
        $createMc = "INSERT INTO motscles(MC_Libelle) VALUES('".$_POST["addMc".$j]."')";
        $bdd = getConnexion();
        $queryCMc = $bdd->prepare($createMc);
        $queryCMc->execute();


        $getNewMc = "SELECT MC_id FROM motscles WHERE MC_Libelle ='".$_POST["addMc".$j]."';";
        $bdd = getConnexion();
        $queryGNMc = $bdd->prepare($getNewMc);
        $queryGNMc->execute();
        $NewMc = $queryGNMc->fetch();
      }

      $insertMc = "INSERT INTO contient(fk_EV,fk_MA) VALUES(:EV,:MC)";
      $bdd = getConnexion();
      $queryInMc = $bdd->prepare($insertMc);
      $queryInMc->execute(array(
        ":EV" => $evId["EV_id"],
        ":MC" => $NewMc["MC_id"]
      ));
    }
  }
  $valIOA = intval($_POST["iOA"])+1;

  for ($iOA=1; $iOA < $valIOA ; $iOA++) {
    if ($_POST["OAsi".$iOA] != "" && $_POST["OAalors".$iOA] != "") {
      $insertIOA = "INSERT INTO optionavance (OA_Conditions,OA_Status,fk_EV) VALUES (:Condition, :Status, :Ev)";
      $bdd = getConnexion();
      $queryInOA = $bdd->prepare($insertIOA);
      $queryInOA->execute(array(
        ":Condition" => $_POST["OAsi".$iOA],
        ":Status" => $_POST["OAalors".$iOA],
        ":Ev" => $evId["EV_id"]
      ));
    }
  }
}




  if (isset($_GET["return"])) {
    $getProjNom = "SELECT PR_Libelle FROM projet WHERE PR_id =".$_POST["NomProj"];
    $bdd = getConnexion();
    $queryGPN = $bdd->prepare($getProjNom);
    $queryGPN->execute();
    $NomProjet = $queryGPN->fetch();
    header("refresh:0; http://localhost/GestionMail/MonProjet.php?proj=".$NomProjet["PR_Libelle"]);
  }
  else {
    header("refresh:0; http://localhost/GestionMail/MesProjets.php");
  }

}





 ?>
