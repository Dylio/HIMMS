<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
 
    <body>
        <?php
            require_once 'class_db.php';            // base de données
            $db = new class_db();
            echo "HELLO WORLD !<br/><br/>" ;
            
            echo "Nombre total de visiteur : ".$db->nb_user()."</br>";
            
            
        ?>
    </body>
</html>
