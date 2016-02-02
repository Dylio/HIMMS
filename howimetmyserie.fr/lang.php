<?php 
class lang {
    private static $lang = NULL;

    private function __construct() {}

    public static function getlang() {
        $lang = $fr_class = $en_class = '';
        /* Récupération de la langue dans la chaîne get */
        $lang = (isset($_GET['lang']) && file_exists('lang/'.$_GET['lang'].'.json')) ? $_GET['lang'] : 'fr';
        /* Définition de la class pour les liens de langue */
        if ($lang == 'fr'){
            $fr_class = ' class="active"';
        }else{
            $eng_class = ' class="active"';
        }
        /* Récupération du contenu du fichier .json */
        $contenu_fichier_json = file_get_contents('lang/'.$lang.'.json');
        /* Les données sont récupérées sous forme de tableau (true) */
        self::$lang = json_decode($contenu_fichier_json, true);
        return self::$lang;
    }
}