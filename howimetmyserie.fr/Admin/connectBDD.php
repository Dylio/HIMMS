<?php 
class Db {
    private static $instance = NULL;

    private function __construct() {}

    // Création d'une instance de connection vers la bd
    public static function getInstance() {
       $host= "localhost";  // Hébergement 
       $dbname= "test";     // Table
       $charset= "utf8";    // Encodage
       $user= "";           // Utilisateur
       $mdp= "";            // Mot de passe
      if (!isset(self::$instance)) { // vérification si la conncetion n'est pas déjà faite.
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; // gestion des erreurs de connection.
        self::$instance = new PDO("mysql:$host=localhost;dbname=$dbname;charset=$charset", "$user","$mdp"); // intance de la connection à la bd
      }
      return self::$instance;
    }
}