<?php require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>
        <?php
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            require_once 'class_controleur.php';
            $db = new class_db(false);
            $affichage = new class_affichage($db, $str);
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur($db->getUser(), $str);
            }
        ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php 
        $affichage->affichage_titrePartie($str['serie']['title']);
        $affichage->like_recommandation();
                
        $_SESSION['SerieTV']->vue_tri($str['serie']['input_search'], false);
        
        $req= $db->serie($_SESSION['SerieTV']->getTxtSearch(), 
                $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder());
        
        $dataNb = $db->serie_count($_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation());
        
        $affichage->affichage_serie($req, $dataNb, $_SESSION['SerieTV']->getMediaObject(), true);
        ?>
    </body>
</html>