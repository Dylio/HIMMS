<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <?php include_once 'incl_import.php'; ?>
    </head>
    
    <body>
        <?php
        if(isset($_POST['btn1'])){
            $db->update_serie($_GET['num_serie'], $_POST['titre'], $_POST['dateD'], $_POST['dateF'], $_POST['nationalite'], $_POST['créateurs'], $_POST['acteurs'], $_POST['genre'], $_POST['format'], $_POST['nbSaison'], $_POST['nbEpisode'], $_POST['classification']);
        }
        if(isset($_POST['btn3'])){
            $db->delete_motcle($_GET['num_serie'], $_POST['btn3']);
        }
        if(isset($_POST['btn4'])){
            $db->optimiser_appartenir($_GET['num_serie']);
        }
        
        $affichage->affichage_menu(1);
        $opt = $_GET['opt'];
        $data = $db->une_serie($_GET['num_serie']);    // sectionne les détail de la série TV 
        $affichage->affichage_site('Modification de la série tv : <br/>'.$data['titre']);
        $titre = $data['titre'];
        $affichage->affichage_menu_serie($opt, $_GET['num_serie']);
        
        
        if($opt == 1){ ?>
            <form action="" method="POST" class="detail_opt">
                <fieldset class="fieldset">
                    <legend class="titre"><?php echo $str['admin']['detail_serie']['tab']['0']; ?></legend>
                    <label for="Titre" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['1']; ?></label>
                        <input type="text" class="detail_input" name="titre" value="<?php echo $data['titre']; ?>" required /><br/>
                    <label for="DateD" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['2']; ?></label>
                        <input type="number" min="1900" max ="<?php echo date('Y')+5; ?>" name="dateD" value="<?php echo $data['dateD']; ?>" style="width:80px;" /><br/>
                    <label for="DateF" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['3']; ?></label>
                        <input type="number" min="1900" max ="<?php echo date('Y')+5; ?>" name="dateF" value="<?php echo $data['dateF']; ?>" style="width:80px;" /><br/>
                    <label for="Nationalité" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['4']; ?></label>
                        <input type="text" class="detail_input" name="nationalite" value="<?php echo $data['nationalite']; ?>"/><br/>
                    <label for="Créateurs" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['5']; ?></label>
                        <input type="text" class="detail_input" name="créateurs" value="<?php echo $data['créateurs']; ?>"/><br/>
                    <label for="Acteurs" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['6']; ?></label>
                        <input type="text" class="detail_input" name="acteurs" value="<?php echo $data['acteurs']; ?>"/><br/>
                    <label for="Genre" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['7']; ?></label>
                        <input type="text" class="detail_input" name="genre" value="<?php echo $data['genre']; ?>"/><br/>
                    <label for="Format" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['8']; ?></label>
                        <input type="number" class="detail_input_number" name="format" value="<?php echo $data['format']; ?>" /><br/>
                    <label for="nbSaison" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['9']; ?></label>
                        <input type="number" class="detail_input_number" name="nbSaison" value="<?php echo $data['nbSaison']; ?>" /><br/>
                    <label for="nbEpisode" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['10']; ?></label>
                        <input type="number" class="detail_input_number" name="nbEpisode" value="<?php echo $data['nbEpisode']; ?>" /><br/>
                    <label for="classification" class="detail_label"><?php echo $str['admin']['detail_serie']['tab']['11']; ?></label>
                        <select name="classification" class="detail_input_number" required>
                            <option value="null">Tout Public</option>
                            <option value="10" <?php if(isset($data['classification']) and  $data['classification'] == 10) { echo "selected"; } ?> >10</option>			
                            <option value="12" <?php if(isset($data['classification']) and  $data['classification'] == 12) { echo "selected"; } ?> >12</option>			
                            <option value="16" <?php if(isset($data['classification']) and  $data['classification'] == 16) { echo "selected"; } ?> >16</option>			
                            <option value="18" <?php if(isset($data['classification']) and  $data['classification'] == 18) { echo "selected"; } ?> >18</option>			
                        </select><br/><br/>
                        <div style='margin-left: auto; margin-right:auto;width:400px;'>
                            <input type="submit" value="<?php echo $str['admin']['detail_serie']['tab']['ok']; ?>" name="btn1" class="btn btn-primary btn-lg" >
                        </div><br/><br/>
                </fieldset>
            </form>
        <?php }else if($opt == 2){ ?>
            <div class="detail_opt">
                <!-- Création d'un formulaire pour selectionner la série et le fichier srt (sous-titre) -->
                <form action="" method="post" enctype="multipart/form-data">
                    <!-- Récupération de toute les titres des séries -->
                    <?php $req = $db->serie(null, null, null, "titre asc"); ?>
                    <fieldset class="fieldset">
                        <legend class="titre"><?php echo $str['admin']['detail_serie']['srt']['0']; ?></legend>
                        <!-- Création d'une entrée de fichier de type .srt -->
                        <label for="file" ><?php echo $str['admin']['detail_serie']['srt']['1']; ?></label>
                            <input type="file" class="detail_input" name="fichier" accept='.srt' required>
                        <label for="Saison" class="detail_label"><?php echo $str['admin']['detail_serie']['srt']['2']; ?></label>
                            <input type="number" class="detail_input_number" name="Saison" value="<?php if(isset($_POST['Saison'])) { echo $_POST['Saison']; } ?>" required><br />
                        <label for="Episode" class="detail_label"><?php echo $str['admin']['detail_serie']['srt']['3']; ?></label>
                            <input type="number" class="detail_input_number" name="Episode" required><br />
                        <label for="Saison" class="detail_label"><?php echo $str['admin']['detail_serie']['srt']['4']; ?></label>
                        <select name="Version" required>
                            <option <?php if(!isset($_POST['Version'])) { echo "selected"; } ?>"></option>
                            <option value="VF" <?php if(isset($_POST['Version']) and 'VF' == $_POST['Version']) { echo "selected"; } ?> >VF</option>
                            <option value="VO" <?php if(isset($_POST['Version']) and 'VO' == $_POST['Version']) { echo "selected"; } ?> >VO</option>		
                        </select><br />			
                        
                        <!-- Création d'un bouton pour valider le formulaire --><br/><br/>
                        <div style='margin-left: auto; margin-right:auto;width:400px;'>
                            <input type="submit" value="<?php echo $str['admin']['detail_serie']['srt']['ok']; ?>" name="btn2" class="btn btn-primary btn-lg" >
                        </div>	
                    </fieldset>
                </form><br/>
                <?php
                // Execute la procédure de récupération des mots-clé dés que l'utilisateur appuye sur le bouton
                if(isset($_POST['btn2'])){
                    //Exclusion
                    $MotExclu = array();
                    $req = $db->motexclu();
                    while ($dataMCE = $req->fetch()){
                        array_push($MotExclu, $dataMCE['libelle']);
                    }
                    $idS = $_GET['num_serie'];
                    $saison = $_POST['Saison'];
                    $episode = $_POST['Episode'];
                    $version = $_POST['Version'];

                    //Verification si le sous-titre n'a pas déjà été inséré pour cette série, cette saison et cet épisode
                    if($db->srt_exist($idS, $saison, $episode, $version) == 0){
                        // Tableau contenant chaque ligne du fichier srt
                        $lines = file($_FILES['fichier']['tmp_name']);
                        // Pour chaque ligne du fichier srt ... ($i numéro de ligne)
                        foreach ($lines as $i => $lineContent){
                            // Exécute pour supprimmer ou modifier les différents caractères spéciaux
                            $lineContent = class_admin_affichage::no_special_character($lineContent);
                            // Tableau contenant chaque mot 
                            $lineContent = explode(" ", $lineContent);
                            // Pour chaque mot ...
                            foreach ($lineContent as $linetxt){
                                // Si le texte n'est pas vide ou n'est pas un caractère numérique 
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
                        // Pour chaque mot du tableau
                        foreach ($tab as $mc => $occ){
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
                        } ?>
                        <div class="alert alert-success alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Succès !</strong> Les nouveaux mots-clés a bien été inserés  dans la base de données.<br/>
                            N'oubliez pas d'optimiser les mots-clés de cette série tv via la page "Listes des mots-clés" quand tous les sous-titres ont été insérés.<br/>
                            Détail des mots :<br/>
                            <ul style='text-align: left;'>
                            <?php ksort($tab);
                            foreach ($tab as $mc => $occ){
                                echo '<li>'.$mc.' ('.$occ.')</li>';
                            } ?></ul>
                        </div>
                        <?php $db->insert_srt($idS, $saison, $episode, $version);
                    } else { ?>
                        <div class="alert alert-warning alert-dismissible" role="alert" style="width:100%; float:right; text-align:center;">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <strong>Attention !</strong> Ce sous-titre a déjà été inséré dans la base de données !
                        </div>
                    <?php }
                }
            ?></div><?php
        }else if($opt == 3){ ?>
            <!-- Table -->
            <table class="table  table-striped table-responsive table-condensed detail_opt" style="text-align:center;">
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
        <?php }elseif($opt == 4){ ?>
            <?php if(isset($_POST['btn4pro'])){?>
                <div class="alert alert-warning alert-dismissible" role="alert" style="width:71%; float:right; text-align:center;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Attention !</strong> Etes-vous sûr de vouloir obtimiser cette liste de mots-clés ?<br/>
                    Ce traitement supprimera tout les mots-clés non pertinent de cette liste.
                    <form method="POST">
                        <button class="btn-danger" name="btn4">
                            Valider
                        </button>
                        <button class="btn-default" class="close" data-dismiss="alert" aria-label="Close">
                            Annuler
                        </button>
                    </form>
                </div>
            <?php }else{ ?>
                <form action="" method="post" style="float:right;">
                    <fieldset class="fieldset">
                            <input type="submit" value="Optimisation des mots-clés." name="btn4pro" class="btn btn-primary btn-lg" >
                    </fieldset>
                </form><br/><br/>
            <?php }
            if(isset($_POST['btn3pro'])){?>
                <div class="alert alert-warning alert-dismissible" role="alert" style="width:71%; float:right; text-align:center;">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <strong>Attention !</strong> Etes-vous sûr de vouloir supprimer le mot-clé "<?php echo $_POST['mc']; ?>" de la liste ?
                    <form method="POST">
                        <button class="btn-danger" name="btn3" value="<?php echo $_POST['btn3pro']; ?>">
                            Valider
                        </button>
                        <button class="btn-default" class="close" data-dismiss="alert" aria-label="Close">
                            Annuler
                        </button>
                    </form>
                </div>
            <?php ob_flush();
            } ?>
            <!-- Table -->
            <table class="table  table-striped table-responsive table-condensed detail_opt" style="text-align:center;">
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
                    <td>
                        <form method="POST">
                            <input type="hidden" value="<?php echo $dataMC['motcle'];?>" name="mc"/>
                            <button class="btn-danger" name="btn3pro" value="<?php echo $dataMC['num_motcle']; ?>">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php } ?> 
            </table>
        <?php } ?>
    </body>
</html>