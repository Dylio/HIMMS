<?php
// Cette classe permet la gestion des événements de synchronisation pour mettre à jour les données affichées. 
// Il reçoit tous les événements de l'utilisateur.
class class_controleur {
    private  $_TxtSearch;           // texte d'une recherche des séries TV spécifiques
    private  $_Like;                // recherche des series TV aimé ou non
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
        $this->_Like = 'all';                       // recherche par défaut de toutes les series TV (aime ou non)
        $this->_Order = 'sort1';                    // ordre de restitution des valeurs par défaut : titre croissant
        $this->_Recommandation = false;             // recherche par défaut de toutes les series TV (recommander ou non)
        $this->_MediaObject = "MediaObjectCaseG";   // styles d'objets abstraits pour la visualisation des séries TV par défaut : sous forme de grande case
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
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur aime ou pas
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur aime ou pas
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
            case 'sort1' :      // tri par titre croissant puis par date de début croissant
                return "s.titre asc, s.dateD desc";
            case 'sort2' :      // tri par titre décroissant puis par date de début croissant
                return "s.titre desc, s.dateD desc";
            case 'sort3' :      // tri par date de début croissant puis par titre croissant
                return "s.dateD asc, s.titre asc";
            case 'sort4' :      // tri par date de début décroissant puis par titre croissant
                return "s.dateD desc, s.titre asc";
             case 'sortRand' :  // tri aléatoire
                return "rand()";
        }
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV dont l'utilisateur veut être recommander
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV dont l'utilisateur veut être recommander
    public function getTxtRecommandation(){
        switch ($this->_Recommandation){
            case false :        // recherche toutes les séries TV
                return " ";
            case true :         // recherche les séries TV qui ne sont pas dans la table "NonRecommandation"
               return " having count(nr.num_serie) = 0 ";
        }
    }
    
    // retourne le style d'objet abstrait pour la visualisation des séries TV 
    // OUT : style d'objet abstrait pour la visualisation des séries TV 
    public function getMediaObject(){
        return $this->_MediaObject;
    }

    // permet d'afficher les différents éléments permettant l'interaction avec l'utilisateur
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    public function vue_tri($placeholder, $valueEmpty){
        $this->like();                                          // choix : aimé/tous/non aimé
        $this->search($placeholder, $valueEmpty).'<br/>';       // recherche ciblé
        $this->order();                                         // choix : ordre d'appartition
        $this->media();                                         // choix : style d'objet abstrait pour la visualisation des séries TV
    }

    // permet d'afficher les différents éléments permettant l'interaction avec l'utilisateur sans zone texte
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $recommandation_exist 1 si recommandation ou 0 si top série
    public function vue_tri2($placeholder){
            $this->like();          // choix : aimé/tous/non aimé
            $this->order();         // choix : ordre d'appartition
            $this->media();         // choix : style d'objet abstrait pour la visualisation des séries TV  
    }       

    // afficher l'élément graphique permettant la recherche de séries TV via une entrée texte 
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    private function search($placeholder, $valueEmpty){
        if(isset($_POST['ok'])){
           require_once 'class_affichage.php';
           // enlève les caractères spécials 
           $this->_TxtSearch = class_affichage::no_special_character($_POST['search']);
        }
        if(isset($_POST['empty'])){
           // réinitialisation de la zone texte 
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
                     // bouton de recherche
                    // avec un tooltip en bas
                    ."<button type='submit' name='ok' class='btn btn-default SearchInput' value='true' data-toggle='tooltip' data-placement='bottom' title='".$this->_str['tooltip']['search']."'>"
                        ."<span class='glyphicon glyphicon-search'></span>"
                    ."</button>"
                     // bouton de réinitialisation de la zone texte
                     // avec un tooltip en bas
                    ."<button type='submit' name='empty' class='btn btn-default SearchInput' data-toggle='tooltip' data-placement='bottom' title='".$this->_str['tooltip']['reset']."'>"
                        ."<span class='glyphicon glyphicon-remove'></span>"
                    ."</button>"
                ."</div>"
            ."</div>"
        ."</form>";
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de choisir de voir 
    // les séries TV qui sont aimées, non aimées ou les deux
    private function like(){
        if(isset($_POST['List_like'])){
           $this->_Like = "like";           // valeur si l'utilisateur veut voir que ces séries TV aimées
        }
        if(isset($_POST['List_all'])){
           $this->_Like = "all";            // valeur si l'utilisateur veut voir toutes les séries TV aimées ou pas encore aimées
        }
        if(isset($_POST['List_unlike'])){
            $this->_Like = "unlike";        // valeur si l'utilisateur veut voir que ces séries TV pas encore aimées
        }
        // Trois boutons correspondant aux 3 options (aimé / tous / non aimé)
        // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable
        // Les autres boutons sont d'une couleur bleu et selectionnable
        echo "<form action='' method='POST' class='formLike'>"
            // bouton séries TV que l'utilisateur aime
            // avec un tooltip à gauche
            .'<button type="submit" name="List_like" data-toggle="tooltip" data-placement="left" title="'.$this->_str['tooltip']['like']['like'].'"';
                if($this->_Like == 'like'){
                    echo "class='btn btn-success buttonTriR' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriR'>";
                }
                echo "<span class='glyphicon glyphicon-heart'></span>"
            ."</button> "
            // bouton toutes les séries TV
            // avec un tooltip en haut
            ."<button type='submit' name='List_all' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['like']['all']."'";
                if($this->_Like == 'all'){
                    echo "class='btn btn-success buttonTriW' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriW'>";
                }
                echo "Tous"
            ."</button> "
            // bouton séries TV que l'utilisateur n'aime pas encore
            // avec un tooltip à droite
            .'<button type="submit" name="List_unlike" data-toggle="tooltip" data-placement="right" title="'.$this->_str['tooltip']['like']['unlike'].'"';
                if($this->_Like == 'unlike'){
                    echo "class='btn btn-success buttonTriN' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriN'>";
                }
                echo "<span class='glyphicon glyphicon-heart-empty'></span>"
            ."</button>"
        ."</form>";
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de choisir le style d'objet abstrait 
    // pour la visualisation des séries TV
    private function media(){
        if(isset($_POST['MediaObjectCaseG'])){          // valeur si l'utilisateur veut voir les séries TV sous forme de grandes cases
            $this->_MediaObject = "MediaObjectCaseG";
        }
        if(isset($_POST['MediaObjectCaseP'])){          // valeur si l'utilisateur veut voir les séries TV sous forme de petites cases
            $this->_MediaObject = "MediaObjectCaseP";
        }
        if(isset($_POST['MediaObjectList'])){           // valeur si l'utilisateur veut voir les séries TV sous forme de liste détaillé
            $this->_MediaObject = "MediaObjectList";
        } 
        
        // Trois boutons correspondant aux 3 options (grande case / petite case / liste détaillé)
        // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable
        // Les autres boutons sont d'une couleur bleu et selectionnable
        echo "<form action='' method='POST' class='thumbnail formMedia'>"
            // bouton grande case
            // avec un tooltip en haut
            .'<button type="submit" name="MediaObjectCaseG" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseG'].'"';
                if($this->_MediaObject == "MediaObjectCaseG"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-th-large'></span>"
            ."</button> "
            // bouton petite case
            // avec un tooltip en haut
            .'<button type="submit" name="MediaObjectCaseP" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseP'].'"';
                if($this->_MediaObject == "MediaObjectCaseP"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-th'></span>"
            ."</button> "
            // bouton liste détaillé
            // avec un tooltip en haut
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
    
    // afficher l'élément graphique permettant à l'utilisateur de l'ordre d'apparition des séries TV
    private function order(){
        if(isset($_POST['sort1'])){         // tri par titre croissant puis par date de début croissant
            $this->_Order =  "sort1";
        }
        if(isset($_POST['sort2'])){         // tri par titre décroissant puis par date de début croissant
            $this->_Order =  "sort2";
        }
        if(isset($_POST['sort3'])){         // tri par date de début croissant puis par titre croissant
            $this->_Order =  "sort3";
        }
        if(isset($_POST['sort4'])){         // tri par date de début décroissant puis par titre croissant
            $this->_Order =  "sort4";
        }
        if(isset($_POST['sortRand'])){      // tri aléatoire
            $this->_Order =  "sortRand";
        }                 
                 
        if(isset($_POST['nonRecommandationF'])){        // voir toutes les séries TV
            $this->_Recommandation = false;
        }else if(isset($_POST['nonRecommandationT'])){  // voir que les séries TV dont l'utilisateur veut être recommandé
            $this->_Recommandation = true;
        }
        // bouton correspondant à l'option : voir les séries TV dont l'utilisateur veut être recommandé
        // avec un tooltip en haut
        echo "<form action='' method='POST'>"
            ."<div class='thumbnail tri_group1'>"
                ."<button type='submit' ";
                    if($this->_Recommandation == true){
                        echo 'name="nonRecommandationF" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['interest']['off'].'">';
                    }else{
                        echo 'name="nonRecommandationT" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['interest']['on'].'">';
                    }
                    echo "<span class='glyphicon glyphicon-eye-open'></span>"
                ."</button>"
            ."</div>"
           
            // 5 boutons correspondant aux différents possibilité de tri des séries TV
            // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable 
            // (exception : option aléatoire reste selectionnable)
            // Les autres boutons sont d'une couleur bleu et selectionnable         
            ."<div class='thumbnail tri_group2'>"
                ."<span>Titre : </span> "
                // option tri par titre croissant puis par date de début croissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort1' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort1']."'";
                    if($this->_Order == "sort1"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo '<span class="glyphicon glyphicon-menu-up"></span>'
                ."</button> "
                // option tri par titre décroissant puis par date de début croissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort2' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort2']."'";
                    if($this->_Order == "sort2"){
                        echo 'class="btn btn-success" disabled>';
                    }else{
                        echo 'class="btn btn-info">';
                    }
                    echo "<span class='glyphicon glyphicon-menu-down'></span>"
                ."</button> "
                // option tri aléatoire
                // avec un tooltip en haut
                ."<button type='submit' name='sortRand' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sortRand']."'";
                    if($this->_Order == "sortRand"){
                        echo 'class="btn btn-success">';
                    }else{ 
                        echo 'class="btn btn-info">';
                    }
                    echo "<span class='glyphicon glyphicon-random'></span>"
                ."</button>"
            ."</div>" 
            ."<div class='thumbnail tri_group2'>"
                ."<span>Date : </span>"
                // option tri par date de début croissant puis par titre croissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort3' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort3']."'";
                    if($this->_Order == "sort3"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo "<span class='glyphicon glyphicon-menu-up'></span>"
                ."</button> "
                // option tri par date de début croissant puis par titre décroissant
                // avec un tooltip en haut
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