<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <!-- Importation des scripts et des stylesheet -->
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
        <?php 
            require_once 'class_admin_db.php';            // base de données
            require_once 'class_admin_affichage.php';     // affichage global
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
        ?>
    </head>
    
    <body>
        <?php 
        $affichage->affichage_site($str['site']['name']);
        $affichage->affichage_menu(0); 
        $data = $db->une_serie($_GET['num_serie']);    // sectionne les détail de la série TV ?>
        <input type="text" name="titre" value="<?php echo $data['titre']; ?>" />
        <input type="text" name="dateD" value="<?php echo $data['dateD']; ?>" />
        <input type="text" name="dateF" value="<?php echo $data['dateF']; ?>" />
        <input type="text" name="nationalite" value="<?php echo $data['nationalite']; ?>" />
    </body>
</html>