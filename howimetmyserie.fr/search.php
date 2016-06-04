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
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">

        <!-- gestion des tooltips -->
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>

        <?php 
        require_once 'class_affichage.php';     // affichage global
        require_once 'class_controleur.php';    // afficahge et gestion des controleurs
        $affichage = new class_affichage($str);
        $controleur = new class_controleur();
        $controleur-> init();
        $controleur-> filtre(); ?>
    </head>
    
    <body class='interfaceUser'>
        <?php $affichage->affichage_titrePartie($str['search']['title'].' : </p>'
            . '<p class="MCRecherche">"'.$_GET['mc'].'"</p>');
        $controleur->like_recommandation();
        
        if(isset($_GET['mc'])){
            // gestion de l'évènement séries TV aimé ou recommandé et son contraire
            $controleur->like_recommandation();

            // affichage des composants servant au tri des séries TV
            $affichage->vue_tri($_SESSION['SerieTV_like'],
                    $_SESSION['SerieTV_order'],
                    $_SESSION['SerieTV_recommandation'],
                    $_SESSION['SerieTV_MediaObject'],
                    null,
                    $str['serie']['input_search']);

            $txtSearch = $controleur->search_mc($_GET['mc']);
            
            // affichage des séries TV
            $controleur->search($txtSearch);
        } ?>
    </body>
</html>