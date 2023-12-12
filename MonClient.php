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
}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Mon Client</title>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </head>
  <body>
    <center>
      <?php
        $idClient = $_GET["id"];
        $getNomClient = "SELECT CL_Nom FROM client WHERE CL_id = '".$idClient."';";
        $bdd = getConnexion();
        $query = $bdd->prepare($getNomClient);
        $query->execute();
        $Client = $query->fetch();
        $NomClient = $Client["CL_Nom"];

        echo "<h3>Client: ".$NomClient."</h3>";

        $Projet = "SELECT PR_id, PR_Libelle, fk_TP FROM projet WHERE fk_CL = '".$idClient."';";
        $bdd = getConnexion();
        $queryNbP = $bdd->prepare($Projet);
        $queryNbP->execute();
        $nbProjet = $queryNbP->fetchAll();


        echo "<br>";

        echo "Nombre de projets: ".sizeof($nbProjet);

        echo "<br><br>";

        $nbtEvent = 0;

        foreach ($nbProjet as $projNbt) {
          $Event = "SELECT EV_Libelle FROM event WHERE fk_PR ='".$projNbt["PR_id"]."';";
          $bdd = getConnexion();
          $queryEv = $bdd->prepare($Event);
          $queryEv->execute();
          $nbEvent = $queryEv->fetchAll();
          $nbtEvent += sizeof($nbEvent);
        }

        echo "Nombre d'évenements: ".$nbtEvent;

      ?>

      <br><br>

      <?php
        $isAdmin = "SELECT US_Roles FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
        $bdd = getConnexion();
        $queryIa = $bdd->prepare($isAdmin);
        $queryIa->execute();
        $Admin = $queryIa->fetch();

        if ($Admin["US_Roles"] == "Admin") {
          echo '<button onclick="AjoutProjet()" type="button" name="button" class="btn btn-secondary" >Ajouter un projet</button>';
        }

       ?>

      <br><br>

      <div id="addProj" style="
        display:none;
        border: 1px solid black;
        background-color: white;
        position: fixed;
        right: 20%;
        left: 20%;
        top: 20%;
          bottom: auto;
        padding-left: 30px;
        padding-right: 30px;
        padding-bottom: 10px;
        ">

        <?php echo "<form action='AjoutProjet.php?idClient=".$idClient."' method='post'>"; ?>
          <button onclick="FermerProj()" class="btn-close" aria-label="Close" id="Fermer" type="button" name="button"></button>
          <br>
          <h2>Création / Modification projet</h2>
          <br>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Nom du projet</span>
            <input type="text" class="form-control" name="NomProj" placeholder="Nom du projet" aria-label="Username" aria-describedby="basic-addon1">
          </div>

            <br>

            <select class="form-select" aria-label="Default select example" name="TypeProj">
              <option value="">Type de projet</option>
              <option value="Infogerance">Infogérance</option>
              <option value="Developpement">Développement</option>
            </select>

            <br><br>

            <button type="submit" name="button" class="btn btn-primary btn-lg">Valider</button>
        </form>
      </div>

      <br>

      <table>
        <thead>
          <th>Nom du projet</th>
          <th>Type de projet</th>
          <th>Nombre d'évenements</th>
        </thead>
        <tbody>

      <?php

        foreach ($nbProjet as $proj) {
          $Event = "SELECT EV_Libelle FROM event WHERE fk_PR ='".$proj["PR_id"]."';";
          $bdd = getConnexion();
          $queryEv = $bdd->prepare($Event);
          $queryEv->execute();
          $nbEvent = $queryEv->fetchAll();
          $nbtEvent += sizeof($nbEvent);


          echo "<td>".$proj["PR_Libelle"]."</td>";

          $TP = "SELECT TP_Libelle FROM typeprojet WHERE TP_id =".$proj["fk_TP"];
          $bdd = getConnexion();
          $queryTP = $bdd->prepare($TP);
          $queryTP->execute();
          $typeProjet = $queryTP->fetch();


          echo "<td>".$typeProjet["TP_Libelle"]."</td>";

          echo "<td>".sizeof($nbEvent)."</td>";

          $IDp = $proj["PR_Libelle"];
          echo "<td><a href='MonProjet.php?proj=".$IDp."'> Voir le projet </a></td>";

          echo "<tr></tr>";
        }

      ?>


        </tbody>
      </table>

      <br><br>

      <div class="">
        <?php
        if (isset($_GET["maj"])) {
          echo "Le projet: ".$_GET["maj"]." à été mis a jour";
        } ?>
      </div>

    </center>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
      function AjoutProjet(){
          $("#addProj").css('display','block');
      }
      function FermerProj(){
          $("#addProj").css('display','none');
      }
    </script>


  </body>
</html>
