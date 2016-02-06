<?php
// Cette classe permet l'affichage les données.
class class_affichage{
    private  $_str;             // constantes textuelles du site web
    private  $_db;              // instance vers la base de données
    
    // Constructeur de la classe class_media_object
    // IN : $db instance vers la base de données
    // IN : $str constantes textuelles du site web
    public function __construct($db, $str){
        require_once 'class_media_object.php';
        $this->_str = $str;
        $this->_db = $db;
        $this->menu_top();          // affichage du menu du haut
        $this->menu_bottom();       // affichage du menu du bas 
    }
    
    public function affichage_titrePartie($partie){
        echo "<div class='jumbotron SerieDetailContainer'><p class='NomPartie'>$partie</p></div>";
    }
    
    // Renvoie l'adresse relatif d'une image aléatoire pour le fond du site
    // OUT : adresse relatif d'une image aléatoire pour le fond du site
    public function alea_Image_Fond(){
        $dirname = './css/fond';                    // adresse relatif des fonds d'écran
        if(file_exists($dirname) ){                 // vérification que le dossier existe
            $j = 0;                                 // $j nombre de fond d'écran
            $dir = opendir($dirname);
            while($file = readdir($dir)) {          // compte le nombre d'affiche de la série TV
                if(substr($file, -4) == ".jpg"){    // les affiches doivent être tous sous format .jpg
                    $j++;
                }
            }
            closedir($dir);
            $aleaImage = rand(1, $j);                 // $aleaImage un nombre aléatoire entre 1 et $j
            $i = 1;                                   // $i numéro actuel de l'affiche de la série TV
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if($i === $aleaImage and substr($file, -4) == ".jpg"){      // tant que $i et $aleaImage ne sont pas égal, incrémenter $i
                    return "style='background: url(./css/fond/$file) no-repeat center fixed !important; "
                            . "background-size: 100% 100% !important;'";   // renvoie la $aleaImage fond d'écran
                }
                if(substr($file, -4) == ".jpg"){
                    $i++;
                }
            }
            closedir($dir);
        }
    }
    
    // Renvoie l'adresse relatif d'une image aléatoire de la série TV
    // OUT : adresse relatif d'une image aléatoire de la série TV
    function alea_Image($titreSerie){
        return class_media_object::alea_image($titreSerie);
    }       
    
    // Affiche toutes les séries TV resultant d'une requete
    // IN : $req requete
    // IN : $dataNb nombre d'éléments à afficher
    // IN : $media style d'objet
    public function affichage_serie($req, $dataNb, $media, $warning){
        echo '<div class="SerieContainerIner">';
        $i = 0;
        $media_object = new class_media_object($this->_db, $this->_str);
        while($data = $req->fetch()){
           $media_object->newSerieDetail($data);
           $media_object->media_object($media);
           $i++;
        }
        echo '</div>';
        // message si aucun élément en fonction du théorique et du réél
        if($warning == true){
            if($i == 0 && $nb != 0){    // message si aucun élément réél mais plusieur éléments théoriques : filtre actuel
            echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée avec les filtres actuels !"
            . "</div>"; 
            } else if($i == 0 && $nb == 0){   // message si aucun élément réél et théorique : aucun résultat
                echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                    . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée ..."
                . "</div>";
            }
        }
    }
    
    // Gestion des évenements quand un utilisateur change d'état une série TV
    public function like_recommandation(){
        // Gestion et Affichage un message d'alerte lorsqu'une série TV devient non recommandé ou pas par l'utilisateur
        if(isset($_POST['Like'])){
            if($this->_db->serie_like_exist($_POST['Serie']) == 0){
                $this->_db->serie_like_insert($_POST['Serie']);
                echo '<div class="alert alert-success" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-ok"></span> La série "'.$_POST['TitreSerie'].'" appartient maintenant à vos séries favorites !<br/>'
                . '</div>';
            }else{
                $this->_db->serie_like_delete($_POST['Serie']);
                echo '<div class="alert alert-danger" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-remove"></span> La série "'.$_POST['TitreSerie'].'" n\'appartient plus à vos séries favorites !<br/>'
                . '</div>';
            }
        }

        // Gestion et Affichage un message d'alerte lorsqu'une série TV devient non recommandé ou pas par l'utilisateur
        if(isset($_POST['Recommandation'])){
            if($this->_db->serie_nonRecommandation_exist($_POST['Serie']) == 0){
                $this->_db->serie_nonRecommandation_insert($_POST['Serie']);
                echo '<div class="alert alert-danger" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-remove"></span>'
                    . 'La série "'.$_POST['TitreSerie'].'" n\'appartient plus à vos séries de recommandation !<br/>'
                . '</div>';
            }else{
                $this->_db->serie_nonRecommandation_delete($_POST['Serie']);
                echo '<div class="alert alert-success" id="info" role="alert">'
                    . '<span class="glyphicon glyphicon-ok"></span>'
                    . 'La série "'.$_POST['TitreSerie'].'" appartient maintenant à vos séries de recommandation !<br/>'
                . '</div>';
            }   
        }
        
        // les messages restent afficher pendant 3,5 secondes
        echo '<script type="text/javascript">
            setTimeout( function(){
                document.getElementById("info").style.display = "none"; }, 3500);
        </script>';
    }
    
    // Affiche un carouselle à 3 items : recommandation / top like / top recommandation
    // IN : $item1 affichage de l'item recommandation
    // IN : $item1 affichage de l'item top like
    // IN : $item1 affichage de l'item top recommandation
    public function carouselle($item1, $item2, $item3){ ?>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="6000" style="height: 380px;">
            <div class="carousel-inner" role="listbox" style="width:72%; margin-left: auto; margin-right : auto;">
                <?php if($item1){ // affichage de l'item recommandation ?>
                    <div class="item active">
                       <div class='IndexCarou'><a href='Recommandation.php'>VOUS POURRIEZ AIMER</a></div> 
                       <div class="SerieContainerIner" style="width: 100%;">
                       <?php
                       $req1=$this->_db->recommandation( null, " having count(nr.num_serie) = 0 ", 'rand()', 3);
                       $this->affichage_serie($req1, null, 'MediaObjectCaseG2', null);
                       ?>
                      </div>
                    </div>
                <?php }
                if($item2){ // affichage de l'item top like ?>
                    <div class="item <?php if(!$item1){ echo 'active'; } ?>">
                     <div class="IndexCarou"><a href='Serie.php'>TOP 9 <?php echo '<span id="$titre" style="color:red" class="glyphicon glyphicon-heart"></span>'; ?> SERIES TV </a></div>
                      <div class="SerieContainerIner" style="width: 100%">
                      <?php $req1=$this->_db->serie_top_coeur(9);
                      $this->affichage_serie($req1, null, 'MediaObjectCaseP2', null); ?>
                     </div>
                    </div>
                <?php }
                if($item3){ // affichage de l'item top recommandation ?>
                    <div class="item <?php if(!$item1 && !$item2){ echo 'active'; } ?>">
                     <div class="IndexCarou"><a href='Serie.php'>TOP 9 <?php echo '<span id="$titre" style="color:red" class="glyphicon glyphicon-eye-open"></span>'; ?> SERIES TV </a></div>
                      <div class="SerieContainerIner" style="width: 100%">
                      <?php $req2=$this->_db->serie_top_recommandation(9);
                      $this->affichage_serie($req2, null, 'MediaObjectCaseP2', null); ?>
                     </div>
                    </div>
                <?php } ?>
            </div> 
            <!-- Indicateurs -->
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
    
    // affichage du menu haut
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
                                    <!-- moteur de recherche -->
                                    <input type="text" class="form-control menu_search" placeholder="<?php echo $this->_str['menu']['placeholder']; ?>" name="mc" required>
                                    <div class="input-group-btn">
                                        <!-- bouton de recherche -->
                                        <button type="submit" class="btn btn-default menu_btn_search" name="btn"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        <!-- suggestion de mots-clés pour une recherche de séries TV basé sur les mots-clés recherchés par les autres utilisateurs -->
                                        <button data-toggle="dropdown" class="btn btn-default menu_btn_search" role="button">
                                        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span></button>
                                        <ul class="dropdown-menu menu_tags_motsCles" role="menu" >
                                            <div class="menu_tags_titre"><?php echo $this->_str['menu']['tags_titre']; ?></div>
                                            <?php $nb = $this->_db->interesser_motcle_count();
                                            $req = $this->_db->interesser_motcle();
                                            while($data = $req->fetch()){
                                                // couleur aléatoire pour chaques mots
                                                $r = rand(1,200);
                                                $g = rand(1,200);
                                                $b = rand(1,200);
                                                echo " <a href='Search.php?mc=".$data['0']."' style='color:rgb($r,$g,$b);'>"
                                                    // la taille du mot-clé dépend du nombre d'utilisateur qui ont cherché ce mot 
                                                    . "<FONT size='".($data['1']*$nb/$nb).";'>"
                                                        .$data['0']
                                                    . '</font>'
                                                . '</a> ';
                                            } ?>
                                        </ul> 
                                    </div>
                                </div>
                            </form>
                        </li>
                        <!-- item recommandation -->
                        <li class="dropdown">
                            <a href="recommandation.php"><?php echo $this->_str['menu']['recommandation']; ?></a>
                        </li>
                        <!-- item séries TV -->
                        <li class="dropdown">
                            <a href="serie.php"><?php echo $this->_str['menu']['serieTV']; ?></a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
    <?php }
    
    // affichage du menu bas
    public function menu_bottom(){ ?>
        <nav class="navbar navbar-inverse navbar-fixed-bottom">
            <div class="container-fluid">
                <div class="navbar-header">
                    <span class="ItemMenuBas" style="color:whitesmoke; position: relative; display: block; padding-top: 5px !important;" >
                        @2015 howimetmyserie
                    </span>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-nav2 navbar-right">
                        <li class="dropdown">
                            <a href="#" type="button" class="ItemMenuBas" data-toggle="modal" data-target="#exampleModal">Contact</a>
                        </li>
                        <?php if($this->_db->questionnaire_exist() == 0){ ?>
                            <li class="dropdown">
                                <a href="donnervotreavis.php" class="ItemMenuBas">Donnez votre avis sur le site</a>
                            </li>
                        <?php } ?>
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
            $this->_db->user_contact($_POST['pseudo'], $_POST['email'], $_POST['Sujet'], $_POST['Message']);
        } ?>

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
    
    public static function question_oui_non($nb, $question){
        echo "<div class='question'>$nb - $question :<br/>"
            ."<div class='cc-selector-2'>"
                ."<span style='margin:20px'>"
                    ."<input type='radio' name='question$nb' id='question".$nb."_0' value='0' required />"
                    ."<label class='drinkcard-cc BoNo' for='question".$nb."_0' data-toggle='tooltip' data-placement='left' title='Non !'></label>"
                ."</span>"
                ."<span style='margin:20px'>"
                    ."<input type='radio' name='question$nb' id='question".$nb."_1' value='1' required />"
                    ."<label class='drinkcard-cc BoOk' for='question".$nb."_1'  data-toggle='tooltip' data-placement='right' title='Oui !'></label>"
                ."</span>"
            ."</div>"
        ."</div>";
    }
    public static function question_satisfaction($nb, $question){
        echo "<div class='question'>$nb - $question<br/>"
            ."<div align='center'>"
                ."<div class='btn-group' data-toggle='buttons'>"
                    ."<label class='btn btn-primary' style='margin-bottom:20px'>"
                        ."<input type='radio' name='question$nb' value='0' required> Non Satisfait"
                    ."</label>"
                    ."<label class='btn btn-primary' style='margin-bottom:20px'>"
                        ."<input type='radio' name='question$nb' value='1' required> Moyennement Satisfait"
                    ."</label>"
                    ."<label class='btn btn-primary' style='margin-bottom:20px'>"
                        ."<input type='radio' name='question$nb' value='2' required> Très Satisfait"
                    ."</label>"
                ."</div>"
            ."</div>"
        ."</div>";
    }
}