<?php 
require_once 'class_db.php';            // base de données
$db = new class_db(false);
if($db->questionnaire_exist() == 1){
    header("Location:index.php");
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
        <!-- Importation des scripts et des stylesheet -->
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <!-- gestion des tooltips -->
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_affichage.php';     // affichage global
            $affichage = new class_affichage($db, $str);
        ?>
    </head>
    
    <body style='background: url(css/cgu.png) no-repeat center fixed; background-size: 100% 100%; min-width: 100%;'>
         
        <?php $affichage->affichage_titrePartie($str['questionnaire']['title']); ?>

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
        <?php if(isset($_POST['valider'])){
            $db->questionnaire($_POST['question1'],
                                $_POST['question2'],
                                $_POST['question3'],
                                $_POST['question4'],
                                $_POST['question5'],
                                $_POST['question6'],
                                $_POST['question7'],
                                $_POST['commentaire']);
        }
        ?>
    </body>               
</html>