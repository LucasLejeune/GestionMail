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
    <title>Mon compte</title>
  </head>
  <body>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <style media="screen">
    .event{
      border: 1px solid black;
      margin-bottom: 25px;
      margin-top: 25px;
    }
    #scrollUp{
      position: fixed;
      bottom : 10px;
      right: -100px;
      opacity: 0.5;
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
      bottom:30%;
    }
    label{
      display: inline-block;
      clear: left;
      width: 250px;
      text-align: right;
    }
    input {
      display: inline-block;
    }
    .err{
      display: block;
    }
    .ChangeMdp{
      padding-left: 35%;
      padding-right: 35%;
    }
    </style>
  </head>
  <body>
    <center>
      <?php
      $NomUser = $_SESSION["login"];

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


        $getRole= "SELECT US_Roles FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
        $bdd = getConnexion();
        $queryGR = $bdd->prepare($getRole);
        $queryGR->execute();
        $Role = $queryGR->fetch();

        if ($Role["US_Roles"] == "Admin") {
          echo '<button type="button" class="btn btn-secondary" name="button" onclick="AjoutProjet()">Ajouter un nouveau projet</button><br><br>';
        }
        ?>
        <center>
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
          $NombreEvent = $queryNBe->fetchAll();

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
        <br><br>
      <div class="ChangeMdp">
        <h4>Modification de mot de passe</h4>
        <form class="" action="ModifMdp.php" method="post">

          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Mot de passe actuel</span>
            <input type="password" class="form-control" id="Amdp" aria-label="Username" aria-describedby="basic-addon1" name="Amdp">
            <button class="input-group-text" id="show" type="button" name="button" onclick="myFunction('Amdp')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
              <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
              <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
            </svg></button>
          </div>
          <?php
          if (isset($_GET["err"])) {
            $erreur = $_GET["err"];
            if ($erreur == "3") {
              echo '<div class="err" id="Amdp"><i style="color:red">Mauvais mot de passe</i></div>';
            }
          }
           ?>

           <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Nouveau mot de passe</span>
             <input type="password" class="form-control" id="Nmdp" aria-label="Username" aria-describedby="basic-addon1" name="Nmdp">
             <button class="input-group-text" id="show" type="button" name="button" onclick="myFunction('Nmdp')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
               <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
               <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
             </svg></button>
           </div>
          <?php
          if (isset($_GET["err"])) {
            $erreur = $_GET["err"];
            if ($erreur == "1") {
              echo "<div class='err' id='Nmdp'><i style='color:red'>Veuillez entrer un mot de passe différent de l'actuel</i></div>";
            }
          }
           ?>

           <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Confirmer le mot de passe</span>
             <input type="password" class="form-control" id="CNmdp" aria-label="Username" aria-describedby="basic-addon1" name="CNmdp">
             <button class="input-group-text" id="show" type="button" name="button" onclick="myFunction('CNmdp')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
               <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
               <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
             </svg></button>
           </div>

          <?php
          if (isset($_GET["err"])) {
            $erreur = $_GET["err"];
            if ($erreur == "2") {
              echo '<div class="err" id="CNmdp"><i style="color:red">Les mots de passe ne correspondent pas</i></div>';
            }
            if ($erreur == 'none') {
              echo '<div class="err" id="errV"><i style="color:green">Mot de passe modifié avec succes</i></div>';
            }
          }
           ?>

          <br><br>
        <button type="submit" name="button" class="btn btn-primary">Confirmer</button>
        </form>
      </div>
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
    <br><br>

    <div id="scrollUp">
    <a href="#top"><img src="to_top.png"/></a>
    </div>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
      <script type="text/javascript">
      function myFunction(id) {
        var x = document.getElementById(id);
        if (x.type === "password") {
          x.type = "text";
        } else {
          x.type = "password";
        }
      }


      jQuery(function(){
        $(function () {
            $(window).scroll(function () { //Fonction appelée quand on descend la page
                if ($(this).scrollTop() > 200 ) {  // Quand on est à 200pixels du haut de page,
                    $('#scrollUp').css('right','10px'); // Replace à 10pixels de la droite l'image
                } else {
                    $('#scrollUp').removeAttr( 'style' ); // Enlève les attributs CSS affectés par javascript
                }
            });
        });
        });

        function AjoutProjet(){
          $("#AjoutUser").css('display','block');
        }
        function retour(div){
          $("#"+div).css('display','block');
        }
        function Fermer(){
          $("#AjoutUser").css('display','none');
        }
      </script>
  </body>
</html>
