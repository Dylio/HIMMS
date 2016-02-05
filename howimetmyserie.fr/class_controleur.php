<?php
class class_controleur {
    private  $_TxtSearch;           // texte d'une recherche des séries TV spécifiques
    private  $_Like;                // recherche des series TV like ou non
    private  $_Order;               // ordre de restitution des valeurs
    private  $_Recommandation;      // recherche des séries TV dont l'utilisateur souhaite être recommander ou non
    private  $_MediaObject;         // styles d'objets abstraits pour la visualisation des séries TV
    private  $_user;                // numéro unique identifiant un utilisateur
    private  $_str;                 // constantes textuelles du site web
    
    // Constructeur de la classe class_controleur
    // IN : $user numéro unique identifiant un utilisateur
    // IN : $str constantes textuelles du site web
    public function __construct($user, $str){
        $this->_TxtSearch = '';                     // aucune recherche spécifique par défaut
        $this->_Like = 'all';                       // recherche par défaut de toutes les series TV (like ou non)
        $this->_Order = 'sort1';                    // ordre de restitution des valeurs par défaut : titre croissant
        $this->_Recommandation = false;             // recherche par défaut de toutes les series TV (recommander ou non)
        $this->_MediaObject = "MediaObjectCaseG";   // styles d'objets abstraits pour la visualisation des séries TV  par défaut : sous forme de grande case
        $this->_user = $user;
        $this->_str = $str;
    }    
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    public function getTxtSearch(){
        return 'and s.titre like "%'.$this->_TxtSearch.'%" ';
    }
    
    // supprimer le contenu de le la varible $_TxtSearch
    public function resetSearch(){
        $this->_TxtSearch = '';
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur like  ou pas
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur like ou pas
    public function getTxtLike(){
        if($this->_Like == "like"){ // si l'utilisateur veut voir que les séries TV like
           return " and s.num_serie in (select num_serie from voir where num_user = '$this->_user')" ;
        }else if($this->_Like == "unlike"){ // si l'utilisateur veut voir que les séries TV pas encore like
            return "and s.num_serie not in (select num_serie from voir where num_user = '$this->_user') ";
        }else{ // si l'utilisateur veut voir toutes les séries TV sans distinction
            return '';
        }
    }
    
    // retourne une partie de requete SQL permettant de chosir l'ordre d'appartition des séries TV
    // OUT : partie de requete SQL permettant de chosir l'ordre d'appartition des séries TV
    public function getTxtOrder(){
        switch ($this->_Order){
            case 'sort1' :      // trie par titre croissant puis par date de début croissant
                return "s.titre asc, s.dateD desc";
            case 'sort2' :      // trie par titre décroissant puis par date de début croissant
                return "s.titre desc, s.dateD desc";
            case 'sort3' :      // trie par date de début croissant puis par titre croissant
                return "s.dateD asc, s.titre asc";
            case 'sort4' :      // trie par date de début décroissant puis par titre croissant
                return "s.dateD desc, s.titre asc";
             case 'sortRand' :  // trie aléatoire
                return "rand()";
        }
    }

    public function getTxtRecommandation(){
        switch ($this->_Recommandation){
            case false :
                return " ";
            case true :
               return " having count(nr.num_serie) = 0 ";
        }
    }
    
    public function getMediaObject(){
        return $this->_MediaObject;
    }

    public function vue_trie($placeholder, $valueEmpty){
        $this->like($this->_user);
        $this->search($placeholder, $valueEmpty).'<br/>';
        $this->order();
        $this->media();
    }

    public function vue_trie2($placeholder){
        $this->like($this->_user);
        echo "<form action='' method='POST'>"
            . "<div class='input-group formSearch'>"
                . "<input type='text' name='search' class='form-control SearchInput RecommandationInput' value='$placeholder' disabled>"
            . "</div>"
         . "</form><br/>";
        $this->order();
        $this->media();
    }       
    
    private function search($placeholder, $valueEmpty){
        if(isset($_POST['ok'])){
           require_once 'class_affichage.php';
           $this->_TxtSearch = class_affichage::no_special_character($_POST['search']);
        }
        if(isset($_POST['empty'])){
           $this->resetSearch();
        }
        
        echo "<form action='' method='POST'>"
            ."<div class='input-group formSearch'>"
                ."<input type='text' name='search' class='form-control SearchInput' value='";
                if($valueEmpty == false){ 
                    echo $this->_TxtSearch; 
                }else{ 
                    echo '';
                }
                echo "'placeholder='$placeholder'>"
                ."<div class='input-group-btn'>"
                    ."<button type='submit' name='ok' class='btn btn-default SearchInput' value='true' data-toggle='tooltip' data-placement='bottom' title='".$this->_str['tooltip']['search']."'>"
                        ."<span class='glyphicon glyphicon-search'></span>"
                    ."</button>"
                    ."<button type='submit' name='empty' class='btn btn-default SearchInput' data-toggle='tooltip' data-placement='bottom' title='".$this->_str['tooltip']['reset']."'>"
                        ."<span class='glyphicon glyphicon-remove'></span>"
                    ."</button>"
                ."</div>"
            ."</div>"
        ."</form>";
    }
    
    private function like(){
        if(isset($_POST['List_like'])){
           $this->_Like = "like";
        }
        if(isset($_POST['List_all'])){
           $this->_Like = "all";
        }
        if(isset($_POST['List_unlike'])){
            $this->_Like = "unlike";
        }
        echo "<form action='' method='POST' class='formLike'>"
            .'<button type="submit" name="List_like" data-toggle="tooltip" data-placement="left" title="'.$this->_str['tooltip']['like']['like'].'"';
                if($this->_Like == 'like'){
                    echo "class='btn btn-success buttonDetailSerieR2' disabled>";
                }else{
                    echo "class='btn btn-info buttonDetailSerieR2'>";
                }
                echo "<span class='glyphicon glyphicon-heart'></span>"
            ."</button> "
            ."<button type='submit' name='List_all' style='font-size:20px' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['like']['all']."'";
                if($this->_Like == 'all'){
                    echo "class='btn btn-success buttonDetailSerieW2' disabled>";
                }else{
                    echo "class='btn btn-info buttonDetailSerieW2'>";
                }
                echo "Tous"
            ."</button> "
            .'<button type="submit" name="List_unlike" data-toggle="tooltip" data-placement="right" title="'.$this->_str['tooltip']['like']['unlike'].'"';
                if($this->_Like == 'unlike'){
                    echo "class='btn btn-success buttonDetailSerieN2' disabled>";
                }else{
                    echo "class='btn btn-info buttonDetailSerieN2'>";
                }
                echo "<span class='glyphicon glyphicon-heart-empty'></span>"
            ."</button>"
        ."</form>";
    }
    
    private function media(){
        if(isset($_POST['MediaObjectCaseG'])){
            $this->_MediaObject = "MediaObjectCaseG";
        }
        if(isset($_POST['MediaObjectCaseP'])){
            $this->_MediaObject = "MediaObjectCaseP";
        }
        if(isset($_POST['MediaObjectList'])){
            $this->_MediaObject = "MediaObjectList";
        } 
        
        echo "<form action='' method='POST' class='thumbnail formMedia'>"
            .'<button type="submit" name="MediaObjectCaseG" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseG'].'"';
                if($this->_MediaObject == "MediaObjectCaseG"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-th-large'></span>"
            ."</button> "
            .'<button type="submit" name="MediaObjectCaseP" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseP'].'"';
                if($this->_MediaObject == "MediaObjectCaseP"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-th'></span>"
            ."</button> "
            .'<button type="submit" name="MediaObjectList" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseList'].'"';
                if($this->_MediaObject == "MediaObjectList"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-list'></span>"
            ."</button>"
        ."</form>";
    }
    
    private function order(){
        if(isset($_POST['sort1'])){
            $this->_Order =  "sort1";
        }
        if(isset($_POST['sort2'])){
            $this->_Order =  "sort2";
        }
        if(isset($_POST['sort3'])){
            $this->_Order =  "sort3";
        }
        if(isset($_POST['sort4'])){
            $this->_Order =  "sort4";
        }
        if(isset($_POST['sortRand'])){
            $this->_Order =  "sortRand";
        }

        if(isset($_POST['nonRecommandationF'])){
            $this->_Recommandation = false;
        }else if(isset($_POST['nonRecommandationT'])){
            $this->_Recommandation = true;
        }
        
        echo "<form action='' method='POST'>"
            ."<div class='thumbnail' style='float: left; margin-left : 100px; margin-bottom : 0px !important;'>"
                ."<button type='submit' ";
                    if($this->_Recommandation == true){
                        echo 'name="nonRecommandationF" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['interest']['off'].'">';
                    }else{
                        echo 'name="nonRecommandationT" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['interest']['on'].'">';
                    }
                    echo "<span class='glyphicon glyphicon-eye-open'></span>"
                ."</button>"
            ."</div>"
            ."<div class='thumbnail' style='float: left; margin-left : 25px; margin-bottom : 0px !important;'>"
                ."<span class='txt2'>Titre : </span> "
                ."<button type='submit' name='sort1' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort1']."'";
                    if($this->_Order == "sort1"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo '<span class="glyphicon glyphicon-menu-up"></span>'
                ."</button> "
                ."<button type='submit' name='sort2' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort2']."'";
                    if($this->_Order == "sort2"){
                        echo 'class="btn btn-success" disabled>';
                    }else{
                        echo 'class="btn btn-info">';
                    }
                    echo "<span class='glyphicon glyphicon-menu-down'></span>"
                ."</button> "
                ."<button type='submit' name='sortRand' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sortRand']."'";
                    if($this->_Order == "sortRand"){
                        echo 'class="btn btn-success">';
                    }else{ 
                        echo 'class="btn btn-info">';
                    }
                    echo "<span class='glyphicon glyphicon-random'></span>"
                ."</button>"
            ."</div>" 
            ."<div class='thumbnail' style='float: left; margin-left : 25px; margin-bottom : 0px !important;'>"
                ."<span class='txt2'>Date : </span>"
                ."<button type='submit' name='sort3' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort3']."'";
                    if($this->_Order == "sort3"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo "<span class='glyphicon glyphicon-menu-up'></span>"
                ."</button> "
                ."<button type='submit' name='sort4' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort4']."'";
                    if($this->_Order == "sort4"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo "<span class='glyphicon glyphicon-menu-down'></span>"
                ."</button>"
            ."</div>"
        ."</form>";
    }
}