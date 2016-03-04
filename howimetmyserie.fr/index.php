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
    
    <body style='background: url(css/4243604.png);'>
        <h1 class="NomSite"><?php echo $str['site']['name']; ?></h1>
        <p class="IndexSlogan"><?php echo $str['site']['slogan']; ?></p><br/>
        
        <?php $affichage->like_recommandation();
        if($db->recommandation_exist() == 1){
            $affichage->carouselle(true, true, true); 
        }else{
            $affichage->carouselle(false, true, true); 
        } ?>
    </body>
</html>