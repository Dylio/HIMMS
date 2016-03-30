<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <?php include_once 'incl_import.php'; ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >    <!-- Affichage image aléatoire -->
        <?php // vérification que l'utilisateur a déjà exprimer ses goûts 
        $controleur->recommandation($str, 20); ?>
        </body>
</html>