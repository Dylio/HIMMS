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
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php $affichage->affichage_titrePartie($str['serie']['title']);
        
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $controleur->like_recommandation();
        
        // affichage des composants servant au tri des séries TV
        $affichage->vue_tri($_SESSION['SerieTV_like'],
                $_SESSION['SerieTV_order'],
                $_SESSION['SerieTV_recommandation'],
                $_SESSION['SerieTV_MediaObject'],
                $_SESSION['SerieTV_search'],
                $str['serie']['input_search']);
        
        // affichage des séries TV
        $controleur->serie();
        ?>
    </body>
</html>