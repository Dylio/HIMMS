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
 
    <body>
        <?php 
        $affichage->message_lu();
        $affichage->affichage_menu(5);
        $affichage->affichage_site($str['admin']['message']['title']);?>
        <br/>
        <?php $affichage->affichage_messagerie(); ?>
    </body>
</html>
