<?php
// Cette classe permet la gestion des événements de synchronisation pour mettre à jour les données affichées. 
// Il reçoit tous les événements de l'utilisateur.
class class_controleur {
    private  $_db;                  // instance vers la base de données
    private  $_user;                  // instance vers la base de données
    
    // Constructeur de la classe class_controleur
    public function __construct(){
        require_once 'class_affichage.php';
        require_once 'class_db.php';
        $this->_db = new class_db();
    }
    
    public function init(){
        $this->_db->init(false);
        $this->_user = $_COOKIE['himms_log'];
        $this->_affichage = new class_affichage();
        $this->menu();
    }
    
    public function getDB(){
        return $this->_db;
    }    
    
    public function serie(){
        // selection séries TV selon les choix de tri de l'utilisateur
        $req= $this->_db->serie($this->getTxtSearch(), 
                $this->getTxtLike(),
                $this->getTxtRecommandation(),
                $this->getTxtOrder());
        
        // calcul nombre de séries TV selon les choix de tri hors recherche de l'utilisateur
        $dataNb =  $this->_db->serie_count($this->getTxtLike(),
                $this->getTxtRecommandation());
                
        $this->_affichage->affichage_serie($req, $dataNb, $_SESSION['SerieTV_MediaObject'], true);
    }
    
    public function une_serie($num_serie){
        $data=$this->_db->une_serie($num_serie);
        $req = $this->_db->recommandation_serie($num_serie);
        $this->_affichage->affichage_une_serie($data, $req);
    }
    
    public function search($txtSearch){
        $req=$this->_db->search($this->getTxtLike(),
                $txtSearch,
                $this->getTxtRecommandation(),
                $this->getTxtOrder());
        $SearchNb=$this->_db->search_count($this->getTxtLike(),
            $txtSearch,
            $this->getTxtRecommandation());
        $this->_affichage->affichage_serie($req, $SearchNb, $_SESSION['SerieTV_MediaObject'], true);
    }
    
    public function recommandation($str, $limit){
        // gestion de l'évènement séries TV aimé ou recommandé et son contraire
        $this->like_recommandation();
        if($this->_db->recommandation_exist()== 1){
            $this->_affichage->affichage_titrePartie($str['recommandation']['title'].'<p class="NomPartie2">'.$str['recommandation']['input_search']."</p>");
            
            // affichage des composants servant au tri des séries TV
            $this->_affichage->vue_tri2($_SESSION['SerieTV_like'],
                    $_SESSION['SerieTV_order'],
                    $_SESSION['SerieTV_recommandation'],
                    $_SESSION['SerieTV_MediaObject']);

            // selection séries TV selon les choix de tri de l'utilisateur
            $req= $this->_db->recommandation($this->getTxtLike(),
                    $this->getTxtRecommandation(),
                    $this->getTxtOrder(),
                    $limit);

            // calcul nombre de séries TV selon les choix de tri hors recherche de l'utilisateur
            $dataNb =  $this->_db->serie_count($this->getTxtLike(),
                    $this->getTxtRecommandation());

            // affichage des séries TV
            $this->_affichage->affichage_serie($req, $dataNb, $_SESSION['SerieTV_MediaObject'], true);
        }else{
            $this->_affichage->affichage_titrePartie("<span class='NomPartie2'>".$str['recommandation']['input_search_no_recommandation']."</span>");
            // selection du top 20 séries TV si aucune recommandation n'est possible (aucun goût exprimé par l'utilisateur)
            $req=$this->_db->serie_top_coeur(20);
            // affichage séries TV
            $this->_affichage->affichage_serie($req, null, 'MediaObjectCaseG', null);
        }
    }     
    
    // Gestion des évenements quand un utilisateur change d'état une série TV
    public function like_recommandation(){
        // Gestion et Affichage un message d'alerte lorsqu'une série TV devient non recommandé ou pas par l'utilisateur
        if(isset($_POST['Like'])){
            if($_POST['Like'] == 1){
                $this->_db->serie_like_insert($_POST['Serie']);
                echo '<div class="alert alert-success" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-ok"></span> La série "'.$_POST['TitreSerie'].'" appartient maintenant à vos séries favorites !<br/>'
                . '</div>';
            }else{
                $this->_db->serie_like_delete($_POST['Serie']);
                echo '<div class="alert alert-danger" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-remove"></span> La série "'.$_POST['TitreSerie'].'" n\'appartient plus à vos séries favorites !<br/>'
                . '</div>';
            }
        }

        // Gestion et Affichage un message d'alerte lorsqu'une série TV devient non recommandé ou pas par l'utilisateur
        if(isset($_POST['Recommandation'])){
            if($_POST['Recommandation'] == 1){
                $this->_db->serie_nonRecommandation_insert($_POST['Serie']);
                echo '<div class="alert alert-danger" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-remove"></span>'
                    . 'La série "'.$_POST['TitreSerie'].'" n\'appartient plus à vos séries de recommandation !<br/>'
                . '</div>';
            }else{
                $this->_db->serie_nonRecommandation_delete($_POST['Serie']);
                echo '<div class="alert alert-success" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-ok"></span>'
                    . 'La série "'.$_POST['TitreSerie'].'" appartient maintenant à vos séries de recommandation !<br/>'
                . '</div>';
            }   
        }
        // les messages restent afficher pendant 3,5 secondes
        echo '<script type="text/javascript">
            setTimeout( function(){
                document.getElementById("info").style.display = "none"; }, 3500);
        </script>';
    }
    
    public function menu(){
        $nb = $this->_db->interesser_motcle_count();
        $req = $this->_db->interesser_motcle();
        $questionnaire = $this->_db->questionnaire_exist();
        $this->_affichage->menu_top($req, $nb);
        $this->_affichage->menu_bottom($questionnaire);
        // Gestion des évènement lorsqu'un utilisateur veut envoyer un message
        if(isset($_POST['Envoyer'])){
            $this->_db->user_contact($_POST['pseudo'], $_POST['email'], $_POST['Sujet'], $_POST['Message']);
        }
    }
    
    public function carouselle(){
        $req2=$this->_db->serie_top_recommandation(9);
        $req3=$this->_db->serie_top_coeur(9);
        if($this->_db->recommandation_exist() == 1){
            $req1=$this->_db->recommandation( null, " having count(nr.num_serie) = 0 ", 'rand()', 3);
            $this->_affichage->carouselle($req1, $req2, $req3); 
        }else{
            $this->_affichage->carouselle(false, $req2, $req3); 
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

    public function search_mc($mc){
        $mc = class_controleur::no_special_character($mc);
        $mc = explode(" ", $mc);
        $txtSearch  = '';
        $motExclu = array();
        $reqME = $this->_db->motexclu();
        while ($dataMCE = $reqME->fetch()){
            array_push($motExclu, $dataMCE['libelle']);
        }
        foreach ($mc as $linetxt){
            if(!in_array($linetxt, $motExclu, true)){
                $id_motcle = $this->_db->search_motcle($linetxt);
                if($this->_db->interesser_nbChercher($id_motcle) == 0){
                    $this->_db->interesser_insert($id_motcle);
                }else{
                    $this->_db->interesser_update($id_motcle);
                }
                $txtSearch = $txtSearch." and s.num_serie in ( select num_serie from appartenir where num_motcle = '$id_motcle' )";
            }
        } 
        return $txtSearch;
    }
    

    public function filtre(){
        (!isset($_SESSION['SerieTV_search'])) ? $_SESSION['SerieTV_search']='' : null;
        $this->f_search();
        (!isset($_SESSION['SerieTV_like'])) ? $_SESSION['SerieTV_like']='all' : null;
        $this->f_like();
        (!isset($_SESSION['SerieTV_order'])) ? $_SESSION['SerieTV_order']='sort1' : null;
        (!isset($_SESSION['SerieTV_recommandation'])) ? $_SESSION['SerieTV_recommandation']='false' : null;
        $this->f_order();
        (!isset($_SESSION['SerieTV_MediaObject'])) ? $_SESSION['SerieTV_MediaObject']='MediaObjectCaseG' : null;
        $this->f_media();
    }
    
    private function f_search(){
        if(isset($_POST['ok'])){
           // enlève les caractères spécials 
           $_SESSION['SerieTV_search'] = class_controleur::no_special_character($_POST['search']);
        }
        if(isset($_POST['empty'])){
           // réinitialisation de la zone texte 
           $_SESSION['SerieTV_search'] = '';
        }
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV selon son titre
    public function getTxtSearch(){
        return 'and s.titre like "%'.$_SESSION['SerieTV_search'].'%" ';
    } 
    
    // afficher l'élément graphique permettant à l'utilisateur de l'ordre d'apparition des séries TV
    private function f_order(){
        if(isset($_POST['sort1'])){         // tri par titre croissant puis par date de début croissant
            $_SESSION['SerieTV_order'] =  "sort1";
        }
        if(isset($_POST['sort2'])){         // tri par titre décroissant puis par date de début croissant
            $_SESSION['SerieTV_order'] =  "sort2";
        }
        if(isset($_POST['sort3'])){         // tri par date de début croissant puis par titre croissant
            $_SESSION['SerieTV_order'] =  "sort3";
        }
        if(isset($_POST['sort4'])){         // tri par date de début décroissant puis par titre croissant
            $_SESSION['SerieTV_order'] =  "sort4";
        }
        if(isset($_POST['sortRand'])){      // tri aléatoire
            $_SESSION['SerieTV_order'] =  "sortRand";
        }                 
                 
        if(isset($_POST['nonRecommandationF'])){        // voir toutes les séries TV
            $_SESSION['SerieTV_recommandation'] = false;
        }else if(isset($_POST['nonRecommandationT'])){  // voir que les séries TV dont l'utilisateur veut être recommandé
            $_SESSION['SerieTV_recommandation'] = true;
        }
    }
    
    // retourne une partie de requete SQL permettant de chosir l'ordre d'appartition des séries TV
    // OUT : partie de requete SQL permettant de chosir l'ordre d'appartition des séries TV
    public function getTxtOrder(){
        switch ($_SESSION['SerieTV_order']){
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
        switch ($_SESSION['SerieTV_recommandation']){
            case false :        // recherche toutes les séries TV
                return " ";
            case true :         // recherche les séries TV qui ne sont pas dans la table "NonRecommandation"
               return " having count(nr.num_serie) = 0 ";
        }
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de choisir de voir 
    // les séries TV qui sont aimées, non aimées ou les deux
    private function f_like(){
        if(isset($_POST['List_like'])){
          $_SESSION['SerieTV_like'] = "like";           // valeur si l'utilisateur veut voir que ces séries TV aimées
        }
        if(isset($_POST['List_all'])){
          $_SESSION['SerieTV_like'] = "all";            // valeur si l'utilisateur veut voir toutes les séries TV aimées ou pas encore aimées
        }
        if(isset($_POST['List_unlike'])){
          $_SESSION['SerieTV_like'] = "unlike";        // valeur si l'utilisateur veut voir que ces séries TV pas encore aimées
        }
    }
    
    // retourne une partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur aime ou pas
    // OUT : partie de requete SQL permettant la recherche spécifique de séries TV que l'utilisateur aime ou pas
    public function getTxtLike(){
        if($_SESSION['SerieTV_like'] == "like"){ // si l'utilisateur veut voir que les séries TV like
           return " and s.num_serie in (select num_serie from voir where num_user = '$this->_user')" ;
        }else if($_SESSION['SerieTV_like'] == "unlike"){ // si l'utilisateur veut voir que les séries TV pas encore like
            return "and s.num_serie not in (select num_serie from voir where num_user = '$this->_user') ";
        }else{ // si l'utilisateur veut voir toutes les séries TV sans distinction
            return '';
        }
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de choisir le style d'objet abstrait 
    // pour la visualisation des séries TV
    private function f_media(){
        if(isset($_POST['MediaObjectCaseG'])){          // valeur si l'utilisateur veut voir les séries TV sous forme de grandes cases
            $_SESSION['SerieTV_MediaObject'] = "MediaObjectCaseG";
        }
        if(isset($_POST['MediaObjectCaseP'])){          // valeur si l'utilisateur veut voir les séries TV sous forme de petites cases
            $_SESSION['SerieTV_MediaObject'] = "MediaObjectCaseP";
        }
        if(isset($_POST['MediaObjectList'])){           // valeur si l'utilisateur veut voir les séries TV sous forme de liste détaillé
            $_SESSION['SerieTV_MediaObject'] = "MediaObjectList";
        }
    }
    
}