<?php
// Cette classe permet l'affichage les données.
class class_admin_affichage{
    private  $_str;             // constantes textuelles du site web
    private  $_db;              // instance vers la base de données
    
    // Constructeur de la classe class_media_object
    // IN : $db instance vers la base de données
    // IN : $str constantes textuelles du site web
    public function __construct($db, $str){
        $this->_db = $db;
        $this->_str = $str;
    }
    
    public function affichage_site($partie){
        echo "<div class='jumbotron SerieDetailContainer'><p class='NomPartie'>$partie</p></div>";
    }
    
    public function affichage_menu($i){
        echo "<p class='navbar-text' style='margin-top: -12px !important;'>"
            . "<span class='NomPartie'> <a href='../index.php'>HIMMS</a> </span>"
            . "<ul class='nav nav-tabs nav-pills nav-justified'>"
                . "<li role='presentation' ";
                    if($i == 1){ echo "class='active'"; }
                    echo "><a href='serie.php'>Mes Séries TV</a>"
                . "</li>"
                . "<li role='presentation' class='dropdown ";
                    if($i == 2 or $i == 3){ echo "active"; }
                    echo "'>"
                    . "<a class='dropdown-toggle' data-toggle='dropdown' href='#' role='button' aria-haspopup='true' aria-expanded='false'>"
                      . "Vos Statistiques</span></a>"
                    . "<ul class='dropdown-menu'>"
                        ."<li role='presentation' ";
                        if($i == 2){ echo "class='active'"; }
                        echo "><a href='stat_visiteur.php?annee=".date('Y')."'>Les Visites</a></li>"
                        ."<li role='presentation' ";
                        if($i == 3){ echo "class='active'"; }
                        echo "><a href='stat_avis.php'>Les Avis</a></li>"
                    . "</ul>"
                . "</li>"
                . "<li role='presentation' ";
                    if($i == 4){ echo "class='active'"; }
                    echo "><a href=''>Mes Messages <span class='glyphicon glyphicon-comment'></a>"
                . "</li>"
            . "</ul>"
        . "</p>";
    }
    
    public function affichage_menu_serie($i, $num_serie){
        echo "<ul class='nav nav-pills nav-stacked' style='float:left; width:25%;'>"
            . "<li role='presentation' ";
                if($i == 1){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=1&num_serie=$num_serie'>Modification des détails</a></li>"
            . "<li role='presentation' ";
                if($i == 2){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=2&num_serie=$num_serie'>Ajouter un nouveau sous-titre</a></li>"
            . "<li role='presentation' ";
                if($i == 3){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=3&num_serie=$num_serie'>Liste des sous-titres insérés</a></li>"
            . "<li role='presentation' ";
                if($i == 4){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=4&num_serie=$num_serie'>Liste des mots-clés</a></li>"
        . "</ul>";
    }
    
    public function affichage_serie($req){
        require_once 'class_admin_media_object.php';
        echo '<div class="SerieContainerIner">';
            $media_object = new class_admin_media_object($this->_db, $this->_str);
            while($data = $req->fetch()){
               $media_object->newSerieDetail($data);
               $media_object->media_object();
            }
        echo '</div>';
    }
}