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
    <title>Mon projet</title>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <style media="screen">
      #scrollUp{
        position: fixed;
        bottom : 10px;
        right: -100px;
        opacity: 0.5;
      }
      .event{
        border: 1px solid black;
        margin-bottom: 25px;
        margin-top: 25px;
        margin-left: 10%;
        margin-right: 10%;
        padding-top: 10px;
        padding-bottom: 10px;
        padding-left: 10px;
        padding-bottom: 10px;
        border-radius: 15px;
      }
      #AjoutUser{
        border: 1px solid black;
        background-color: white;
        position: fixed;
        padding-bottom: 30px;
        display: none;
        padding-left: 50px;
        padding-right: 50px;
        right: 20%;
        left: 20%;
        top: 20%;
        bottom: auto;
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
      #creationEvent, #modale, #modaleMail, #tabMailsSemaine, #ModaleAddExp{
        display: none;
        border: 1px solid black;
        background-color: white;
        position: fixed;
        right: 20%;
        left: 20%;
        top: 20%;
        bottom: 10%;
        padding-bottom: 40px;
        padding-left: 50px;
        padding-right: 50px;
        max-height: 650px;
        overflow: auto;
      }
      .content{
        display: none;
      }

      table, th, td{
        border: 1px solid black;
        border-collapse: collapse;
        padding: 5px 5px 5px 5px;
        width: 700px;
      }

      th{
        background-color: lightgrey;
      }

      th, td{
        height: 30px;
      }
      .AfficherMail{
        background-color: grey;
      }
      .rouge{
        background-color: red;
        padding-left: 20px;
        padding-right: 20px;
      }
      .vert{
        background-color: green;
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
      .gris{
        background-color: lightgrey;
        padding-left: 20px;
        padding-right: 20px;
      }
      #OuvrirDiv, #delEv, #editEv{
        float: right;
        margin-right: 5px;
        border-radius: 50%;
        background-color: white;
      }
      #OuvrirDiv:hover, #delEv:hover, #editEv:hover{
        background-color: #DFDFDF;
      }
      .texte{
        font-size: 20px;
        margin-left: 30px;
      }
      .MessageErreur{
        position: fixed;
        right: 30px;
        top: 100px;
        max-width: 500px;
        max-height: 200px;
        text-align: left;
        overflow: auto;
        border: 0.5px solid black;
        background-color: white;
        border-collapse: collapse;
        padding-left: 10px;
        padding-right: 10px;
      }
      #BoutonOA{
        position: fixed;
        right: 2px;
        top: 100px;
        background-color: white;
      }
      #BoutonOA:hover{
        background-color: #DFDFDF;
      }

      .boutonDoa{
        background: none;
        border: none;
      }
    </style>
  </head>
  <body>
    <div id="scrollUp">
    <a href="#top"><img src="to_top.png"/></a>
    </div>
    <center>
      <button type="button" name="button" id="BoutonOA" onclick="cacherOA()"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8zm-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5z"/>
</svg></button>
      <div class="MessageErreur">
        <?php

          $Proj = $_GET["proj"];

          $getProjId = "SELECT PR_id FROM projet WHERE PR_Libelle ='".$Proj."';";
          $bdd = getConnexion();
          $queryPid = $bdd->prepare($getProjId);
          $queryPid->execute();
          $ProjId = $queryPid->fetch();
          $idProjet = $ProjId["PR_id"];

          $getOA = "SELECT MA_id, MA_date, MA_Oa, fk_OA, fk_EV FROM mail JOIN event ON EV_id = fk_EV WHERE MA_Oa != 'NULL' AND fk_PR=".$idProjet;
          $bdd = getConnexion();
          $queryGOa = $bdd->prepare($getOA);
          $queryGOa->execute();
          $OA = $queryGOa->fetchAll();


          $jours = array(
            "Monday" => "lundi",
            "Tuesday" => "mardi",
            "Wednesday" => "mercredi",
            "Thursday" => "jeudi",
            "Friday" => "vendredi",
            "Saturday" => "samedi",
            "Sunday" => "dimanche",
          );

          echo "<center><h4>Liste des Options Avancées reçues</h4></center>";


          if (sizeof($OA) > 0) {
            foreach ($OA as $OptA) {
              $getEvent= "SELECT EV_Libelle FROM event WHERE EV_id =".$OptA["fk_EV"]." AND fk_PR=".$idProjet;
              $bdd = getConnexion();
              $queryGEv = $bdd->prepare($getEvent);
              $queryGEv->execute();
              $Ev = $queryGEv->fetch();

              $getOADesc = "SELECT OA_Conditions FROM optionavance WHERE OA_id =".$OptA["fk_OA"];
              $bdd = getConnexion();
              $queryGOaD = $bdd->prepare($getOADesc);
              $queryGOaD->execute();
              $OAdesc = $queryGOaD->fetch();

              $jour = date('l', strtotime($OptA["MA_date"]));


              echo '<form action="deleteOA.php?ma='.$OptA["MA_id"].'&return='.$_GET["proj"].'" method="post">';

              echo "-> ".$Ev["EV_Libelle"].", reçu le: ".date('d-m-Y', strtotime($OptA["MA_date"]))." (".$jours[$jour]."), contient: ".$OAdesc["OA_Conditions"].' <button class="boutonDoa" type="submit" name="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
              </svg></button></form>';
            }
          } else {
              echo "<p style='text-align: center'>Rien à signaler</p>";
          }




         ?>
      </div>
      <div id="tabMailsSemaine">
        <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="dropTms()"></button>
        <h4>Mails de cette semaine</h4>
        <table>
          <thead>
            <th>#</th>
            <th>Expéditeur</th>
            <th>Objet</th>
            <th>Contenu</th>
          </thead>
          <tbody id = "modaleMailsSemaine">

          </tbody>
        </table>
      </div>
    </center>
    <br><br>

    <?php

      $getClient = "SELECT CL_Nom FROM client JOIN projet ON CL_id = fk_CL WHERE PR_id ='".$idProjet."';";
      $bdd = getConnexion();
      $queryGC = $bdd->prepare($getClient);
      $queryGC->execute();
      $Client = $queryGC->fetch();

      echo "<center>";
      echo "Client: ".$Client["CL_Nom"];
      echo "<h1>".$Proj."</h1>";

      $isAdmin = "SELECT US_Roles FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
      $bdd = getConnexion();
      $queryIa = $bdd->prepare($isAdmin);
      $queryIa->execute();
      $Admin = $queryIa->fetch();

      if ($Admin["US_Roles"] == "Admin"){
        echo "<button class='btn btn-secondary' type='button' id='BoutonAddEvent' onclick='Cevent()' value='".$idProjet."'>Ajout d'une tâche</button>";
        echo "&emsp;";
      }

      echo "</center>";

      $getEvents = "SELECT EV_id, EV_Libelle, EV_Frequence,EV_Jour, EV_MailEvent FROM event WHERE fk_PR =".$idProjet.";";
      $bdd = getConnexion();
      $queryGE = $bdd->prepare($getEvents);
      $queryGE->execute();
      $Event = $queryGE->fetchAll();

      foreach ($Event as $ev) {
        echo "<div class='event'>";
        echo $ev["EV_Libelle"];
        echo "&emsp;";
        echo $ev["EV_Frequence"]." fois par ".$ev["EV_Jour"];
        $typeFreq = "'".$ev["EV_Jour"]."'";
        echo '<button type="button" name="button" id="OuvrirDiv" onclick="OuvrirDiv('.$ev["EV_id"].','.$ev["EV_Frequence"].','.$typeFreq.')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
          <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
        </svg></button>';
        if ($Admin["US_Roles"] == "Admin") {
          echo '<button type="button" id="editEv" name="button" onclick="EditEv('.$ev["EV_id"].')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/>
          </svg></button>';
          echo '<button type="button" id="delEv" name="button" onclick="delEv('.$ev["EV_id"].')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
          </svg></button>';
        }

        ?>
        <center>
          <?php
            echo "<div class='content' id='".$ev["EV_id"]."'>";
            echo "<br><br>";


        echo "</center>";
        echo "</div>";

      }

      echo "<center>";
      echo "<h2>Utilisateurs liés au projet</h2>";
      if ($Admin["US_Roles"] == "Admin"){
        echo '<button class="btn btn-secondary" type="button" name="button" onclick="AfficherAddUser()">Ajouter un utilisateur au projet</button><br><br>';
      }

      $getUtilisateurs = "SELECT US_id, US_Nom FROM participe JOIN user ON US_id = fk_US JOIN projet ON PR_id = fk_PR WHERE PR_id =".$idProjet.";";
      $bdd = getConnexion();
      $queryGU = $bdd->prepare($getUtilisateurs);
      $queryGU->execute();
      $users = $queryGU->fetchAll();

      echo "<table>";
      echo "<tbody>";
      foreach ($users as $us) {
        echo "<tr>";
        echo "<td>".$us["US_Nom"]."</td>";
        echo '<td><a href="http://localhost/GestionMail/RetirerUser.php?user='.$us["US_id"].'&proj='.$idProjet.'">Retirer</a></td>';
        echo "</tr>";
      }
      echo "</tbody>";
      echo "</table>";

      if (isset($_GET["erreur"])) {
        echo "L'utilisateur ".$_GET["erreur"]." n'existe pas";
      }

   ?>

  </div>

   <div id="AjoutUser">
     <br>
     <h2>Ajout de l'utilisateur au projet</h2>
     <br>
     <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="FermerAddUser()"></button>
      <form class="" action="AjoutUser.php" method="post">
        <?php echo '<input type="hidden" name="projet" value="'.$idProjet.'">'; ?>
          <?php
          $getUserProj = "SELECT fk_US FROM participe WHERE fk_PR = ".$idProjet;
          $bdd = getConnexion();
          $queryGUP = $bdd->prepare($getUserProj);
          $queryGUP->execute();
          $usersProj = $queryGUP->fetchAll();
          $list = "";
          foreach ($usersProj as $usP) {
            if ($list == "") {
              $str = strval($usP["fk_US"]);
              $list .= $str;
            } else {
              $str = ",".strval($usP["fk_US"]);
              $list .= $str;
            }
          }

          $getUsers = "SELECT US_Nom, US_id FROM user WHERE US_id NOT IN (".$list.")";
          $bdd = getConnexion();
          $queryGU = $bdd->prepare($getUsers);
          $queryGU->execute();
          $users = $queryGU->fetchAll();

            if (sizeof($users) !== 0) {
              for ($i=1; $i <5 ; $i++) {
                echo "<select class ='form-select' name='users".$i."'><option value=''>selectionner un utilisateur a ajouter</option>";
                foreach ($users as $user) {
                  echo "<option value='".$user["US_Nom"]."'>".$user["US_Nom"]."</option>";
                }
                echo "</select><br><br>";
              }
              echo "<button class='btn btn-primary' type='submit' name='button'>Valider</button><br>";
            }
            else {
              echo "<h3>Plus aucun utilisateur disponible</h3>";
            }

          ?>


      </form>
   </div>


   <div id="creationEvent">
     <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="FermerCevent()"></button>
     <?php

      echo '<form class="" action="AjoutEvent.php?return=MonProjet" method="post">'

      ?>
       <center>
         <br>
         <h2>Création / Modification d'une tâche</h2>
         <br>
         <input id="NomProj" type="hidden" name="NomProj">
         <input type="hidden" name="idEvent" id="idEventEdit">
         <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Nom de la tâche</span>
            <input type="text" class="form-control" id="LibelleProj" name="Nom" aria-describedby="basic-addon1">
         </div>
         <br>

         <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Type de la tâche</span>
            <input type="text" class="form-control" id="TypeProj" name="Type" aria-describedby="basic-addon1">
         </div>

         <br>
         <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Frequence</span>
            <input type="text" class="form-control" id="NbMailProj" placeholder="Nombre de mails reçus souhaité" name="Frequence" aria-describedby="basic-addon1">
            <select class="input-group-text" id="SelectFreqProj" name="semaine">
              <option value="jour">/jour</option>
              <option value="semaine">/semaine</option>
            </select>
         </div>
         <br>

         <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Temps de sauvegarde</span>
            <input type="text" class="form-control" id="TempSaveProj" placeholder="Par défault 5 semaines (écrire en semaines)" name="TempsSauv" aria-describedby="basic-addon1">
         </div>
         <br>

         <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Boite mail dans laquelle chercher</span>
            <input type="text" class="form-control" id="MailEvProj" placeholder="Ex: exemple@gmail.com" name="Mail" aria-describedby="basic-addon1">
         </div>
         <br>
         <div id="ExpDiv">
           <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Ajouter un expediteur</span>
              <input type="text" class="form-control" id="addExp1" placeholder="Ex: exemple@gmail.com" name="addExp1" aria-describedby="basic-addon1">
              <button class="input-group-text" type="button" name="button" onclick="addExp()">+</button>
           </div>
         </div>

         <br>

         <div id="McDiv">
           <div class="input-group mb-3">
              <span class="input-group-text" id="basic-addon1">Ajouter un mot clé de l'objet</span>
              <input type="text" class="form-control" id= "addMc1" placeholder="Ex: Update, success" name="addMc1" aria-describedby="basic-addon1">
              <button class="input-group-text" type="button" name="button" onclick="addMc()">+</button>
           </div>
         </div>
         <br>
         <input type="hidden" name="i" id="i" value="1">
         <input type="hidden" name="j" id="j" value="1">

         <div id="OptA">
           <input type="hidden" name="iOA" id="iOA" value="0">
         </div>
         <br>
         <input type="hidden" id="EventEdit" name="crea" value="on">
         <div class="form-check form-switch" style="text-align: left; margin-left: 45%;">
           <label class="form-check-label" for="Status">Actif</label>
           <input class="form-check-input" type="checkbox" role="switch" name="Status" id="Status" checked>
         </div>
         <button type="button" onclick="OptionsAvancees()" class="btn btn-link">+ Option avancée</button>
         <br>
         <div class="d-grid gap-2">
           <button type="submit" name="button" class="btn btn-primary">Valider</button>
         </div>
       </center>
     </form>
   </div>



   <div id="modale" style="display: none">
     <h2>Mails de la journée</h2>
     <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="Fermer()"></button>
     <table>
       <thead>
         <tr>
           <th>#</th>
           <th>Expéditeur</th>
           <th>Objet</th>
           <th>Contenu</th>
         </tr>
       </thead>
       <tbody id='test'>

       </tbody>
     </table>
   </div>

   <div id="modaleMail">
     <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="viderAfficherMail()"></button>
     <table>
       <tbody id="Container">

       </tbody>
     </table>
  </div>


   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <script type="text/javascript">
      var i = 1;
      var j = 1;
      var iOA = 0;

      function addExp(){
        i += 1;
        var inp = '<div id="dropExp'+i+'" class="input-group mb-3"><span class="input-group-text" id="basic-addon1">Ajouter un expediteur</span><input id="addExp'+i+'" type="text" class="form-control" placeholder="Ex: exemple@gmail.com" name="addExp'+i+'" aria-describedby="basic-addon1"><button class="input-group-text" type="button" name="button" onclick="addExp()">+</button></div>';
        $("#ExpDiv").append(inp);
        document.getElementById("i").value = i;
      }

      async function EditEv(ev){
        let donnee;
        await $.ajax("http://localhost/GestionMail/getEv.php?ev="+ev).done(function(data){donnee = JSON.parse(data)});
        Cevent();
        document.getElementById("LibelleProj").value = donnee.EV_Libelle;
        document.getElementById("TypeProj").value = donnee.TE_Libelle;
        document.getElementById("NbMailProj").value = donnee.EV_Frequence;
        document.getElementById("SelectFreqProj").value = donnee.EV_Jour;
        document.getElementById("TempSaveProj").value = donnee.EV_TempSauvegarde;
        document.getElementById("MailEvProj").value = donnee.EV_MailEvent;
        document.getElementById("addExp1").value = donnee.Exp1;
        let exp = donnee.Exp;
        document.getElementById("addExp1").value = donnee.Exp[1];
          for (var i = 2; i < Object.keys(exp).length; i++) {
            addExp();
            document.getElementById("addExp"+i).value = donnee.Exp[i];
          }
        let Mc = donnee.Mc;
        document.getElementById("addMc1").value = Mc[1];
        for (var i = 2; i <= Object.keys(Mc).length; i++) {
          addMc();
          document.getElementById("addMc"+i).value = Mc[i];
        }
        let Oa = donnee.Oa;
        for (var k = 1; k <= Object.keys(Oa).length; k++) {
          OptionsAvancees();
          document.getElementById("OAsi"+k).value = donnee.Oa[k];
          document.getElementById("OAalors"+k).value = donnee.OaS[k];
        }
        document.getElementById("EventEdit").value = "off";
        document.getElementById("idEventEdit").value = ev;
      }

      async function delEv(ev){
        if (window.confirm('Êtes-vous sûr de vouloir supprimer cette tâche ?')) {
          await $.ajax("http://localhost/GestionMail/delEv.php?ev="+ev);
          location.reload();
        }
      }

      function addMc(){
        j += 1;
        var inp = '<div id="dropMc'+j+'" class="input-group mb-3"><span class="input-group-text" id="basic-addon1">Ajouter un mot clé</span><input type="text" id="addMc'+j+'" class="form-control" placeholder="Ex: Update, success" name="addMc'+j+'" aria-describedby="basic-addon1"><button class="input-group-text" type="button" name="button" onclick="addMc()">+</button></div>';
        $("#McDiv").append(inp);
        document.getElementById("j").value = j;
      }

      function OptionsAvancees(){
        iOA += 1;
        var apot = "'";
        var OptA = '<div id="dropOa'+iOA+'" class="input-group mb-3" ><span class="input-group-text" id="basic-addon1">Si le mail contient dans l'+apot+'objet:</span><input type="text" class="form-control" id="OAsi'+iOA+'" name="OAsi'+iOA+'" placeholder="Mot clé (Ex: Error, failed)" aria-describedby="basic-addon1"><select class="input-group-text" id="OAalors'+iOA+'" name="OAalors'+iOA+'"><option value="">alors</option><option value="rouge">Mettre le background en rouge</option><option value="vert">Mettre le background en vert</option><option value="bleu">Mettre le background en bleu</option><option value="orange">Mettre le background en orange</option></select></div>'
        $("#OptA").append(OptA);
        document.getElementById("iOA").value = iOA;
      }

     function AfficherAddUser(){
       $('#AjoutUser').css('display', 'block');
     }
     function FermerAddUser(){
       $('#AjoutUser').css('display','none');
     }
     function Cevent(){
       document.getElementById("EventEdit").value = "on";
       var myvalue =  document.getElementById("BoutonAddEvent").value;
       document.getElementById('NomProj').setAttribute('value', myvalue);
       $('#creationEvent').css('display','block');
     }


     function FermerCevent(){
       document.getElementById("LibelleProj").value = null;
       document.getElementById("TypeProj").value = null;
       document.getElementById("NbMailProj").value = null;
       document.getElementById("SelectFreqProj").value = "jour";
       document.getElementById("TempSaveProj").value = null;
       document.getElementById("MailEvProj").value = null;
       document.getElementById("addExp1").value = null;
       document.getElementById("addMc1").value = null;
       for (var m = 2; m <= i; m++) {
         if (document.getElementById("dropExp"+m) != null) {
           document.getElementById("dropExp"+m).remove();
         }
       }
       for (var n = 2; n <= j; n++) {
         if (document.getElementById("dropMc"+n) != null) {
           document.getElementById("dropMc"+n).remove();
         }
       }
       for (var l = 1; l <= iOA; l++) {
         if (document.getElementById("dropOa"+l) != null) {
           document.getElementById("dropOa"+l).remove();
         }
       }
       i = 1;
       j = 1;
       iOA = 0;
       $('#creationEvent').css('display','none');
     }

     function cacherOA(){
       if ($(".MessageErreur").css('display') == 'block') {
         $(".MessageErreur").css('display', 'none');
       } else {
         $(".MessageErreur").css('display', 'block');
       }
     }

     async function OuvrirDiv(id, freq, typeFreq){
       var donnee;
       await $.ajax("http://localhost/GestionMail/tableauJours.php?event="+id).done(function(data){donnee = JSON.parse(data)})
       var strid = "#"+id;
       if ($(strid).css('display') == 'none') {
         let tr = '<table id="tableJours"><tbody><tr onclick="(test('+id+',1))"><td>Lundi</td><td><span class="'+donnee.MondayC+'">'+donnee.Monday+'/'+freq+'</span></td></tr><tr onclick="(test('+id+',2))"><td>Mardi</td><td><span class="'+donnee.TuesdayC+'">'+donnee.Tuesday+'/'+freq+'</span></td></tr><tr onclick="(test('+id+',3))"><td>Mercredi</td><td><span class="'+donnee.WednesdayC+'">'+donnee.Wednesday+'/'+freq+'</span></td></tr><tr onclick="(test('+id+',4))"><td>Jeudi</td><td><span class="'+donnee.ThursdayC+'">'+donnee.Thursday+'/'+freq+'</span></td></tr><tr onclick="(test('+id+',5))"><td>Vendredi</td><td><span class="'+donnee.FridayC+'">'+donnee.Friday+'/'+freq+'</td></tr><tr onclick="(test('+id+',6))"><td>Samedi</td><td><span class="'+donnee.SaturdayC+'">'+donnee.Saturday+'/'+freq+'</td></tr><tr onclick="(test('+id+',7))"><td>Dimanche</td><td><span class="'+donnee.SundayC+'">'+donnee.Sunday+'/'+freq+'</td></tr></tbody></table>';
         $("#"+id).append(tr);
         historique(id,freq,strid, typeFreq);
            $(strid).css('display', 'block');
       } else {
          var table = document.getElementById("tableJours");
          var tableH = document.getElementById("tableHisto");
          var titreH = document.getElementById("titreH");
          table.remove();
          tableH.remove();
          titreH.remove();
          $(strid).css('display', 'none');
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

     function Fermer(){
       $("#modale").css('display','none');
       dropTable();
     }

     async function test(ev,jour){
       var donnee;
       await $.ajax("http://localhost/GestionMail/modaleMailsDuJour.php?event="+ev+"&jour="+jour).done(function(data){donnee = JSON.parse(data)});
       $("#modale").css('display','block');
       donnee.forEach((d, i) => {
         i+=1
         let tr = '<tr id="drop" onclick="afficherMail('+d.MA_id+')"><td class="'+d.MA_Oa+'">'+i+'</td><td>'+d.MA_Expediteur+'</td><td>'+d.MA_Object+'</td><td>'+d.MA_Contenu+'</tr>';
         $("#test").append(tr);
       });
     }

     function dropTable(){
       var tr = document.getElementById("drop");
       while (tr != null) {
         tr.remove();
         var tr = document.getElementById("drop");
       }
     }

     async function afficherMail(id){
       var donnee;
       await $.ajax("http://localhost/GestionMail/modaleMailDetail.php?id="+id).done(function(data){donnee = JSON.parse(data)});

       $("#modaleMail").css('display','block');
       var app = '<tr><td id="numMail">Mail n°'+id+'</td></tr><tr><td id="expediteur">'+donnee.MA_Expediteur+'</td></tr><tr><td id="objet">'+donnee.MA_Object+'</td></tr><tr><td id="contenu">'+donnee.MA_Contenu+'</td></tr>';

       $("#Container").append(app);

     }

     function viderAfficherMail(){
       var expediteur = document.getElementById("expediteur");
       var mail = document.getElementById("numMail");
       var objet = document.getElementById("objet");
       var contenu = document.getElementById("contenu");
       expediteur.remove();
       mail.remove();
       objet.remove();
       contenu.remove();
       $("#modaleMail").css('display','none');
     }

     async function historique(ev, freq, div, typeFreq){
       var donnee;
       await $.ajax("http://localhost/GestionMail/semaineMonProjet.php?ev="+ev).done(function(data){donnee = JSON.parse(data)});
       if (typeFreq == "semaine") {
         var histo = "<h4 id='titreH'>Historique</h4><table id='tableHisto'><tbody><tr onclick='MailsSemaine(1,"+ev+")'><td>Du: "+donnee.dateDS1+" au: "+donnee.dateS1+"</td><td><span class='"+donnee.sc1+"'>"+donnee.s1+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(2,"+ev+")'><td>Du: "+donnee.dateDS2+" au: "+donnee.dateS2+"</td><td><span class='"+donnee.sc2+"'>"+donnee.s2+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(3,"+ev+")'><td>Du: "+donnee.dateDS3+" au: "+donnee.dateS3+"</td><td><span class='"+donnee.sc3+"'>"+donnee.s3+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(4,"+ev+")'><td>Du: "+donnee.dateDS4+" au: "+donnee.dateS4+"</td><td><span class='"+donnee.sc4+"'>"+donnee.s4+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(5,"+ev+")'><td>Du: "+donnee.dateDS5+" au: "+donnee.dateS5+"</td><td><span class='"+donnee.sc5+"'>"+donnee.s5+"/"+freq+"</span></td></tr><tr><td>Total</td><td><span class='"+donnee.sct+"'>"+donnee.total+"/"+freq*5+"</span></td></tbody></table>";
       }
       else {
         freq *= 7;
         var histo = "<h4 id='titreH'>Historique</h4><table id='tableHisto'><tbody><tr onclick='MailsSemaine(1,"+ev+")'><td>Du: "+donnee.dateDS1+" au: "+donnee.dateS1+"</td><td><span class='"+donnee.sc1+"'>"+donnee.s1+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(2,"+ev+")'><td>Du: "+donnee.dateDS2+" au: "+donnee.dateS2+"</td><td><span class='"+donnee.sc2+"'>"+donnee.s2+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(3,"+ev+")'><td>Du: "+donnee.dateDS3+" au: "+donnee.dateS3+"</td><td><span class='"+donnee.sc3+"'>"+donnee.s3+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(4,"+ev+")'><td>Du: "+donnee.dateDS4+" au: "+donnee.dateS4+"</td><td><span class='"+donnee.sc4+"'>"+donnee.s4+"/"+freq+"</span></td></tr><tr onclick='MailsSemaine(5,"+ev+")'><td>Du: "+donnee.dateDS5+" au: "+donnee.dateS5+"</td><td><span class='"+donnee.sc5+"'>"+donnee.s5+"/"+freq+"</span></td></tr><tr><td>Total</td><td><span class='"+donnee.sct+"'>"+donnee.total+"/"+freq*5+"</span></td></tbody></table>";
       }
       $(div).append(histo);
     }

     function dropTms(){
       var tr = document.getElementById("droptms");
       while (tr != null) {
         tr.remove();
         var tr = document.getElementById("droptms");
       }
       $("#tabMailsSemaine").css('display','none');
     }

     async function MailsSemaine(semaine, ev){
         var donnee;
         await $.ajax("http://localhost/GestionMail/modaleMailSemaine.php?ev="+ev+"&sem="+semaine).done(function(data){donnee = JSON.parse(data)});
         donnee.forEach((d, i) => {
             var nb = i+1;
             var tr = "<tr onclick='afficherMail("+d.MA_id+")' id='droptms'><td class='"+d.MA_Oa+"'>"+nb+"<td>"+d.MA_Expediteur+"</td><td>"+d.MA_Object+"</td><td>"+d.MA_Contenu.slice(0,15)+"</td>";
             $("#modaleMailsSemaine").append(tr);
         });
         $("#tabMailsSemaine").css('display','block');
       }


   </script>

  </body>
</html>
