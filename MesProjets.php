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
}?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Mes projets</title>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <style media="screen">
    .bordure{
      border-collapse: collapse;
      padding-top: 15px;
      padding-bottom: 15px;
    }
    .bordureC{
      border-collapse: collapse;
      border-radius: 20px;
      border: 1px solid black;
      padding-top: 15px;
      padding-bottom: 15px;
      margin-left: 5%;
      margin-right: 5%;
    }

    .titre{
      font-size: 19px;
    }

    #scrollUp{
      position: fixed;
      bottom : 10px;
      right: -100px;
      opacity: 0.5;
    }

    #BoutonAddEvent{
      border-radius: 25px;
      font-size: 13px;
      width: 150px;
      height: 30px;
    }

    .texte{
      font-size: 20px;
      margin-left: 30px;
    }

    #creationEvent{
      display: none;
      border: 1px solid black;
      background-color: white;
      position: fixed;
      right: 20%;
      left: 20%;
      top: 20%;
      bottom: auto;
      padding-bottom: 25px;
      max-height: 650px;
      overflow: scroll;
    }

    .content{
      display: none;
    }

    table, th, td{
      border: 1px solid black;
      border-collapse: collapse;
      text-align: center;
      padding: 5px 5px 5px 5px;
      width: 700px;
    }

    th{
      background-color: lightgrey;
    }

    th, td{
      height: 30px;
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
    .select{
      border-radius: 8px;
    }
    #OuvrirDiv{
      background-color: white;
      border-radius: 50%;
    }
    </style>
  </head>
  <body>

    <div id="scrollUp">
    <a href="#top"><img src="to_top.png"/></a>
    </div>

    <center>
      <h1>Mes projets</h1>
    </center>
    <br>

    <?php

    $getRole= "SELECT US_Roles, US_id FROM user WHERE US_Nom ='".$_SESSION["login"]."';";
    $bdd = getConnexion();
    $queryGR = $bdd->prepare($getRole);
    $queryGR->execute();
    $Role = $queryGR->fetch();

    if ($Role["US_Roles"] == "Admin") {
      $client = "SELECT CL_id, CL_Nom FROM client";
      $bdd = getConnexion();
      $query = $bdd->prepare($client);
      $query->execute();
      $NomClient = $query->fetchAll();

    }else {
      $client = "SELECT CL_id, CL_Nom FROM participe JOIN user ON fk_US = US_id JOIN projet ON fk_PR = PR_id JOIN client ON fk_CL = CL_id WHERE fk_US = ".$Role["US_id"];
      $bdd = getConnexion();
      $query = $bdd->prepare($client);
      $query->execute();
      $NomClient = $query->fetchAll();
    }



      ?>
      <center>
      <form class="" action="index.html" method="post">
        <select class="select" id="client" onselect="SelectProj()" >
          <option value ="">Selectionnez votre client</option>
          <?php
            foreach ($NomClient as $nomC) {
              $nom = $nomC["CL_Nom"];
              echo "<option value='".$nomC["CL_id"]."'>".$nom."</option>";
            }

           ?>


        </select>
          <select class="select" id="projet" disabled>
            <option value="">Selectionner un projet</option>
          </select>
      </form>
      <br>
      <button type="button" class="btn btn-secondary btn-sm" onclick="reset()">Reset</button>
    </center>
    <br>

      <?php

      foreach ($NomClient as $r ) {
        echo "<div class='bordureC' id='C".$r["CL_id"]."'>";
        echo "<center>";
        echo "<p class='titre'>".$r["CL_Nom"]."</p>";
        echo '<button type="button" name="button" id="OuvrirDiv" onclick="OuvrirClient('.$r["CL_id"].')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
              <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
            </svg></button>';
        echo "</center>";
        echo "<div class='content' id='".$r["CL_id"]."'>";

        $getIdClient = "SELECT CL_id FROM client WHERE CL_Nom ='".$r["CL_Nom"]."';";
        $bdd = getConnexion();
        $queryIdC = $bdd->prepare($getIdClient);
        $queryIdC->execute();
        $idClient = $queryIdC->fetch();


        $ProjetsClients = "SELECT PR_id, PR_Libelle, fk_CL FROM participe JOIN user ON US_id = fk_US JOIN projet ON fk_PR = PR_id WHERE fk_CL =".$idClient["CL_id"]. " AND US_id = ".$Role["US_id"];
        $bdd = getConnexion();
        $queryProjC = $bdd->prepare($ProjetsClients);
        $queryProjC->execute();
        $ProjClient = $queryProjC->fetchAll();
        $compt = 1;
        foreach ($ProjClient as $proj) {
          $deqdqed = "'".$proj["PR_id"].$proj["PR_Libelle"][0]."'";
          echo "<div class='bordure' id='D".$proj["PR_id"].$proj["PR_Libelle"][0]."'>";
            echo "<center>";
            echo "<hr>";
            echo "<a href='http://localhost/GestionMail/MonProjet.php?proj=".$proj['PR_Libelle']."' class='titre'>".$proj['PR_Libelle']."</a>";
            echo '<button type="button" name="button" id="OuvrirDiv" onclick="OuvrirDiv('.$deqdqed.','.$proj["PR_id"].','.$compt.')"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/>
              </svg></button>';
            $compt += 1;
            echo '<div style="display:none" class="content" id="'.$proj["PR_id"].$proj["PR_Libelle"][0].'">';
            if ($Role["US_Roles"] == "Admin") {
              echo "<br><button class='btn btn-secondary' type='button' id='BoutonAddEvent' onclick='Cevent()' value='".$proj['PR_id']."'>Ajout d'une tâche</button>";
            }
            echo "<br><br>";

            ?>
            <table>
              <thead>
                <th>Nom Evénement</th>
                <th>Type de tâche</th>
                <th>Réception par jours</th>
                <th>Réception par semaine</th>
                <th>Récapitulatif de la semaine</th>
              </thead>
              <?php echo '<tbody id="tableauProj'.$proj["PR_id"].'">';
              ?>

              </tbody>
            </table>
          </div>
            <?php

            echo "<br><br>";
            echo "</div>";

          echo "</center>";
        }
          echo "</div>";
          echo "</div>";
          echo "<br>";
      }

    ?>

    <div id="creationEvent">
      <button id="Fermer" class="btn-close" aria-label="Close" type="button" name="button" onclick="FermerCevent()"></button>
       <form class="" action="AjoutEvent.php" method="post">
        <center>
          <br>
          <h2>Création / Modification d'une tâche</h2>
          <br>
          <input id="NomProj" type="hidden" name="NomProj">
          <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Nom de la tâche</span>
             <input type="text" class="form-control" name="Nom" aria-describedby="basic-addon1">
          </div>
          <br>

          <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Type de la tâche</span>
             <input type="text" class="form-control" name="Type" placeholder="Ex: Sauvegarde" aria-describedby="basic-addon1">
          </div>

          <br>
          <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Frequence</span>
             <input type="text" class="form-control" placeholder="Nombre de mails reçus souhaité" name="Frequence" aria-describedby="basic-addon1">
             <select class="input-group-text" name="semaine">
               <option value="jour">/jour</option>
               <option value="semaine">/semaine</option>
             </select>
          </div>
          <br>

          <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Temps de sauvegarde</span>
             <input type="text" class="form-control" placeholder="Par défault 3 mois (écrire en jours)" name="TempsSauv" aria-describedby="basic-addon1">
          </div>
          <br>

          <div class="input-group mb-3">
             <span class="input-group-text" id="basic-addon1">Boite mail dans laquelle chercher</span>
             <input type="text" class="form-control" placeholder="Ex: exemple@gmail.com" name="Mail" aria-describedby="basic-addon1">
          </div>
          <br>
          <div id="ExpDiv">
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1">Ajouter un expediteur</span>
               <input type="text" class="form-control" placeholder="Ex: exemple@gmail.com" name="addExp1" aria-describedby="basic-addon1">
               <button class="input-group-text" type="button" name="button" onclick="addExp()">+</button>
            </div>
          </div>

          <br>

          <div id="McDiv">
            <div class="input-group mb-3">
               <span class="input-group-text" id="basic-addon1">Ajouter un mot clé de l'objet</span>
               <input type="text" class="form-control" placeholder="Ex: Update, success" name="addMc1" aria-describedby="basic-addon1">
               <button class="input-group-text" type="button" name="button" onclick="addMc()">+</button>
            </div>
          </div>
          <br>
          <input type="hidden" name="i" id="i" value="1">
          <input type="hidden" name="j" id="j" value="1">

          <div id="OptA">
            <input type="hidden" name="iOA" id="iOA" value="0">
          </div>
          <div class="form-check form-switch" style="text-align: left; margin-left: 45%;">
            <label class="form-check-label" for="crea">Création</label>
            <input class="form-check-input" type="checkbox" role="switch" name="crea" id="crea" checked>
          </div>
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
    document.getElementById("client").addEventListener("change",projet);
    document.getElementById("projet").addEventListener("change",OuvrirProj);

    var i = 1;
    var j = 1;
    var iOA = 0;
    var d = 0;

    function addExp(){
      i += 1;
      var inp = '<div class="input-group mb-3"><span class="input-group-text" id="basic-addon1">Ajouter un expediteur</span><input type="text" class="form-control" placeholder="Ex: exemple@gmail.com" name="addExp'+i+'" aria-describedby="basic-addon1"><button class="input-group-text" type="button" name="button" onclick="addExp()">+</button></div>';
      $("#ExpDiv").append(inp);
      document.getElementById("i").value = i;
    }

    function addMc(){
      j += 1;
      var inp = '<div class="input-group mb-3"><span class="input-group-text" id="basic-addon1">Ajouter un mot clé</span><input type="text" class="form-control" placeholder="Ex: Update, success" name="addMc'+j+'" aria-describedby="basic-addon1"><button class="input-group-text" type="button" name="button" onclick="addMc()">+</button></div>';
      $("#McDiv").append(inp);
      document.getElementById("j").value = j;
    }

    function OptionsAvancees(){
      iOA += 1;
      var OptA = '<div class="input-group mb-3"><span class="input-group-text" id="basic-addon1">Si le mail contient:</span><input type="text" class="form-control" name="OAsi'+iOA+'" placeholder="Mot clé (Ex: Error, failed)" aria-describedby="basic-addon1"><select class="input-group-text" name="OAalors'+iOA+'"><option value="">alors</option><option value="rouge">Mettre le background en rouge</option><option value="vert">Mettre le background en vert</option><option value="bleu">Mettre le background en bleu</option><option value="orange">Mettre le background en orange</option></select></div>'
      $("#OptA").append(OptA);
      document.getElementById("iOA").value = iOA;
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

    function reset(){
      var projet = document.getElementById("projet").value = "";
      var projet = document.getElementById("client").value = "";
      $(".bordure").css('display','block');
      $(".bordureC").css('display','block');
      $(".content").css('display','none');
    }

    async function OuvrirProj(){
      var projet = document.getElementById("projet");
      var strUser = projet.options[projet.selectedIndex].value;
      $(".bordure").css('display','none');
      $('#D'+strUser).css('display', 'block');
      await OuvrirDiv(strUser, strUser.slice(0, -1), strUser.slice(0,strUser.length - 1));
      document.getElementById(strUser).scrollIntoView();
    }

    async function projet(){
      var client = document.getElementById("client");
      var strUser = client.options[client.selectedIndex].value;
      var donnee;
      var projet = document.getElementById("projet");
      if (strUser == "") {
        projet.disabled = true;
        projet.value = "";
      } else {
      while (document.getElementById("delete") !== null) {
        document.getElementById("delete").remove();
      }
      await $.ajax("http://localhost/GestionMail/rechercheProj.php?client="+strUser).done(function(data){donnee = JSON.parse(data)});
      donnee.forEach((d, i) => {
        var option = document.createElement("option");
        option.text = d.PR_Libelle;
        option.id = "delete";
        option.value = d.PR_id + d.PR_Libelle[0];
        projet.add(option);
      });
      projet.disabled = false;
    }
    $(".bordureC").css('display','none');
    $("#C"+strUser).css('display','block');
    OuvrirClient(strUser);
  }

    function Cevent(){
      var myvalue =  document.getElementById("BoutonAddEvent").value;
      document.getElementById('NomProj').setAttribute('value', myvalue);
      $('#creationEvent').css('display','block');
    }

    function FermerCevent(){
      $('#creationEvent').css('display','none');
    }

    function dropTable(){
      var tr = document.getElementById("drop");
      while (tr != null) {
        tr.remove();
        var tr = document.getElementById("drop");
      }
    }

    function OuvrirClient(nom){
      if ($("#"+nom).css('display')=='none') {
        $("#"+nom).css('display','block');
      } else {
        $("#"+nom).css('display','none');
      }
    }

    async function OuvrirDiv(div, id, tab){
      var tr = document.getElementById("drop");
      if (tr != null) {
        dropTable();
      }
      if ($('#'+div).css('display') == 'none') {
        var donnee;
        await $.ajax("http://localhost/GestionMail/tableauMesProjets.php?proj="+id).done(function(data){donnee = JSON.parse(data)});
        donnee.forEach((d, i) => {
          var tr = "<tr id='drop'><td id='Nom'>"+d.Nom+"</td><td id='Type'>"+d.Type+"</td><td id='jour'>"+d.Jour+"</td><td id='semaine'>"+d.Semaine+"</td><td id='recap'><span class='"+d.Couleur+"'>"+d.Recap+"/"+d.Semaine+"</span></td></tr>"
          console.log(tab);
          $("#tableauProj"+tab).append(tr);
        });

          $('#'+div).css('display', 'block');

      } else {
          dropTable();
          $('#'+div).css('display', 'none');
      }

    }
    </script>

  </body>
</html>
