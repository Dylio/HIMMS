<?php class class_affichage {
    private $_str;
    
    public function __construct($num_user){
        require_once 'class_media_object.php';
        require_once 'lang.php';
        $this->_str = lang::getlang();
        echo '<script type="text/javascript">
            setTimeout( function(){
                document.getElementById("info").style.display = "none"; }, 3500);
        </script>';
        echo "<script type='text/javascript'> $(function () {
            $('[data-toggle='tooltip']').tooltip();
        })</script>";
        
        $this->menu_top();
        $this->menu_bottom($num_user);
    }
    
    public function affichage_titrePartie($partie){
        echo "<div class='jumbotron SerieDetailContainer'><p class='NomPartie'>$partie</p></div>";
    }
    
    public function alea_Image_Fond(){
        $dirname = './css/fond';
        if(file_exists($dirname) ){
            $j = 0;
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if(substr($file, -4) == ".jpg"){
                    $j++;
                }
            }
            closedir($dir);
            $nbImage = rand(1, $j);
            $j = 1;
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if($j === $nbImage and substr($file, -4) == ".jpg"){
                    return "style='background: url(./css/fond/$file) no-repeat center fixed !important; background-size: 100% 100% !important;'";
                }
                if(substr($file, -4) == ".jpg"){
                    $j++;
                }
            }
            closedir($dir);
        }
    }
    
    function alea_Image($titreSerie){
        $dirname = '.\Affiche\\'.$titreSerie;
        if(file_exists($dirname) ){
            $j = 0;
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if(substr($file, -4) == ".png"){
                    $j++;
                }
            }
            closedir($dir);
            $nbImage = rand(1, $j);
            $j = 1;
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if($j === $nbImage and substr($file, -4) == ".png"){
                    return 'Affiche/'.$titreSerie.'/'.$file;
                }
                if(substr($file, -4) == ".png"){
                    $j++;
                }
            }
            closedir($dir);
        }
    }       
    
    public function affichage_serie($req, $dataNb, $num_user, $media){
        echo '<div class="SerieContainerIner">';
        $i = 0;
        $media_object = new class_media_object($num_user);
        while($data = $req->fetch()){
           $media_object->newSerie($data);
           $media_object->media_object($media);
           $i++;
        }
        echo '</div>';
        $this->serie_warning($i, $dataNb);
    }
    
    public function affichage_serie2($req, $dataNb, $num_user, $media){
        echo '<div class="SerieContainerIner">';
        $i = 0;
        $media_object = new class_media_object($num_user);
        while($data = $req->fetch()){
           $media_object->newSerie($data);
           $media_object->media_object($media);
           $i++;
        }
        echo '</div>';
        $this->search_warning($i, $dataNb);
    }

    private function search_warning($i, $nb){
        if($i == 0 && $nb != 0){ 
            echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée avec les filtres actuels !"
            . "</div>"; 
        }
        if($i == 0 && $nb == 0){
            echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée ..."
            . "</div>";
        } 
    }

    private function serie_warning($i, $nb){
        if($i == 0 && $nb == 0 ){ 
            echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                . "Oups !<br/>Vous n'avez pas encore aimer de série.<br/>Revenez nous voir quand vous aurez fait votre choix !"
            . "</div>"; 
        }
        if($i == 0 && $nb == 0 ){
            echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                . "Wouah !<br/>Félicitation,vous avez aimez toutes nos séries !<br/>Nous sommes actuellement en train d'en rajouter.<br/>Revenez jeter un coup d'oeil de temps en temps."
            . "</div>";
        }
        if($i == 0 && $nb != 0){
            echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée..."
            . "</div>";
        } 
    }
    
    public function like_recommandation($num_user){
        $bd = new class_db();
        if(isset($_POST['Like'])){
            if($bd->serie_like_exist($num_user, $_POST['Serie']) == 0){
                $bd->serie_like_insert($num_user, $_POST['Serie']);
                echo '<div class="alert alert-success" id="info" role="alert"><span class="glyphicon glyphicon-ok"></span> La série "'.$_POST['TitreSerie'].'" appartient maintenant à vos séries favorites !<br/></div>';
            }else{
                $bd->serie_like_delete($num_user, $_POST['Serie']);
                echo '<div class="alert alert-danger" id="info" role="alert"><span class="glyphicon glyphicon-remove"></span> La série "'.$_POST['TitreSerie'].'" n\'appartient plus à vos séries favorites !<br/></div>';
            }
        }

        if(isset($_POST['Recommandation'])){
            if($bd->serie_nonRecommandation_exist($num_user, $_POST['Serie']) == 0){
                $bd->serie_nonRecommandation_insert($num_user, $_POST['Serie']);
                echo '<div class="alert alert-danger" id="info" role="alert"><span class="glyphicon glyphicon-remove"></span> La série "'.$_POST['TitreSerie'].'" n\'appartient plus à vos séries de recommandation !<br/></div>';
            }else{
                $bd->serie_nonRecommandation_delete($num_user, $_POST['Serie']);
                echo '<div class="alert alert-success" id="info" role="alert"><span class="glyphicon glyphicon-ok"></span> La série "'.$_POST['TitreSerie'].'" appartient maintenant à vos séries de recommandation !<br/></div>';
            }   
        } 
    }
    
    public function carouselle($num_user, $item1, $item2, $item3){ 
        require_once 'class_db.php';
        $bd= new class_db(); ?>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="6000" style="height: 380px;">
            <div class="carousel-inner" role="listbox" style="width:72%; margin-left: auto; margin-right : auto;">
                <?php if($item1){ ?>
                    <div class="item active">
                       <div class='IndexCarou'><a href='Recommandation.php'>VOUS POURRIEZ AIMER</a></div> 
                       <div class="SerieContainerIner" style="width: 100%;">
                       <?php
                       $req1=$bd->recommandation( null, null, 'rand()', 3, $num_user);
                       $this->affichage_serie($req1, null, $num_user, 'MediaObjectCaseG2');
                       ?>
                      </div>
                    </div>
                <?php }
                if($item2){ ?>
                    <div class="item <?php if(!$item1){ echo 'active'; } ?>">
                     <div class="IndexCarou"><a href='Serie.php'>TOP 9 <?php echo '<span id="$titre" style="color:red" class="glyphicon glyphicon-heart"></span>'; ?> SERIES TV </a></div>
                      <div class="SerieContainerIner" style="width: 100%">
                      <?php $req1=$bd->serie_top_coeur(9);
                      $this->affichage_serie($req1, null, $num_user, 'MediaObjectCaseP2'); ?>
                     </div>
                    </div>
                <?php }
                if($item3){ ?>
                    <div class="item <?php if(!$item1 && !$item2){ echo 'active'; } ?>">
                     <div class="IndexCarou"><a href='Serie.php'>TOP 9 <?php echo '<span id="$titre" style="color:red" class="glyphicon glyphicon-eye-open"></span>'; ?> SERIES TV </a></div>
                      <div class="SerieContainerIner" style="width: 100%">
                      <?php $req2=$bd->serie_top_recommandation(9);
                      $this->affichage_serie($req2, null, $num_user, 'MediaObjectCaseP2'); ?>
                     </div>
                    </div>
                <?php } ?>
            </div> 
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
              <?php if(($item1 && $item2) or ($item2 && $item3) or ($item1 && $item3)){ ?>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
              <?php }
              if($item1 && $item2 && $item3){ ?>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
              <?php } ?>
            </ol>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev" style="color : #57d1d1; width: 7% !important; padding-right: 15%;">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next" style="color : #57d1d1; width: 7% !important; padding-left: 15%;">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>
    <?php }
        
    public function menu_top(){ 
        ob_start(); ?>
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand navTitre" href="index.php"><?php echo $this->_str['site']['name2']; ?></a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-left">
                        <li class="dropdown">
                            <form class="navbar-form" role="search" action="Search.php" method="GET">
                                <div class="input-group">
                                    <input type="text" class="form-control" style="width:320px; height: 35px;" placeholder="Que recherchez-vous dans une série ?" name="mc" required>
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-default" name="btn" style=" height: 35px;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        <button data-toggle="dropdown" class="btn btn-default" role="button" style=" height: 35px;">
                                        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span></button>
                                        <ul class="dropdown-menu" role="menu" style="width: 600px; border-width: 3px; border-color: black; text-align: justify; overflow:hidden; white-space:nowrap; text-justify: inter-word;">
                                            <?php require_once 'class_db.php';
                                            $linkpdo = Db::getInstance();
                                            $reqNbT = $linkpdo->query("SELECT count(i.num_motcle) from interesser i;");
                                            $dataNbT = $reqNbT->fetch();
                                            $req = $linkpdo->query("SELECT m.motcle, sum(a.occurrence), count(i.num_motcle) from appartenir a, motcle m, interesser i, serie s where s.num_serie = a.num_serie and i.num_motcle = m.num_motcle and a.num_motcle = m.num_motcle group by m.motcle ORDER BY RAND() LIMIT 150;");
                                            while($data = $req->fetch()){
                                               echo '<a href="Search.php?mc='.$data['0'].'&btn=Valider"><FONT size="'.($data['2']/$dataNbT['0']).';">'.$data['0'].'</font></a> ';
                                            } ?>
                                        </ul> 
                                    </div>
                                </div>
                            </form>
                        </li>
                        <li class="dropdown">
                            <a href="recommandation.php">Recommandation</a>
                        </li>
                        <li class="dropdown">
                            <a href="serie.php">Séries TV</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    <?php }
    
    public function menu_bottom($num_user){ ?>
        <nav class="navbar navbar-inverse navbar-fixed-bottom">
            <div class="container-fluid">
                <div class="navbar-header">
                    <span class="ItemMenuBas" style="color:whitesmoke" >@2015 howimetmyserie</span>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-nav2 navbar-right">
                        <li class="dropdown">
                            <a href="#" type="button" class="ItemMenuBas" data-toggle="modal" data-target="#exampleModal">Contact</a>
                        </li>
                        <li class="dropdown">
                            <a class="ItemMenuBas">Donnez votre avis sur le site</a>
                        </li>
                        <li class="dropdown">
                            <a href="cgu.php" class="ItemMenuBas">CGU</a>
                        </li>
                        <li class="dropdown">
                            <a href="#" type="button" class="ItemMenuBas" data-toggle="modal" data-target="#exampleModal2">Plan du site</a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title NomPartie2" id="exampleModalLabel">Nouveau message</h4>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="recipient-name" class="control-label">Pseudo :</label>
                                <input type="text" name="pseudo" class="form-control" id="recipient-name" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient-mail" class="control-label">E-Mail :</label>
                                <input type="email" name="email" class="form-control" id="recipient-mail" maxlength="255" required>
                            </div>
                            <div class="form-group">
                                <label for="recipient-sujet" class="control-label">Sujet :</label>
                                <input type="text" name="Sujet" class="form-control" id="recipient-sujet" maxlength="255" required>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="control-label">Message :</label>
                                <textarea name="Message" class="form-control" id="message-text" maxlength="2000"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-dismiss="modal">Annuler</button>
                            <input type="submit" name="Envoyer" value="Envoyer" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if(isset($_POST['Envoyer'])){
            require_once 'class_db.php';
            $bd= new class_db();
            $bd->user_contact($num_user, $_POST['pseudo'], $_POST['email'], $_POST['Sujet'], $_POST['Message']);
        }
        ?>

        <div class="modal fade bs-example-modal-lg" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title NomPartie2" id="exampleModalLabel">Plan du Site</h4>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body" style="text-align: center;">
                            <img style="margin-left:auto; margin-right:auto; width:850px; height: 500px" src="Page Aide.png">
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <p>One fine body&hellip;</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    <?php }
    
    public static function no_special_character($chaine){
        $a = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
        $b = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
        $chaine = utf8_encode(strtr(utf8_decode($chaine), utf8_decode($a), utf8_decode($b)));
        // Ecrit la chaîne de caractères en miniscule
        $chaine = strtolower($chaine);
        // Supprime la ponctuation de la chaîne de caractères
        $carac = array('.' ,'!' ,'?' ,'-->' ,',' ,'<i>' ,'</i>' ,':' ,'"','|' ,'\'' ,'"' ,'-' ,';' ,'_' ,'&' ,'>' ,'<', '$', '\\', '/', '$', '€', '£', '+', '=', '[', ']', '*', '(', ')', '{', '}');
        $chaine = str_replace ($carac, ' ', $chaine);
        $chaine = str_replace ('œ', 'oe', $chaine);
        $chaine=trim($chaine);
        return $chaine;
    }
}