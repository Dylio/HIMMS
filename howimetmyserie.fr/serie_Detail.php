<?php // constantes textuelles du site web
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
    
    <body style='background: url("<?php echo $affichage->alea_image($_GET['num_serie']); ?>")'>
        <?php 
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $controleur->like_recommandation();
        
        $controleur->une_serie($_GET['num_serie']); ?>
    </body>
</html>