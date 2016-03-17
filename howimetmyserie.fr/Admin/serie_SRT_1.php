<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <!-- Importation des scripts et des stylesheet -->
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
        <?php 
            set_time_limit (0);
            session_start();
            if(!isset($_SESSION['admin'])){
                header('Location:index_1.php');
            }
            require_once 'class_admin_db.php';            // base de données
            require_once 'class_admin_affichage.php';     // affichage global
            require_once 'class_admin_controleur.php';    // afficahge et gestion des controleurs
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
        ?>
    </head>
    
    <body>
        <?php $affichage->affichage_menu(2);
        $affichage->affichage_site('AJOUTER DES SRT SERIES TV'); ?><br />
        <div style="width:80%; margin: 0 auto;">
            <!-- Création d'un formulaire pour selectionner la série et le fichier srt (sous-titre) -->
            <form action="" method="post" enctype="multipart/form-data">
                <fieldset class="fieldset">
                    <legend class="titre">Ajouter des nouveaux sous-titres :</legend>

                    <!-- Création d'une entrée de fichier de type .srt -->
                    <label for="sfile" style="width: 100%" class="txt">Fichier .zip contenant à la racine que des sous-titres .srt :</label>
                        <input type="file" style="width: 100%" name="fichier" accept='.zip' required ><br/>
                        <b>Attention !</b> Format d'écriture des fichiers .srt : <i style='font-weight: normal'>(titre sans espace)</i>s<i style='font-weight: normal'>(saison)</i>e<i style='font-weight: normal'>(episode)(version)</i>.srt<br />
                        Exemple : breakingbads02e06VF.srt ou southparks10e07VF.srt

                    <!-- Création d'un bouton pour valider le formulaire --><br/><br/>
                    <div style='margin:0 auto; width:400px;'>
                        <input type="submit" value="Ajouter les sous-titres." name="btn2" class="btn btn-primary btn-lg" >
                    </div>	
                </fieldset>
            </form><br/>
            <?php
            // Execute la procédure de récupération des mots-clé dés que l'utilisateur appuye sur le bouton
            if(isset($_POST['btn2'])){
                //Exclusion
                $MotExclu = array();
                $tab = array();
                $TotalS=0;
                $req = $db->motexclu();
                while ($dataMCE = $req->fetch()){
                    array_push($MotExclu, $dataMCE['libelle']);
                }
                $zip = zip_open($_FILES['fichier']['tmp_name']);
                if ($zip) {
                    while ($zip_entry = zip_read($zip)) {
                        if (zip_entry_open($zip, $zip_entry, "r")) {
                            $file = zip_entry_name($zip_entry);
                            $titre = substr($file, 0, -12);
                            $idS = $db->num_serie($titre);
                            $saison = substr($file, -11, 2);
                            $episode = substr($file, -8, 2);
                            $version = substr($file, -6, 2);
                            if(substr($file, -4) == ".srt" or substr($file, -4) == ".SRT"){
                                if(strtoupper($version) == 'VF' || strtoupper($version) == 'VO'){
                                    if(ctype_digit($episode)){
                                        if(ctype_digit($saison)){
                                            if(isset($idS)){
                                                //Verification si le sous-titre n'a pas déjà été inséré pour cette série, cette saison et cet épisode
                                                if($db->srt_exist($idS, $saison, $episode, $version) == 0){
                                                    $lineContent = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                                                    // Exécute pour supprimmer ou modifier les différents caractères spéciaux
                                                    $lineContent = class_admin_controleur::no_special_character($lineContent);
                                                    // Tableau contenant chaque mot 
                                                    $lineContent = explode(" ", $lineContent);
                                                    // Pour chaque mot ...
                                                    foreach ($lineContent as $linetxt){
                                                        // Si le texte n'est pas vide 
                                                        // ou n'est pas un caractère numérique 
                                                        // ou n'est pas un mot clé representatif (selon une liste) 
                                                        if(!in_array($linetxt, $MotExclu, false)){
                                                            if($linetxt != '' && $linetxt !=  null && $linetxt != " " && $linetxt != "-" && !ctype_digit(substr($linetxt, 0, 1))){
                                                                // Si le mot clé existe dans le tableau incrémenté la valeur associé
                                                                if(array_key_exists($linetxt, $tab)){
                                                                    $tab[$idS][$linetxt]++;
                                                                }else{
                                                                    // Sinon crée le mot clé et y associé la valeur 1
                                                                    $tab[$idS][$linetxt] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <div class="alert alert-success alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                                                        <strong>Succès du traitement ! <?php echo 'fichier : '.$file; ?>
                                                    </div>
                                                    <?php $db->insert_srt($idS, $saison, $episode, $version);
                                                    $TotalS ++;
                                                }else{ ?>
                                                    <div class="alert alert-warning alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                                                        <strong>Attention !</strong> Traitement impossible : ce sous-titre à déjà été inséré. <br/><?php echo 'fichier : '.$file; ?>
                                                    </div>
                                                <?php }
                                            }else{ ?>
                                                <div class="alert alert-warning alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                                                    <strong>Attention !</strong> Traitement impossible : série tv inconnu. <br/><?php echo 'fichier : '.$file; ?>
                                                </div>
                                            <?php }
                                        }else{?>
                                            <div class="alert alert-warning alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                                                <strong>Attention !</strong> Traitement impossible : la valeur de la saison est erroné. <br/><?php echo 'fichier : '.$file; ?>
                                            </div>
                                        <?php }
                                    }else{ ?>
                                        <div class="alert alert-warning alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                                            <strong>Attention !</strong> Traitement impossible : la valeur de la episode est erroné. <br/><?php echo 'fichier : '.$file; ?>
                                        </div>
                                    <?php }
                                }else{ ?>
                                    <div class="alert alert-warning alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                                        <strong>Attention !</strong> Traitement impossible : la valeur de la version est erroné. <br/><?php echo 'fichier : '.$file; ?>
                                    </div>
                                <?php }
                            }
                        zip_entry_close($zip_entry);
                        ob_flush();
                        }
                    }
                }
                $i=0;
                $nbEpisode = $TotalS + $db->nb_episode($idS);
                foreach ($tab as $serie){
                    foreach ($serie as $mc => $occ){
                        if($nbEpisode/4 > $db->test_mc($mc)+$occ){
                            unset($serie[$mc]);
                        }else{
                            $i ++;
                        }
                    }
                }
                zip_close($zip); 
                if($i != 0){ ?>
                    <div class="alert alert-info alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                        <strong>Veuillez patienter.</strong> Ajout de <?php echo $i; ?> mots-clés dans la base de données ...
                    </div>
                <?php }
                ob_flush();
                foreach ($tab as $serie){
                    foreach ($serie as $mc => $occ){
                        //Requete SQL vérifiant si le mot clé existe dans la BDD
                        $data1 = $db->motcle_exist($mc);
                        // Si il existe pas, création du mot clé et de l'association avec une occurence de $occ
                        if($data1['0'] == 0){
                            // Création d'une ID (Génère un identifiant unique basé sur la date et heure courante en microsecondes.)
                            $idMC = uniqid(rand(), true);
                            // Insertion dans la BDD
                            $db->motcle_insert($idMC, $mc);
                            $db->appartenir_insert($idMC, $idS, $occ);
                        }else{	
                            $idMC = $data1['1'];
                            if($db->motcle_occ($idMC, $idS) > '0'){
                                // Si il existe, ajout de l'occurence à l'occurence existante
                                $db->update_appartenir($idMC, $idS, $occ);
                            }else{
                                // Sinon création de l'association avec une occurence de $occ
                                $db->appartenir_insert($idMC, $idS, $occ);
                            }
                        }
                    }
                }
                ?>
                <div class="alert alert-info alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                    <strong>Fin du traitement !</strong>
                </div>
            <?php
            }
        ?></div>
    </body>
</html>