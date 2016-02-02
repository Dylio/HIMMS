<?php class class_db {
    private $_db;
    private $_serie;
    
    public function __construct() {
        require_once 'connectBDD.php';
        $this->_db = Db::getInstance();
        $reqRestriction = $this->_db->query("Select restriction from utilisateur where num_user = '".$_COOKIE['log']."';");
        $dataRestriction = $reqRestriction->fetch();
        if($dataRestriction['restriction'] == 1){
            $this->_serie = 'serietp';
        }else{
            $this->_serie = 'serie';
        }
    }
    
    
    public function serie($TxtSearch, $TxtLike, $TxtRecommandation, $TxtOrder){
        return $this->_db->query("SELECT s.num_serie, s.titre, s.dateD, s.dateF, s.synopsis, s.classification "
                                . "from $this->_serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "where 1 $TxtSearch $TxtLike "
                                . "group by s.num_serie "
                                . "$TxtRecommandation "
                                . "order by $TxtOrder");
    }
    
    public function serie_count($TxtLike, $TxtRecommandation){
        $req = $this->_db->query("SELECT count(s.num_serie) "
                                . "from $this->_serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "where 1 $TxtLike "
                                . "$TxtRecommandation ");
        return $data = $req->fetch();
    }
    
    public function serie_top_coeur($limit){
        return $this->_db->query("SELECT s.* "
                                . "from $this->_serie s left join voir v on s.num_serie = v.num_serie "
                                . "group by s.num_serie "
                                . "order by count(v.num_serie) desc, rand() "
                                . "limit $limit;");
    }
    
    public function serie_top_recommandation($limit){
        return $this->_db->query("SELECT s.titre, s.num_serie, s.dateD, s.dateF, s.synopsis, s.classification "
                                . "from $this->_serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "group by s.num_serie "
                                . "order by count(nr.num_serie) asc, rand() "
                                . "limit $limit;");
    }

    public function recommandation2($TxtLike, $TxtRecommandation, $TxtOrder, $limit, $num_user){
        $end_date = date('Y-m-d H:m:s', strtotime("-30 days"));
        return $this->_db->query("Select s.titre, s.num_serie, s.dateD, s.dateF, s.synopsis, s.classification "
                    . "from ((SELECT  s.titre, s.num_serie, s.dateD, s.dateF, s.synopsis, s.classification " 
                        . "from $this->_serie s join appartenir a on s.num_serie = a.num_serie "
                        . "join interesser i on a.num_motcle = i.num_motcle "
                        . "where i.dateDerniereSaisie > '$end_date' "
                        . "and i.num_user = '$num_user' "
                        . "group by s.num_serie "
                        . "order by count(*) desc limit $limit ) "
                    . " UNION"
                        . " (SELECT s.titre, s.num_serie, s.dateD, s.dateF, s.synopsis, s.classification " 
                        . "FROM appartenir a join $this->_serie s on s.num_serie = a.num_serie "
                        . "WHERE a.num_motcle in (select a2.num_motcle "
                                        . "from appartenir a2, voir v2 "
                                        . "where v2.num_user = '$num_user' "
                                        . "and v2.num_serie = a2.num_serie "
                                        . "and v2.num_serie not in (select num_serie "
                                                                    . "from nonrecommandation "
                                                                    . "where num_user = v2.num_user) ) "
                        . "group by s.num_serie "
                        . "order by count(*) desc limit $limit )) as s "
                    . "left join nonrecommandation nr on s.num_serie = nr.num_serie "
                    . "where 1 "
                    . "$TxtLike "
                    . "group by s.num_serie "
                    . "$TxtRecommandation "
                    . "order by count(nr.num_serie), $TxtOrder "
                    . "limit $limit;");
    }
    
    public function recommandation($TxtLike, $TxtRecommandation, $TxtOrder, $limit, $num_user){
         return $this->_db->query("SELECT s.titre, s.num_serie, s.dateD, s.dateF, s.synopsis, s.classification "
                    . "from $this->_serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                    . "where (s.num_serie in (SELECT r1.num_serie from (SELECT s.num_serie " 
                        . "from $this->_serie s join appartenir a on s.num_serie = a.num_serie "
                        . "join interesser i on a.num_motcle = i.num_motcle "
                        . "where i.dateDerniereSaisie >= DATE_ADD(NOW(), INTERVAL -30 DAY) "
                        . "and i.num_user = '$num_user' "
                        . "group by s.num_serie "
                        . "order by count(*) desc"
                        . " limit $limit ) as r1) "
                    . "or s.num_serie in (SELECT r2.num_serie from (SELECT s.num_serie "
                        . "FROM appartenir a join $this->_serie s on s.num_serie = a.num_serie "
                        . "WHERE a.num_motcle in (select a2.num_motcle "
                                        . "from appartenir a2 join voir v2 on v2.num_serie = a2.num_serie "
                                        . "where v2.num_user = '$num_user' "
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
    
    public function recommandation_exist($num_user){
        $reqRec = $this->_db->query("Select count(*) from interesser where num_user = '$num_user';");
            $dataRec = $reqRec->fetch();
        $reqRec2 = $this->_db->query("Select count(*) from voir where num_user = '$num_user';");
            $dataRec2 = $reqRec2->fetch();
        if($dataRec['0'] > 0 or $dataRec2['0'] > 0){
            return 1;
        } else{
            return 0;
        }
    }
    
    public function search($TxtLike, $TxtSearch, $TxtRecommandation, $TxtOrder){
        return $this->_db->query("SELECT s.num_serie, s.titre, s.dateD, s.dateF, s.synopsis, s.classification "
                                . "from $this->_serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "where 1 "
                                . "$TxtLike "
                                . "$TxtSearch "
                                . "group by s.titre "
                                . "$TxtRecommandation "
                                . "order by count(nr.num_serie) asc, $TxtOrder; ");
    }

     public function search_exist($TxtLike, $TxtSearch, $TxtRecommandation){
        $req = $this->_db->query("SELECT count(*) "
                                . "from $this->_serie s left join nonrecommandation nr on s.num_serie = nr.num_serie "
                                . "where 1 "
                                . "$TxtLike "
                                . "$TxtSearch "
                                . "group by s.titre "
                                . "$TxtRecommandation ");
        $data = $req->fetch();
        return $data['0'];
    }

    public function serie_like_exist($num_user, $serie){
        $req = $this->_db->query("Select count(*), s.titre "
                                . "from voir v, $this->_serie s "
                                . "where v.num_user = '$num_user' "
                                . "and s.num_serie = v.num_serie "
                                . "and s.num_serie = '$serie';");
        $data = $req->fetch();
        return $data['0'];
    }
    public function serie_like_insert($num_user, $serie){
        $this->_db->query("INSERT INTO voir values('$num_user','$serie');");
    }
    public function serie_like_delete($num_user, $serie){
        $this->_db->query("DELETE from voir where num_user='$num_user' and num_serie='$serie';");
    }
    
    public function serie_nonRecommandation_exist($num_user, $serie){
        $req = $this->_db->query("Select count(*), s.titre "
                                . "from NonRecommandation r, serie s "
                                . "where r.num_user = '$num_user' "
                                . "and s.num_serie = r.num_serie "
                                . "and s.num_serie = '$serie';");
        $data = $req->fetch();
        return $data['0'];
    }
    public function serie_nonRecommandation_insert($num_user, $serie){
        $this->_db->query("INSERT INTO NonRecommandation values('$num_user','$serie');");
    }
    public function serie_nonRecommandation_delete($num_user, $serie){
        $this->_db->query("DELETE from NonRecommandation where num_user='$num_user' and num_serie='$serie';");
    }
    public function user_contact($num_user, $pseudo, $email, $sujet, $texte){
        $this->_db->query("INSERT INTO contact values('$num_user', 'now()','$pseudo','$email','$sujet','$texte', false);");
    }
    public function questionnaire($num_user, $question1, $question2, $question3, $question4, $question5, $question6, $question7, $commentaire){
        $this->_db->query("UPDATE utilisateur "
                        . "set question_1 = $question1, "
                        . "question_2 = $question2, "
                        . "question_3 = $question3, "
                        . "question_4 = $question4, "
                        . "question_5 = $question5, "
                        . "question_6 = $question6, "
                        . "question_7 = $question7, "
                        . "question_commentaire = $commentaire, "
                        . "question_date = now() "
                        . "where num_user = $num_user;");
    }
    
    public function user($restriction){ 
        if(isset($_COOKIE['log'])){
            $num_user = $_COOKIE['log'];
            setcookie('log', $num_user , time() + 365*24*3600, null, null, false, true);
            if(!isset($_COOKIE['PHPSESSID'])){
                $this->_db->query("UPDATE utilisateur set nbVisite = nbVisite + 1 where num_user = '$num_user' ;");
                session_start();
            }else{
                session_start();
            }
        }else{
            $num_user = uniqid(rand(), true);
            setcookie('log', $num_user , time() + 365*24*3600, null, null, false, true);
            $this->_db->query("INSERT INTO utilisateur(num_user, nbVisite, restriction) values('$num_user', 1, null);");
            session_start();
        }
        $reqRestriction = $this->_db->query("Select restriction from utilisateur where num_user = '$num_user';");
        $dataRestriction = $reqRestriction->fetch();
        if($dataRestriction['restriction'] == NULL && $restriction == 'false'){
            header("Location:index_First.php");
        }
        return $num_user;
    }
}