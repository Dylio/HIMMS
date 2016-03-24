<?php if(isset($_POST['ok'])){
    header("Location:Search.php?mc=".$_GET['mc']." ".$_POST['search']);
    exit;
}
// constantes textuelles du site web
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
        <?php $affichage->affichage_titrePartie($str['search']['title'].' : </p>'
            . '<p class="MCRecherche">"'.$_GET['mc'].'"</p>');
        $affichage->like_recommandation();
        
        if(isset($_GET['mc'])){
            $_SESSION['SerieTV']->controleur();
            $affichage->vue_tri($_SESSION['SerieTV']->getLike(),
                    $_SESSION['SerieTV']->getOrder(),
                    $_SESSION['SerieTV']->getRecommandation(),
                    $_SESSION['SerieTV']->getMediaObject(),
                    null,
                    $str['search']['input_search']);
            $txtSearch = class_controleur::search_mc($db, $_GET['mc']);
            
            $req=$db->search($_SESSION['SerieTV']->getTxtLike(),
                $txtSearch,
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder());
            $SearchNb=$db->search_count($_SESSION['SerieTV']->getTxtLike(),
                $txtSearch,
                $_SESSION['SerieTV']->getTxtRecommandation());
            $affichage->affichage_serie($req, $SearchNb, $_SESSION['SerieTV']->getMediaObject(), true);
        } ?>
    </body>
</html>