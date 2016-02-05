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
                $_SESSION['SerieTV'] = new class_controleur($str);
            }
        ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php $affichage->affichage_titrePartie($str['recommendation']['title']);
       
        $affichage->like_recommandation();
        if($db->recommandation_exist() == 1){
            $_SESSION['SerieTV']->vue_tri2($str['recommendation']['input_search']);
            $req=$db->recommandation( $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder(),
                20); 
            $affichage->affichage_serie($req, null, $_SESSION['SerieTV']->getMediaObject());
        }else{
            $req=$db->serie_top_coeur(20);
            $affichage->affichage_serie($req, null, $_SESSION['SerieTV']->getMediaObject());
        } ?>
        </body>
</html>