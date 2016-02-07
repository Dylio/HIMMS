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
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >    <!-- Affichage image aléatoire -->
        <?php // vérification que l'utilisateur a déjà exprimer ses goûts 
        if($db->recommandation_exist()== 1){
            $affichage->affichage_titrePartie($str['recommandation']['title'].'<br/><span class="NomPartie2">'.$str['recommandation']['input_search']."</span>");
            $affichage->like_recommandation();
            // affichage des composants servant au tri des séries TV
            $_SESSION['SerieTV']->vue_tri2($str['recommandation']['input_search'], 1);
            // selection de 20 séries TV recommandé pour l'utilisateur en fonction des goûts exprimés par l'utilisateur
            $req=$db->recommandation( $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder(),
                20);
            // affichage séries TV
            $affichage->affichage_serie($req, null, $_SESSION['SerieTV']->getMediaObject(), true);
        }else{
            $affichage->affichage_titrePartie("<span class='NomPartie2'>".$str['recommandation']['input_search_no_recommandation']."</span>");
            $affichage->like_recommandation();
            // selection du top 20 séries TV si aucune recommandation n'est possible (aucun goût exprimé par l'utilisateur)
            $req=$db->serie_top_coeur(20);
            // affichage séries TV
            $affichage->affichage_serie($req, null, $_SESSION['SerieTV']->getMediaObject(), null);
        } ?>
        </body>
</html>