<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>HIMMS - Administrateur</title>
        
        <!-- Importation des scripts et des stylesheet -->
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
        
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_admin_db.php';
            require_once 'class_admin_affichage.php';     // affichage global
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
            session_start();
            unset($_SESSION['admin']);
        ?>
    </head>
 
    <body>
        <h1 class="NomSite">ADMINISTRATION<br/><?php echo $str['site']['name']; ?></h1>
        <form class="form-horizontal" method="POST" style="width:50%; margin:auto;">
            <div class="form-group">
              <label for="inputEmail3" class="col-sm-4 control-label">Utilisateur :</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="admin" placeholder="Utilisateur" required>
              </div>
            </div>
            <div class="form-group">
              <label for="inputPassword3" class="col-sm-4 control-label">Mot de passe :</label>
              <div class="col-sm-8">
                <input type="password" class="form-control" name="password" required placeholder="Mot de passe">
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-offset-4 col-sm-10">
                <button type="submit" name="btn" class="btn btn-default">Se Connecter.</button>
              </div>
            </div>
          </form>
          <?php if(isset($_POST['btn'])){
              if($db->admin($_POST['admin'], $_POST['password']) == 1){
                $_SESSION['admin']=$_POST['admin'];
                header('Location:index.php');
              }else{
                  echo "ERREUR : Le nom d'utilistateur ou le mot de passe est incorrect...<br/>";
              }
          } ?>
    </body>
</html>
