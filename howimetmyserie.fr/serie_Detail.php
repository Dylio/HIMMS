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
            $req=$db->une_serie($_GET['num_serie']);
            $data = $req->fetch(); 
        ?>
    </head>
    
    <body style='background: url("<?php echo $affichage->alea_Image($data['titre']); ?>")'>
        <?php 
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $affichage->like_recommandation();
        $req=$db->une_serie($_GET['num_serie']);    // sectionne les détail de la série TV
        $affichage->affichage_serie($req, null, 'MediaObjectDetail', null); ?>
    </body>
</html>