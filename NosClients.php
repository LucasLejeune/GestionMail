<?php
session_start();
include 'header.php';
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
<html>
  <head>
    <link rel="stylesheet" href="GestionMail.css">
    <meta charset="utf-8">
    <title>Nos Clients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <style media="screen">
      #creationClient{
        padding-left: 30px;
        padding-right: 30px;
      }
      tr,td,th {
        border: 1px solid black;
      }
      table{
        border: none;
      }
    </style>
  </head>
  <body>
    <center>
      <h1>Nos Clients</h1>

      <?php
      $getRole = "SELECT US_Roles FROM user WHERE US_Nom = '".$_SESSION["login"]."';";
      $bdd = getConnexion();
      $queryRole = $bdd->prepare($getRole);
      $queryRole->execute();
      $Role = $queryRole->fetch();

      if ($Role["US_Roles"] == "Admin") {
        echo '<button class="btn btn-secondary" type="button" onclick="AfficherCclient()">Ajouter un client</button>';
      }

       ?>

      <br><br>

      <table>
        <thead>
          <th>Nom</th>
          <th>Nombre de projet</th>
        </thead>
        <tbody>
          <?php
            $client = "SELECT CL_Nom FROM client";
            $bdd = getConnexion();
            $query = $bdd->prepare($client);
            $query->execute();
            $NomClient = $query->fetchAll();


            foreach ($NomClient as $r ) {
              $idClient = "SELECT CL_id FROM client WHERE CL_Nom = '".$r["CL_Nom"]."';";
              $bdd = getConnexion();
              $queryID = $bdd->prepare($idClient);
              $queryID->execute();
              $idClient = $queryID->fetch();

              $Projet = "SELECT PR_Libelle FROM projet WHERE fk_CL = '".$idClient["CL_id"]."';";
              $bdd = getConnexion();
              $queryNbP = $bdd->prepare($Projet);
              $queryNbP->execute();
              $nbProjet = $queryNbP->fetchAll();

              echo "<td>".$r["CL_Nom"]."</td>";
              echo "<td>".sizeof($nbProjet)."</td>";
              $Idc = $idClient["CL_id"];
              echo "<td><a href='MonClient.php?id=".$Idc."'> Voir le Client </a></td>";
              echo "<tr></tr>";

            }
           ?>
        </tbody>
      </table>
    </center>

    <div id="creationClient">
      <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="FermerCclient()"></button>
      <center>
        <form action="Cclient.php" method="post">
          <h2>Création d'un Client</h2>
          <br><br><br>
          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Nom du client</span>
            <input type="text" class="form-control" name="NomClient" aria-label="Username" aria-describedby="basic-addon1">
          </div>
          <br><br>
            <button class="btn btn-primary" type="submit" name="button" >Valider</button>
        </form>

      </center>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        function AfficherCclient(){
          $('#creationClient').css('display','block');
        }

        function FermerCclient(){
          $('#creationClient').css('display','none');
        }


    </script>


  </body>
</html>
