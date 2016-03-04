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
            session_start();
            if(!isset($_SESSION['admin'])){
                header('Location:index_1.php');
            }
            require_once 'class_admin_db.php';            // base de données
            require_once 'class_admin_affichage.php';     // affichage global
            require_once 'class_admin_controleur.php';    // afficahge et gestion des controleurs
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
            // garde en mémoire pour la session actuel les préférences visuelles de l'utilisateur
            if(!isset($_SESSION['Admin_SerieTV'])){
                $_SESSION['Admin_SerieTV'] = new class_admin_controleur($str);
            }
        ?>
    </head>
    
    <body>
        <?php $affichage->affichage_menu(1);
        $affichage->affichage_site('MODIFICATION DES SERIES TV');
        $_SESSION['Admin_SerieTV']->vue_tri($str['serie']['input_search']);
        $req=$db->serie($_SESSION['Admin_SerieTV']->getTxtSearch(), $_SESSION['Admin_SerieTV']->getTxtOrder());
        $affichage->affichage_serie($req);
        ?>
    </body>
</html>