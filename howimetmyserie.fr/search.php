<?php if(isset($_POST['ok'])){
    header("Location:Search.php?mc=".$_GET['mc']." ".$_POST['search']);
    exit;
}
// constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <?php include_once 'incl_import.php'; ?>
    </head>
    
    <body <?php echo $affichage->alea_Image_Fond(); ?> >
        <?php $affichage->affichage_titrePartie($str['search']['title'].' : </p><p class="MCRecherche">"'.$_GET['mc'].'"</p>');
        $affichage->like_recommandation();
        
        if(isset($_GET['mc'])){
            $_SESSION['SerieTV']->controleur();
            $affichage->vue_tri($_SESSION['SerieTV']->getLike(),
                    $_SESSION['SerieTV']->getOrder(),
                    $_SESSION['SerieTV']->getRecommandation(),
                    $_SESSION['SerieTV']->getMediaObject(),
                    null,
                    $str['search']['input_search']);
            $mc = class_controleur::no_special_character($_GET['mc']);
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
                $txtSearch = $txtSearch." and s.num_serie in ( select num_serie from appartenir where num_motcle = '".$dataMotCle['0']."' )";
            }
            $req=$db->search($_SESSION['SerieTV']->getTxtLike(),
                $txtSearch,
                $_SESSION['SerieTV']->getTxtRecommandation(),
                $_SESSION['SerieTV']->getTxtOrder());
            $SearchNb=$db->search_count($_SESSION['SerieTV']->getTxtLike(),
                $txtSearch,
                $_SESSION['SerieTV']->getTxtRecommandation());
            $affichage->affichage_serie($req, $SearchNb, $_SESSION['SerieTV']->getMediaObject(), true);
        } ?>
    </body>
</html>