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
        <?php $affichage->affichage_menu(4); 
        $affichage->affichage_site('LES AVIS DES UTILISATEURS'); ?>
        <div style="float:left">Nombre d'utilisateur ayant remplit le questionnaire : </div>
            <div class="compteur_txt" style="float:left; margin-left: 10px; margin-top: -10px;">0<?php echo $db->questionnaire_nb_user(); ?></div>
        <p style="clear: both"></p>
        <a href='stat_avis_graph1.php' class='graphAvisContainer'>
            <img src='stat_avis_graph1.php' class='graphAvis'>
        </a>
        <a href='stat_avis_graph2.php' class='graphAvisContainer'>
            <img src='stat_avis_graph2.php' class='graphAvis'>
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
                    <td style="width:112px;"><?php echo date("d/m/Y", strtotime($data['question_date'])); ?></td>
                    <td style="text-align: left;"><?php echo $data['question_commentaire']; ?></td>
                </tr>
                <?php } ?> 
            </table>
        </div>
    </body>
</html>
