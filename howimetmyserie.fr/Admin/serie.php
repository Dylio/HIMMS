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
        <?php $affichage->affichage_menu(1); ?>
        <p class='NomPartie'><small>MODIFICATION DES SERIES TV</small></p><br/>
        <?php $req=$db->serie(null, null, null, 'titre asc');
        $affichage->affichage_serie($req);
        ?>
    </body>
</html>