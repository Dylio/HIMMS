<?php 
class Db {
    private static $instance = NULL;

    private function __construct() {}

    public static function getInstance() {
       $host= "localhost";
       $dbname= "test";
       $charset= "utf8";
       $user= "";
       $mdp= "";
      if (!isset(self::$instance)) {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        self::$instance = new PDO("mysql:$host=localhost;dbname=$dbname;charset=$charset", "$user","$mdp");
      }
      return self::$instance;
    }
}