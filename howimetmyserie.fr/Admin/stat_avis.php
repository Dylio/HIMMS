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
        Nombre d'utilisateur ayant remplit le questionnaire : <b><?php echo $db->questionnaire_nb_user(); ?></b><br/><br/>
            <!-- panel contents -->
        <div class="panel panel-info">
            <!-- Default panel contents -->
            <div class="panel-heading" style="text-align:center">Satisfaction Générale du Site</div>
            <a href='stat_avis_graph1.php' style='margin-left:15%; margin-right:15%;'>
                <img src='stat_avis_graph1.php' style='width:70%;' >
            </a>
            <br/><br/>
            
            <div class="panel-heading" style="text-align:center">Fonctionnalités Principales du Site</div>
            <a href='stat_avis_graph2.php' style='margin-left:15%; margin-right:15%;'>
                <img src='stat_avis_graph2.php' style='width:70%;' >
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
