<?php 
// constantes textuelles du site web
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

        <!-- gestion des tooltips -->
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>

        <?php 
        require_once 'class_affichage.php';     // affichage global
        require_once 'class_controleur.php';    // afficahge et gestion des controleurs
        $affichage = new class_affichage($str);
        $controleur = new class_controleur();
        $controleur-> init();
        $controleur-> filtre();
        ?>
    </head>
    
    <body>
        <?php $affichage->affichage_titrePartie($str['questionnaire']['title']); 
        $controleur -> questionnaire(); ?>
        <form action="" method="POST">
            <div  class="jumbotron SerieDetailContainer2"> 
                <?php $i = 1;                
                echo "<p class='question_entete'>".$str['questionnaire']['texte']."</p><br/>";
                $affichage->question_oui_non($i++, $str['questionnaire']['1']);
                $affichage->question_oui_non($i++, $str['questionnaire']['2']);
                $affichage->question_oui_non($i++, $str['questionnaire']['3']);
                $affichage->question_oui_non($i++, $str['questionnaire']['4']);
                $affichage->question_satisfaction($i++, $str['questionnaire']['5']);
                $affichage->question_satisfaction($i++, $str['questionnaire']['6']); 
                $affichage->question_oui_non($i++, $str['questionnaire']['7']); ?>
                <div class='question'><?php echo $str['questionnaire']['commentaire']; ?><br/>
                    <TEXTAREA name="commentaire" rows=6 maxlength=1000 style="width: 70%" placeholder="Laissez nous un message" ></TEXTAREA>
                </div><br/>
                <div class='question'>
                    <input type="submit" class="btn btn-info btn-lg" style="font-size: 22px" name="valider" value="Valider !">
                </div>
            </div>
        </form>
    </body>               
</html>