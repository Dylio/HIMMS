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
            $db = new class_db(false);
            $affichage = new class_affichage($db, $str);
        ?>
    </head>
    
    <body style='background: url(css/4243604.png) no-repeat center fixed; background-size: 100% 100%;;'>
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