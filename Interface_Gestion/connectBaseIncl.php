<?php 
// Fixe le temps maximum d'exécution d'un script (S'il vaut 0, aucune limite)
set_time_limit (0);
try {
	// Création de la connection avec la BDD
	// Nom de la variable contenant la connection linkpdo
	// Nom de la base de données : test 
	// Host : Localhost
	// Aucun nom d'utilisateur
	// Aucun mdp
	$linkpdo = new PDO("mysql:host=localhost;dbname=test;charset=utf8", '','');
}catch (Exception $e){
	// Gérer les exception
	die('Erreur : ' . $e->getMessage());
}
?>