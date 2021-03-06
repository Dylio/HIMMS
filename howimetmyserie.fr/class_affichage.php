<?php
// Cette classe permet l'affichage les données.
class class_affichage{
    private  $_str;             // constantes textuelles du site web
    
    // Constructeur de la classe class_media_object
    public function __construct(){
        require_once 'class_media_object.php';
        require_once 'lang.php';
        $this->_str = lang::getlang();
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
                if(substr($file, -4) == ".png"){    // les affiches doivent être tous sous format .jpg
                    $j++;
                }
            }
            closedir($dir);
            $aleaImage = rand(1, $j);                 // $aleaImage un nombre aléatoire entre 1 et $j
            $i = 1;                                   // $i numéro actuel de l'affiche de la série TV
            $dir = opendir($dirname);
            while($file = readdir($dir)) {
                if($i === $aleaImage and substr($file, -4) == ".png"){      // tant que $i et $aleaImage ne sont pas égal, incrémenter $i
                    return "style='background: url(./css/fond/$file);'"; // renvoie la $aleaImage fond d'écran
                }
                if(substr($file, -4) == ".png"){
                    $i++;
                }
            }
            closedir($dir);
        }
    }
    
    // Renvoie l'adresse relatif d'une image aléatoire de la série TV
    // IN : $num_serie numéro d'une série TV
    // OUT : adresse relatif d'une image aléatoire de la série TV
    function alea_Image($num_serie){
        return class_media_object::alea_image($num_serie);
    }       
    
    // Affiche toutes les séries TV resultant d'une requete
    // IN : $req requete
    // IN : $dataNb nombre d'éléments à afficher
    // IN : $media style d'objet
    // IN : $warning boolean true si gestion des message si non résultat
    public function affichage_serie($req, $dataNb, $media, $warning){
        echo '<div class="SerieContainerIner">';
        $i = 0;
        while($data = $req->fetch()){
           $media_object = new class_media_object($data);
           $media_object->media_object($media);
           $i++;
        }
        echo '</div>';
        // message si aucun élément en fonction du théorique et du réél
        if($warning == true){
            if($i == 0 && $dataNb != 0){    // message si aucun élément réél mais plusieur éléments théoriques : filtre actuel
                echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                    . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée avec les filtres actuels !"
                . "</div>"; 
            } else if($i == 0 && $dataNb == 0){   // message si aucun élément réél et théorique : aucun résultat
                echo "<div class='alert alert-warning SerieSearchMessAlert'>"
                    . "Oups !<br/>Aucun résultat pour cette recherche n'a été trouvée ..."
                . "</div>";
            }
        }
    }
    
    public function affichage_une_serie($data, $req){
        echo '<div class="SerieContainerIner">';
            $media_object = new class_media_object($data);
            $media_object->media_object('MediaObjectDetail');
            echo "<div class='jumbotron SerieDetailContainer2'>" // conteneur des recommandations par rapport à la serie TV
                ."<div class='SerieDetailNomPartie'>".$this->_str['serie_detail']['Recommandation']."</div>";
            echo '</div>';
                    while($data = $req->fetch()){
                        $media_object = new class_media_object($data);
                        $media_object->media_object("MediaObjectCaseG2");
                    }
                echo '</div>';
    }
    
    // Affiche un carouselle à 3 items : recommandation / top like / top recommandation
    // IN : $item1 affichage de l'item recommandation
    // IN : $item2 affichage de l'item top like
    // IN : $item3 affichage de l'item top recommandation
    public function carouselle($item1, $item2, $item3){ ?>
        <div id="carousel-example-generic" class="carousel slide" data-ride="carousel" data-interval="4000" style="height: 430px;">
            <div class="carousel-inner" role="listbox" style="width:72%; margin-left: auto; margin-right : auto;">
                <?php if($item1 != false){ // affichage de l'item recommandation ?>
                    <div class="item active">
                       <div class='IndexCarou'><a href='Recommandation.php'>VOUS POURRIEZ AIMER</a></div> 
                       <div class="SerieContainerIner" style="width: 100%;">
                       <?php $this->affichage_serie($item1, null, 'MediaObjectCaseG2', null);
                       ?>
                      </div>
                    </div>
                <?php }
                if($item2 != false){ // affichage de l'item top like ?>
                    <div class="item <?php if(!$item1){ echo 'active'; } ?>">
                     <div class="IndexCarou"><a href='Serie.php'>TOP 9 <?php echo '<span id="$titre" style="color:red" class="glyphicon glyphicon-heart"></span>'; ?> SERIES TV </a></div>
                      <div class="SerieContainerIner" style="width: 100%">
                      <?php $this->affichage_serie($item2, null, 'MediaObjectCaseP2', null); ?>
                     </div>
                    </div>
                <?php }
                if($item3 != false){ // affichage de l'item top recommandation ?>
                    <div class="item <?php if(!$item1 && !$item2){ echo 'active'; } ?>">
                     <div class="IndexCarou"><a href='Serie.php'>TOP 9 <?php echo '<span id="$titre" style="color:red" class="glyphicon glyphicon-eye-open"></span>'; ?> SERIES TV </a></div>
                      <div class="SerieContainerIner" style="width: 100%">
                      <?php $this->affichage_serie($item3, null, 'MediaObjectCaseP2', null); ?>
                     </div>
                    </div>
                <?php } ?>
            </div> 
            <!-- Indicateurs -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active" style="background-image: none; border: 2px rgb(227, 225, 219) solid;"></li>
              <?php if(($item1 && $item2) or ($item2 && $item3) or ($item1 && $item3)){ ?>
                <li data-target="#carousel-example-generic" data-slide-to="1" style="background-image: none; border: 2px rgb(227, 225, 219) solid;"></li>
              <?php }
              if($item1 && $item2 && $item3){ ?>
                <li data-target="#carousel-example-generic" data-slide-to="2" style="background-image: none; border: 2px rgb(227, 225, 219) solid;"></li>
              <?php } ?>
            </ol>
            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev" style="background-image: none; width: 7% !important; padding-right: 15%;">
              <span class="glyphicon glyphicon-chevron-left" aria-hidden="true" style="color: rgb(227, 225, 219);"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next" style="background-image: none; width: 7% !important; padding-left: 15%;">
              <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" style="color: rgb(227, 225, 219);"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>
    <?php }
    
    // affichage du menu haut
    public function menu_top($req, $nb){
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
                            <!-- Système de recherche -->
                            <form class="navbar-form" role="search" action="Search.php" method="GET">
                                <div class="input-group">
                                    <!-- moteur de recherche -->
                                    <input type="text" class="form-control menu_search" placeholder="<?php echo $this->_str['menu']['placeholder']; ?>" name="mc" required>
                                    <div class="input-group-btn" style="display: inline !important;">
                                        <!-- bouton de recherche -->
                                        <button type="submit" class="btn btn-default menu_btn_search" name="btn"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        <!-- suggestion de mots-clés pour une recherche de séries TV basé sur les mots-clés recherchés par les autres utilisateurs -->
                                        <button data-toggle="dropdown" class="btn btn-default menu_btn_search" role="button">
                                        <span class="glyphicon glyphicon-tags" aria-hidden="true"></span></button>
                                        <ul class="dropdown-menu menu_tags_motsCles" role="menu" >
                                            <div class="menu_tags_titre"><?php echo $this->_str['menu']['tags_titre']; ?></div>
                                            <?php
                                            while($data = $req->fetch()){
                                                // couleur aléatoire pour chaques mots-clés
                                                $r = rand(1,200);
                                                $g = rand(1,200);
                                                $b = rand(1,200);
                                                echo " <a href='Search.php?mc=".$data['0']."' style='color:rgb($r,$g,$b);'>"
                                                    // la taille du mot-clé dépend du nombre d'utilisateur qui ont cherché ce mot 
                                                    . "<font size='".($data['1']*$nb/$nb).";'>"
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
    public function menu_bottom($questionnaire){ ?>
        <nav class="navbar navbar-inverse navbar-fixed-bottom">
            <div class="container-fluid">
                <div class="navbar-header">
                    <span class="ItemMenuBas">
                        @2015 howimetmyserie
                    </span>
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-nav2 navbar-right">
                        <!-- item Contact : toggle -->
                        <li class="dropdown">
                            <a href="#" type="button" class="ItemMenuBas" data-toggle="modal" data-target="#exampleModal">
                                <?php echo $this->_str['menu']['contact']; ?>
                            </a>
                        </li>
                        <!-- item recommandation
                        affiche si le questionnaire n'a pas déjà été complété par l'utilisateur -->
                        <?php if($questionnaire == 0){ ?>
                            <li class="dropdown">
                                <a href="donnervotreavis.php" class="ItemMenuBas">
                                    <?php echo $this->_str['menu']['avis']; ?>
                                </a>
                            </li>
                        <?php } ?>
                        <!-- item CGU -->
                        <li class="dropdown">
                            <a href="cgu.php" class="ItemMenuBas">
                                <?php echo $this->_str['menu']['cgu']; ?>
                            </a>
                        </li>
                        <!-- item Plan du site : toggle -->
                        <li class="dropdown">
                            <a href="#" type="button" class="ItemMenuBas" data-toggle="modal" data-target="#exampleModal2">
                                <?php echo $this->_str['menu']['plan_site']; ?>
                            </a>
                        </li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div>
        </nav>

        <!-- toggle Contact  -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title NomPartie2" id="exampleModalLabel"><?php echo $this->_str['contact']['title']; ?></h4>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body">
                            <!-- pseudo -->
                            <div class="form-group">
                                <label for="recipient-name" class="control-label"><?php echo $this->_str['contact']['pseudo']; ?></label>
                                <input type="text" name="pseudo" class="form-control" id="recipient-name" maxlength="50" required>
                            </div>
                            <!-- email -->
                            <div class="form-group">
                                <label for="recipient-mail" class="control-label"><?php echo $this->_str['contact']['email']; ?></label>
                                <input type="email" name="email" class="form-control" id="recipient-mail" maxlength="255" required>
                            </div>
                            <!-- sujet -->
                            <div class="form-group">
                                <label for="recipient-sujet" class="control-label"><?php echo $this->_str['contact']['sujet']; ?></label>
                                <input type="text" name="Sujet" class="form-control" id="recipient-sujet" maxlength="255" required>
                            </div>
                            <!-- message (taille max 2000 caractères) -->
                            <div class="form-group">
                                <label for="message-text" class="control-label"><?php echo $this->_str['contact']['message']; ?></label>
                                <textarea name="Message" class="form-control" id="message-text" maxlength="2000"></textarea>
                            </div>
                        </div>
                        <!-- bouton valider et annuler -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-dismiss="modal"><?php echo $this->_str['contact']['annuler']; ?></button>
                            <input type="submit" name="Envoyer" value="Envoyer" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- toggle Plan du Site  -->
        <div class="modal fade bs-example-modal-lg" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title NomPartie2" id="exampleModalLabel"><?php echo $this->_str['plan_site']['title']; ?></h4>
                    </div>
                    <form action="" method="POST">
                        <div class="modal-body planSite_conener_img">
                            <img class="planSite_img" src="Page Aide.png">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php }
    
    // affiche un questionnaire oui/non
    // IN : $nb numéro de question
    // IN : $question intitulé de la question
    public function question_oui_non($nb, $question){
        echo "<div class='question'>$nb - $question :<br/>"
            ."<div class='cc-selector-2'>"
                ."<span class='btnQuestion'>"
                    ."<input type='radio' name='question$nb' id='question".$nb."_0' value='0' required />"
                    ."<label class='drinkcard-cc BoNo' for='question".$nb."_0' data-toggle='tooltip' data-placement='left' title='".$this->_str['questionnaire']['reponse']['non']."'></label>"
                ."</span>"
                ."<span class='btnQuestion'>"
                    ."<input type='radio' name='question$nb' id='question".$nb."_1' value='1' required />"
                    ."<label class='drinkcard-cc BoOk' for='question".$nb."_1'  data-toggle='tooltip' data-placement='right' title='".$this->_str['questionnaire']['reponse']['oui']."'></label>"
                ."</span>"
            ."</div>"
        ."</div>";
    }
    // affiche un questionnaire Très Satisfait / Moyennement Satisfait / Non Satisfait
    // IN : $nb numéro de question
    // IN : $question intitulé de la question
    public function question_satisfaction($nb, $question){
        echo "<div class='question'>$nb - $question<br/>"
            ."<div align='center'>"
                ."<div class='btn-group' data-toggle='buttons'>"
                    ."<label class='btn btn-primary btnQuestion'>"
                        ."<input type='radio' name='question$nb' value='0' required>".$this->_str['questionnaire']['reponse']['satisfaction0']
                    ."</label>"
                    ."<label class='btn btn-primary btnQuestion'>"
                        ."<input type='radio' name='question$nb' value='1' required>".$this->_str['questionnaire']['reponse']['satisfaction1']
                    ."</label>"
                    ."<label class='btn btn-primary btnQuestion'>"
                        ."<input type='radio' name='question$nb' value='2' required>".$this->_str['questionnaire']['reponse']['satisfaction2']
                    ."</label>"
                ."</div>"
            ."</div>"
        ."</div>";
    }

    // permet d'afficher les différents éléments permettant l'interaction avec l'utilisateur
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    public function vue_tri($like, $order, $recommandation, $media, $placeholder, $value){
        $this->like($like);                                          // choix : aimé/tous/non aimé
        $this->search($placeholder, $value).'<br/>';       // recherche ciblé
        $this->order($order, $recommandation);                              // choix : ordre d'appartition
        $this->media($media);                              // choix : style d'objet abstrait pour la visualisation des séries TV
    }

    // permet d'afficher les différents éléments permettant l'interaction avec l'utilisateur sans zone texte
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $recommandation_exist 1 si recommandation ou 0 si top série
    public function vue_tri2($like, $order, $recommandation, $media){
        $this->like($like);                                          // choix : aimé/tous/non aimé
        echo "<br/>";
        $this->order($order, $recommandation);                              // choix : ordre d'appartition
        $this->media($media);                              // choix : style d'objet abstrait pour la visualisation des séries TV
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de l'ordre d'apparition des séries TV
    private function order($order, $recommandation){
        // bouton correspondant à l'option : voir les séries TV dont l'utilisateur veut être recommandé
        // avec un tooltip en haut
        echo "<form action='' method='POST'>"
            ."<div class='thumbnail tri_group1'>"
                ."<button type='submit' ";
                    if($recommandation == true){
                        echo 'name="nonRecommandationF" class="btn btn-success" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['interest']['off'].'">';
                    }else{
                        echo 'name="nonRecommandationT" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['interest']['on'].'">';
                    }
                    echo "<span class='glyphicon glyphicon-eye-open'></span>"
                ."</button>"
            ."</div>"
           
            // 5 boutons correspondant aux différents possibilité de tri des séries TV
            // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable 
            // (exception : option aléatoire reste selectionnable)
            // Les autres boutons sont d'une couleur bleu et selectionnable         
            ."<div class='thumbnail tri_group2'>"
                ."<span>Titre : </span> "
                // option tri par titre croissant puis par date de début croissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort1' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort1']."'";
                    if($order == "sort1"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo '<span class="glyphicon glyphicon-menu-up"></span>'
                ."</button> "
                // option tri par titre décroissant puis par date de début croissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort2' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort2']."'";
                    if($order == "sort2"){
                        echo 'class="btn btn-success" disabled>';
                    }else{
                        echo 'class="btn btn-info">';
                    }
                    echo "<span class='glyphicon glyphicon-menu-down'></span>"
                ."</button> "
                // option tri aléatoire
                // avec un tooltip en haut
                ."<button type='submit' name='sortRand' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sortRand']."'";
                    if($order == "sortRand"){
                        echo 'class="btn btn-success">';
                    }else{ 
                        echo 'class="btn btn-info">';
                    }
                    echo "<span class='glyphicon glyphicon-random'></span>"
                ."</button>"
            ."</div>" 
            ."<div class='thumbnail tri_group2'>"
                ."<span>Date : </span>"
                // option tri par date de début croissant puis par titre croissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort3' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort3']."'";
                    if($order == "sort3"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo "<span class='glyphicon glyphicon-menu-up'></span>"
                ."</button> "
                // option tri par date de début croissant puis par titre décroissant
                // avec un tooltip en haut
                ."<button type='submit' name='sort4' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['sort']['sort4']."'";
                    if($order == "sort4"){
                        echo "class='btn btn-success' disabled>";
                    }else{
                        echo "class='btn btn-info'>";
                    }
                    echo "<span class='glyphicon glyphicon-menu-down'></span>"
                ."</button>"
            ."</div>"
        ."</form>";
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de choisir de voir 
    // les séries TV qui sont aimées, non aimées ou les deux
    private function like($like){
        // Trois boutons correspondant aux 3 options (aimé / tous / non aimé)
        // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable
        // Les autres boutons sont d'une couleur bleu et selectionnable
        echo "<form action='' method='POST' class='formLike'>"
            // bouton séries TV que l'utilisateur aime
            // avec un tooltip à gauche
            .'<button type="submit" name="List_like" data-toggle="tooltip" data-placement="left" title="'.$this->_str['tooltip']['like']['like'].'"';
                if($like == 'like'){
                    echo "class='btn btn-success buttonTriR' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriR'>";
                }
                echo "<span class='glyphicon glyphicon-heart'></span>"
            ."</button> "
            // bouton toutes les séries TV
            // avec un tooltip en haut
            ."<button type='submit' name='List_all' data-toggle='tooltip' data-placement='top' title='".$this->_str['tooltip']['like']['all']."'";
                if($like == 'all'){
                    echo "class='btn btn-success buttonTriW' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriW'>";
                }
                echo "Tous"
            ."</button> "
            // bouton séries TV que l'utilisateur n'aime pas encore
            // avec un tooltip à droite
            .'<button type="submit" name="List_unlike" data-toggle="tooltip" data-placement="right" title="'.$this->_str['tooltip']['like']['unlike'].'"';
                if($like == 'unlike'){
                    echo "class='btn btn-success buttonTriN' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriN'>";
                }
                echo "<span class='glyphicon glyphicon-heart-empty'></span>"
            ."</button>"
        ."</form>";
    }
    
    // afficher l'élément graphique permettant la recherche de séries TV via une entrée texte 
    // IN : $placeholder texte indicatif par défaut dans un champ de formulaire
    // IN : $valueEmpty 1 si la valeur dans la zone de texte doit être garder en mémoire sinon 0
    private function search($value, $placeholder){
        echo "<form action='' method='POST'>"
            ."<div class='input-group formSearch'>"
                ."<input type='text' name='search' class='form-control SearchInput' value='$value'placeholder='$placeholder'>"
                ."<div class='input-group-btn'>"
                     // bouton de recherche
                    // avec un tooltip en bas
                    ."<button type='submit' name='ok' class='btn btn-default SearchInput' value='true' data-toggle='tooltip' data-placement='bottom' title='".$this->_str['tooltip']['search']."'>"
                        ."<span class='glyphicon glyphicon-search'></span>"
                    ."</button>"
                     // bouton de réinitialisation de la zone texte
                     // avec un tooltip en bas
                    ."<button type='submit' name='empty' class='btn btn-default SearchInput' data-toggle='tooltip' data-placement='bottom' title='".$this->_str['tooltip']['reset']."'>"
                        ."<span class='glyphicon glyphicon-remove'></span>"
                    ."</button>"
                ."</div>"
            ."</div>"
        ."</form>";
    }
    
    // afficher l'élément graphique permettant à l'utilisateur de choisir le style d'objet abstrait 
    // pour la visualisation des séries TV
    private function media($media){        
        // Trois boutons correspondant aux 3 options (grande case / petite case / liste détaillé)
        // Le bouton de l'option selectionné est d'une couleur verte et non selectionnable
        // Les autres boutons sont d'une couleur bleu et selectionnable
        echo "<form action='' method='POST' class='thumbnail formMedia'>"
            // bouton grande case
            // avec un tooltip en haut
            .'<button type="submit" name="MediaObjectCaseG" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseG'].'"';
                if($media == "MediaObjectCaseG"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-th-large'></span>"
            ."</button> "
            // bouton petite case
            // avec un tooltip en haut
            .'<button type="submit" name="MediaObjectCaseP" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseP'].'"';
                if($media == "MediaObjectCaseP"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-th'></span>"
            ."</button> "
            // bouton liste détaillé
            // avec un tooltip en haut
            .'<button type="submit" name="MediaObjectList" data-toggle="tooltip" data-placement="top" title="'.$this->_str['tooltip']['MediaObject']['CaseList'].'"';
                if($media == "MediaObjectList"){
                    echo 'class="btn btn-success buttonDetailSerieW2" disabled>';
                }else{
                    echo 'class="btn btn-info buttonDetailSerieW2">';
                }
                echo "<span class='glyphicon glyphicon-list'></span>"
            ."</button>"
        ."</form>";
    }
}