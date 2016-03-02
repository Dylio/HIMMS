<?php
// Cette classe permet la gestion des événements de synchronisation pour mettre à jour les données affichées. 
// Il reçoit tous les événements de l'utilisateur.
class class_admin_controleur {
    private  $_TxtSearch;           // texte d'une partie de requete SQL permettant une recherche de séries TV spécifiques
    private  $_Order;               // ordre de restitution des valeurs
    private  $_str;                 // constantes textuelles du site web
    
    // Constructeur de la classe class_controleur
    // IN : $user numéro unique identifiant un utilisateur
    // IN : $str constantes textuelles du site web
    public function __construct($str){
        $this->_TxtSearch = '';                     // aucune recherche spécifique par défaut
        $this->_Order = 'sort1';                    // ordre de restitution des valeurs par défaut : titre croissant
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
        }
    }

    // permet d'afficher les différents éléments permettant l'interaction avec l'utilisateur
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    public function vue_tri($placeholder){
        $this->search($placeholder).'<br/>';       // recherche ciblé
        $this->order();                            // choix : ordre d'appartition
    }     

    // afficher l'élément graphique permettant la recherche de séries TV via une entrée texte 
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    private function search($placeholder){
        if(isset($_POST['ok'])){
           require_once 'class_admin_affichage.php';
           // enlève les caractères spécials 
           $this->_TxtSearch = class_admin_affichage::no_special_character($_POST['search']);
        }
        if(isset($_POST['empty'])){
           // réinitialisation de la zone texte 
           $this->resetSearch();
        }
        echo "<form action='' method='POST'>"
            ."<div class='input-group formSearch'>"
                ."<input type='text' name='search' class='form-control SearchInput' value='$this->_TxtSearch' placeholder='$placeholder'>"
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
        
        // 4 boutons correspondant aux différents possibilité de tri des séries TV
        // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable
        // Les autres boutons sont d'une couleur bleu et selectionnable       
        echo "<form method='post'>"
            . "<div class='thumbnail tri_group2'>"
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