<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>HIMMS - Administrateur</title>
        
        <!-- Importation des scripts et des stylesheet -->
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
        
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_admin_db.php';
            require_once 'class_admin_affichage.php';     // affichage global
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
        ?>
    </head>
 
    <body>
        <?php $affichage->affichage_site($str['site']['name']);
        $affichage->affichage_menu(3); ?> 
        <p class='NomPartie'><small>LES AVIS DES UTILISATEURS</small></p><br/>
        <!-- panel contents -->
        <div class="panel panel-info">    
            <!-- Default panel contents -->
            <div class="panel-heading">Questionnaire de Satisfaction :</div>
            <div class="panel-body">
                <p class="panel_texte">
                    Nombre d'utilisateur ayant remplit le questionnaire : <b><?php echo $db->questionnaire_nb_user(); ?></b><br/>
                    Q1: <?php echo $str['questionnaire']['1']; ?>.
                    <span class="badge">1 : Oui</span> <span class="badge">0 : Non</span><br/>
                    Q2: <?php echo $str['questionnaire']['2']; ?>.
                    <span class="badge">1 : Oui</span> <span class="badge">0 : Non</span><br/>
                    Q3: <?php echo $str['questionnaire']['3']; ?>.
                    <span class="badge">1 : Oui</span> <span class="badge">0 : Non</span><br/>
                    Q4: <?php echo $str['questionnaire']['4']; ?>.
                    <span class="badge">1 : Oui</span> <span class="badge">0 : Non</span><br/>
                    Q5: <?php echo $str['questionnaire']['5']; ?>.
                    <span class="badge">1 : Très Satisfait</span> <span class="badge">0.5 : Moyennement Satisfait</span> <span class="badge">0 : Non Satisfait</span><br/>
                    Q6: <?php echo $str['questionnaire']['6']; ?>. 
                    <span class="badge">1 : Très Satisfait</span> <span class="badge">0.5 : Moyennement Satisfait</span> <span class="badge">0 : Non Satisfait</span><br/>
                    Q7: <?php echo $str['questionnaire']['7']; ?>. 
                    <span class="badge">1 : Oui</span> <span class="badge">0 : Non</span>
                </p>
            </div>
            
            <a href='graph3_1.php' style='margin-left:15%; margin-right:15%;'>
                <img src='graph3_1.php' style='width:70%;' >
            </a>
            <br/><br/>
            
            <!-- Table -->
            <table class="table  table-striped table-responsive table-condensed">
                <tr>
                    <th class="alert-info">Date</th>
                    <th class="alert-info">Commentaire</th>
                </tr>
                <?php $req = $db->questionnaire_commentaire(); 
                while($data = $req->fetch()){ ?> 
                <tr>
                    <td><?php echo date("d/m/Y", strtotime($data['question_date'])); ?></td>
                    <td><?php echo $data['question_commentaire']; ?></td>
                </tr>
                <?php } ?> 
            </table>
        </div>
    </body>
</html>
