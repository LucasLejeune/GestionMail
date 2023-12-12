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
<html>
  <head>
    <meta charset="utf-8">
    <title>Accueil</title>
    <style media="screen">
      #scrollUp{
        position: fixed;
        bottom : 10px;
        right: -100px;
        opacity: 0.5;
      }
      .clients{
        border: 1px solid black;
        border-collapse: collapse;
        padding-top: 10px;
        padding-bottom: 10px;
      }
      .rouge{
        background-color: red;
        padding-left: 20px;
        padding-right: 20px;
      }
      .vert{
        background-color: lightgreen;
        padding-left: 20px;
        padding-right: 20px;
      }
      .orange{
        background-color: orange;
        padding-left: 20px;
        padding-right: 20px;
      }
      .bleu{
        background-color: lightblue;
        padding-left: 20px;
        padding-right: 20px;
      }
      table, td{
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        padding: 5px 5px 5px 5px;
        width: 700px;
      }
      span {
        border-radius: 200px;
        margin-left: 5px;
      }
      .select{
        border-radius: 50%;
        background-color: white;
      }
      .select:hover{
        background-color: #DFDFDF;
      }
    </style>
  </head>
  <body>
    <center>
      <h1>Page d'acceuil</h1>
      <br>
    </center>
    <div id="scrollUp">
      <a href="#top"><img src="to_top.png"/></a>
    </div>
    <?php
      $getUsRole = "SELECT US_Roles, US_id FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
      $bdd = getConnexion();
      $queryGUsR = $bdd->prepare($getUsRole);
      $queryGUsR->execute();
      $Role = $queryGUsR->fetch();

      if ($Role["US_Roles"] == "Admin") {
        $getClients = "SELECT CL_id, CL_Nom FROM client";
        $bdd = getConnexion();
        $queryGCl = $bdd->prepare($getClients);
        $queryGCl->execute();
        $Clients = $queryGCl->fetchAll();

        foreach ($Clients as $client) {
          echo "<center><div id='".$client["CL_id"]."' class='clients'><h4>".$client["CL_Nom"]."</h4><button onclick='afficherClient(".$client["CL_id"].")'type='button' name='button' class='select'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-caret-down-fill' viewBox='0 0 16 16'>
              <path d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/>
            </svg></button></center></div>";
        }
      } else {
        $getProjets = "SELECT fk_PR FROM participe WHERE fk_US=".$Role["US_id"];
        $bdd = getConnexion();
        $queryGPr = $bdd->prepare($getProjets);
        $queryGPr->execute();
        $Projets = $queryGPr->fetchAll();
        $listeClient = array();
        $condition = "WHERE ";

        foreach ($Projets as $projs) {
          $getClId = "SELECT fk_CL FROM projet WHERE PR_id = ".$projs["fk_PR"];
          $bdd = getConnexion();
          $queryGClId = $bdd->prepare($getClId);
          $queryGClId->execute();
          $ClientsId = $queryGClId->fetch();

          if (array_search($ClientsId,$listeClient) === false) {
            array_push($listeClient,$ClientsId);
          }

        }
        $i = 0;
        foreach ($listeClient as $listeCL) {
          if ($i == 0) {
            $condition .= "CL_id =".strval($listeCL[$i]);
          } else {
            $condition .= "OR CL_id =".$listeCL;
          }
          $i += 1;

        }
          $getClients = "SELECT CL_id, CL_Nom FROM client ".$condition;
          $bdd = getConnexion();
          $queryGCl = $bdd->prepare($getClients);
          $queryGCl->execute();
          $Clients = $queryGCl->fetchAll();

          foreach ($Clients as $client) {
            echo "<center><div id='".$client["CL_id"]."' class='clients'><h4>".$client["CL_Nom"]."</h4><button onclick='afficherClient(".$client["CL_id"].")'type='button' name='button' class='select'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-caret-down-fill' viewBox='0 0 16 16'>
                <path d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/>
              </svg></button></center></div>";
          }
      }

     ?>
  </body>
</html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
async function afficherClient(cl){
  if (document.getElementById("drop") != null) {
    viderClient()
  } else {
      var donnee;
      await $.ajax("http://localhost/GestionMail/RecupAccueil.php?cl="+cl).done(function(data){donnee = JSON.parse(data)});
      donnee.forEach((d, i) => {
        var proj = "<center><div><br id='drop'><h4 id='drop'><a id='drop' href='http://localhost/GestionMail/MonProjet.php?proj="+d.PR_Libelle+"'>"+d.PR_Libelle+"</a></h4><table id='drop'><tbody id='"+d.PR_id+"P'><td colspan='2'>code couleur: <span class='rouge'>Pas assez de mails reçus</span><span class='orange'>En attente</span><span class='bleu'>Trop de mails reçus</span><span class='vert'>ok</span></td></tbody></table></center></div>";
        $("#"+cl).append(proj);
        afficherEvents(d.PR_id);
      });
    }
  }

async function afficherEvents(pr){
  var donnee;
  await $.ajax("http://localhost/GestionMail/RecupEvAccueil.php?pr="+pr).done(function(data){donnee = JSON.parse(data)});
  donnee.forEach((d, i) => {
    var events = "<tr><td id='drop'>"+d.EV_Libelle+"</td><td id='drop'>Etat: <span id='drop' class ='"+d.EV_Etat+"'></span></td></tr>";
    $("#"+pr+"P").append(events);
  });

}

function viderClient(){
  var tr = document.getElementById("drop");
  while (tr != null) {
    tr.remove();
    var tr = document.getElementById("drop");
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
</script>
