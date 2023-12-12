<?php
function getConnexion(){
  $dsn = 'mysql:dbname=gestionmail;host=127.0.0.1:3308';

  try {
      $bdd = new PDO($dsn, "root", "");
      return $bdd;
  } catch (PDOExeption $e) {
      die('DB Error: '.$e->getMessage());
  }

}


$boiteMail = 'imap.gmail.com';
$port = 993;
$login = 'gestionmailfcdigital@gmail.com';
$motDePasse = 'P@ssword/59';

$mbox = imap_open('{'.$boiteMail.':'.$port.'/imap/ssl/novalidate-cert}INBOX', $login, $motDePasse);
  $mails = FALSE;
  if (FALSE === $mbox) {
      $err = 'La connexion a échoué. Vérifiez vos paramètres!';
  } else {
      $info = imap_check($mbox);
      if (FALSE !== $info) {
          $nbMessages = min(50, $info->Nmsgs);
          $mails = imap_fetch_overview($mbox, '1:'.$nbMessages, 0);

      } else {
          $err = 'Impossible de lire le contenu de la boite mail';
      }

  }

  $jours = array(
    "Mon"=>1,
    "Tue"=>2,
    "Wed"=>3,
    "Thu"=>4,
    "Fri"=>5,
    "Sat"=>6
);

$j = 1;
$mailFrom = $_GET["ev"];
$i=1;
$lesMails = array();
    foreach ($mails as $mail) {
      $head = imap_headerinfo($mbox,$i);
      $from = $head->from;
      $date = $head->date;
      $day = $date[0].$date[1].$date[2];
      $date = $jours[$day];
      $uid = $mail->uid;

        if ($date == $_GET["jour"]) {
          foreach ($from as $id) {
            $fromMail = $id->mailbox;
            $fromhost = $id->host;
            $fromAdress = $fromMail."@".$fromhost;
            if ($fromAdress == $mailFrom) {
                      $message = imap_fetchbody($mbox,$i,1);
                      $liste = array("id"=>$j,"mail"=>$fromAdress,"objet"=>$mail->subject, "contenu"=>$message,"uid"=>$uid);
                      $lesMails[] = $liste;
                      $j +=1;
            }
        }
      }
      $i += 1;
    }


echo json_encode($lesMails);
imap_close($mbox);


 ?>
