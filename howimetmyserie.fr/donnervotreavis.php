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
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            $bd = new class_db();
            $num_user = $bd->user("false");
            $affichage = new class_affichage($num_user);
        ?>
    </head>
    
    <body style='background: url(css/cgu.png) no-repeat center fixed; background-size: 100% 100%; min-width: 100%;'>
            
        <div class="jumbotron SerieDetailContainer"><p class="NomPartie"> VOTRE AVIS NOUS INTERESSE </p></div>

        <form action="" method="POST">
            <div  style="width: 70%;
                    margin-left: auto;
                    margin-right: auto;
                    background-color: rgba(255,255,255,0.8);
                    padding: 20px; "> 
                <?php 
                $affichage->question_oui_non('Je trouve le site moderne :', 1);
                $affichage->question_oui_non('Je trouve le site facile d\'utilisation :', 2);
                $affichage->question_oui_non('Je pense que le site est cohéren :', 3);
                $affichage->question_oui_non('Je trouve les differentes fonctions de ce site bien intégrées :', 4);
                $affichage->question_satisfaction('Vos résultats de recherche sont-ils satifaisants :', 5);
                $affichage->question_satisfaction('Les recommandations qui vous ont été proposées vous ont-elles satisfaites :', 6); 
                $affichage->question_oui_non('Je recommanderais ce site à un ami :', 7); ?>
                <div class='question'>Commentaire :<br/>
                    <TEXTAREA name="commentaire" rows=6 maxlength=1000 style="width: 75%" placeholder="Laissez nous un message" ></TEXTAREA>
                </div><br/>
                <div class='question'>
                    <input type="submit" class="btn btn-info btn-lg" style="font-size: 22px" name="valider" value="Valider !">
                </div>
            </div>
        </form>
        <br/>
        <?php if(isset($_POST['valider'])){
            $bd->questionnaire($num_user, 
                                $_POST['question1'],
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