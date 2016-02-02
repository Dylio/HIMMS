<?php
class class_media_object {
    private  $_num_serie;
    private  $_titre;
    private  $_dateD;
    private  $_dateF;
    private  $_classification;
    private  $_num_user;
    
    public function __construct($num_user){
        $this->_num_serie = "";
        $this->_titre = "";
        $this->_dateD = "";
        $this->_dateF = "";
        $this->_classification = "";
        $this->_num_user = $num_user;
    } 
    
    public function newSerie($data){
        $this->_num_serie = $data['num_serie'];
        $this->_titre = $data['titre'];
        $this->_dateD = $data['dateD'];
        $this->_dateF = $data['dateF'];
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
        return "<div class='$style'>$date</div>";
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