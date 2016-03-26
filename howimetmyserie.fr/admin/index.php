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
        <?php $affichage->affichage_menu(null); ?>
        
        <div class="panel panel-info" style="width:45%; margin:0% 2.5%; float:left;">
            <div class="panel-heading">VOS SERIES TV</div>
            <div class="panel-body" style="text-align:center;">
                <a href='serie.php'><span class="glyphicon glyphicon-film" style="font-size: 175px;"></span></a>
            </div>
        </div>
        <div class="panel panel-success" style="width:45%; margin:0% 2.5%; float:right">
            <div class="panel-heading">VOS MESSSAGES</div>
            <div class="panel-body" style="text-align:center;">
                <a href='messages.php'><span class="glyphicon glyphicon-envelope" style="font-size: 175px;"></span></a>
            </div>
        </div>
        <div style='clear: both;'></div>
        <br/>
        <div class="panel panel-warning" style="width:95%;margin:auto;">
            <div class="panel-heading">VOS STATISTIQUES</div>
            <div class="panel-body" style="text-align:center;">
                <a href='stat_visiteur.php'><span class="glyphicon glyphicon-stats" style="font-size: 100px; margin-right:55px;">Visiteurs</span></a>
                <a href='stat_avis.php'><span class="glyphicon glyphicon-stats" style="font-size: 100px; margin-left:55px;">Avis</span></a>
            </div>
        </div><br/>
    </body>
</html>
