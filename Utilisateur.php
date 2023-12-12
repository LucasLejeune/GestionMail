<?php session_start();
include "header.php";
function getConnexion(){
  $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

  try {
      $bdd = new PDO($dsn, "root", "");
      return $bdd;
  } catch (PDOExeption $e) {
      die('DB Error: '.$e->getMessage());
  }
} ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Utilisateur</title>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <style media="screen">
    table, th, td{
      border: 1px solid black;
    }
    .event{
      border: 1px solid black;
      margin-bottom: 25px;
      margin-top: 25px;
    }
    #AjoutUser{
      border: 1px solid black;
      background-color: white;
      position: fixed;
      right: 20%;
      left: 20%;
      top: 20%;
      bottom: auto;
      padding-bottom: 10px;
    }
    #Valider{
      border-radius: 25px;
      font-size: 20px;
      width: 150px;
      height: 30px;
    }
    #Valider:hover{
      background-color: lightgrey;
    }
    #BoutonAddEvent{
      border-radius: 25px;
      font-size: 13px;
      width: 150px;
      height: 30px;
    }
    #creationEvent{
      display: none;
      border: 1px solid black;
      background-color: white;
      position: fixed;
      right: 20%;
      left: 20%;
      top: 20%;
      bottom: 30%;
    }
    </style>
  </head>
  <body>
    <center>
      <?php
      $NomUser = $_GET["user"];

      $NbProj = "SELECT PR_id, PR_Libelle, fk_TP FROM participe JOIN user ON US_id = fk_US JOIN projet ON PR_id = fk_PR WHERE US_Nom ='".$NomUser."';";
      $bdd = getConnexion();
      $queryNBp = $bdd->prepare($NbProj);
      $queryNBp->execute();
      $NbProjets = $queryNBp->fetchAll();
      $NombreProjets = sizeof($NbProjets);

      $infoUser = "SELECT US_id, US_Roles, US_Mail FROM user WHERE US_Nom ='".$NomUser."';";
      $bdd = getConnexion();
      $queryIfu = $bdd->prepare($infoUser);
      $queryIfu->execute();
      $InfosUser = $queryIfu->fetch();



        echo "<h3>".$NomUser."</h3>";

        echo "Nombre total de projet: ".$NombreProjets;
        echo "<br><br>";

        echo "Role: ".$InfosUser["US_Roles"];
        echo "<br><br>";

        echo "Contact: ".$InfosUser["US_Mail"];
        echo "<br><br>";

        $getRole= "SELECT US_Roles FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
        $bdd = getConnexion();
        $queryGR = $bdd->prepare($getRole);
        $queryGR->execute();
        $Role = $queryGR->fetch();

        if ($Role["US_Roles"] == "Admin") {
          echo '<button type="button" class="btn btn-secondary" name="button" onclick="AjoutProjet()">Ajouter un nouveau projet</button><br><br>';
        }
        ?>
          <table>
            <thead>
              <th>Nom du projet</th>
              <th>Type de projet</th>
              <th>Nombre d'événements</th>
            </thead>

            <tbody>

        <?php

        foreach ($NbProjets as $nbp) {
          $getTP = "SELECT TP_Libelle FROM typeprojet WHERE TP_id =".$nbp["fk_TP"];
          $bdd = getConnexion();
          $queryGTP = $bdd->prepare($getTP);
          $queryGTP->execute();
          $TypeProjet = $queryGTP->fetch();

          $nbEvent = "SELECT EV_Libelle FROM event WHERE fk_PR =".$nbp["PR_id"];
          $bdd = getConnexion();
          $queryNBe = $bdd->prepare($nbEvent);
          $queryNBe->execute();
          $NombreEvent = $queryNBe->fetchall();

          echo "<td>".$nbp["PR_Libelle"]."</td>";
          echo "<td>".$TypeProjet["TP_Libelle"]."</td>";
          if ($NombreEvent != false) {
            echo "<td>".sizeof($NombreEvent)."</td>";
          } else {
            echo "<td>0</td>";
          }

          echo "<td><a href='http://localhost/GestionMail/MonProjet.php?proj=".$nbp["PR_Libelle"]."'>Ouvrir</a>";
          echo "<tr></tr>";

        }

       ?>

          </tbody>
        </table>

        <div id="AjoutUser" style="display:none">
          <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="Fermer()"></button>

          <?php
            echo '<form class="" action="AddUserProjet.php?user='.$InfosUser["US_id"].'" method="post">';

            $getProjUser = "SELECT fk_PR FROM participe WHERE fk_US =".$InfosUser["US_id"];
            $bdd = getConnexion();
            $queryGPU = $bdd->prepare($getProjUser);
            $queryGPU->execute();
            $ProjetUser = $queryGPU->fetchall();

            $list = "";
            foreach ($ProjetUser as $PrU) {
              if ($list == "") {
                $str = strval($PrU["fk_PR"]);
                $list.=$str;
              } else {
                $str = ",".strval($PrU["fk_PR"]);
                $list.=$str;
              }
            }
            $getProjets = "SELECT PR_Libelle, PR_id FROM projet WHERE PR_id NOT IN (".$list.")";
            $bdd = getConnexion();
            $queryGP = $bdd->prepare($getProjets);
            $queryGP->execute();
            $projets = $queryGP->fetchAll();

              if (sizeof($projets) !== 0) {
                echo "<h3>Ajouter un projet</h3><br>";
                for ($i=1; $i <5 ; $i++) {
                  echo "<br><select class ='form-select' name='projet".$i."'><option value=''>selectionner un projet a ajouter</option>";
                  foreach ($projets as $projet) {
                    echo "<option value='".$projet["PR_Libelle"]."'>".$projet["PR_Libelle"]."</option>";
                  }
                  echo "</select><br>";
                }
                echo "<button type='submit' class='btn btn-primary' name='button'>Valider</button>";
              }
              else {
                echo "<h3>Plus aucun projet disponible</h3>";
              }


           ?>

          </form>

        </div>
      </center>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script type="text/javascript">
        function AjoutProjet(){
          $("#AjoutUser").css('display','block');
        }
        function Fermer(){
          $("#AjoutUser").css('display','none');
        }
      </script>
  </body>
</html>
