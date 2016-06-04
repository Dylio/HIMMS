<?php
// Cette classe permet l'interaction avec la base de données.
class class_db {
    private $_db;       // instance de connexion à la bd
    private $_serie;    // table série en fonction de la restriction du l'utilisateur
    private $_user;     // numero unique d'un utilisateur
     
    // Constructeur de la classe class_db
    public function __construct() {
        require_once 'connectBDD.php';              // connexion à la bd 
        $this->_db = Db::getInstance();             // création de l'instance de connexion
    }
    
    // Initialisation de la classe class_db
    // IN : $pageIndexFirst boolean true si la page est IndexFirst sinon false
    public function init($pageIndexFirst) {
        if(isset($_COOKIE['himms_log'])){                 // si un cookie contenant le numéro d'utilisateur existe
            $this->setUser($_COOKIE['himms_log']); 
            if(!isset($_COOKIE['PHPSESSID'])){      // s'il n'y a pas déjà eu une création d'une session avant
                // mise à jour du cookie : rajout un an à sa durée 
                setcookie('himms_log', $this->_user , time() + 365*24*3600, null, null, false, true);     
                // mise à jour des données de l'utilisateur (incrémente de 1 le nombre de visite)
                $this->user_update_visite();       
            }
        }else{
            // si un cookie contenant le numéro d'utilisateur n'existe pas encore et donc est un nouveau utilisateur
            // création d'un numéro d'utilisateur aléatoire unique
            $this->setUser(uniqid(rand(), true));    
            // création d'un cookie contenant le numéro d'utilisateur
            setcookie('himms_log', $this->_user , time() + 365*24*3600, null, null, false, true); 
            // insertion du nouveau utilisateur dans la base de données
            $this->user_insert();           
        }
        // Démarre une nouvelle session ou reprend une session existante
        session_start(); 
        //gestion des restrictions des séries pour l'utilisateur
        $this->restriction($pageIndexFirst);    
    }
        
    // requete : insertion du nouveau utilisateur dans la base de données
    public function user_insert(){
        $this->_db->query("INSERT INTO utilisateur(num_user, nbVisite, date_inscription, date_derniere_visite) "
                        . "values('$this->_user', 1, now(), now());");
    }
    
    // requete : mise à jour des données de l'utilisateur (incrémente de 1 le nombre de visite)
    public function user_update_visite(){
        $this->_db->query("UPDATE utilisateur "
                        . "set nbVisite = nbVisite + 1, "
                        . "date_derniere_visite = now() "
                        . "where num_user = '$this->_user' ;");
    }
    
    // change le numéro unique de l'utilisateur
    // IN : numéro unique de l'utilisateur
    public function setUser($user){
        $this->_user = $user;
    }
    
    // change le numéro unique de l'utilisateur
    // IN : numéro unique de l'utilisateur
    public function getUser(){
       return  $this->_user;
    }
    
    public function restriction($pageIndexFirst){
        $req = $this->_db->query("Select restriction "
                                . "from utilisateur "
                                . "where num_user = '$this->_user';");
        $data = $req->fetch();
        if($data['0'] == -1 && $pageIndexFirst== false){ // vérification si l'utilisateur a déjà annoncer sa majorité ou non pour la restriction
            header("Location:index_First.php");
        }elseif($data['0'] == 1){ // restriction : selectionne la table serietp (tout public)
            $this->_serie = 'serietp';
        }else if($data['0'] == 0){ // aucune restriction : selectionne la table serie
            $this->_serie = 'serie';
        }        
    }
    
    public function user_update_restriction($restriction){
        $this->_db->query("UPDATE utilisateur "
                        . "set restriction = $restriction "
                        . "where num_user = '$this->_user'");   
    }
    
    // change le numéro unique de l'utilisateur
    // IN : numéro unique de l'utilisateur
    public function getSerieRestriction(){
        return $this->_serie;
    }
    
    // requete : selectionne toutes les séries TV selon plusieurs critères
    // IN : $TxtSearch recherche des séries TV spécifiques
    // IN : $TxtLike recherche des series TV like ou non
    // IN : $TxtRecommandation recherche des séries TV dont l'utilisateur souhaite être recommander ou non
    // IN : $TxtOrder ordre de restitution des valeurs
    // OUT : requete de selection des séries TV
    public function serie($TxtSearch, $TxtLike, $TxtRecommandation, $TxtOrder){
        return $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                                . "from $this->_serie s "
                                . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                                    . "on s.num_serie = nr.num_serie "
                                . "left join (select * from voir where num_user = '$this->_user') v "
                                    . "on s.num_serie = v.num_serie "
                                . "where 1 $TxtSearch $TxtLike "
                                . "group by s.num_serie "
                                . "$TxtRecommandation "
                                . "order by $TxtOrder");
    }
    
    // requete : selectionne toutes les séries selon plusieur critères
    // IN : $num_serie numéro unique d'une série TV
    // OUT : requete de selection d'une série TV
    public function une_serie($num_serie){
        $req = $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                                . "from $this->_serie s "
                                . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                                    . "on s.num_serie = nr.num_serie "
                                . "left join (select * from voir where num_user = '$this->_user') v "
                                    . "on s.num_serie = v.num_serie "
                                . "where s.num_serie = '$num_serie';");
        return $data = $req->fetch();
    }
    
    // requete : compte le nombre de séries TV selon plusieurs critères
    // IN : $TxtLike recherche des series TV like ou non
    // IN : $TxtRecommandation recherche des séries TV dont l'utilisateur souhaite être recommander
    // OUT : nombre de séries TV
    public function serie_count($TxtLike, $TxtRecommandation){
        $req = $this->_db->query("SELECT count(s.num_serie) "
                                . "from $this->_serie s left join "
                                    . "(select * "
                                    . "from nonrecommandation "
                                    . "where num_user = '$this->_user') nr "
                                    . "on s.num_serie = nr.num_serie "
                                . "where 1 $TxtLike "
                                . "$TxtRecommandation ");
        return $data['0'] = $req->fetch();
    }
    
    // requete : selectionne les séries TV les plus aimées par tous les utilisateurs
    // IN : $limit nombre de tuple à retourner
    // OUT : requete de selection des séries TV les plus aimées
    public function serie_top_coeur($limit){
        return $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                        . "from $this->_serie s "
                        . "left join voir v2 "
                            . "on s.num_serie = v2.num_serie "
                        . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                            . "on s.num_serie = nr.num_serie "
                        . "left join (select * from voir where num_user = '$this->_user') v "
                            . "on s.num_serie = v.num_serie "
                        . "group by s.num_serie "
                        . "order by count(v2.num_serie) desc, rand() "
                        . "limit $limit;");
    }
    
    // requete : selectionne les séries TV les plus recommandées par tous les utilisateurs
    // IN : $limit nombre de tuple à retourner
    // OUT : requete de selection des séries TV les plus recommandées
    public function serie_top_recommandation($limit){
        return $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                                . "from $this->_serie s "
                                . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                                    . "on s.num_serie = nr.num_serie "
                                . "left join (select * from voir where num_user = '$this->_user') v "
                                    . "on s.num_serie = v.num_serie "
                                . "group by s.num_serie "
                                . "order by count(nr.num_serie) asc, rand() "
                                . "limit $limit;");
    }
    
    // requete : selectionne les séries TV qui correspond le plus aux goût de l'utilisateur
    // c'est à dire les séries TV like par l'utilisateur
    // et les mots-clés recherchés par l'utilisateur
    // IN : $TxtLike recherche des series TV like ou non
    // IN : $TxtRecommandation recherche des séries TV dont l'utilisateur souhaite être recommander
    // IN : $TxtOrder ordre de restitution des valeurs
    // IN : $limit nombre de tuple à retourner
    // OUT : requete de selection des séries TV recommandées
    public function recommandation($TxtLike, $TxtRecommandation, $TxtOrder, $limit){
        return $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                    . "from $this->_serie s "
                    . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                        . "on s.num_serie = nr.num_serie "
                    . "left join (select * from voir where num_user = '$this->_user') v "
                        . "on s.num_serie = v.num_serie "
                    . "where (s.num_serie in (SELECT r1.num_serie from (SELECT s.num_serie " 
                        . "from $this->_serie s "
                        . "join appartenir a "
                            . "on s.num_serie = a.num_serie "
                        . "join interesser i"
                            . " on a.num_motcle = i.num_motcle "
                        . "where i.dateDerniereSaisie >= DATE_ADD(NOW(), INTERVAL -30 DAY) "    // mots-clés recherchés par l'utilisateur les 30 derniers jours
                        . "and i.num_user = '$this->_user' "
                        . "group by s.num_serie "
                        . "order by count(*) desc"
                        . " limit $limit ) as r1) "
                    . "or s.num_serie in (SELECT r2.num_serie "
                                        . "from (SELECT s.num_serie "
                                                . "FROM appartenir a "
                                                . "join $this->_serie s "
                                                . "on s.num_serie = a.num_serie "
                                                . "WHERE a.num_motcle in (select a2.num_motcle "
                                                                        . "from appartenir a2 "
                                                                        . "join voir v2 "
                                                                            . "on v2.num_serie = a2.num_serie "
                                                                        . "where v2.num_user = '$this->_user' "
                                                                        . "and v2.num_serie not in (select num_serie "
                                                                                                    . "from nonrecommandation "
                                                                                                    . "where num_user = v2.num_user) ) "
                                                . "group by s.num_serie "
                                                . "order by count(*) desc "
                                                . "limit $limit ) as r2 )) "
                    . "$TxtLike "
                    . "group by s.num_serie "
                    . "$TxtRecommandation "
                    . "order by count(nr.num_serie), $TxtOrder "
                    . "limit $limit;");
    }
    
    // requete : selectionne les 5 séries TV qui correspond le plus à une série TV
    // IN : $num_serie numéro unique de la série TV
    // OUT : requete de selection des séries TV ressemblant à la série TV
    public function recommandation_serie($num_serie){
        return $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                    . "from appartenir a, $this->_serie s "
                    . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                        . "on s.num_serie = nr.num_serie "
                    . "left join (select * from voir where num_user = '$this->_user') v "
                        . "on s.num_serie = v.num_serie "
                    . "WHERE a.num_motcle in (select num_motcle "
                                . "from appartenir "
                                . "where num_serie = '$num_serie') "
                    . "and s.num_serie <> '$num_serie' "
                    . "and s.num_serie = a.num_serie "
                    . "group by s.num_serie "
                    . "ORDER BY count(*) DESC "
                    . "limit 4;");
    }
    
    // requete : vérification si l'utilisateur a entré ses goûts (like ou recherche)
    // OUT : retourne 1 si l'utilisateur a entré ses goûts (like ou recherche) sinon 0
    public function recommandation_exist(){
        // Nombre de mots-clés recherchés par l'utilisateur
        $reqRec = $this->_db->query("Select count(*) "
                                . "from interesser "
                                . "where num_user = '$this->_user';");
        $dataRec = $reqRec->fetch();
        // Nombre de série TV like par l'utilisateur
        $reqRec2 = $this->_db->query("Select count(*) "
                                    . "from voir "
                                    . "where num_user = '$this->_user';");
        $dataRec2 = $reqRec2->fetch();
        if($dataRec['0'] > 0 or $dataRec2['0'] > 0){
            return 1;
        } else{
            return 0;
        }
    }
    
    // requete : selectionne toutes les séries TV selon plusieurs critères
    // IN : $TxtLike recherche des series TV like ou non
    // IN : $TxtSearch recherche de mots-clés spécifiques liés à des séries TV
    // IN : $TxtRecommandation recherche des séries TV dont l'utilisateur souhaite être recommander
    // IN : $TxtOrder ordre de restitution des valeurs
    // OUT : requete de selection des séries TV
    public function search($TxtLike, $TxtSearch, $TxtRecommandation, $TxtOrder){
        return $this->_db->query("SELECT s.*, count(v.num_user) as 'like', count(nr.num_user) as 'nr' "                                
                            . "from $this->_serie s "
                            . "left join (select * from nonrecommandation where num_user = '$this->_user') nr "
                                . "on s.num_serie = nr.num_serie "
                            . "left join (select * from voir where num_user = '$this->_user') v "
                                . "on s.num_serie = v.num_serie "
                            . "where 1 "
                            . "$TxtLike "
                            . "$TxtSearch "
                            . "group by s.titre "
                            . "$TxtRecommandation "
                            . "order by count(nr.num_serie) asc, $TxtOrder; ");
    }
    
    // requete : compte le nombre de séries TV selon plusieurs critères
    // IN : $TxtLike recherhce des series TV like ou non
    // IN : $TxtSearch recherche de mots-clés spécifiques liés à des séries TV
    // IN : $TxtRecommandation recherche des séries TV dont l'utilisateur souhaite être recommander
    // OUT : nombre de séries TV
     public function search_count($TxtLike, $TxtSearch, $TxtRecommandation){
        $req = $this->_db->query("SELECT count(*) "
                                . "from $this->_serie s left join (select * from nonrecommandation where num_user = '$this->_user') nr on s.num_serie = nr.num_serie "
                                . "where 1 "
                                . "$TxtLike "
                                . "$TxtSearch "
                                . "group by s.titre "
                                . "$TxtRecommandation ");
        $data = $req->fetch();
        return $data['0'];
    }
    
    // requete : insert dans la table "voir" le like de la série TV de l'utilisateur
    // IN : $num_serie numéro unique de la série TV 
    public function serie_like_insert($serie){
        $this->_db->query("INSERT INTO voir "
                        . "values('$this->_user','$serie');");
    }
    // requete : supprime dans la table "voir" le like de la série TV de l'utilisateur
    // IN : $num_serie numéro unique de la série TV 
    public function serie_like_delete($serie){
        $this->_db->query("DELETE from voir "
                        . "where num_user='$this->_user' "
                        . "and num_serie='$serie';");
    }
    
    // requete : insert dans la table "NonRecommandation" le souhait de l'utilisateur de ne pas être recommander par rapport à cette série TV
    // IN : $num_serie numéro unique de la série TV 
    public function serie_nonRecommandation_insert($serie){
        $this->_db->query("INSERT INTO NonRecommandation "
                        . "values('$this->_user','$serie');");
    }
    // requete : supprime dans la table "NonRecommandation" le souhait de l'utilisateur de ne pas être recommander par rapport à cette série TV
    // IN : $num_serie numéro unique de la série TV 
    public function serie_nonRecommandation_delete($serie){
        $this->_db->query("DELETE from NonRecommandation "
                        . "where num_user='$this->_user' "
                        . "and num_serie='$serie';");
    }
    
    // requete : selectionne 150 mots-clés que les utilisateurs ont recherché
    // OUT : 150 mots-clés que les utilisateurs ont recherché
    public function interesser_motcle(){
        return $this->_db->query("SELECT m.motcle, count(i.nbChercher) "
                            . "from motcle m, interesser i "
                            . "where m.num_motcle = i.num_motcle "
                            . "group by m.motcle "
                            . "ORDER BY RAND() "
                            . "LIMIT 150;");
    }
    
    // requete : compte le nombre total de mots-clés que les utilisateurs ont recherché
    // OUT : nombre total de mots-clés que les utilisateurs ont recherché
    public function interesser_motcle_count(){
        $req = $this->_db->query("SELECT count(nbChercher) "
                                . "from interesser "
                                . "group by num_motcle "
                                . "order by sum(nbChercher) "
                                . "desc limit 1;");
        $data = $req->fetch();
        return $data['0'];
    }
    
    // requete : insert dans la table "contact" le message de l'utilisateur
    // IN : $pseudo pseudo de l'utilisateur
    // IN : $email email de l'uitlisateur
    // IN : $sujet sujet du message
    // IN : $texte texte du message
    public function user_contact($pseudo, $email, $sujet, $texte){
        $date = date("Y-m-d h:m:s");
        $this->_db->query("INSERT INTO contact values('$this->_user', '$date','$pseudo','$email','$sujet','$texte', false);");
    }
    
    // requete : mise à jour des données de l'utilisateur avec les reponse du questionnaire
    // IN : $questionX reponse de l'utilisateur à la question X
    // IN : $commentaire commentaire du questionnaire de l'utilisateur
    public function questionnaire($question1, $question2, $question3, $question4, $question5, $question6, $question7, $commentaire){
        $this->_db->query("UPDATE utilisateur "
                        . "set question_1 = $question1, "
                        . "question_2 = $question2, "
                        . "question_3 = $question3, "
                        . "question_4 = $question4, "
                        . "question_5 = $question5, "
                        . "question_6 = $question6, "
                        . "question_7 = $question7, "
                        . "question_commentaire = '$commentaire', "
                        . "question_date = now() "
                        . "where num_user = '$this->_user';");
    }
    
    // requete : vérifie si l'utilisateur a déjà remplit le questionnaire
    // OUT : renvoie 1 si l'utilisateur a déjà remplit le questionnaire, sinon 0
    public function questionnaire_exist(){
        $req = $this->_db->query("Select count(*) "
                        . "from utilisateur "
                        . "where num_user = '$this->_user' "
                        . "and question_date is not null;");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function motexclu(){
        return $this->_db->query("SELECT * "
                                . "from exclusion;");
    }
    
    public function search_motcle($motcle){
        $req = $this->_db->query("SELECT num_motcle "
                                . "from motcle "
                                . "where motcle = '$motcle';");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function interesser_nbChercher($motcle){
        $req = $this->_db->query("SELECT nbChercher "
                                . "from interesser "
                                . "where num_user = '$this->_user' "
                                . "and num_motcle = '$motcle';");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function interesser_insert($num_motcle){
        $this->_db->query("Insert into interesser "
                        . "values('$this->_user','$num_motcle', 1, DATE(NOW()));");
    }
    
    public function interesser_update($num_motcle){
        $this->_db->query("UPDATE interesser "
                        . "set nbChercher = nbChercher + 1, "
                        . "DateDerniereSaisie = DATE(NOW()) "
                        . "where num_user = '$this->_user' "
                        . "and DateDerniereSaisie <> DATE(NOW()) "
                        . "and num_motcle = '$num_motcle';");
    }
}