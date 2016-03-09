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
    
    <body style='background: url("<?php echo $affichage->alea_image($_GET['num_serie']); ?>")'>
        <?php 
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $affichage->like_recommandation();
        $req=$db->une_serie($_GET['num_serie']);    // sectionne les détail de la série TV
        $affichage->affichage_serie($req, null, 'MediaObjectDetail', null); ?>
    </body>
</html>