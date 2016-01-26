<!DOCTYPE html>
<html>
    <head>
            <title>PTUTAGBD</title>	
            <meta charset="utf-8"/> 
    </head>
    <body>
        <?php include 'nav.php' ;
        if(!isset($_POST['btn'])){ ?>

            <form action="" method="post">
                <!-- Création d'un bouton pour valider le formulaire -->	
                <fieldset class="fieldset"><legend class="titre">Création de la BD : <input type="submit" value="Valider" name="btn"></legend>
                </fieldset>
            </form>

       <?php 
        }else{ 
            
             // Suppression des tables existantes
            $linkpdo->query("DROP TABLE IF EXISTS appartenir;");
            $linkpdo->query("DROP TABLE IF EXISTS soustitre;");
            $linkpdo->query("DROP TABLE IF EXISTS voir;");
            $linkpdo->query("DROP TABLE IF EXISTS interesser;");
            $linkpdo->query("DROP TABLE IF EXISTS motcle;");
            $linkpdo->query("DROP TABLE IF EXISTS serie;");
            $linkpdo->query("DROP TABLE IF EXISTS utilisateur;");
            
            //Création des tables
            $linkpdo->query('create table Serie(
                    num_serie varchar(30) primary key,
                    titre varchar(255) not null) CHARACTER SET utf8;');

            $linkpdo->query('create table MotCle(
                    num_motcle varchar(30) primary key,
                    motcle varchar(255) not null) CHARACTER SET utf8;');

            $linkpdo->query('create table Appartenir(
                    num_motcle varchar(30) not null,
                    num_serie varchar(30) not null,
                    occurrence int not null,
                    nbEp int not null,
                    CONSTRAINT pk_Appartenir PRIMARY KEY (num_motcle, num_serie),
                    CONSTRAINT fk_Appartenir_motcle FOREIGN KEY (num_motcle) REFERENCES MotCle(num_motcle) on delete cascade,
                    CONSTRAINT fk_Appartenir_serie FOREIGN KEY (num_serie) REFERENCES Serie(num_serie) on delete cascade) CHARACTER SET utf8;');

            //creation d'une table exclusion
            $linkpdo->query('create table Exclusion(
                    num_exclusion varchar(30) primary key,
                    libelle varchar(255) unique not null) CHARACTER SET utf8;');

            $linkpdo->query('create table SousTitre(
                    num_serie varchar(30) not null,
                    saison int not null,
                    episode int not null,
                    version varchar(2) not null,
                    CONSTRAINT pk_SousTitre PRIMARY KEY (num_serie, Saison, Episode, Version),
                    CONSTRAINT fk_SousTitre_serie FOREIGN KEY (num_serie) REFERENCES Serie(num_serie) on delete cascade) CHARACTER SET utf8;');	
            
            $linkpdo->query("create table utilisateur(
                    num_user varchar(30) primary key,
                    adresse varchar(20),
                    nbVisite int(5)) CHARACTER SET utf8 ;");
            
            $linkpdo->query("create table voir(
                    num_user varchar(30), 
                    num_serie varchar(30),
                    CONSTRAINT pk_voir PRIMARY KEY (num_user, num_serie),
                    CONSTRAINT fk_voir_user FOREIGN KEY (num_user) REFERENCES utilisateur(num_user) on delete cascade,
                    CONSTRAINT fk_voir_serie FOREIGN KEY (num_serie) REFERENCES Serie(num_serie) on delete cascade) CHARACTER SET utf8 ;");
                    
            $linkpdo->query("create table interesser(
                    num_user varchar(30), 
                    num_motcle varchar(30),
                    nbChercher int(5) not null,
                    CONSTRAINT pk_interesser PRIMARY KEY (num_user, num_motcle),
                    CONSTRAINT fk_interesser_user FOREIGN KEY (num_user) REFERENCES utilisateur(num_user) on delete cascade,
                    CONSTRAINT fk_interesser_motcle FOREIGN KEY (num_motcle) REFERENCES MotCle(num_motcle) on delete cascade) CHARACTER SET utf8 ;");

            $linkpdo->query("CHARACTER SET utf8");

            echo "La base de données a été créée.";
        }
        ?>
    </body>
</html>