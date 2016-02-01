<?php require_once 'lang.php';
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
            require_once 'connectBDD.php';
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            require_once 'class_controleur.php';
            $bd = new class_db();
            $num_user = $bd->user(false);
            $affichage = new class_affichage($num_user);
            $req1 = Db::getInstance()->query("SELECT * from serie where num_serie = '".$_GET['num_serie']."';"); 
            $data = $req1->fetch();
        ?>
    </head>
    
    <body style='background: url("<?php echo $affichage->alea_Image($data['titre']); ?>") no-repeat center fixed; background-size: 100% auto !important'>
        
        <div class="jumbotron SerieDetailContainer">
            <form action="" method="POST" style="text-align:left;">
                <input type="hidden" name="Serie" value ="<?php echo $data['num_serie']?>"/>
                <input type="hidden" name="TitreSerie" value ="<?php echo $data['titre']?>"/>
                <?php $reqLike = Db::getInstance()->query("Select count(*) from voir a where num_user = '$num_user' and num_serie = '".$data['num_serie']."' ;");
                $dataLike = $reqLike->fetch();
                echo "<span class='TitreSerie'> ".$data['titre']."</span>";
                if($dataLike['0'] == 0){
                  echo '<b><button type="submit" class="btn btn-danger SerieDetailButtonB" name="Like"><span id="'.$data['titre'].'" class="glyphicon glyphicon-heart-empty"></button></span>';
                }else{
                  echo '<b><button type="submit" class="btn btn-primary SerieDetailButtonR" name="Like"><span id="'.$data['titre'].'" class="glyphicon glyphicon-heart"></button></span>';
                }?>
            </form>
        </div>
        <div class="jumbotron SerieDetailContainer2">
            <p>Produit par : <span class="txtDetailSerie">LP PROD</span><br/>
            Créée par : <span class="txtDetailSerie"><?php echo $data['créateurs']; ?></span><br/>
            Avec : <span class="txtDetailSerie"><?php echo $data['acteurs'].' ...'; ?></span><br/><br/>
            Date : <span class="txtDetailSerie">
                <?php if($data['dateD'] != null ){ echo $data['dateD'];  
                if($data['dateD'] == $data['dateF']){ echo ''; } else if($data['dateF'] != null and $data['dateD'] != null){ echo ' - '.$data['dateF']; } else{ echo ' - En production'; } } ?>
            </span><br/>
            Nationalité : <span class="txtDetailSerie"><?php echo $data['nationalite']; ?></span><br/>
            Genre : <span class="txtDetailSerie"><?php echo $data['genre']; ?></span><br/>
            Format : <span class="txtDetailSerie"><?php echo $data['nbSaison'].' saison';
                if($data['nbSaison'] > 1){ echo "s"; }
                echo ' répartis en '.$data['nbEpisode'].' épisodes de '.$data['format'].' min'; ?></span>
            </p>
        </div></div>
        <div class="jumbotron SerieDetailContainer2">
        <p style="text-align: center">Si vous aimez cette série, vous pourriez aimer ...</p>
        <?php $req = Db::getInstance()->query("SELECT s.titre, s.num_serie, s.dateD, s.dateF, s.synopsis, s.classification "
                . "FROM appartenir a join serie s on s.num_serie = a.num_serie "
                . "WHERE exists(select num_serie "
                            . "from appartenir "
                            . "where num_motcle = a.num_motcle "
                            . "and num_serie = '".$_GET['num_serie']."') "
                . "group by s.num_serie "
                . "ORDER BY count(*) DESC "
                . "limit 3 OFFSET 1;");
            $affichage->affichage_serie($req, null, $num_user, 'MediaObjectCaseG');
        ?></div>
        </div>    
    </body>
</html>