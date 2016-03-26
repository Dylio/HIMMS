<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">

<!-- gestion des tooltips -->
<script type="text/javascript"> $(function () {
    $('[data-toggle="tooltip"]').tooltip();
})</script>

<?php 
require_once 'class_db.php';            // base de données
require_once 'class_affichage.php';     // affichage global
require_once 'class_controleur.php';    // afficahge et gestion des controleurs
$db = new class_db();
$db->init(false);
$affichage = new class_affichage($db, $str);
// garde en mémoire pour la session actuel les préférences visuelles de l'utilisateur
if(!isset($_SESSION['SerieTV'])){
    $_SESSION['SerieTV'] = new class_controleur($db->getUser());
}