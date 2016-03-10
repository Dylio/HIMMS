<?php
// Cette classe permet de restituer sous différents styles d'objets abstraits la construction graphique d'une
// séries TV via divers types de composants qui présentent son affiche et le contenu textuel.
class class_admin_media_object {
    private  $_serie;           // numéro de la série
    private  $_titre;           // titre de la série
    private  $_date;            // date de début et de fin de la série
    private  $_str;             // constantes textuelles du site web
    private  $_db;              // instance vers la base de données
    
    // Constructeur de la classe class_media_object
    // IN : $db instance vers la base de données
    // IN : $str constantes textuelles du site web
    public function __construct($db, $str){
        $this->_str = $str;
        $this->_db = $db;
    }
    
    // Supprimmer la série courante
    private function cleanSerie(){
        unset ($this->_serie);
        unset ($this->_titre);
        unset ($this->_date);
    }
    
    // Renvoie une forme textuel des dates de la série.
    // IN : $dateD date de début de la série.
    // IN : $dateF date de fin de la série.
    private function date($dateD, $dateF){
        $date="";
        if($dateD != null ){
            $date = $dateD;
            if($dateD != $dateF and $dateF != null){
                $date = $date.' - '.$dateF;
            }else{
                $date = $date.' - En production';
            }
        }
        $this->_date = $date;
    }
    
    // Permet d'initialiser les varibles nécessaire pour décrire une série
    // IN : $data tableau contenant les informations nécessaire pour décrire une série.
    public function newSerieDetail($data){
        $this->cleanSerie();
        $this->_serie = $data['num_serie'];
        $this->_titre = $data['titre'];
        $this->date($data['dateD'], $data['dateF']);
    }
    
    // Renvoie le titre d'une série avec son style de representation
    // IN : $style style qui permet de décrire la representation du titre d'une série.
    private function serie_titre($style){
        return "<div class='$style'><a href='./Serie_Detail.php?opt=0&num_serie=$this->_serie' class='$style'>$this->_titre</a></div>";
    }
    
    // Renvoie les dates d'une série avec son style de représentation
    // IN : $style style qui permet de décrire la representation des dates d'une série.
    private function serie_date($style){
        return "<span class='$style'>$this->_date</span>";
    }

    // Créer sous différents styles d'objets abstraits la construction graphique de la séries TV
    // IN : $object la forme de restitution de la séries TV
    public function media_object(){
        echo "<div class='thumbnail SerieCasePContainerSerie'>" // conteneur de l'affiche de la serie TV
            ."<a href='./serie_detail.php?opt=0&num_serie=$this->_serie' class='media-left media-middle'>"
                ."<img class='media-object SerieCaseP2Img' src='".$this->alea_image($this->_serie)."' alt='".$this->_titre."'>"
            ."</a>"
            ."<div class='media-body SerieCaseP2Txt'>"; // conteneur de la description de la serie TV
                echo $this->serie_titre("SerieCasePTitre");
                echo $this->serie_date("SerieCasePDate");
            echo '</div>'
        . '</div>';
    }
    
    // Renvoie l'adresse relatif d'une image aléatoire de la série TV
    // OUT : adresse relatif d'une image aléatoire de la série TV
    public static function alea_image($titreSerie){
        $dirname = "../Affiche/$titreSerie";       // adresse relatif de la série TV
        if(file_exists($dirname) ){                 // vérification que le dossier existe
            $j = 0;                                 // $j nombre d'affiche de la série TV
            $dir = opendir($dirname);       
            while($file = readdir($dir)) {          // compte le nombre d'affiche de la série TV
                if(substr($file, -4) == ".png"){    // les affiches doivent être tous sous format .png
                    $j++;
                }
            }
            closedir($dir);
            $aleaImage = rand(1, $j);                 // $aleaImage un nombre aléatoire entre 1 et $j
            $i = 1;                                   // $i numéro actuel de l'affiche de la série TV
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if($i === $aleaImage and substr($file, -4) == ".png"){  // tant que $i et $aleaImage ne sont pas égal, incrémenter $i
                    return "../Affiche/$titreSerie/$file";              // renvoie la $aleaImage affiche de série TV
                }
                if(substr($file, -4) == ".png"){
                    $i++;
                }
            }
            closedir($dir);
        }
    }
}