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
$controleur-> filtre();