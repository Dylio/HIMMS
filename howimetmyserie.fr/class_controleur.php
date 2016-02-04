<?php
class class_controleur {
    private  $_TxtSearch;
    private  $_TxtLike;
    private  $_Like;
    private  $_Order;
    private  $_Recommandation;
    private  $_MediaObject;
    
    private  $_str;
    private  $_user;
    
    public function __construct($user){
        $this->_TxtSearch = '';
        $this->_TxtLike = '';
        $this->_Like = 'all';
        $this->_Order = 'sort1';
        $this->_Recommandation = false;
        $this->_MediaObject = "MediaObjectCaseG";
        $this->_user = $user;
        require_once 'lang.php';
        $this->_str = lang::getlang();
    }    
    
    public function getTxtSearch(){
        return 'and s.titre like "%'.$this->_TxtSearch.'%" ';
    }
    
    public function resetSearch(){
        $this->_TxtSearch = '';
    }
    
    public function getTxtLike(){
        return $this->_TxtLike;
    }
    public function getTxtOrder(){
        switch ($this->_Order){
            case 'sort1' :
                return "s.titre asc, s.dateD desc";
            case 'sort2' :
                return "s.titre desc, s.dateD desc";
            case 'sort3' :
                return "s.dateD asc, s.titre asc";
            case 'sort4' :
                return "s.dateD desc, s.titre asc";
             case 'sortRand' :
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
           $this->_TxtLike=" and s.num_serie in (select num_serie from voir where num_user = '$this->_user')" ;
        }
        if(isset($_POST['List_all'])){
           $this->_Like = "all";
           $this->_TxtLike='';
        }
        if(isset($_POST['List_unlike'])){
            $this->_Like = "unlike";
            $this->_TxtLike="and s.num_serie not in (select num_serie from voir where num_user = '$this->_user') ";
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
                if($this->_MediaObject == "MediaObjectCaseList"){
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