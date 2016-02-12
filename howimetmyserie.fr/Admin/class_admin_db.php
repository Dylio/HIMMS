<?php
// Cette classe permet l'interaction avec la base de données.
class class_admin_db {
    private $_db;       // instance de connexion à la bd
     
    // Constructeur de la classe class_db
    // IN : $pageIndexFirst boolean true si la page est IndexFirst sinon false
    public function __construct() {
        require_once 'connectBDD.php';              // connection à la bd 
        $this->_db = Db::getInstance();             // création de l'intance de connexion
    }
    
    // requete : selectionne toutes les séries TV selon plusieurs critères
    // IN : $TxtSearch recherche des séries TV spécifiques
    // IN : $TxtLike recherche des series TV like ou non
    // IN : $TxtRecommandation recherche des séries TV dont l'utilisateur souhaite être recommander ou non
    // IN : $TxtOrder ordre de restitution des valeurs
    // OUT : requete de selection des séries TV
    public function serie($TxtSearch, $TxtLike, $TxtRecommandation, $TxtOrder){
        return $this->_db->query("SELECT s.num_serie, s.titre, s.dateD, s.dateF "
                                . "from serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "where 1 $TxtSearch $TxtLike "
                                . "group by s.num_serie "
                                . "$TxtRecommandation "
                                . "order by $TxtOrder");
    }
    
    public function date_nb_user() { 
        $req = $this->_db->query("Select count(*), min(date_inscription), max(date_inscription) "
                                . "from utilisateur");
        $data = $req->fetch();
        return $data;
    }
    
    public function nb_user_inf_date($date) { 
        $req = $this->_db->query("SELECT count(*) as 'nb' "
                                . "FROM utilisateur "
                                . "WHERE date_inscription <= '$date'");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function nb_user_inf_year($year) { 
        $req = $this->_db->query("SELECT count(*) as 'nb' "
                                . "FROM utilisateur "
                                . "WHERE YEAR(date_inscription) <= '$year'");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function nb_user_sup_date($date) { 
        $req = $this->_db->query("SELECT count(*) as 'nb' "
                                . "FROM utilisateur "
                                . "WHERE date_inscription > '$date%'");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function nb_user_egal_date($date) { 
        $req = $this->_db->query("SELECT count(*) as 'nb' "
                                . "FROM utilisateur "
                                . "WHERE date_inscription like '$date%'");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function nb_user_visite_borne($inf, $sup) { 
        $req = $this->_db->query("SELECT count(*) as 'nb' "
                                . "FROM utilisateur "
                                . "WHERE nbVisite >= $inf "
                                . "AND nbVisite <= $sup;");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function nb_user_actif() { 
        $req = $this->_db->query("Select count(*) "
                                . "from utilisateur "
                                . "where date_derniere_visite >= DATE_ADD(NOW(), INTERVAL -30 DAY)"
                                . "and nbVisite > 5;");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function questionnaire_nb_user() { 
        $req = $this->_db->query("Select count(*) "
                                . "from utilisateur "
                                . "where question_date is not null "
                                . "order by question_date desc;");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function questionnaire_user() { 
        return $this->_db->query("Select question_1, question_2, question_3, question_4, question_5, question_6, question_7 "
                                . "from utilisateur "
                                . "where question_date is not null "
                                . "order by question_date desc;");
    }
    
    public function questionnaire_commentaire() { 
        return $this->_db->query("Select question_date, question_commentaire "
                                . "from utilisateur "
                                . "where question_date is not null "
                                . "and question_commentaire is not null "
                                . "order by question_date desc;");
    }
    
    public function questionnaire_resultat() { 
        $req = $this->_db->query("Select avg(question_1), avg(question_2), avg(question_3), avg(question_4), avg(question_5), avg(question_6), avg(question_7), avg(question_1)+avg(question_2)+avg(question_3)+avg(question_4)+avg(question_5)+avg(question_6)+avg(question_7) "
                                . "from utilisateur;");
        $data = $req->fetch();
        return $data;
    }
    
    // requete : selectionne toutes les séries selon plusieur critères
    // IN : $num_serie numéro unique d'une série TV
    // OUT : requete de selection d'une série TV
    public function une_serie($num_serie){
        $req = $this->_db->query("SELECT * "
                                . "from serie "
                                . "where num_serie = '$num_serie';");
        $data = $req->fetch();
        return $data;
    }
}