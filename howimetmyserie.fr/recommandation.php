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
            $num_user = $bd->user(false);
            $affichage = new class_affichage($num_user);
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur();
            }else{
                $_SESSION['SerieTV']->resetSearch();
            }
        ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php $affichage->affichage_titrePartie($str['recommendation']['title']);
       
        $affichage->like_recommandation($num_user);
        if($bd->recommandation_exist($num_user) == 1){
            $_SESSION['SerieTV']->vue_trie2($str['recommendation']['input_search'],$num_user);
            $req=$bd->recommandation( $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder(),
                20,
                $num_user); 
            $affichage->affichage_serie($req, null, $num_user, $_SESSION['SerieTV']->getMediaObject());
        }else{
            $req=$bd->serie_top_coeur(20);
            $affichage->affichage_serie($req, null, $num_user, $_SESSION['SerieTV']->getMediaObject());
        } ?>
        </body>
</html>