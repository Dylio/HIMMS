<?php
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
        <?php 
            require_once 'class_db.php';            // base de données
            $db = new class_db();
            $db->init(true);
            if(isset($_POST['btn'])){
                $num_user = $_COOKIE['himms_log'];
                // mise à jour de la restriction pour l'utilisateur
                $db->user_update_restriction($_POST['blankRadio']);
                header("Location:index.php");
                exit;
            }
            require_once 'class_affichage.php';     // affichage global
            $affichage = new class_affichage($db, $str);
        ?>
    </head>
    
    <body class='interfaceUser'> 
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