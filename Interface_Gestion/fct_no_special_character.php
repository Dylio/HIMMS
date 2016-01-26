<?php // Fonction pour supprimmer ou modifier les différents caractères spéciaux
function no_special_character($chaine){
    $chaine=utf8_encode($chaine);
    $a = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
    $b = 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
    $chaine = utf8_encode(strtr(utf8_decode($chaine), utf8_decode($a), utf8_decode($b)));
    // Ecrit la chaîne de caractères en miniscule
    $chaine = strtolower($chaine);
    // Supprime la ponctuation de la chaîne de caractères
    $carac = array('.' ,'!' ,'?' ,'-->' ,',' ,'<i>' ,'</i>' ,':' ,'"','|' ,'\'' ,'"' ,'-' ,';' ,'_' ,'&' ,'>' ,'<', '$', '\\', '/', '$', '€', '£', '+', '=', '[', ']', '*', '(', ')', '{', '}');
    $chaine = str_replace ($carac, ' ', $chaine);
    $chaine = str_replace ('œ', 'oe', $chaine);
    $chaine=trim($chaine);
    return $chaine;
} ?>