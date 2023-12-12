<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Page pour éxécuter les scripts</title>
  </head>
  <body>
    <button type="button" name="button" onclick="mail()">Lancer mailToBDD</button>
    <button type="button" name="button" onclick="etat()">Lancer changementEtat</button>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
      async function mail(){
        $.ajax("http://localhost/GestionMail/mailToBDD.php");
      }
      async function etat(){
        $.ajax("http://localhost/GestionMail/changeEtat.php");
      }
    </script>
  </body>
</html>
