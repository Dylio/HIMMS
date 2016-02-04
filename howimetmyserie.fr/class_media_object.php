<?php
// Cette classe permet de restituer sous différents styles d'objets abstraits la construction graphique d'une
// séries TV via divers types de composants qui présentent son affiche et le contenu textuel.
class class_media_object {
    private  $_str;             // variable stockant le texte du site décrit dans lang selon la langue
    private  $_num_serie;       // numéro de la série
    private  $_titre;           // titre de la série
    private  $_date;            // date de début et de fin de la série
    private  $_nationalite;     // nationalité de la série   
    private  $_créateurs;       // créateur de la série
    private  $_acteurs;         // acteurs de la série
    private  $_genre;           // genre de la série
    private  $_format;          // durée moyen d'un épisode de cette série
    private  $_nbSaison;        // nombre de saison de la série
    private  $_nbEpisode;       // nombre d'épisode total de la série
    private  $_classification;  // classification (restriction d'âge) de la série
    private  $_num_user;        // numéro unqiue de l'utilisateur courrant
    
    // Constructeur de la classe class_media_object
    // IN : $num_user numéro unique de l'utilisateur courrant
    public function __construct($num_user){
        require_once 'lang.php';        // importation la classe lang.fr qui permet d'initiliser les zone texte du site hors description des séries.
        $this->_str = lang::getlang();
        $this->_num_user = $num_user;
    }
    
    // Supprimmer la série courante
    private function cleanSerie(){
        unset ($this->_num_serie);
        unset ($this->_titre);
        unset ($this->_date);
        unset ($this->_nationalite);
        unset ($this->_créateurs);
        unset ($this->_acteurs);
        unset ($this->_genre);
        unset ($this->_format);
        unset ($this->_nbSaison);
        unset ($this->_nbEpisode);
        unset ($this->_classification);
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
    
    // Permet d'initialiser les varibles minimum nécessaire pour décrire une série
    // IN : $data tableau contenant les informations minimum nécessaire pour décrire une série.
    public function newSerie($data){
        $this->cleanSerie();
        $this->_num_serie = $data['num_serie'];
        $this->_titre = $data['titre'];
        $this->date($data['dateD'], $data['dateF']);
        $this->_classification = $data['classification'];
    }
    
    // Permet d'initialiser les varibles nécessaire pour décrire une série
    // IN : $data tableau contenant les informations nécessaire pour décrire une série.
    public function newSerieDetail($data){
        $this->cleanSerie();
        $this->_num_serie = $data['num_serie'];
        $this->_titre = $data['titre'];
        $this->date($data['dateD'], $data['dateF']);
        $this->_nationalite = $data['nationalite'];
        $this->_créateurs = $data['créateurs'];
        $this->_acteurs = $data['acteurs'];
        $this->_genre = $data['genre'];
        $this->_format = $data['format'];
        $this->_nbSaison = $data['nbSaison'];
        $this->_nbEpisode = $data['nbEpisode'];
        $this->_classification = $data['classification'];
    }
    
    // Renvoie le titre d'une série avec son style de representation
    // IN : $style style qui permet de décrire la representation du titre d'une série.
    private function serie_titre($style){
        return "<div class='$style'><a href='./Serie_Detail.php?num_serie=$this->_num_serie'>$this->_titre</a></div>";
    }
    
    // Renvoie les dates d'une série avec son style de représentation
    // IN : $style style qui permet de décrire la representation des dates d'une série.
    private function serie_date($style){
        return "<span class='$style'>$this->_date</span>";
    }
    
    // Renvoie les boutons like et nonRecommandation d'une série avec son style de représentation
    // IN : $style style qui permet de décrire la representation des boutons like et recommandation d'une série.
    private function serie_like_recommandation($style){
        $bd = new class_db();
        echo "<form action='' method='POST'> "
            ."<input type='hidden' name='Serie' value ='$this->_num_serie'/>"
            ."<input type='hidden' name='TitreSerie' value ='$this->_titre'/>";

            // vérifie l'utilisateur like ou non cette série
            // change le glyphicons et le style(couleur) du bouton selon le cas
            if($bd->serie_like_exist($this->_num_user, $this->_num_serie) == 0){
              echo '<button type="submit" class="btn button2 '.$style.'N" name="Like" data-toggle="tooltip" data-placement="bottom" title="J\'aime cette série !">'
                      . '<span id="$titre" class="glyphicon glyphicon-heart-empty"/></button>';
            }else{
              echo '<button type="submit" class="btn button2 '.$style.'R" name="Like" data-toggle="tooltip" data-placement="bottom" title="Je n\'aime plus cette série !">'
                      . '<span id="$titre" class="glyphicon glyphicon-heart"/></button>';
            }

            // vérifie l'utilisateur veut être recommander par rapport à cette série ou non
            // change le glyphicons et le style(couleur) du bouton selon le cas
            if($bd->serie_nonRecommandation_exist($this->_num_user, $this->_num_serie) == 0){
              echo '<button type="submit" class="btn button2 '.$style.'B" name="Recommandation" data-toggle="tooltip" data-placement="bottom" title="Je ne veux pas être recommandé par rapport à cette série.">'
                      . '<span id="$titre" class="glyphicon glyphicon-eye-open"/></button>';
            }else{
              echo '<button type="submit" class="btn button2 '.$style.'N" name="Recommandation" data-toggle="tooltip" data-placement="bottom" title="Je veux être recommandé par rapport à cette série.">'
                      . '<span id="$titre" class="glyphicon glyphicon-eye-close"/></button>';
            }
            
            // rencoie selon la classification de la série un pictogramme de restriction d'âge adapté
            if($this->_classification == 10){ // classification : -10 ans
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa10.jpg"/>';
            }
            if($this->_classification == 12){ // classification : -12 ans
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa12.jpg"/>';
            }
            if($this->_classification == 16){ // classification : -16 ans
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa16.jpg"/>';
            }
            if($this->_classification == 18){ // classification : -18 ans
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa18.jpg"/>';
            }
        echo '</form>';
    }

    // Créer sous différents styles d'objets abstraits la construction graphique de la séries TV
    // IN : $object la forme de restitution de la séries TV
    public function media_object($object){
        switch ($object) {
            case "MediaObjectList" : // sous forme de liste
                echo "<div class='thumbnail SerieListContainerSerie'>" // conteneur de l'affiche de la serie TV
                    ."<a href='./Serie_Detail.php?num_serie=$this->_num_serie' class='media-left media-middle'>"
                        ."<img class='media-object SerieListImg' src='".$this->alea_image($this->_titre)."' alt='".$this->_titre."'>"
                   ."</a>"
                   ."<div class='media-body'>";
                        echo $this->serie_titre("SerieListTitre");
                        echo $this->serie_like_recommandation("SerieListButton");
                        echo $this->serie_date("SerieListDate");
                    echo '</div>'
                .'</div>';
            break;
            case "MediaObjectCaseP" : // sous forme de petite case (1er forme)
                echo "<div class='thumbnail SerieCasePContainerSerie'>" // conteneur de l'affiche de la serie TV
                    ."<a href='./Serie_Detail.php?num_serie=$this->_num_serie' class='media-left media-middle'>"
                        ."<img class='media-object SerieCasePImg' src='".$this->alea_image($this->_titre)."' alt='".$this->_titre."'>"
                    ."</a>"
                    ."<div class='media-body SerieCasePTxt'>";
                        echo $this->serie_like_recommandation("SerieCasePButton");
                        echo $this->serie_titre("SerieCasePTitre");
                        echo $this->serie_date("SerieCasePDate");
                    echo '</div>'
                . '</div>';
            break;
            case "MediaObjectCaseP2" : // sous forme de petite case (2e forme)
                echo "<div class='thumbnail SerieCasePContainerSerie'>" // conteneur de l'affiche de la serie TV
                    ."<a href='./Serie_Detail.php?num_serie=$this->_num_serie' class='media-left media-middle'>"
                        ."<img class='media-object SerieCaseP2Img' src='".$this->alea_image($this->_titre)."' alt='".$this->_titre."'>"
                    ."</a>"
                    ."<div class='media-body SerieCaseP2Txt'>";
                        echo $this->serie_like_recommandation("SerieCasePButton");
                        echo $this->serie_titre("SerieCasePTitre");
                        echo $this->serie_date("SerieCasePDate");
                    echo '</div>'
                . '</div>';
            break;
            case "MediaObjectCaseG" : // sous forme de grande case (1er forme)
                echo "<div class='thumbnail SerieCaseGContainerSerie'>" // conteneur de l'affiche de la serie TV
                    ."<a href='./Serie_Detail.php?num_serie=$this->_num_serie'>"
                        ."<img class='media-object SerieCaseGImg' src='".$this->alea_image($this->_titre)."' alt='".$this->_titre."'>"
                    . "</a>"
                    ."<div class='caption'>";
                       echo $this->serie_like_recommandation("SerieCaseGButton");    
                       echo $this->serie_titre("SerieCaseGTitre");
                       echo $this->serie_date("SerieCaseGDate");
                    echo "</div>"
                . "</div>";
            break;
            case "MediaObjectCaseG2" : // sous forme de grande case (2e forme)
                echo "<div class='thumbnail SerieCaseG2ContainerSerie'>" // conteneur de l'affiche de la serie TV
                    ."<a href='./Serie_Detail.php?num_serie=$this->_num_serie'>"
                        ."<img class='media-object SerieCaseG2Img' src='".$this->alea_image($this->_titre)."' alt='".$this->_titre."'>"
                    . "</a>"
                    ."<div class='caption'>";
                       echo $this->serie_like_recommandation("SerieCaseGButton");    
                       echo $this->serie_titre("SerieCaseGTitre");
                       echo $this->serie_date("SerieCaseGDate");
                    echo '</div>'
                . '</div>';
            break;
            case "MediaObjectDetail" : // sous forme détaillé
                echo "<div class='jumbotron SerieDetailContainer'>";
                    echo $this->serie_titre("TitreSerie");
                    echo $this->serie_like_recommandation("SerieDetailButton");
                echo "</div>"
                ."<div class='jumbotron SerieDetailContainer2'>"
                    .$this->_str['serie_detail']['Production']."<span class='txtDetailSerie'>LP PROD</span><br/>"
                    .$this->_str['serie_detail']['Création']."<span class='txtDetailSerie'>$this->_créateurs</span><br/>"
                    .$this->_str['serie_detail']['Acteur']."<span class='txtDetailSerie'>$this->_acteurs ...</span><br/><br/>"
                    .$this->_str['serie_detail']['Date'];
                    echo $this->serie_date("txtDetailSerie")."<br/>";
                    echo $this->_str['serie_detail']['Nationalité']."<span class='txtDetailSerie'>$this->_nationalite</span><br/>"
                    .$this->_str['serie_detail']['Genre']."<span class='txtDetailSerie'>$this->_genre</span><br/>"
                    .$this->_str['serie_detail']['Format']."<span class='txtDetailSerie'>$this->_nbSaison saison";
                        if($this->_nbSaison > 1){ echo "s"; }
                        echo " répartis en $this->_nbEpisode épisodes de $this->_format min.</span>"
                ."</div>"
                ."<div class='jumbotron SerieDetailContainer2'>"
                    ."<p style='text-align: center'>".$this->_str['serie_detail']['Recommandation']."</p>";
                    require_once 'class_db.php';
                    require_once 'class_affichage.php';
                    $bd = new class_db();
                    $affichage = new class_affichage($this->_num_user);
                    $req = $bd->recommandation_serie($this->_num_serie);
                    $affichage->affichage_serie($req, null, $this->_num_user, 'MediaObjectCaseG2');
                echo "</div>";
            break;
        }   
    }
    
    // Renvoie l'adresse relatif d'une image aléatoire de la série TV
    // OUT : adresse relatif d'une image aléatoire de la série TV
    private function alea_image(){
        $dirname = "./Affiche/$this->_titre";       // adresse relatif de la série TV
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
                if($i === $aleaImage and substr($file, -4) == ".png"){ // tant que $i et $aleaImage ne sont pas égal, incrémenter $i
                    return "Affiche/$this->_titre/".$file;      // renvoie la $aleaImage affiche de série TV
                }
                if(substr($file, -4) == ".png"){
                    $i++;
                }
            }
            closedir($dir);
        }
    }
}