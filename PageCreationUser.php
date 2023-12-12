<?php session_start() ?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="GestionMail.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <meta charset="utf-8">
    <title>Créer / modifier utilisateur</title>
    <style media="screen">
      center{
        padding-left: 25%;
        padding-right: 25%;
      }
    </style>
  </head>
  <body>
    <?php if (isset($_SESSION["login"])) {
      include "header.php";
    }?>
    <center>
      <br>
        <h1 class="titre">Création / Modification d'un Utilisateur</h1>

        <br><br>

        <form class="FormCUser" action="CUser.php" method="post">

          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Nom de l'utilisateur</span>
            <input type="text" class="form-control" name="Nom" aria-label="Username" aria-describedby="basic-addon1">
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Contact de l'utilisateur</span>
            <input type="text" class="form-control" name="Contact" aria-label="Username" aria-describedby="basic-addon1">
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Mot de passe de l'utilisateur</span>
            <input type="password" class="form-control" name="mdp" aria-label="Username" aria-describedby="basic-addon1">
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Role de l'utilisateur</span>
            <select class="form-select" name="RoleUser">
              <option value="">Choisir...</option>
              <option value="Admin">Admin</option>
              <option value="User">User</option>
            </select>
          </div>

          <div class="input-group mb-3">
            <span class="input-group-text" id="basic-addon1">Type de l'utilisateur</span>
            <select class="form-select" name="TypeUser">
              <option value="">Choisir...</option>
              <option value="Developpeur">Développeur</option>
              <option value="Infogerant">Infogérant</option>
            </select>
          </div>

            <br>

          <div class="d-grid gap-2">
            <button class="btn btn-secondary" type="submit" name="button" id="ValidButton">Valider</button>
          </div>


        </form>

          <?php
          if (isset($_GET["Cuser"])) {
            if ($_GET["Cuser"] == "ok") {
              echo "<div class='BonCuser'>";
              echo "<p>Utilisateur crée avec succès !</p>";
              echo "</div>";
            } else {
                echo "<div class='PbCuser'>";
                echo "<p>Il y a eu une erreur lors de la création de l'utilisteur, merci de réessayer</p>";
                echo "</div>";
          }
        }


        if (isset($_GET["role"]) && !isset($_GET["type"])) {
          echo "<div class='BonCuser'>";
          echo "L'utilisateur ".$_GET["user"]." a reçu le rôle ".$_GET["role"]." avec succès";
          echo "</div>";
        }
        elseif (!isset($_GET["role"]) && isset($_GET["type"])) {
          echo "<div class='BonCuser'>";
          echo "L'utilisateur ".$_GET["user"]." a reçu le type ".$_GET["type"]." avec succès";
          echo "</div>";
        } elseif (isset($_GET["role"]) && isset($_GET["type"])) {
          echo "<div class='BonCuser'>";
          echo "L'utilisateur ".$_GET["user"]." a reçu le type ".$_GET["type"]." et le rôle ".$_GET["role"]." avec succès";
          echo "</div>";
        }
          ?>


        </div>
    </center>
  </body>
</html>
