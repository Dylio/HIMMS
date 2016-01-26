<!DOCTYPE html>
<html>
    <head>
            <title>PTUTAGBD</title>	
            <meta charset="utf-8"/> 
    </head>
    <body>
        <?php include 'nav.php' ;
        flush();
        ob_flush();
        include('fct_no_special_character.php'); 
        ?> 

        <!-- Création d'un formulaire -->		
        <form action="" method="post">
                <!-- Création d'une entrée de texte -->
                <fieldset class="fieldset"><legend class="titre">Nouveau Mot Exclu :</legend>
                <label for="MotExclu">Mot Exclu :</label><input type="text" name="MotExclu">

                <!-- Création d'un bouton pour valider le formulaire -->	
                <div class="sub"><input type="submit" value="Valider" name="btn"></div>
                </fieldset>
        </form>
        
        <?php // Création de la série dés que l'utilisateur appuye sur le bouton
        if(isset($_POST['btn'])){
            // Récupération d'une libelle
            $MotExclu = $_POST['MotExclu'];
            $MotExclu = no_special_character($MotExclu);
            // Tableau contenant chaque mot 
            $MotExclu = explode(" ", $MotExclu);
            // Pour chaque mot ...
            foreach ($MotExclu as $Mot){
                $linkpdo->exec("Delete from MotCle where motcle='$Mot';");
                $req1 = $linkpdo->query("Select count(*) from Exclusion where libelle = '$Mot';");
                $data1 = $req1->fetch();
                // Si il existe pas, création du mot clé et de l'association avec une occurence de $occ
                if($data1['0'] == 0){
                    // Création d'une ID (Génère un identifiant unique basé sur la date et heure courante en microsecondes.)
                    $idE = uniqid(rand(), true);
                    // Requete de création 
                    $linkpdo->exec("INSERT INTO Exclusion values('$idE', '$Mot');");
                    // Requete de supression
                    $linkpdo->exec("Delete from MotCle where motcle='$Mot';");
                    echo "\"$Mot\" ajouté à la table Exclusion. <br />";
                }else{
                    echo "\"$Mot\" non ajouté à la table Exclusion ~ Déjà existant.<br />";
                }
            }
        } ?>
    </body>
</html>