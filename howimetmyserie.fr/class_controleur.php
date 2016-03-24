<?php
// Cette classe permet la gestion des événements de synchronisation pour mettre à jour les données affichées. 
// Il reçoit tous les événements de l'utilisateur.
class class_controleur {
    private  $_TxtSearch;           // texte d'une partie de requete SQL permettant une recherche de séries TV spécifiques
    private  $_Like;                // recherche des series TV aimé ou non
    private  $_Order;               // ordre de restitution des valeurs
    private  $_Recommandation;      // recherche des séries TV dont l'utilisateur souhaite être recommander ou non
    private  $_MediaObject;         // styles d'objets abstraits pour la visualisation des séries TV
    private  $_user;                // numéro unique identifiant un utilisateur
    
    // Constructeur de la classe class_controleur
    // IN : $user numéro unique identifiant un utilisateur
    public function __construct($user){
        $this->_TxtSearch = '';                     // aucune recherche spécifique par défaut
        $this->_Like = 'all';                       // recherche par défaut de toutes les series TV (aime ou non)
        $this->_Order = 'sort1';                    // ordre de restitution des valeurs par défaut : titre croissant
        $this->_Recommandation = false;             // recherche par défaut de toutes les series TV (recommander ou non)
        $this->_MediaObject = "MediaObjectCaseG";   // styles d'objets abstraits pour la visualisation des séries TV par défaut : sous forme de grande case
        $this->_user = $user;
    }    
    
    public function controleur(){
        $this->like();
        $this->media();
        $this->order();
        $this->search();
    }
    
    // afficher l'élément graphique permettant la recherche de séries TV via une entrée texte 
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    private function search(){
        if(isset($_POST['ok'])){
           require_once 'class_affichage.php';
           // enlève les caractères spécials 
           $this->_TxtSearch = class_controleur::no_special_character($_POST['search']);
        }
        if(isset($_POST['empty'])){
           // réinitialisation de la zone texte 
           $this->resetSearch();
        }
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    public function getTxtSearch(){
        return 'and s.titre like "%'.$this->_TxtSearch.'%" ';
    } 
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    public function getSearch(){
        return  $this->_TxtSearch;
    }
    
    // supprimer le contenu de le la varible $_TxtSearch
    public function resetSearch(){
        $this->_TxtSearch = '';
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
    
    // retourne une partie de requete SQL permettant de chosir l'ordre d'appartition des séries TV
    // OUT : partie de requete SQL permettant de chosir l'ordre d'appartition des séries TV
    public function getOrder(){
        return $this->_Order;
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV dont l'utilisateur veut être recommander
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV dont l'utilisateur veut être recommander
    public function getRecommandation(){
        return $this->_Recommandation;
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
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur aime ou pas
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur aime ou pas
    public function getLike(){
        return $this->_Like;
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
    }
    
    // retourne le style d'objet abstrait pour la visualisation des séries TV 
    // OUT : style d'objet abstrait pour la visualisation des séries TV 
    public function getMediaObject(){
        return $this->_MediaObject;
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

    public static function search_mc($db, $mc){
        $mc = class_controleur::no_special_character($mc);
        $mc = explode(" ", $mc);
        $txtSearch  = '';
        $motExclu = array();
        $reqME = $db->motexclu();
        while ($dataMCE = $reqME->fetch()){
            array_push($motExclu, $dataMCE['libelle']);
        }
        foreach ($mc as $linetxt){
            if(!in_array($linetxt, $motExclu, true)){
                $id_motcle = $db->search_motcle($linetxt);
                if($db->interesser_nbChercher($id_motcle) == 0){
                    $db->interesser_insert($id_motcle);
                }else{
                    $db->interesser_update($id_motcle);
                }
                $txtSearch = $txtSearch." and s.num_serie in ( select num_serie from appartenir where num_motcle = '$id_motcle' )";
            }
        } 
        return $txtSearch;
    }
    
}