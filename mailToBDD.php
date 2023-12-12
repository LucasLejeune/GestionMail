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
$login = 'gestionmailfcdigital@gmail.com'; //adresse mail complète
$motDePasse = 'rlca xsoe iptd mzqf'; // mot de passe de l'adresse mail

$mbox = imap_open('{'.$boiteMail.':'.$port.'/imap/ssl/novalidate-cert}INBOX', $login, $motDePasse); //remplacer INBOX par le dossier si nécessaire, attention aux accents, pour plus de détails: https://openclassrooms.com/forum/sujet/imap-recuperer-les-mails-d-un-dossier-96763
  $mails = FALSE;
  if (FALSE === $mbox) {
      $err = 'La connexion a échoué. Vérifiez vos paramètres!';
  } else {
      $info = imap_check($mbox);
      if (FALSE !== $info) {
          $search = imap_search($mbox, 'UNSEEN');
          $nbMessages = $info->Nmsgs;
          $mails = imap_fetch_overview($mbox, '1:'.$nbMessages, 0);

          $EVfinalId = 0;

          $getMC = "SELECT MC_Libelle, MC_id FROM motscles";
          $bdd = getConnexion();
          $queryGMC = $bdd->prepare($getMC);
          $queryGMC->execute();
          $listeMC = $queryGMC->fetchAll();

          $allEvent = "SELECT fk_EV FROM contient";
          $bdd = getConnexion();
          $queryAEV = $bdd->prepare($allEvent);
          $queryAEV->execute();
          $getEvent = $queryAEV->fetchAll();
          $arraynbMails = array();

          foreach ($getEvent as $key) {
            $sql = "SELECT fk_EV, fk_MA FROM contient WHERE fk_EV = :EV;";
            $bdd = getConnexion();
            $query = $bdd->prepare($sql);
            $query->execute(array(":EV" => $key["fk_EV"]));
            $getMails = $query->fetchAll();
            $arraynbMails += [$key["fk_EV"] => sizeof($getMails)];
          }
          foreach ($search as $rech) {
            foreach ($mails as $mail) {
              if ($mail->msgno == $rech) {
                $send = false;
                $date = strtotime($mail->date);
                $newDate = date("Y-m-d H:i:s",$date);
                $head = imap_headerinfo($mbox,$mail->msgno);
                $from = $head->from;

                foreach ($from as $id) {
                  $fromMail = $id->mailbox;
                  $fromhost = $id->host;
                  $expediteur = $fromMail."@".$fromhost;
                  $objet = $mail->subject;
                  $contenu = imap_fetchbody($mbox,$mail->msgno,1);
                  $MCevent = array();

                  $j = 0;
                  foreach ($listeMC as $lmc) {
                    if (strpos($objet,$lmc["MC_Libelle"]) !== false) {
                      $lib = $lmc["MC_Libelle"];
                      $idMC = $lmc["MC_id"];
                      $MCevent += [ $j => $idMC ];
                      $j += 1;
                    }
                  }



                  if (sizeof($MCevent) > 0) {
                    if (sizeof($MCevent) == 1) {
                      $event = "SELECT fk_EV FROM contient WHERE fk_MA =".$MCevent[0];
                      $bdd = getConnexion();
                      $queryEV = $bdd->prepare($event);
                      $queryEV->execute();
                      $evId = $queryEV->fetchAll();

                      foreach ($evId as $identEvent) {
                        $getExp = "SELECT fk_EX, fk_EV FROM envoie WHERE fk_EV =".$identEvent["fk_EV"];
                        $bdd = getConnexion();
                        $queryGEx = $bdd->prepare($getExp);
                        $queryGEx->execute();
                        $Expediteurs = $queryGEx->fetchAll();

                        foreach ($Expediteurs as $exp) {
                          $getExNom = "SELECT EX_adresse FROM expediteurs WHERE EX_id = ".$exp["fk_EX"];
                          $bdd = getConnexion();
                          $queryGExN = $bdd->prepare($getExNom);
                          $queryGExN->execute();
                          $ExpNom = $queryGExN->fetch();
                          if ($ExpNom["EX_adresse"] == $expediteur) {
                            $send = true;
                            $EVfinalId = $identEvent["fk_EV"];
                          }
                        }
                      }
                    } else {
                      $listeEVid = array();
                      foreach ($MCevent as $mcev) {
                        $event = "SELECT fk_EV FROM contient WHERE fk_MA =".$mcev;
                        $bdd = getConnexion();
                        $queryEV = $bdd->prepare($event);
                        $queryEV->execute();
                        $evId = $queryEV->fetchAll();
                        foreach ($evId as $zev) {
                          if ($listeEVid[$zev["fk_EV"]] != null) {
                            $listeEVid[$zev["fk_EV"]] += 1;
                          } else {
                            $listeEVid += [$zev["fk_EV"] => 1];
                          }
                        }
                      }
                      $max = 0;
                      foreach ($listeEVid as $lsei) {
                        if ($lsei > $max) {
                          $EVgetID = $lsei;
                          $max = $lsei;
                        }
                      }
                      $cles = array_keys($listeEVid);
                      $arrayMC= array();
                      foreach ($cles as $key) {
                        if ($arraynbMails[$key] == $EVgetID) {
                          $getExp = "SELECT fk_EX, fk_EV FROM envoie WHERE fk_EV =".$key;
                          $bdd = getConnexion();
                          $queryGEx = $bdd->prepare($getExp);
                          $queryGEx->execute();
                          $Expediteurs = $queryGEx->fetchAll();

                          foreach ($MCevent as $MotCle) {
                            $geg = "SELECT fk_EV, fk_MA FROM contient WHERE fk_MA =".$MotCle." AND fk_EV=".$key;
                            $bdd = getConnexion();
                            $queryGEg = $bdd->prepare($geg);
                            $queryGEg->execute();
                            $ge = $queryGEg->fetch();

                            if ($ge !== false) {
                              if ($arrayMC[$ge["fk_EV"]] === null) {
                                $arrayMC += [$ge["fk_EV"] => 1];
                              } else {
                                $arrayMC[$ge["fk_EV"]] += 1;
                              }
                            }
                          }
                          $MCkeys = array_keys($arrayMC);
                          foreach ($arrayMC as $arrMC) {
                            if ($max == $arrMC) {
                              foreach ($MCkeys as $MCkey) {
                                if ($arrayMC[$MCkey] == $arrMC) {
                                  $finalKey = $MCkey;
                                }
                              }
                              foreach ($Expediteurs as $exp) {
                                $getExNom = "SELECT EX_adresse FROM expediteurs WHERE EX_id =".$exp["fk_EX"];
                                $bdd = getConnexion();
                                $queryGExN = $bdd->prepare($getExNom);
                                $queryGExN->execute();
                                $ExpNom = $queryGExN->fetch();
                                if ($ExpNom["EX_adresse"] == $expediteur) {
                                  $send = true;
                                  $EVfinalId = $finalKey;
                                }
                              }
                            }
                          }
                        }
                      }
                    }
                    $isNewMail = "SELECT MA_id FROM mail WHERE MA_Expediteur='".$expediteur."' AND MA_Object='".$objet."' AND MA_Contenu='".$contenu."' AND MA_Date='".$newDate."' AND fk_EV=".$EVfinalId;
                    $bdd = getConnexion();
                    $queryINM = $bdd->prepare($isNewMail);
                    $queryINM->execute();
                    $new = $queryINM->fetch();
                    var_dump($new);

                    $getOA = "SELECT OA_id, OA_Conditions, OA_Status,fk_EV FROM optionavance WHERE fk_EV =".$EVfinalId;
                    $bdd = getConnexion();
                    $queryGOa = $bdd->prepare($getOA);
                    $queryGOa->execute();
                    $OA = $queryGOa->fetchAll();
                    $isOA = false;
                    if ($OA !== false) {
                      foreach ($OA as $OptA) {
                        if (strpos($objet, $OptA["OA_Conditions"]) !== false) {
                          $isOA = true;
                          $OptAid = $OptA["OA_Status"];
                          $OptEv = $OptA["fk_EV"];
                          $OptAidV = $OptA["OA_id"];
                        }
                      }
                    }

                    if ($new == FALSE) {
                      if ($send == true) {
                        if ($isOA === false) {
                          $insertMail = "INSERT INTO mail (MA_Expediteur, MA_Object, MA_Contenu, MA_date, fk_EV) VALUES (:expediteur, :objet, :contenu, :date, :event)";
                          $bdd = getConnexion();
                          $queryIM = $bdd->prepare($insertMail);
                          $queryIM->execute(array(
                            ":expediteur" => $expediteur,
                            ":objet" => $objet,
                            ":contenu" => $contenu,
                            ":date" => $newDate,
                            ":event" => $EVfinalId
                          ));
                        } else {
                          $insertMail = "INSERT INTO mail (MA_Expediteur, MA_Object, MA_Contenu, MA_date, MA_Oa,fk_OA, fk_EV) VALUES (:expediteur, :objet, :contenu, :date, :OA, :OAid, :event)";
                          $bdd = getConnexion();
                          $queryIM = $bdd->prepare($insertMail);
                          $queryIM->execute(array(
                            ":expediteur" => $expediteur,
                            ":objet" => $objet,
                            ":contenu" => $contenu,
                            ":date" => $newDate,
                            ":OA" => $OptAid,
                            ":OAid" => $OptAidV,
                            ":event" => $OptEv
                          ));
                        }
                      }
                    }
                  }
                  else {
                    $insertMail = "INSERT INTO mail (MA_Expediteur, MA_Object, MA_Contenu, MA_date) VALUES (:expediteur, :objet, :contenu, :date)";
                    $bdd = getConnexion();
                    $queryIM = $bdd->prepare($insertMail);
                    $queryIM->execute(array(
                      ":expediteur" => $expediteur,
                      ":objet" => $objet,
                      ":contenu" => $contenu,
                      ":date" => $newDate
                    ));

                    var_dump($insertMail, $expediteur, $objet,$contenu,$newDate);
                  }
              }
            }
          }
      }
  }
}

 ?>
