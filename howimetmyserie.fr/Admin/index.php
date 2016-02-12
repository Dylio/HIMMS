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
        $affichage->affichage_menu(null); ?>
    </body>
</html>
