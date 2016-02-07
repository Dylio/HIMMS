<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <!-- Importation des scripts et des stylesheet -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <!-- gestion des tooltips -->
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_db.php';            // base de données
            require_once 'class_affichage.php';     // affichage global
            require_once 'class_controleur.php';    // afficahge et gestion des controleurs
            $db = new class_db(false);
            $affichage = new class_affichage($db, $str);
            // garde en mémoire pour la session actuel les préférences visuelles de l'utilisateur
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur($db->getUser(), $str);
            }
        ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php 
        $affichage->affichage_titrePartie($str['serie']['title']);
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $affichage->like_recommandation();
        // affichage des composants servant au tri des séries TV
        $_SESSION['SerieTV']->vue_tri($str['serie']['input_search'], false);
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