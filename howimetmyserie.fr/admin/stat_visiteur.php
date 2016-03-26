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
        <?php $affichage->affichage_menu(3);
        $affichage->affichage_site($str['admin']['stat_visiteur']['title']); ?>
        <table class="table table-striped table-responsive table-condensed" style="width:50%; margin: 0 auto;">
            <tr>
                <th colspan="2" class="alert-info" style="text-align:center;">
                    <?php echo $str['admin']['stat_visiteur']['stat'].date('d/m/Y'); ?>
                </th>
            </tr>
            <tr>
                <td class="alert-info compteur_title"><?php echo $str['admin']['stat_visiteur']['total_membre']; ?></td>
                <td class="compteur_txt">0<?php echo $db->nb_user_actif(); ?></td>
            </tr>
            <tr>
                <td class="alert-info compteur_title" ><?php echo $str['admin']['stat_visiteur']['total_utilisateur']; ?></td>
                <td class="compteur_txt">0<?php echo $db->date_nb_user()[0]; ?></td>
            </tr>
        </table>
        <br/>
        <div class="dropdown" style='width:300px; margin-left:auto;margin-right:auto;'>
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <?php echo $str['admin']['stat_visiteur']['stat2'].$_GET['annee']; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php $annee=date('Y',strtotime($db->date_nb_user()[1]));
                while($annee <= date('Y')){
                    echo "<li role='presentation'";
                    if($annee==$_GET['annee']){ echo "class='active'"; }
                    echo ">"
                        . "<a href='stat_visiteur.php?annee=$annee'>"
                            . $str['admin']['stat_visiteur']['stat2'].$annee
                        . "</a>"
                    . "</li>";
                    $annee++;
                } ?>
            </ul>
        </div><br/>
        <a href='stat_visiteur_graph1.php?annee=<?php echo $_GET['annee']; ?>' class="graphVisiteurContainer">
            <img src='stat_visiteur_graph1.php?annee=<?php echo $_GET['annee']; ?>' class="graphVisiteur">
        </a>
        <br/><br/>
    </body>
</html>
