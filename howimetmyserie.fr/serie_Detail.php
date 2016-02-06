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
            $req=$db->une_serie($_GET['num_serie']);
            $data = $req->fetch(); 
        ?>
    </head>
    
    <body style='background: url("<?php echo $affichage->alea_Image($data['titre']); ?>") no-repeat center fixed; background-size: 100% auto !important'>
        <?php $affichage->like_recommandation();
        $req=$db->une_serie($_GET['num_serie']);
        $affichage->affichage_serie($req, null, 'MediaObjectDetail', null); ?>
    </body>
</html>