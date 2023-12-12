<?php
if (session_status() === PHP_SESSION_DISABLED){
  session_start();
}
 ?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
  </head>
  <body>
    <nav id="body" class="navbar navbar-expand-lg bg-light" id="nav">
  <div class="container-fluid">
    <a class="navbar-brand" href="">My mail</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" href="http://localhost/GestionMail/Accueil.php">Acceuil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="http://localhost/GestionMail/MesProjets.php">Mes projets</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="http://localhost/GestionMail/NosClients.php">Nos Clients</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="http://localhost/GestionMail/MonPersonnel.php">Mon personnel</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="MonCompte.php">Mon compte</a>
        </li>
        <?php

        $getMailNA = "SELECT MA_id FROM mail WHERE fk_EV IS NULL";
        $bdd = getConnexion();
        $queryGMNa = $bdd->prepare($getMailNA);
        $queryGMNa->execute();
        $MailsNa = $queryGMNa->fetchAll();

        if ($_SESSION["login"] != "") {
            echo '<li class="nav-item">
                    <a class="nav-link active" href="deconnexion.php">Déconnexion</a>
                  </li>';
          }
          echo '<span id="NA" class="navbar-text"><a href="MailsNA.php">Il y a '.sizeof($MailsNa).' mail(s) non attaché(s)<a></span>';

         ?>
      </ul>
    </div>
  </div>
</nav>
  </body>
</html>
