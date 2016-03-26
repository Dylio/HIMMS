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
        <?php $affichage->affichage_menu(1);
        $affichage->affichage_site('MODIFICATION DES SERIES TV');
        $_SESSION['Admin_SerieTV']->vue_tri($str['serie']['input_search']);
        $req=$db->serie($_SESSION['Admin_SerieTV']->getTxtSearch(), $_SESSION['Admin_SerieTV']->getTxtOrder());
        $affichage->affichage_serie($req);
        ?>
    </body>
</html>