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
        $affichage->affichage_menu(2);
        ?>
        <p class='NomPartie'><small>LES VISITES</small></p><br/>
        <table class="table  table-striped table-responsive table-condensed" style="margin-left:25%; margin-left:25%; width: 50%;">
            <tr>
                <th colspan="2" class="alert-info" style="text-align:center;">
                    Statistiques au <?php echo date('d/m/Y'); ?>
                </th>
            </tr>
            <tr>
                <td class="alert-info" style="width :70%">Total Membre Actif :</td>
                <td style="width :30%; text-align: center;"><?php echo $db->nb_user_actif(); ?></td>
            </tr>
            <tr>
                <td class="alert-info" style="width :70%">Total Utilisateur :</td>
                <td style="width :30%; text-align: center;"><?php echo $db->date_nb_user()[0]; ?></td>
            </tr>
        </table>
        <a href='graph1.php' style='margin-left:33%; margin-right:33%;'>
            <img src='graph1.php' style='width:34%;' >
        </a>
        <br/>
        <br/>
        <br/>
        <div class="dropdown" style='width:300px; margin-left:auto;margin-right:auto;'>
            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <?php echo "Statistique pour l'année ".$_GET['annee']; ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php $annee=date('Y',strtotime($db->date_nb_user()[1]));
                while($annee <= date('Y')){
                    echo "<li role='presentation'";
                    if($annee==$_GET['annee']){ echo "class='active'"; }
                    echo ">"
                        . "<a href='visiteur.php?annee=$annee'>"
                            . "Statistique pour l'année $annee"
                        . "</a>"
                    . "</li>";
                    $annee++;
                } ?>
            </ul>
        </div><br/>
        <a href='graph3.php?annee=<?php echo $_GET['annee']; ?>' style='margin-left:15%; margin-right:15%;'>
            <img src='graph3.php?annee=<?php echo $_GET['annee']; ?>' style='width:70%;' >
        </a>
        <br/><br/>
    </body>
</html>
