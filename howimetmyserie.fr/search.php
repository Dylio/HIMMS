<?php if(isset($_POST['ok'])){
    header("Location:Search.php?mc=".$_GET['mc']." ".$_POST['search']);
    exit;
}
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>
        <?php
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            require_once 'class_controleur.php';
            $db = new class_db(false);
            $affichage = new class_affichage($db, $str);
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur($db->getUser(), $str);
            }
        ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php $affichage->affichage_titrePartie($str['search']['title'].' : </p><p class="MCRecherche">"'.$_GET['mc'].'"</p>');
        $affichage->like_recommandation();
        
        if(isset($_GET['mc'])){
            $_SESSION['SerieTV']->vue_trie($str['search']['input_search'], true);
            $mc = $affichage->no_special_character($_GET['mc']);
            $mc = explode(" ", $mc);
            $txtSearch  = '';
            $nbMC = 0;
            $k = -1;
            foreach ($mc as $i => $linetxt){
                $linkpdo = Db::getInstance();
                $reqMotCle = $linkpdo->query("SELECT num_motcle from motcle where motcle = '$linetxt';");
                $dataMotCle = $reqMotCle->fetch();
                $reqI = $linkpdo->query("SELECT count(*) from interesser where num_user = '".$db->getUser()."' and num_motcle = '".$dataMotCle['0']."';");
                $dataI = $reqI->fetch();
                if($dataI['0'] == 0){
                    $req = $linkpdo->query("Insert into interesser values('".$db->getUser()."','".$dataMotCle['0']."', 1, now());");
                }else{
                    $req = $linkpdo->query("UPDATE interesser set nbChercher = nbChercher + 1, DateDerniereSaisie = now() where num_user = '".$db->getUser()."' and DateDerniereSaisie <> now() and  num_motcle = '".$dataMotCle['0']."';"); 
                }
                $nbMC ++;
                $txtSearch = $txtSearch." and s.num_serie in ( select a.num_serie from appartenir a, motcle m where a.num_motcle = m.num_motcle and m.motcle = '$linetxt' )";
            }
            $req=$db->search($_SESSION['SerieTV']->getTxtLike(),
                $txtSearch,
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder());
            $SearchNb=$db->search_count($_SESSION['SerieTV']->getTxtLike(),
                $txtSearch,
                $_SESSION['SerieTV']->getTxtRecommandation());
            $affichage->affichage_serie2($req, $SearchNb, $_SESSION['SerieTV']->getMediaObject());
        } ?>
    </body>
</html>