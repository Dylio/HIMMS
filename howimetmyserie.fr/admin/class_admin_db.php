<?php
// Cette classe permet l'interaction avec la base de données.
class class_admin_db {
    private $_db;       // instance de connexion à la bd
     
    // Constructeur de la classe class_db
    // IN : $pageIndexFirst boolean true si la page est IndexFirst sinon false
    public function __construct() {
        require_once '../connectBDD.php';              // connection à la bd 
        $this->_db = Db::getInstance();             // création de l'intance de connexion
    }
    
    // requete : selectionne toutes les séries TV selon plusieurs critères
    // IN : $TxtSearch recherche des séries TV spécifiques
    // IN : $TxtOrder ordre de restitution des valeurs
    // OUT : requete de selection des séries TV
    public function serie($TxtSearch, $TxtOrder){
        return $this->_db->query("SELECT s.num_serie, s.titre, s.dateD, s.dateF "
                                . "from serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "where 1 $TxtSearch "
                                . "group by s.num_serie "
                                . "order by $TxtOrder");
    }
    
    public function nb_serie(){
        $req = $this->_db->query("SELECT count(*) "
                                . "from serie");
        $data = $req->fetch();
        return $data['0'];
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
  
    public function messagerie($j) { 
        return $this->_db->query("Select * "
                                . "from contact "
                                . "where lu = $j "
                                . "order by dateContact desc;");
    }
  
    public function messagerie_nonlu() { 
        $req = $this->_db->query("Select count(*) "
                                . "from contact "
                                . "where lu = 0 ;");
        $data = $req->fetch();
        return $data['0'];
    }
  
    public function update_messagerie_lu($num, $num_user, $dateContact) { 
        return $this->_db->query("Update contact "
                                . "set lu = $num "
                                . "where num_user = '$num_user' "
                                . "and dateContact = '$dateContact';");
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
    
    public function num_serie($titre){
        $req = $this->_db->query("SELECT num_serie "
                                . "from serie "
                                . "where replace(UPPER(titre), ' ','') = replace(UPPER('$titre'),' ','');");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function update_serie($num_serie, $titre, $dateD, $dateF, $nationalite, $créateurs, $acteurs, $genre, $format, $nbSaison, $nbEpisode, $classification){
        if($dateF == 0){ $dateF=' null'; }else{$dateF = $dateF; }
        return $this->_db->query("UPDATE serie "
                . "SET titre='$titre', "
                . "dateD='$dateD', "
                . "dateF = '$dateF', "
                . "nationalite='$nationalite', "
                . "créateurs='$créateurs', "
                . "acteurs='$acteurs', "
                . "genre='$genre', "
                . "format='$format', "
                . "nbSaison='$nbSaison', "
                . "nbEpisode='$nbEpisode', "
                . "classification='$classification' "
                . "WHERE num_serie='$num_serie';");
    }   
    
    public function motexclu(){
        return $this->_db->query("SELECT * "
                                . "from exclusion;");
    }
    
    public function motcle($num_serie){
        return $this->_db->query("SELECT m.num_motcle, m.motcle, a.occurrence "
                                . "from motcle m, appartenir a "
                                . "where m.num_motcle = a.num_motcle "
                                . "and a.num_serie = '$num_serie' "
                                . "order by m.motcle;");
    }
    
    public function delete_motcle($num_serie, $num_motcle){
        $this->_db->query("DELETE FROM appartenir "
                        . "where num_motcle = '$num_motcle' "
                        . "and num_serie = '$num_serie';");
    }
    
    public function serie_SRT($num_serie){
        return $this->_db->query("SELECT num_serie, saison, episode, sum(vf), sum(vo) "
                                . "from("
                                    . "select num_serie, saison, episode, 1 as vf, 0 as vo "
                                    . "from soustitre "
                                    . "where num_serie = '$num_serie' "
                                    . "and version = 'VF' "
                                . "UNION ALL "
                                    . "select num_serie, saison, episode, 0 as vf, 1 as vo "
                                    . "from soustitre "
                                    . "where num_serie = '$num_serie' "
                                    . "and version = 'VO' "
                                . ") s1 "
                                . "group by num_serie, saison, episode "
                                . "order by 1 asc, 2 asc, 3 asc;");
    }
    
    public function srt_exist($idS, $saison, $episode, $version){
        $req = $this->_db->query("SELECT count(*) "
                                . "FROM SousTitre "
                                . "where num_serie = '$idS' "
                                . "and saison = '$saison' "
                                . "and episode = '$episode' "
                                . "and version = '$version';");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function insert_srt($idS, $saison, $episode, $version){
        return $this->_db->query("Insert into SousTitre "
                            . "values('$idS', '$saison', '$episode', '$version');");
    }
    
    public function motcle_exist($motcle){
        $req = $this->_db->query("SELECT count(*), num_motcle "
                                . "from MotCle "
                                . "where motcle = '$motcle' "
                                . "group by num_motcle;");
        $data = $req->fetch();
        return $data;
    }
    
    
    public function motcle_insert($idMC, $mc){
        return $this->_db->query("Insert into MotCle "
                                . "values('$idMC', '$mc');");
    }
    
    public function appartenir_insert($idMC, $idS, $occ){
        return $this->_db->query("Insert into Appartenir "
                                . "values('$idMC', '$idS', $occ);");
    }
    
    public function motcle_occ($idMC, $idS){
        $req = $this->_db->query("SELECT occurrence "
                                . "from Appartenir "
                                . "where num_motcle = '$idMC' "
                                . "and num_serie = '$idS';");
        $data = $req->fetch();
        return $data['0'];
    }
    
    public function update_appartenir($occ, $idMC, $idS){
        return $this->_db->query("Update Appartenir "
                                . "set occurrence = occurrence + $occ, "
                                . "where num_serie = '$idS' "
                                . "and num_motcle = '$idMC';");
    }
    
    public function optimiser_appartenir($idS){
        $this->_db->query("delete from appartenir "
                        . "where num_serie = '$idS' "
                        . "and occurrence <= (select count(*)/4 "
                                            . "from soustitre "
                                            . "where num_serie='$idS'"
                                            . "and version='VF');");
        $this->_db->query("delete from appartenir "
                        . "where num_serie = '$idS' "
                        . "and num_motcle in (select num_motcle "
                                            . "from motcle "
                                            . "where motcle in (select libelle "
                                                                . "from exclusion ));");
        
    }
    
    public function nb_episode($idS){
        $req1 = $this->_db->query("select count(*)/3 "
                        . "from soustitre "
                        . "where num_serie='$idS'"
                        . "and version='VF'");
        $data1 = $req1->fetch();        
        $req2 = $this->_db->query("select count(*)/3 "
                        . "from soustitre "
                        . "where num_serie='$idS'"
                        . "and version='VO'");
        $data2 = $req2->fetch();
        return max(array($data1[0], $data2[0]));
    }
    
    public function test_mc($mc){
        $this->_db->query("select occurrence from appartenir a, motcle m "
                        . "where a.num_motcle = a.num_motcle "
                        . "and m.motcle = '$mc'");
    }
    
    public function admin($user, $mdp){
        $req = $this->_db->query("SELECT count(*) "
                                . "from admin "
                                . "where num_admin = '$user' "
                                . "and mdp = password('$mdp');");
        $data = $req->fetch();
        return $data[0];
    }
}