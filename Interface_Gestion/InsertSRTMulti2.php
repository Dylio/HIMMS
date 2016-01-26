<!DOCTYPE html>
<html>
    <head>
        <title>PTUTAGBD</title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
    </head>
    <body>
        <?php include 'nav.php' ;
        $HDeb = date("H:i:s");
        flush();
        ob_flush();
        // Fonction pour supprimmer ou modifier les différents caractères spéciaux
        include('fct_no_special_character.php'); 
        //Exclusion
        $tab = array();
        $MotExclu = array();
        $req = $linkpdo->query("SELECT * from exclusion;");
        while ($data = $req->fetch()){
            array_push($MotExclu, $data['libelle']);
        }?>
        <!-- Création d'un formulaire pour selectionner la série et le fichier srt (sous-titre) -->
        <form action="" method="post">
            <!-- Récupération de toute les titres des séries -->
            <?php $req = $linkpdo->query("SELECT * from Serie order by Titre"); ?>
            <fieldset class="fieldset"><legend>Série :</legend><br />
            <label for="Serie" class="txt">Série</label>
            <!-- Création d'une Liste déroulante contenant chaque titre de série. Chaque série est associé à son ID -->
            <select name="Serie" required>
                    <option></option>
                    <?php while($data = $req->fetch()){?>
                        <option value="<?php echo $data['0']; ?>" <?php if(isset($_POST['Serie']) and  $_POST['Serie'] == $data['0']) { echo "selected"; } ?> ><?php echo $data['1'];?></option><?php
                    }?>				
            </select><br />

            <label for="Saison">Version : </label>
            <select name="Version" required>
                <option <?php if(!isset($_POST['Version'])) { echo "selected"; } ?>"></option>
                <option value="VF" <?php if(isset($_POST['Version']) and 'VF' == $_POST['Version']) { echo "selected"; } ?> >VF</option>
                <option value="VO" <?php if(isset($_POST['Version']) and 'VO' == $_POST['Version']) { echo "selected"; } ?> >VO</option>		
            </select><br />			

            <!-- Création d'un bouton pour valider le formulaire -->	
            <input type="submit" value="Valider" name="btn">
            </fieldset>
        </form><br />
        <?php
        // Execute la procédure de récupération des mots-clé dés que l'utilisateur appuye sur le bouton
        if(isset($_POST['btn']) and isset($_POST['Serie'])){
            echo '<span style="color : blue">-------------- -------------- -------------- -------------- -------------- START -------------- -------------- -------------- -------------- ---------------</span><br/>';
            flush();
            ob_flush();
            $idS = $_POST['Serie'];
            $Version = $_POST['Version'];
            $TotalS = 1;
            // Préparation des requêtes SQL
            $req1 = $linkpdo->prepare("SELECT count(*), num_motcle from MotCle where motcle = :mc group by num_motcle;");
            $req2 = $linkpdo->prepare("SELECT occurrence from Appartenir where num_motcle = :idMC and num_serie = :idS;");
            $ins1 = $linkpdo->prepare("Insert into MotCle values(:idMC, :mc);");
            $ins2 = $linkpdo->prepare("Insert into Appartenir values(:idMC, :idS, :occ, 1);");
            $up1 = $linkpdo->prepare("Update Appartenir set occurrence = occurrence + :occ, nbEp = nbEp + 1 where num_serie = :idS and num_motcle = :idMC;");

            // Récupération de l'ID de la série selectionnée dans le formulaire
            $reqS = $linkpdo->query("SELECT * FROM serie where num_serie = '$idS';");
            $dataS = $reqS->fetch();       
            $TitreSerie = $dataS['1'];
            $dirnameS = '.\SRT\\'.$TitreSerie;
            $s = 0;
            $dirS = opendir($dirnameS);
            while($fileS = readdir($dirS)) {
               if(substr($fileS, 0, 7) == "Saison "){
                $s++;
               }
            }
            closedir($dirS);
            $nums = 1;
            while($s >= $nums) {
                echo $s.' '.$nums;
                $Episode = 1;
                echo "<br/><b style='color:black'>Traitement SAISON $nums</br>"; 
                flush(); 
                ob_flush();
                $dirname = '.\SRT\\'.$TitreSerie.'\\Saison '.$nums.'\\'.$Version;
                $j = 0;
                $dir = opendir($dirname);
                while($file = readdir($dir)) {
                   if(substr($file, -4) == ".srt" or substr($file, -4) == ".SRT"){
                    $j++;
                   }
                }
                if($j != 0){
                    closedir($dir);
                    $dir = opendir($dirname);
                    while($file = readdir($dir)) {
                        if($file != '.' && $file != '..' && !is_dir($dirname.$file) && (substr($file, -4) == ".srt" || substr($file, -4) == ".SRT")){
                            echo "<b class='NotifMesssage'>  Traitement (".$Episode."/".$j.") du srt : $file </b></br>";
                            flush(); 
                            ob_flush();
                            $TotalS ++;
                            $reqST = $linkpdo->query("SELECT count(*) FROM SousTitre where num_serie = '$idS' and saison = '$nums' and Episode = '$Episode' and Version = '$Version';");
                            $dataST = $reqST->fetch();
                            //Verification si le sous-titre n'a pas déjà été inséré pour cette série, cette saison et cet épisode
                            if($dataST['0'] == 0){
                                // Tableau contenant chaque ligne du fichier srt
                                $lines = file($dirname.'\\'.$file);
                                 // Pour chaque ligne du fichier srt ... ($i numéro de ligne)
                                foreach ($lines as $lineContent){
                                    // Exécute pour supprimmer ou modifier les différents caractères spéciaux
                                    $lineContent = no_special_character($lineContent);
                                    // Tableau contenant chaque mot 
                                    $lineContent = explode(" ", $lineContent);
                                    // Pour chaque mot ...
                                    foreach ($lineContent as $linetxt){
                                        // Si le texte n'est pas vide 
                                        // ou n'est pas un caractère numérique 
                                        // ou n'est pas un mot clé representatif (selon une liste) 
                                        if(!in_array($linetxt, $MotExclu)){
                                            if($linetxt != "" && $linetxt != " " && $linetxt != "-" && !ctype_digit(substr($linetxt, 0, 1))){
                                                // Si le mot clé existe dans le tableau incrémenté la valeur associé
                                                if(isset($tab[$linetxt])){
                                                    $tab[$linetxt]++;
                                                }else{
                                                    // Sinon crée le mot clé et y associé la valeur 1
                                                    $tab[$linetxt] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                                    echo "<b class='NotifOk'>Succés : Ce sous-titre a été traité : $file </b></br>";
                                $linkpdo->query("Insert into SousTitre values('$idS', '$nums', '$Episode', '$Version');");
                                $Episode ++;
                                if($Episode > $j){
                                    $nums ++;
                                }
                            } else {
                                echo "<b class='NotifError'>Erreur : Ce sous-titre a déjà été inséré : $file</b></br>";
                                $Episode ++;
                                if($Episode > $j){
                                    $nums ++;
                                }
                            }
                            flush();
                            ob_flush();
                         } 
                    }
                } else {  $nums ++; }
                }
                echo 'Mot-clé à inséré : '.sizeof($tab);
                flush(); 
                ob_flush();
                // Pour chaque mot du tableau
                foreach ($tab as $mc => $occ){
                    if($occ >= $TotalS/3){
                        //Requete SQL vérifiant si le mot clé existe dans la BDD
                        $req1->execute(array('mc' => $mc));
                        $data1 = $req1->fetch();
                        // Si il existe pas, création du mot clé et de l'association avec une occurence de $occ
                        if($data1['0'] == 0){
                            // Création d'une ID (Génère un identifiant unique basé sur la date et heure courante en microsecondes.)
                            $idMC = uniqid(rand(), true);
                            // Insertion dans la BDD
                            $ins1->execute(array('idMC' => $idMC, 'mc' => $mc));
                            $ins2->execute(array('idMC' => $idMC, 'idS' => $idS, 'occ' => $occ));
                        }else{				
                            $idMC = $data1['1'];
                            $req2->execute(array('idMC' => $idMC, 'idS' => $idS ));
                            $data2 = $req2->fetch();
                            if($data2['0'] > '0'){
                                // Si il existe, ajout de l'occurence à l'occurence existante
                                $up1->execute(array('idMC' => $idMC, 'idS' => $idS, 'occ' => $occ));
                            }else{
                                // Sinon création de l'association avec une occurence de $occ 
                                $ins2->execute(array('idMC' => $idMC, 'idS' => $idS, 'occ' => $occ));
                            }
                        }
                        flush(); 
                        ob_flush();
                    }
                }
            closedir($dir);
            echo '<span style="color : blue"><br />-------------- -------------- -------------- -------------- -------------- END -------------- -------------- -------------- -------------- ---------------</span>';
            $HFin = date("H:i:s");
            echo '<br />'.$HFin.' - '.$HDeb;
        }
        ?> 
    </body>
</html>