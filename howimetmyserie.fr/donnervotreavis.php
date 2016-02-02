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
                background-color: rgba(255,255,255,0.8);"> 
            <?php 
            $affichage->question('Je trouve le site moderne :', 1);
            $affichage->question('Je trouve le site facile d\'utilisation :', 2);
            $affichage->question('Je pense que le site est cohérence :', 3);
            $affichage->question('Je trouve les differentes fonctions de ce site bien intégrées :', 4); ?>

                Vos résultats de rcherche sont-ils satifaisants 
                <div align="center">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                          <input type="radio" name="Question5" value="2"> Très Satisfait
                        </label>
                        <label class="btn btn-primary">
                          <input type="radio" name="Question5" value="1"> Moyennement Satisfait
                        </label>
                        <label class="btn btn-primary">
                          <input type="radio" name="Question5" value="0"> Non Satisfait
                        </label>
                    </div>
                </div>
                <p style="text-align:center; font-size: 16px;"> 
                <br/><br/> Les recommandations qui vous ont été proposées vous ont-elles satisfaites 
                <div align="center">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-primary">
                          <input type="radio" name="Question5" value="2"> Très Satisfait
                        </label>
                        <label class="btn btn-primary">
                          <input type="radio" name="Question5" value="1"> Moyennement Satisfait
                        </label>
                        <label class="btn btn-primary">
                          <input type="radio" name="Question5" value="0"> Non Satisfait
                        </label>
                    </div>
                </div>
                
                <?php $affichage->question('Je recommanderais ce site à un ami :', 7); ?>
                
                Commentaire : 
                <br/>
                <div align="center"> 
                    <TEXTAREA name="nom" rows=4 cols=100 placeholder="Laissez nous un message" ></TEXTAREA>
                </div><br/> 
                
                <input type="submit" class="btn btn-info btn-lg sub" name="valider" value="Valider !">
            </div>
        </form>
        <br/>
    </body>               
</html>