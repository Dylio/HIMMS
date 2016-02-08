<?php
// Cette classe permet l'interaction avec la base de données.
class class_db {
    private $_db;       // instance de connexion à la bd
     
    // Constructeur de la classe class_db
    // IN : $pageIndexFirst boolean true si la page est IndexFirst sinon false
    public function __construct() {
        require_once 'connectBDD.php';              // connection à la bd 
        $this->_db = Db::getInstance();             // création de l'intance de connexion
    }
    
    public function nb_user() { 
        $req = $this->_db->query("Select count(*) "
                                . "from utilisateur");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function user() { 
        return $this->_db->query("Select num_user, nbVisite "
                                . "from utilisateur");
    }
}