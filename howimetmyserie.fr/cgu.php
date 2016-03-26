<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        
        <!-- Importation des scripts et des stylesheet -->
        <?php include_once 'incl_import.php'; ?>
    </head>
    
    <body style='background: url(css/cgu.png) no-repeat center fixed; background-size: 100% 100%;'>
         <?php $affichage->affichage_titrePartie($str['cgu']['title']); ?>
        
        <!-- Lien vers les différents articles -->
        <div class="jumbotron SerieDetailContainer2">
            <div class='cgu_entete'><?php echo $str['cgu']['entete']; ?></div><br/>
            <ul>
                <?php for($i=1; $i<=10; $i++){
                    echo "<li><a href='#art$i'>".$str['cgu']['article'][$i]['title']."</a></li>";
                } ?>
            </ul>
            <?php echo $str['cgu']['date']; ?>
            
            <!-- Différents articles du CGU avec l'id de l'article pour référence -->
            <?php for($i=1; $i<=10; $i++){
                echo "<div id='art$i' class='artId'></div>"
                . '<span class="cgu_article">'.$str['cgu']['article'][$i]['title'].'</span><br/>'
                . $str['cgu']['article'][$i]['texte']."<br/>";
            }?>
        </div> 
    </body>
</html>