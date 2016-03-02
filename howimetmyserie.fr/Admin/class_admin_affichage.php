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
        echo "<p class='NomPartie3'>$partie</p>";
    }
    
    public function affichage_menu($i){
        echo "<p class='navbar-text' style='margin-top: -12px !important;'>"
            . "<span class='NomPartie'> <a href='./index.php'>HIMMS</a> </span>"
            . "<ul class='nav nav-tabs nav-pills nav-justified'>"
                . "<li role='presentation' ";
                    if($i == 1){ echo "class='active'"; }
                    echo "><a href='serie.php'>Mes Séries TV</a>"
                . "</li>"
                . "<li role='presentation' class='dropdown ";
                    if($i == 2 or $i == 3){ echo "active"; }
                    echo "'>"
                    . "<a class='dropdown-toggle' data-toggle='dropdown' href='#' role='button' aria-haspopup='true' aria-expanded='false'>"
                      . "Mes Statistiques</span></a>"
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
                    echo "><a href='messages.php'>Mes Messages <span class='badge'>".$this->_db->messagerie_nonlu()."</span></a>"
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
    
    public function message_lu(){
        if(isset($_POST['lu'])){
            if($_POST['lu'] == 0){
                $this->_db->update_messagerie_lu(1, $_POST['num_user'], $_POST['dateContact']);
            }else{
                $this->_db->update_messagerie_lu(0, $_POST['num_user'], $_POST['dateContact']);
                
            }
        }
    }
    
    // renvoie une chaîne texte entrée en paramètre sans characteres specials
    // IN : $chaine chaîne texte
    // OUT : chaîne texte sans characteres specials
    public static function no_special_character($chaine){
        // Enlève tout les accents de la chaîne texte
        $a = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
        $b = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
        $chaine = utf8_encode(strtr(utf8_decode($chaine), utf8_decode($a), utf8_decode($b)));
        // Ecrit la chaîne de caractères en miniscule
        $chaine = strtolower($chaine);
        // Supprime la ponctuation de la chaîne texte
        $carac = array('.' ,'!' ,'?' ,'-->' ,',' ,'<i>' ,'</i>' ,':' ,'"','|' ,'\'' ,'"' ,'-' ,';' ,'_' ,'&' ,'>' ,'<', '$', '\\', '/', '$', '€', '£', '+', '=', '[', ']', '*', '(', ')', '{', '}');
        $chaine = str_replace ($carac, ' ', $chaine);
        $chaine = str_replace ('œ', 'oe', $chaine);
        $chaine=trim($chaine);
        return $chaine;
    }
}