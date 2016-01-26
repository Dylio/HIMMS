<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="style.css" rel="stylesheet">
<?php header("Content-Type: text/html; charset=UTF-8");
include'connectBaseIncl.php';  
if(isset($_COOKIE['log'])){
    $id = $_COOKIE['log'];
    setcookie('log', $id , time() + 365*24*3600, null, null, false, true);
    if(!isset($_COOKIE['PHPSESSID'])){
        $linkpdo->query("UPDATE utilisateur set nbVisite = nbVisite + 1 where num_user = '$id' ;");
        session_start();
    }else{    
        session_start();
    }
}else{
    $id = uniqid(rand(), true);
    setcookie('log', $id , time() + 365*24*3600, null, null, false, true);
    include'connectBaseIncl.php';
    $linkpdo->query("INSERT INTO utilisateur values('".$id."', 1);");
    session_start();
} ?>


<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
        <li><a class="navbar-brand" href="index.php"><span class="navTitre">HIMMS</span></a></li>
    </div>
    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-left">
        <li class="dropdown">
         <a href="#" class="dropdown-toggle ItemNav" data-toggle="dropdown" role="button" aria-expanded="false" style="color : violet;">CréaBDD ↓ </a>
         <ul class="dropdown-menu" role="menu">
          <li class="dropdown-submenu">
           <a href="Creabase.php">CreaBase</a>
          </li>
          <li class="dropdown-submenu">
           <a href="CreaSerie.php">CreaSérie</a>
          </li>
          <li class="dropdown-submenu">
            <a href="InsertSRTMulti2.php">Inserer SRT - Version</a>
          </li>
          <li class="dropdown">
            <a href="CreaMotExclu.php">Mots à Exclure</a>
          </li>
         </ul>
        </li>
        <li class="dropdown">
          <a href="ResumeSousTitre.php" class="ItemNav" style="color : #d5d5d5;">Sous-titres BDD</a>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<?php flush();
    ob_flush();?>

<nav class="navbar navbar-default navbar-fixed-bottom">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-left">
        <li><a href="#" style="color : #222222;">Interface de gestion de la BDD</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#" style="color : #222222;">Acr'ô Films</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div>
</nav>
<?php flush();
 ob_flush(); ?>