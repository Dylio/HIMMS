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
        $controleur->like_recommandation();
        
        $controleur->une_serie($_GET['num_serie']); ?>
    </body>
</html>