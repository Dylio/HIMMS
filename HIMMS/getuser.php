<!DOCTYPE html>
<html>
<head>
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
        require_once 'class_controleur.php';
        $bd = new class_db();
        $num_user = $bd->user("false");
        $affichage = new class_affichage();
        if(!isset($_SESSION['SerieTV'])){
            $_SESSION['SerieTV'] = new class_controleur();
        }else{
            $_SESSION['SerieTV']->resetSearch();
        }
    ?>
</head>
<body>

<?php
$con = new PDO("mysql:host=localhost;dbname=test;charset=utf8", '','');

$req=$bd->recommandation( $_SESSION['SerieTV']->getTxtLike(),
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder(),
                20,
                $_COOKIE['log']);
$affichage->affichage_serie($req, null, $_COOKIE['log'], $_SESSION['SerieTV']->getMediaObject());
?>
</body>
</html>