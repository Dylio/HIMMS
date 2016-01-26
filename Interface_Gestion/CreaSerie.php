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
            // Création de la série dés que l'utilisateur appuye sur le bouton
            if(isset($_POST['btn'])){
                // Création d'une ID (Génère un identifiant unique basé sur la date et heure courante en microsecondes.)
                $idS = uniqid(rand(), true);
                // Récupération du titre de la série
                $titreS = $_POST['Serie'];
                // Requete de création de la série
                $linkpdo->query("INSERT INTO Serie(num_serie, titre) values('$idS', '$titreS');");
            }
            ?>

            <!-- Création d'un formulaire -->		
            <form action="" method="post">
                <!-- Création d'une entrée de texte -->
                <fieldset class="fieldset"><legend class="titre">Nouvelle Série :</legend>
                <label for="Serie">Serie :</label><input type="text" name="Serie">

                <!-- Création d'un bouton pour valider le formulaire -->	
                <input type="submit" value="Valider" name="btn">
                </fieldset>
            </form>
	</body>
</html>