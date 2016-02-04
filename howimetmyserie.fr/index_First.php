<?php if(isset($_POST['btn'])){
    require_once 'connectBDD.php';
    $linkpdo = Db::getInstance();
    $num_user = $_COOKIE['log'];
    $linkpdo->query("UPDATE utilisateur set restriction = ".$_POST['blankRadio']."  where num_user = '$num_user'");
    header("Location:index.php");
    exit;
}
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
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>
        <?php
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            require_once 'class_controleur.php';
            $bd = new class_db();
            $num_user = $bd->user("true");
            $affichage = new class_affichage($num_user);
            if(!isset($_SESSION['SerieTV'])){
                $_SESSION['SerieTV'] = new class_controleur();
            }else{
                $_SESSION['SerieTV']->resetSearch();
            }
        ?>
    </head>
    
    <body style='background: url(css/4243604.png) no-repeat center fixed; background-size: 100% 100%; min-width: 100%;'> 

      <div class="modal " id='myModal' style='height: 100% !important; padding:1%; width: 100% !important'>
      <div class="modal-header" style='height: 8%!important;'></div>
      <form method='POST'>    
      <div class="modal-body" style='height: 90%!important; padding:1%;'>
          <p class='IndexFirstTxt'>Bonjour,<br>
            Bienvenue sur <span class="IndexFirstNomSite">HOW I MET MY SERIE !</span><br>
          Votre site de recommandation de séries.<br/><br>
            Certaines séries sont susceptibles de heurter la sensibilité, leur accés sont limités :</p>
            <label>
              <p class='IndexFirstTxtRadio' id='radio1' onclick="a()"><input type="radio" name="blankRadio" id="blankRadio1" value="0" aria-label="..." required>
              Je confirme avoir au moins 18 ans et avoir lu et accepter les conditions générales du site.</p>
            </label>
          
            <label>
                <p class='IndexFirstTxtRadio' id='radio2' onclick="b()"><input type="radio" name="blankRadio" id="blankRadio2" value="1" aria-label="..." required>
              Je confirme avoir 17 ans ou moins et avoir lu et accepter les conditions générales du site.</p>
            </label><br/>
          <p class='IndexFirstTxt'><small>
              En poursuivant votre navigation sur ce site, vous acceptez l'utilisation de cookies et de sessions pour vous proposer des séries adaptées à vos centres d'intérêts et réaliser des statistiques de visites.
          </small></p>
        <img src='css/cartman.png' alt='cartman' style='float:left; margin-left:30px;'/>
      </div>
      <div class="modal-footer" style='height: 10%!important; padding:1%;'>
        <input type="submit" class="btn btn-primary" name="btn" value='Accéder à How I Met My Serie'>
      </div></form>
    </div>
        <script type="text/javascript">
                $('#myModal').modal('show');
                $('#myModal').modal({
                  keyboard: false
                });
                $('#myModal').modal({
                  backdrop: true
                });

                function a(){
                    document.getElementById('radio1').style.color = '#57d1d1';
                    document.getElementById('radio2').style.color = 'white'; }
                function b(){
                    document.getElementById('radio1').style.color = 'white';
                    document.getElementById('radio2').style.color = '#57d1d1'; }
        </script>
    </body>
</html>