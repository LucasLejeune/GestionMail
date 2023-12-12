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
    <title>Mails non attachés</title>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <style media="screen">
    table, th, td{
      border: 1px solid black;
      border-collapse: collapse;
      padding: 5px 5px 5px 5px;
      width: 900px;
    }

    th{
      background-color: lightgrey;
    }

    th, td{
      height: 30px;
    }
    select{
      text-align: center;
      width: 150px;
    }
    button{
      border: none;
    }
    #del{
      background-color: white;
    }
    </style>
  </head>
  <body>
  <center>
    <h1>Liste des mails non attachés à un événement</h1>
    <br>
    <table>
      <thead>
        <th>Expéditeur</th>
        <th>Objet</th>
        <th>Contenu</th>
        <th>Date</th>
      </thead>
      <tbody>
        <?php

        $getMailNA = "SELECT MA_id, MA_Expediteur, MA_Object, MA_Contenu, MA_date FROM mail WHERE fk_EV IS NULL";
        $bdd = getConnexion();
        $queryGMNa = $bdd->prepare($getMailNA);
        $queryGMNa->execute();
        $MailsNa = $queryGMNa->fetchAll();

        $getClient = "SELECT CL_Nom, CL_id FROM client";
        $bdd = getConnexion();
        $queryGCl = $bdd->prepare($getClient);
        $queryGCl->execute();
        $clients = $queryGCl->fetchAll();

        foreach ($MailsNa as $key) {
          echo "<tr>";
          echo "<td>".$key["MA_Expediteur"]."</td>";
          echo "<td>".$key["MA_Object"]."</td>";
          echo "<td>".$key["MA_Contenu"]."</td>";
          echo "<td>".$key["MA_date"]."</td>";
          echo "<td>";
          echo '<form class="" action="attacherMail.php" method="post">';
          echo "<input type='hidden' name='mailId' value='".$key["MA_id"]."'></input>";
          echo '<select name="client" id="client">';
          echo '<option value="">Ajouter au client</option>';
          foreach ($clients as $client) {
            echo '<option value="'.$client["CL_id"].'">'.$client["CL_Nom"].'</option>';
          }
          echo '</select>';
          echo "<br>";
          echo "<select name='projet' id='proj' style:'display: none' disabled><option value=''>Au projet</option></select>";
          echo "<br>";
          echo "<select name='event' id='ev' style:'display: none' disabled><option value=''>A la tâche</option></select>";
          echo "<br>";
          echo "<input type='hidden' name='evId' id='evId' value=''></input>";
          echo "<button type='submit' class='btn btn-primary btn-sm'>Valider</button>";
          echo "</form>";
          echo "</td>";
          echo "<td>";
          echo "<input type='hidden' name='mailId' value='".$key["MA_id"]."'></input>";
          echo '<button type="button" id="del" onclick="delMail('.$key["MA_id"].')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
            <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
            <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
          </svg></button>';
          echo "</td>";
          echo "</tr>";
        }


         ?>

      </tbody>
    </table>
  </center>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script type="text/javascript">
  document.getElementById("client").addEventListener("change",client);
  document.getElementById("proj").addEventListener("change",projet);
  document.getElementById("ev").addEventListener("change",evenement);

  async function client(){
    var donnee;
    var client = document.getElementById("client");
    var clId = client.options[client.selectedIndex].value;
    var projet = document.getElementById("proj");
    if (clId == "") {
      projet.disabled = true;
      projet.value = "";
    } else {
      projet.disabled = false;  
      while (document.getElementById("delete") !== null) {
        document.getElementById("delete").remove();
      }

      await $.ajax("http://localhost/GestionMail/getProj.php?cl="+clId).done(function(data){donnee=JSON.parse(data)});
      donnee.forEach((d, i) => {
        var option = document.createElement("option");
        option.text = d.PR_Libelle;
        option.id = "delete";
        option.value = "P"+d.PR_id;
        projet.add(option);

      });

    }

  }

  async function projet(){
    var donnee;
    var projet = document.getElementById("proj");
    var prId = projet.options[projet.selectedIndex].value;
    var ev = document.getElementById("ev");
    if (prId == "") {
      ev.disabled = true;
      ev.value = "";
    } else {
      ev.disabled = false;
      while (document.getElementById("deleteEv") !== null) {
        document.getElementById("deleteEv").remove();
      }

      await $.ajax("http://localhost/GestionMail/getEvent.php?pr="+prId).done(function(data){donnee=JSON.parse(data)});
      donnee.forEach((d, i) => {
        var option = document.createElement("option");
        option.text = d.EV_Libelle;
        option.id = "deleteEv";
        option.value = "E"+d.EV_id;
        ev.add(option);

      });
    }
  }

  function evenement(){
    var projet = document.getElementById("ev");
    var prId = projet.options[projet.selectedIndex].value;
    document.getElementById("evId").value = prId;
  }

  async function delMail(mail){
    if (window.confirm('Êtes-vous sûr de vouloir supprimer ce mail ?')) {
      await $.ajax("http://localhost/GestionMail/delMail.php?mail="+mail);
      location.reload();
    }
  }

  </script>
  </body>
</html>
