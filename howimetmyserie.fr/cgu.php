<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <!-- Importation des scripts et des stylesheet -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            $db = new class_db(false);
            $affichage = new class_affichage($db, $str);
        ?>
    </head>
    
    <body style='background: url(css/cgu.png) no-repeat center fixed; background-size: 100% 100%;'>
         <?php $affichage->affichage_titrePartie("<span style='font-size:35px'>".$str['cgu']['title']."</span>"); ?>
        
        <div class="jumbotron SerieDetailContainer2">
            <div class='cgu_entete'><?php echo $str['cgu']['entete']; ?></div><br/>
            <ul>
                <li><a href="#art1"><?php echo $str['cgu']['article']['1']['title']; ?></a></li>
                <li><a href="#art2"><?php echo $str['cgu']['article']['2']['title']; ?></a></li>
                <li><a href="#art3"><?php echo $str['cgu']['article']['3']['title']; ?></a></li>
                <li><a href="#art4"><?php echo $str['cgu']['article']['4']['title']; ?></a></li>
                <li><a href="#art5"><?php echo $str['cgu']['article']['5']['title']; ?></a></li>
                <li><a href="#art6"><?php echo $str['cgu']['article']['6']['title']; ?></a></li>
                <li><a href="#art7"><?php echo $str['cgu']['article']['7']['title']; ?></a></li>
                <li><a href="#art8"><?php echo $str['cgu']['article']['8']['title']; ?></a></li>
                <li><a href="#art9"><?php echo $str['cgu']['article']['9']['title']; ?></a></li>
                <li><a href="#art10"><?php echo $str['cgu']['article']['10']['title']; ?></a></li>
            </ul>
            
            (Dernière mise à jour le 02/02/2016)<br/><br/>
            <div id="art1" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['1']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['1']['texte']; ?>
            <br/>
            
            <div id="art2" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['2']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['2']['texte']; ?>
            <br/>
            
            <div id="art3" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['3']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['3']['texte']; ?>
            
            <div id="art4" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['4']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['4']['texte']; ?>
            <br/>
            
            <div id="art5" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['5']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['5']['texte']; ?>
            <br/>
            
            <div id="art6" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['6']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['6']['texte']; ?>
            <br/>
            
            <div id="art7" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['7']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['7']['texte']; ?>
            <br/>
            
            <div id="art8" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['8']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['8']['texte']; ?>
            <br/>
            
            <div id="art9" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['9']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['9']['texte']; ?>
            <br/>
            
            <div id="art10" class="artId"></div>
            <span class="cgu_article"><?php echo $str['cgu']['article']['10']['title']; ?></span><br/>
            <?php echo $str['cgu']['article']['10']['texte']; ?>
            <br/><br/>
        </div> 
    </body>
</html>