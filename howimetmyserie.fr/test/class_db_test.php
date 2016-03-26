<?php
require_once '../class_controleur.php';    // afficahge et gestion des controleurs
require_once '../class_db.php';            // base de données
class class_db_test {
    private  $_class_db;
    private  $_db;
    
    const id_test = 'testdb';
    const value_test = 'testdb';
    const id_test2 = 'testdb2';
    const value_test2 = 'testdb2';
    const id_test3 = 'testdb3';
    const value_test3 = 'testdb3';
    const id_test4 = 'testdb4';
    const value_test4 = 'testdb4';
    
    const ok = '<span style="color:green">OK</span>';
    const no = '<span style="color:red">NO</span>';
    
    public function __construct() {
        require_once 'connectBDD.php';
        $this->_db = Db::getInstance();
        assert_options(ASSERT_WARNING,false);
    }

    private function setUp() {
        $this->setDown();
        $this->motcle_insert(self::id_test, self::value_test);
        $this->motcle_insert(self::id_test2, self::value_test2);
        $this->motcle_insert(self::id_test3, self::value_test3);
        $this->serie_insert(self::id_test, self::value_test);
        $this->serie_insert(self::id_test2, self::value_test2);
        $this->serie_insert(self::id_test3, self::value_test3);
        $this->serie_insert(self::id_test4, self::value_test4);
        $this->appartenir_insert(self::id_test, self::id_test);
        $this->appartenir_insert(self::id_test2, self::id_test);
        $this->appartenir_insert(self::id_test, self::id_test2);
        $this->appartenir_insert(self::id_test2, self::id_test3);
        $this->appartenir_insert(self::id_test3, self::id_test3);
        $this->appartenir_insert(self::id_test3, self::id_test4);
        if(isset($this->_class_db)){
            unset($this->_class_db);
        }
        $this->_class_db = new class_db();
        $this->_class_db->setUser(self::id_test);
        $this->_class_db->user_insert();
    }

    private function setDown() {
        $this->user_delete(self::id_test);
        $this->interesser_delete(self::id_test);
        $this->appartenir_delete(self::id_test);
        $this->appartenir_delete(self::id_test2);
        $this->appartenir_delete(self::id_test3);
        $this->serie_delete(self::id_test);
        $this->serie_delete(self::id_test2);
        $this->serie_delete(self::id_test3);
        $this->serie_delete(self::id_test4);
        $this->motcle_delete(self::id_test);
        $this->motcle_delete(self::id_test2);
        $this->motcle_delete(self::id_test3);
    }

    public function test() {
        echo '<script src="../js/jquery.js"></script>';
        echo '<script src="../js/bootstrap.min.js"></script>';
        echo '<link href="../css/bootstrap.min.css" rel="stylesheet">';
        
        echo "<table class='table table-striped' style='width:85%; margin: 15px auto;'> "
            . "<tr style='font-size:23px' ><td class='alert-info'><b>Test</b></td>";
                
        echo "<td class='alert-info'><b>Résultat</b></td></tr>";
            $this->test_up("Création d'un utilisateur", $this->test_user());
            $this->test_up("Nombre de visite d'un utilisateur à la création", $this->test_user_visite_first());
            $this->test_up("Incrémentation du nombre de visite d'un utilisateur", $this->test_user_visite_incrementation());
            $this->test_up("Restriction d'âge d'un utilisateur", $this->test_user_visite_restriction_oui());
            $this->test_up("Aucune restriction d'âge d'un utilisateur", $this->test_user_visite_restriction_non());

        echo '<tr><td class="alert-success"  colspan="2"><br/><b>QUESTIONNAIRE</b></td></tr>';
            $this->test_up("Vérification questionnaire : non complété", $this->test_questionnaire_non());
            $this->test_up("Vérification questionnaire : complété", $this->test_questionnaire_oui());
            $this->test_up("Vérification valeur du questionnaire", $this->test_questionnaire_rep());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>MOTS-CLES</b></td></tr>';
            $this->test_up("Recherche d'un identifiant d'un mot-clé à partir d'un mot", $this->test_motcle());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>FONCTION LIKE</b></td></tr>';
            $this->test_up("Serie TV fonction like : non (par défaut)", $this->test_like_defaut());
            $this->test_up("Serie TV fonction like : oui", $this->test_like_oui());
            $this->test_up("Serie TV fonction like : non", $this->test_like_non());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>FONCTION NON RECOMMANDATION</b></td></tr>';
            $this->test_up("Serie TV fonction non recommandation : non (par défaut)", $this->test_nonrecommandation_defaut());
            $this->test_up("Serie TV fonction non recommandation : oui", $this->test_nonrecommandation_oui());
            $this->test_up("Serie TV fonction non recommandation : non", $this->test_nonrecommandation_non());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>FONCTION INTERESSER</b></td></tr>';
            $this->test_up("Fonction interesser mot-clé : non (par défaut)", $this->test_interesser_defaut());
            $this->test_up("Fonction interesser mot-clé : oui (première recherche)", $this->test_interesser_first());
            $this->test_up("Fonction interesser mot-clé : incrémentation (même jour que la précédente fonction interesser)", $this->test_interesser_incrementation_jour_egal());
            $this->test_up("Fonction interesser mot-clé : incrémentation (jour ≠ que la précédente fonction interesser)", $this->test_interesser_incrementation_jour_inf());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>SERIE TV</b></td></tr>';
            $this->test_up("Sélection d'une série via son identifiant", $this->test_une_serie());
            $this->test_up("Selection toutes les série TV avec ou sans filtre (recherche, recommandation, like)", $this->test_serie());
            $this->test_up("Restriction de l'utilisateur : aucune (par défaut)", $this->test_serie_restriction_defaut());
            $this->test_up("Restriction de l'utilisateur : serie", $this->test_serie_restriction_non());
            $this->test_up("Restriction de l'utilisateur : serietp", $this->test_serie_restriction_oui());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>RECHERCHE SERIE TV</b></td></tr>';
            $this->test_up("Recherche de serie à partir d'un mot-clé", $this->test_search_1mc());
            $this->test_up("Recherche de serie à partir de plusieur mots-clés", $this->test_search_2mc());
            $this->test_up("Recherche de serie à partir de plusieur mots-clés dont un mot exclu", $this->test_search_2mc_motexclu());
            $this->test_up("Recherche de serie à partir de plusieur mots-clés dont un mot inconnu", $this->test_search_2mc_motinconnu());

        echo '<tr><td  class="alert-success" colspan="2"><br/><b>RECOMMANDATION SERIE TV</b></td></tr>';
            $this->test_up("Recommandation : recherche", $this->test_recommandation_1search());
            $this->test_up("Recommandation : plusieurs recherches", $this->test_recommandation_2search());
            $this->test_up("Recommandation : plusieurs recherches top", $this->test_recommandation_2search_top());
            $this->test_up("Recommandation : like", $this->test_recommandation_1like());
            $this->test_up("Recommandation : like et recherche", $this->test_recommandation());
            $this->test_up("Recommandation : série tv", $this->test_recommandation_serie());
        echo "</table>";
        $this->setDown();
    }

    
    private function test_up($intitule, $fct) {
        echo "<tr><td>$intitule</td>"
            . "<td>$fct</td></tr>";
    }

    private function test_user() {
        $this->setUp();
        return (assert(self::id_test == $this->user_select(self::id_test)["num_user"]))? self::ok : self::no;
    }

    private function test_user_visite_first() {
        $this->setUp();
        return (assert(1 == $this->user_select(self::id_test)["nbVisite"]))? self::ok : self::no;
    }

    private function test_user_visite_incrementation() {
        $this->setUp();
        $this->_class_db->user_update_visite();
        return (assert(2 == $this->user_select(self::id_test)["nbVisite"]))? self::ok : self::no;
    }

    private function test_user_visite_restriction_oui() {
        $this->setUp();
        $this->_class_db->user_update_restriction(1);
        return (assert(1 == $this->user_select(self::id_test)["restriction"]))? self::ok : self::no;
    }

    private function test_user_visite_restriction_non() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        return (assert(0 == $this->user_select(self::id_test)["restriction"]))? self::ok : self::no;
    }
    
    private function test_questionnaire_non() {
        $this->setUp();
        return (assert(0 == $this->_class_db->questionnaire_exist()))? self::ok : self::no;
    }
    
    private function test_questionnaire_oui() {
        $this->setUp();
        $this->_class_db->questionnaire(1,1,1,1,1,1,1,"");
        $data = $this->_class_db->questionnaire_exist();
        return (assert(1 == $data))? self::ok : self::no;
    }
    
    private function test_questionnaire_rep() {
        $this->setUp();
        $this->_class_db->questionnaire(1,0,1,1,2,1,0,"test reponse questionnaire");
        $data = $this->user_select(self::id_test);
        return (assert($data['question_1'] == 1
                and $data['question_2'] == 0
                and $data['question_3'] == 1
                and $data['question_4'] == 1
                and $data['question_5'] == 2
                and $data['question_6'] == 1
                and $data['question_7'] == 0
                and $data['question_commentaire'] == "test reponse questionnaire"
                and $data['question_date'] == date("Y-m-d")))? self::ok : self::no;
    }
    
    
    private function test_une_serie() {
        $this->setUp();
        $req = $this->_class_db->une_serie(self::id_test);
        $data = $req->fetch();
        return (assert($data["titre"] == self::value_test))? self::ok : self::no;
    }
    
    private function test_serie() {
        $this->setUp();
        return (assert($this->test_serie1() == self::ok && $this->test_serie2() == self::ok && $this->test_serie3() == self::ok))? self::ok : self::no;
    }
    
    private function test_serie1() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $req = $this->_class_db->serie(null, null, null, 1);
        $data = $req->rowCount();
        return (assert($data == 132))? self::ok : self::no;
    }
    
    private function test_serie2() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_like_insert(self::id_test);
        $id = self::id_test;
        $req = $this->_class_db->serie(null, " and s.num_serie in (select num_serie from voir where num_user = '$id')", null, 1);
        $data = $req->rowCount();
        $this->_class_db->serie_like_delete(self::id_test);
        return (assert($data == 1))? self::ok : self::no;
    }
    
    private function test_serie3() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_nonRecommandation_insert(self::id_test);
        $req = $this->_class_db->serie(null,null, " having count(nr.num_serie) = 0 ",  1);
        $data = $req->rowCount();
        $this->_class_db->serie_nonRecommandation_delete(self::id_test);
        return (assert($data == 131))? self::ok : self::no;
    }
    
    private function test_serie_restriction_defaut() {
        $this->setUp();
        return (assert($this->_class_db->getSerieRestriction()== ""))? self::ok : self::no;
    }
    
    private function test_serie_restriction_non() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        return (assert($this->_class_db->getSerieRestriction()== "serie"))? self::ok : self::no;
    }
    
    private function test_serie_restriction_oui() {
        $this->setUp();
        $this->_class_db->user_update_restriction(1);
        $this->_class_db->restriction(true);
        return (assert($this->_class_db->getSerieRestriction()== "serietp"))? self::ok : self::no;
    }
    
    private function test_motcle() {
        $this->setUp();
        $this->motcle_insert(self::id_test, self::value_test);
        return (assert($this->_class_db->search_motcle(self::value_test) == self::id_test))? self::ok : self::no;
    }
    
    
    private function test_like_defaut() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        return (assert($this->_class_db->serie_like_exist(self::id_test)==0))? self::ok : self::no;
    }
    
    private function test_like_oui() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_like_insert(self::id_test);
        return (assert($this->_class_db->serie_like_exist(self::id_test)==1))? self::ok : self::no;
    }
    
    private function test_like_non() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_like_insert(self::id_test);
        $this->_class_db->serie_like_delete(self::id_test);
        return (assert($this->_class_db->serie_like_exist(self::id_test)==0))? self::ok : self::no;
    }
    
    private function test_nonrecommandation_defaut() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        return (assert($this->_class_db->serie_nonRecommandation_exist(self::id_test)==0))? self::ok : self::no;
    }
    
    private function test_nonrecommandation_oui() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_nonRecommandation_insert(self::id_test);
        return (assert($this->_class_db->serie_nonRecommandation_exist(self::id_test)==1))? self::ok : self::no;
    }
    
    private function test_nonrecommandation_non() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_nonRecommandation_insert(self::id_test);
        $this->_class_db->serie_nonRecommandation_delete(self::id_test);
        return (assert($this->_class_db->serie_nonRecommandation_exist(self::id_test)==0))? self::ok : self::no;
    }
    
    private function test_interesser_defaut() {
        $this->setUp();
        $data = $this->_class_db->interesser_nbChercher(self::id_test);
        return (assert(!isset($data)))? self::ok : self::no;
    }
    
    private function test_interesser_first() {
        $this->setUp();
        $this->_class_db->interesser_insert(self::id_test);
        $data = $this->_class_db->interesser_nbChercher(self::id_test);
        return (assert($data==1))? self::ok : self::no;
    }
    
    private function test_interesser_incrementation_jour_egal() {
        $this->setUp();
        $this->_class_db->interesser_insert(self::id_test);
        $this->_class_db->interesser_update(self::id_test);
        return (assert($this->_class_db->interesser_nbChercher(self::id_test)==1))? self::ok : self::no;
    }
    
    private function test_interesser_incrementation_jour_inf() {
        $this->setUp();
        $this->interesser_insert(self::id_test, self::id_test);
        $this->_class_db->interesser_update(self::id_test);
        return (assert($this->_class_db->interesser_nbChercher(self::id_test)==2))? self::ok : self::no;
    }
    
    private function test_search_1mc() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $value = self::value_test2;
        $txtSearch = $controleur->search_mc($this->_class_db, $value);
        $req = $this->_class_db->search($txtSearch ,null, null, 2);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && $data[1][1]==self::id_test3 && count($data)==2))? self::ok : self::no;
    }
    
    private function test_search_2mc() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $value = self::value_test.' '.self::value_test2;
        $txtSearch = $controleur->search_mc($this->_class_db, $value);
        $req = $this->_class_db->search($txtSearch ,null, null, 2);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && count($data)==1))? self::ok : self::no;
    }
    
    private function test_search_2mc_motexclu() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $value = self::value_test.' le '.self::value_test2;
        $txtSearch = $controleur->search_mc($this->_class_db, $value);
        $req = $this->_class_db->search($txtSearch ,null, null, 2);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && count($data)==1))? self::ok : self::no;
    }
    
    private function test_search_2mc_motinconnu() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $value = self::value_test.' valuetest4 '.self::value_test2;
        $txtSearch = $controleur->search_mc($this->_class_db, $value);
        $req = $this->_class_db->search($txtSearch ,null, null, 2);
        $data = $req->fetchAll();
        return (assert(count($data)==0))? self::ok : self::no;
    }
    
    private function test_recommandation_1search() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $controleur->search_mc($this->_class_db, self::value_test2);
        $req = $this->_class_db->recommandation(null ,null, 2, 10);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && $data[1][1]==self::id_test3 && count($data)==2))? self::ok : self::no;
    }
    
    private function test_recommandation_2search() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $controleur->search_mc($this->_class_db, self::value_test);
        $controleur->search_mc($this->_class_db, self::value_test2);
        $req = $this->_class_db->recommandation(null ,null, 2, 10);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && $data[1][1]==self::id_test2  && $data[2][1]==self::id_test3 && count($data)==3))? self::ok : self::no;
    }
    
    private function test_recommandation_2search_top() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $controleur->search_mc($this->_class_db, self::value_test);
        $controleur->search_mc($this->_class_db, self::value_test2);
        $req = $this->_class_db->recommandation(null ,null, 2, 1);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && count($data)==1))? self::ok : self::no;
    }
    
    private function test_recommandation_1like() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $this->_class_db->serie_like_insert(self::id_test4);
        $req = $this->_class_db->recommandation(null ,null, 2, 10);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test3 && $data[1][1]==self::id_test4 && count($data)==2))? self::ok : self::no;
    }
    
    private function test_recommandation() {
        $this->setUp();
        $controleur = new class_controleur(null, null);
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $value = self::value_test2;
        $controleur->search_mc($this->_class_db, $value);
        $this->_class_db->serie_like_insert(self::id_test);
        $req = $this->_class_db->recommandation(null ,null, 2, 10);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test && $data[1][1]==self::id_test2 && $data[2][1]==self::id_test3 && count($data)==3))? self::ok : self::no;
    }
    
    private function test_recommandation_serie() {
        $this->setUp();
        $this->_class_db->user_update_restriction(0);
        $this->_class_db->restriction(true);
        $req = $this->_class_db->recommandation_serie(self::id_test4);
        $data = $req->fetchAll();
        return (assert($data[0][1]==self::id_test3 && count($data)==1))? self::ok : self::no;
    }
    
    
    private function serie_insert($num_serie, $titre){
        $this->_db->query("INSERT INTO serie(num_serie, titre) "
                        . "values('$num_serie', '$titre');");
    }
    
    private function serie_delete($num_serie){
        $this->_db->query("DELETE FROM serie "
                        . "where num_serie = '$num_serie';");
    }
    
    private function motcle_insert($num_motcle, $motcle){
        $this->_db->query("INSERT INTO motcle(num_motcle, motcle) "
                        . "values('$num_motcle', '$motcle');");
    }
    
    private function motcle_delete($num_motcle){
        $this->_db->query("DELETE FROM motcle "
                        . "where num_motcle = '$num_motcle';");
    }
    
    private function interesser_insert($num_user, $num_motcle){
        $this->_db->query("Insert into interesser "
                        . "values('$num_user','$num_motcle', 1, DATE(NOW())- INTERVAL 1 DAY);");
    }
    
    private function interesser_delete($num_motcle){
        $this->_db->query("DELETE FROM interesser "
                        . "where num_motcle = '$num_motcle';");
    }
    
    private function appartenir_insert($num_motcle, $num_serie){
        $this->_db->query("Insert into appartenir "
                        . "values('$num_motcle','$num_serie', 1);");
    }
    
    private function appartenir_delete($num_motcle){
        $this->_db->query("DELETE FROM appartenir "
                        . "where num_motcle = '$num_motcle';");
    }
        
    public function user_delete($num_user){
        $this->_db->query("delete from utilisateur "
                        . "where num_user = '$num_user' ;");
    }
    
    public function user_select($num_user){
        $req = $this->_db->query("select * from utilisateur "
                        . "where num_user = '$num_user' ;");
        return $data = $req->fetch();
    }
}