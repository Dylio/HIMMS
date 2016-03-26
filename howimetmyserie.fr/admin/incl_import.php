<!-- Importation des scripts et des stylesheet -->
<script src="../js/jquery.js"></script>
<script src="../js/bootstrap.min.js"></script>
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../style.css" rel="stylesheet">
<?php 
    require_once 'class_admin_controleur.php';    // afficahge et gestion des controleurs
    session_start();
    // garde en mémoire pour la session actuel les préférences visuelles de l'utilisateur
    if(!isset($_SESSION['Admin_SerieTV'])){
        $_SESSION['Admin_SerieTV'] = new class_admin_controleur($str);
    }
    if(!isset($_SESSION['admin'])){
        header('Location:index_1.php');
    }
    require_once 'class_admin_db.php';            // base de données
    require_once 'class_admin_affichage.php';     // affichage global
    $db = new class_admin_db();     // base de données 
    $affichage = new class_admin_affichage($db, $str);