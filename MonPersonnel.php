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
    <title>Mon personnel</title>
    <link rel="stylesheet" href="GestionMail.css">
  </head>
  <body>
    <center>
      <h1>Mon personnel</h1>
      <?php

      $getRole= "SELECT US_Roles FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
      $bdd = getConnexion();
      $queryGR = $bdd->prepare($getRole);
      $queryGR->execute();
      $Role = $queryGR->fetch();

      if ($Role["US_Roles"] == "Admin") {
        echo "<a href='http://localhost/GestionMail/PageCreationUser.php'>Ajout d'un utilisateur</a>";
      }
       ?>

      <br><br>


      <table>
        <thead>
          <th>Nom</th>
          <th>Rôle</th>
          <th>Mail</th>
          <th>Nombre de projet</th>
        </thead>
        <tbody>

          <?php




            $getUsers = "SELECT US_Nom, US_Mail, US_Roles FROM user";
            $bdd = getConnexion();
            $queryGu = $bdd->prepare($getUsers);
            $queryGu->execute();
            $Users = $queryGu->fetchAll();

            foreach ($Users as $us) {
              $NbProj = "SELECT PR_Libelle FROM participe JOIN user ON US_id = fk_US JOIN projet ON fk_PR = PR_id WHERE US_Nom ='".$us["US_Nom"]."';";
              $bdd = getConnexion();
              $queryNBp = $bdd->prepare($NbProj);
              $queryNBp->execute();
              $NombreProjets = $queryNBp->fetchAll();
              $NombreProjets = sizeof($NombreProjets);



              echo "<td>".$us["US_Nom"]."</td>";
              echo "<td>".$us["US_Roles"]."</td>";
              echo "<td>".$us["US_Mail"]."</td>";
              echo "<td>".$NombreProjets."</td>";
              echo "<td><a href='http://localhost/GestionMail/Utilisateur.php?user=".$us["US_Nom"]."'>Voir l'utilisateur</a></td>";
              echo "<tr></tr>";
            }

           ?>


        </tbody>
      </table>
    </center>
  </body>
</html>
