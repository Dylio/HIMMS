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
            $bd = new class_db();
            $num_user = $bd->user("false");
            $affichage = new class_affichage();
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur();
            }
        ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php 
        $affichage->affichage_titrePartie($str['serie']['title']);
        $affichage->like_recommandation($num_user);
                
        $_SESSION['SerieTV']->vue_trie($str['serie']['input_search'], false, $num_user);
        
        $req= $bd->serie($_SESSION['SerieTV']->getTxtSearch(), 
                $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder());
        
        $dataNb = $bd->serie_count($_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation());
        
        $affichage->affichage_serie($req, $dataNb, $num_user, $_SESSION['SerieTV']->getMediaObject());
        ?>
    </body>
</html>