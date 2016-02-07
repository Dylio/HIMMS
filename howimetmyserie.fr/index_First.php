<?php if(isset($_POST['btn'])){
    require_once 'connectBDD.php';
    $linkpdo = Db::getInstance();
    $num_user = $_COOKIE['himms_log'];
    // mise à jour de la restriction pour l'utilisateur
    $linkpdo->query("UPDATE utilisateur set restriction = ".$_POST['blankRadio']."  where num_user = '$num_user'");
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
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_db.php';            // base de données
            require_once 'class_affichage.php';     // affichage global
            require_once 'class_controleur.php';    // afficahge et gestion des controleurs
            $db = new class_db(true);
            $affichage = new class_affichage($db, $str);
            // garde en mémoire pour la session actuel les préférences visuelles de l'utilisateur
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur($db->getUser(), $str);
            }
        ?>
    </head>
    
    <body style='background: url(css/4243604.png)'> 

        <div class="modal IndexFirst_Modal" id='myModal'>
            <div class="modal-header"></div>
            <form method='POST'>    
                <div class="modal-body">
                    <p class='IndexFirst_Txt'><br><?php echo $str['index_first']['bienvenu']; ?>
                        <span class="IndexFirstNomSite"><?php echo $str['site']['name']; ?></span> !<br>
                        <?php echo $str['index_first']['recommandation']; ?><br/><br>
                        <?php echo $str['index_first']['sensibilite']; ?>
                    </p>
                    
                    <!-- deux possibilités avant d'accéder au site web -->
                    <label>
                        <p class='IndexFirst_TxtRadio' id='radio1' onclick="a()">
                            <input type="radio" name="blankRadio" id="blankRadio1" value="0" required />
                            <?php echo $str['index_first']['option_1']; ?>
                        </p>
                    </label>
                    <label>
                        <p class='IndexFirst_TxtRadio' id='radio2' onclick="b()">
                            <input type="radio" name="blankRadio" id="blankRadio2" value="1" required />
                            <?php echo $str['index_first']['option_2']; ?>
                        </p>
                    </label><br/>
                    
                    <p class='IndexFirst_Txt'><small>
                            <?php echo $str['index_first']['utilisation']; ?>
                    </small></p>
                    <!-- image de cartman -->
                    <img src='css/cartman.png' class="IndexFirst_img" alt='cartman' />
                </div>
                <div class="modal-footer IndexFirst_Footer">
                    <input type="submit" class="btn btn-primary" name="btn" value='<?php echo $str['index_first']['bouton']; ?>'>
                </div>
            </form>
        </div>
        <!-- gestion du Modal -->
        <script type="text/javascript">
            $('#myModal').modal('show');
            $('#myModal').modal({
              keyboard: false
            });
            $('#myModal').modal({
              backdrop: true
            });
            
            // gestion des radios
            function a(){
                document.getElementById('radio1').style.color = '#57d1d1';
                document.getElementById('radio2').style.color = 'white'; }
            function b(){
                document.getElementById('radio1').style.color = 'white';
                document.getElementById('radio2').style.color = '#57d1d1'; }
        </script>
    </body>
</html>