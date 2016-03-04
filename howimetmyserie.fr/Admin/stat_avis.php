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
            session_start();
            if(!isset($_SESSION['admin'])){
                header('Location:index_1.php');
            }
            require_once 'class_admin_db.php';
            require_once 'class_admin_affichage.php';     // affichage global
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
        ?>
    </head>
 
    <body>
        <?php $affichage->affichage_menu(3); 
        $affichage->affichage_site('LES AVIS DES UTILISATEURS'); ?>
        <div style="float:left">Nombre d'utilisateur ayant remplit le questionnaire : </div>
            <div class="Compteur" style="float:left; margin-left: 10px; margin-top: -10px;">0<?php echo $db->questionnaire_nb_user(); ?></div>
        <p style="clear: both"></p>
        <a href='stat_avis_graph1.php' style='margin-left:0.5%; margin-right:0.5%;'>
            <img src='stat_avis_graph1.php' style='width:48.5%;'>
        </a>
        <a href='stat_avis_graph2.php' style='margin-left:0.5%; margin-right:0.5%;'>
            <img src='stat_avis_graph2.php' style='width:48.5%;'>
        </a>
        <br/><br/>

        <table class="table table-striped table-responsive table-condensed" style="overflow:auto; width:90%; margin: auto;">
            <tr>
                <th class="alert-info" style="width:112px;">Date</th>
                <th class="alert-info">Commentaire</th>
            </tr>
        </table>
        <div style="overflow:auto; width:90%; height:300px; margin: auto;">
            <table class="table table-striped table-responsive table-condensed">
                <?php $req = $db->questionnaire_commentaire(); 
                while($data = $req->fetch()){ ?> 
                <tr STYLE='font-size: 15px;'>
                    <td><?php echo date("d/m/Y", strtotime($data['question_date'])); ?></td>
                    <td><?php echo $data['question_commentaire']; ?></td>
                </tr>
                <?php } ?> 
            </table>
        </div>
    </body>
</html>
