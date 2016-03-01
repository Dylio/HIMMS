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
            require_once 'class_admin_db.php';            // base de données
            require_once 'class_admin_affichage.php';     // affichage global
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
        ?>
    </head>
    
    <body>
        <?php
        if(isset($_POST['btn1'])){
            $db->update_serie($_GET['num_serie'], $_POST['titre'], $_POST['dateD'], $_POST['dateF'], $_POST['nationalite'], $_POST['créateurs'], $_POST['acteurs'], $_POST['genre'], $_POST['format'], $_POST['nbSaison'], $_POST['nbEpisode'], $_POST['classification']);
        }
        if(isset($_POST['btn3'])){
            $db->delete_motcle($_GET['num_serie'], $_POST['btn3']);
        }
        $affichage->affichage_menu(1);
        $opt = $_GET['opt'];
        $data = $db->une_serie($_GET['num_serie']);    // sectionne les détail de la série TV 
        
        echo "<p class='NomPartie3'>Modification de la série tv : <br/>".$data['titre']."</p>";
        $affichage->affichage_menu_serie($opt, $_GET['num_serie']);
        if($opt == 1){ ?>
            <form action="" method="POST" style="width:71%; float:right;">
                <fieldset class="fieldset">
                    <legend class="titre">Modification des détails :</legend>
                    <label for="Titre" style="width: 20%">Titre : </label>
                        <input type="text" style="width: 70%" name="titre" value="<?php echo $data['titre']; ?>" required /><br/>
                    <label for="DateD" style="width: 20%">Date de début : </label>
                        <input type="number" min="1900" max ="<?php echo date('Y')+5; ?>" name="dateD" value="<?php echo $data['dateD']; ?>" style="width:80px;" /><br/>
                    <label for="DateF" style="width: 20%">Date de fin : </label>
                        <input type="number" min="1900" max ="<?php echo date('Y')+5; ?>" name="dateF" value="<?php echo $data['dateF']; ?>" style="width:80px;" /><br/>
                    <label for="Nationalité" style="width: 20%">Nationalité : </label>
                        <input type="text" style="width: 70%" name="nationalite" value="<?php echo $data['nationalite']; ?>"/><br/>
                    <label for="Créateurs" style="width: 20%">Créateurs : </label>
                        <input type="text" style="width: 70%" name="créateurs" value="<?php echo $data['créateurs']; ?>"/><br/>
                    <label for="Acteurs" style="width: 20%" style="width: 20%">Acteurs : </label>
                        <input type="text" style="width: 70%" name="acteurs" value="<?php echo $data['acteurs']; ?>"/><br/>
                    <label for="Genre" style="width: 20%" style="width: 20%">Genre : </label>
                        <input type="text" style="width: 70%" name="genre" value="<?php echo $data['genre']; ?>"/><br/>
                    <label for="Format" style="width: 20%">Format : </label>
                        <input type="number" name="format" value="<?php echo $data['format']; ?>" style="width:80px;" /><br/>
                    <label for="nbSaison" style="width: 20%">Nb Saison : </label>
                        <input type="number" name="nbSaison" value="<?php echo $data['nbSaison']; ?>" style="width:80px;" /><br/>
                    <label for="nbEpisode" style="width: 20%">Nb Episode : </label>
                        <input type="number" name="nbEpisode" value="<?php echo $data['nbEpisode']; ?>" style="width:80px;" /><br/>
                    <label for="classification" style="width: 20%">Classification : </label>
                        <select name="classification" style="width: 20%" required>
                            <option value="null">Tout Public</option>
                            <option value="10" <?php if(isset($_POST['classification']) and  $_POST['classification'] == 10) { echo "selected"; } ?> >10</option>			
                            <option value="12" <?php if(isset($_POST['classification']) and  $_POST['classification'] == 12) { echo "selected"; } ?> >12</option>			
                            <option value="16" <?php if(isset($_POST['classification']) and  $_POST['classification'] == 16) { echo "selected"; } ?> >16</option>			
                            <option value="18" <?php if(isset($_POST['classification']) and  $_POST['classification'] == 18) { echo "selected"; } ?> >18</option>			
                        </select><br/><br/>
                        <div style='margin-left: auto; margin-right:auto;width:400px;'>
                            <input type="submit" value="Modifier les détails." name="btn1" class="btn btn-primary btn-lg" >
                        </div><br/><br/>
                </fieldset>
            </form>
        <?php }
        
        if($opt == 2){ ?>
            <!-- Création d'un formulaire pour selectionner la série et le fichier srt (sous-titre) -->
            <form action="" method="post" style="width:71%; float:right;">
                <!-- Récupération de toute les titres des séries -->
                <?php $req = $db->serie(null, null, null, "titre asc"); ?>
                <fieldset class="fieldset">
                    <legend class="titre">Ajouter un nouveau sous-titre :</legend>

                    <!-- Création d'une entrée de fichier de type .srt -->
                    <label for="s" style="width: 20%" class="txt">Sous-Titre :</label>
                        <input type="file" style="width: 70%" name="s" id="s" accept='.srt' required>
                    <label for="Saison" style="width: 20%">Saison : </label>
                        <input type="number" name="Saison" value="<?php if(isset($_POST['Saison'])) { echo $_POST['Saison']; } ?>" required><br />
                    <label for="Episode" style="width: 20%">Episode : </label>
                        <input type="number" name="Episode" required><br />
                    <label for="Saison" style="width: 20%">Version : </label>
                    <select name="Version" required>
                        <option <?php if(!isset($_POST['Version'])) { echo "selected"; } ?>"></option>
                        <option value="VF" <?php if(isset($_POST['Version']) and 'VF' == $_POST['Version']) { echo "selected"; } ?> >VF</option>
                        <option value="VO" <?php if(isset($_POST['Version']) and 'VO' == $_POST['Version']) { echo "selected"; } ?> >VO</option>		
                    </select><br />			

                    <!-- Création d'un bouton pour valider le formulaire --><br/><br/>
                    <div style='margin-left: auto; margin-right:auto;width:400px;'>
                        <input type="submit" value="Ajouter le sous-titre." name="btn2" class="btn btn-primary btn-lg" >
                    </div><br/><br/>	
                </fieldset>
            </form><br />
            <?php
            // Execute la procédure de récupération des mots-clé dés que l'utilisateur appuye sur le bouton
            if(isset($_POST['btn2'])){
                //Exclusion
                $MotExclu = array();
                $req = $db->motexclu();
                while ($data = $req->fetch()){
                    array_push($MotExclu, $data['libelle']);
                }
                $idS = $_GET['num_serie'];
                $Saison = $_POST['Saison'];
                $Episode = $_POST['Episode'];
                $Version = $_POST['Version'];

                $reqST = $linkpdo->query("SELECT count(*) FROM SousTitre where num_serie = '$idS' and saison = '$Saison' and Episode = '$Episode' and Version = '$Version';");
                $dataST = $reqST->fetch();
                //Verification si le sous-titre n'a pas déjà été inséré pour cette série, cette saison et cet épisode
                if($dataST['0'] == 0){
                    // Préparation des requêtes SQL
                    $req1 = $linkpdo->prepare("SELECT count(*), num_motcle from MotCle where motcle = :mc group by num_motcle;");
                    $req2 = $linkpdo->prepare("SELECT occurrence from Appartenir where num_motcle = :idMC and num_serie = :idS;");
                    $ins1 = $linkpdo->prepare("Insert into MotCle values(:idMC, :mc);");
                    $ins2 = $linkpdo->prepare("Insert into Appartenir values(:idMC, :idS, :occ, 1);");
                    $up1 = $linkpdo->prepare("Update Appartenir set occurrence = occurrence + :occ, nbEp = nbEp + 1 where num_serie = :idS and num_motcle = :idMC;");

                    // Récupération de l'ID de la série selectionnée dans le formulaire
                    $reqS = $linkpdo->query("SELECT * FROM serie where num_serie = '$idS';");
                    $dataS = $reqS->fetch();
                    // Tableau contenant chaque ligne du fichier srt
                    $lines = file($_FILES['s']['tmp_name']);
                    // Pour chaque ligne du fichier srt ... ($i numéro de ligne)
                    foreach ($lines as $i => $lineContent){
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
                        flush();
                        ob_flush();
                }
                // Pour chaque mot du tableau
                foreach ($tab as $mc => $occ){
                    flush();
                    ob_flush();
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
                    }
                    $linkpdo->query("Insert into SousTitre values('$idS', '$Saison', '$Episode', '$Version');");
                } else {
                        echo "<b style='color:red'>Erreur : Ce sous-titre a déjà été inséré.</b>";
                }
            }
        }
        
        if($opt == 3){ ?>
            <!-- Table -->
            <table class="table  table-striped table-responsive table-condensed" style="width:71%; float:right; text-align:center;">
                <tr>
                    <th class="alert-info" style="text-align:center;">Saison</th>
                    <th class="alert-info" style="text-align:center;">Episode</th>
                    <th class="alert-info" style="text-align:center;">VF</th>
                    <th class="alert-info" style="text-align:center;">VO</th>
                </tr>
                <?php $reqSRT = $db->serie_SRT($_GET['num_serie']); 
                while($dataSRT = $reqSRT->fetch()){ ?> 
                <tr>
                    <td><?php echo $dataSRT['1']; ?></td>
                    <td><?php echo $dataSRT['2']; ?></td>
                    <td><?php if($dataSRT['3'] == 1){ echo 'X'; } ?></td>
                    <td><?php if($dataSRT['4'] == 1){ echo 'X'; } ?></td>
                </tr>
                <?php } ?> 
            </table>
        <?php } 
        
        if($opt == 4){ ?>
            <!-- Table -->
            <table class="table  table-striped table-responsive table-condensed" style="width:71%; float:right; text-align:center;">
                <tr>
                    <th class="alert-info" style="text-align:center;">Mots-clés</th>
                    <th class="alert-info" style="text-align:center;">Occurrence</th>
                    <th class="alert-info" style="text-align:center;">Supprimer</th>
                </tr>
                <?php
                $reqMC = $db->motcle($_GET['num_serie']); 
                while($dataMC = $reqMC->fetch()){ ?> 
                <tr>
                    <td><?php echo $dataMC['motcle']; ?></td>
                    <td><?php echo $dataMC['occurrence']; ?></td>
                    <td><form method="POST"><button class="btn-warning" name="btn3" value="<?php echo $dataMC['num_motcle']; ?>"><span class="glyphicon glyphicon-remove"></span></button></form></td>
                </tr>
                <?php } ?> 
            </table>
        <?php } ?>
    </body>
</html>