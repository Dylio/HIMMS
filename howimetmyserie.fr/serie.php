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
        <?php 
        $affichage->affichage_titrePartie($str['serie']['title']);
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $affichage->like_recommandation();
        $_SESSION['SerieTV']->controleur();
        
        // affichage des composants servant au tri des séries TV
        $affichage->vue_tri($_SESSION['SerieTV']->getLike(),
                $_SESSION['SerieTV']->getOrder(),
                $_SESSION['SerieTV']->getRecommandation(),
                $_SESSION['SerieTV']->getMediaObject(),
                $_SESSION['SerieTV']->getSearch(),
                $str['serie']['input_search']);
        
        // selection séries TV selon les choix de tri de l'utilisateur
        $req= $db->serie($_SESSION['SerieTV']->getTxtSearch(), 
                $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder());
        
        // calcul nombre de séries TV selon les choix de tri hors recherche de l'utilisateur
        $dataNb = $db->serie_count($_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation());
        
        // affichage séries TV
        $affichage->affichage_serie($req, $dataNb, $_SESSION['SerieTV']->getMediaObject(), true);
        ?>
    </body>
</html>