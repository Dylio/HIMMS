<?php
class class_media_object {
    private  $_str;
    private  $_num_serie;
    private  $_titre;
    private  $_dateD;
    private  $_dateF;
    private  $_nationalite;
    private  $_créateurs;
    private  $_acteurs;
    private  $_genre;
    private  $_format;
    private  $_nbSaison;
    private  $_nbEpisode;
    	
    private  $_classification;
    private  $_num_user;
    
    public function __construct($num_user){
        require_once 'lang.php';
        $this->_str = lang::getlang();
        $this->_num_user = $num_user;
    } 
    
    public function newSerie($data){
        $this->_num_serie = $data['num_serie'];
        $this->_titre = $data['titre'];
        $this->_dateD = $data['dateD'];
        $this->_dateF = $data['dateF'];
        $this->_classification = $data['classification'];
    }    
    
    public function newSerieDetail($data){
        $this->_num_serie = $data['num_serie'];
        $this->_titre = $data['titre'];
        $this->_dateD = $data['dateD'];
        $this->_dateF = $data['dateF'];
        $this->_nationalite = $data['nationalite'];
        $this->_créateurs = $data['créateurs'];
        $this->_acteurs = $data['acteurs'];
        $this->_genre = $data['genre'];
        $this->_format = $data['format'];
        $this->_nbSaison = $data['nbSaison'];
        $this->_nbEpisode = $data['nbEpisode'];
        $this->_classification = $data['classification'];
    }    
    
    private function serie_titre($style){
        return "<div class='$style'><a href='./Serie_Detail.php?num_serie=$this->_num_serie'>$this->_titre</a></div>";
    }

    private function serie_date($style){
        $date = '';
        if($this->_dateD != null ){
            $date = $this->_dateD;
            if($this->_dateD != $this->_dateF and $this->_dateF != null){
                $date = $date.' - '.$this->_dateF;
            }else{ 
                $date = $date.' - En production';
            }
        }    
        return "<span class='$style'>$date</span>";
    }

    private function serie_like_recommandation($style){
        $bd = new class_db();
        echo "<form action='' method='POST'> "
            ."<input type='hidden' name='Serie' value ='$this->_num_serie'/>"
            ."<input type='hidden' name='TitreSerie' value ='$this->_titre'/>";

            if($bd->serie_like_exist($this->_num_user, $this->_num_serie) == 0){
              echo '<button type="submit" class="btn button2 '.$style.'N" name="Like" data-toggle="tooltip" data-placement="bottom" title="J\'aime cette série !">'
                      . '<span id="$titre" class="glyphicon glyphicon-heart-empty"/></button>';
            }else{
              echo '<button type="submit" class="btn button2 '.$style.'R" name="Like" data-toggle="tooltip" data-placement="bottom" title="Je n\'aime plus cette série !">'
                      . '<span id="$titre" class="glyphicon glyphicon-heart"/></button>';
            }

            if($bd->serie_nonRecommandation_exist($this->_num_user, $this->_num_serie) == 0){
              echo '<button type="submit" class="btn button2 '.$style.'B" name="Recommandation" data-toggle="tooltip" data-placement="bottom" title="Je ne veux pas être recommandé par rapport à cette série.">'
                      . '<span id="$titre" class="glyphicon glyphicon-eye-open"/></button>';
            }else{
              echo '<button type="submit" class="btn button2 '.$style.'N" name="Recommandation" data-toggle="tooltip" data-placement="bottom" title="Je veux être recommandé par rapport à cette série.">'
                      . '<span id="$titre" class="glyphicon glyphicon-eye-close"/></button>';
            }
            if($this->_classification == 10){
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa10.jpg"/>';
            }
            if($this->_classification == 12){
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa12.jpg"/>';
            }
            if($this->_classification == 16){
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa16.jpg"/>';
            }
            if($this->_classification == 18){
                echo '<img width=32 height=24 class="'.$style.'N" src="css/signes-csa18.jpg"/>';
            }
        echo '</form>';
    }

    public function media_object($object){
        switch ($object) {
            case "MediaObjectList" :
                echo "<div class='thumbnail SerieListContainerSerie'>"
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
            case "MediaObjectCaseP" :
                echo "<div class='thumbnail SerieCasePContainerSerie'>"
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
            case "MediaObjectCaseP2" :
                echo "<div class='thumbnail SerieCasePContainerSerie'>"
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
            case "MediaObjectCaseG" :
                echo "<div class='thumbnail SerieCaseGContainerSerie'>"
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
            case "MediaObjectCaseG2" :
                echo "<div class='thumbnail SerieCaseG2ContainerSerie'>"
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
            case "MediaObjectDetail" :
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
    
    private function alea_image($titreSerie){
        $dirname = './Affiche/'.$titreSerie;
        if(file_exists($dirname) ){
            $j = 0;
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if(substr($file, -4) == ".png"){
                    $j++;
                }
            }
            closedir($dir);
            $nbImage = rand(1, $j);
            $j = 1;
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if($j === $nbImage and substr($file, -4) == ".png"){
                    return 'Affiche/'.$titreSerie.'/'.$file;
                }
                if(substr($file, -4) == ".png"){
                    $j++;
                }
            }
            closedir($dir);
        }
    }
}