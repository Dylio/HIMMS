<?php
header('Content-type: image/jpg');
require_once 'class_admin_db.php';
$db = new class_admin_db();     // base de données 

$element=array();
$element['Viste = 1'] = $db->nb_user_visite_borne(1,1);
$element['Viste >= 2 et <= 9'] = $db->nb_user_visite_borne(2,9);
$element['Viste >= 10'] = $db->nb_user_visite_borne(10,'nbVisite');
$total = $element['Viste = 1']+$element['Viste >= 2 et <= 9']+$element['Viste >= 10'];
$largeur=400;
$hauteur=350;
$courbe=imagecreatetruecolor($largeur, $hauteur);
$couleur=array();
$red=20;$blue=20;$green=20;
$nbe=count($element);
$pas=round(100*3/($nbe));
//Génération des couleurs pour chaque produit
for($n=0;$n<$nbe;$n++){
    $x = $n%3;
    switch ($x){
        case(0):
            $red+=150;
            break;
        case(1):
            $blue+=150;
            break;
        case(2):
            $green+=150;
            break;
    }
    $couleur[$n][0]=imagecolorallocate($courbe, $red,$green+20,$blue-20);
    //Couleur sombre pour l'effet 3D
    $couleur[$n][1]=imagecolorallocate($courbe, $red,$green,$blue);
}
$ligne=imagecolorallocate($courbe, 220, 220, 220);
$fond=imagecolorallocate($courbe, 250, 250, 250);
$noir=imagecolorallocate($courbe, 0, 0, 0);
imagefilledrectangle($courbe,0 , 0, $largeur, $hauteur, $fond);
//Creation de l'effet 3D
//Dessiner des arc remplis de 20px d'épeseur 
for ($i = 150; $i > 130; $i--){
//Angle de début pour le premier produit
$debut=0;
$j=0;
foreach ($element as $libelle=>$quantite)
{
   //Calcul de l'angle correspondant à la quantité de produit vendu
   $valeur=$quantite/$total*360;
   //Calcul de l'angle de la fin pour le produit
   $fin=$debut+$valeur;
   //Dessiner l'arc
   imagefilledarc($courbe, 200, $i, 350, 220, $debut,$fin, $couleur[$j][1], IMG_ARC_PIE);
   $j++;
   //L'angle de début pour le produit suivant
   $debut=$fin;
}
   }
$j=0;
$pasX=20;
$pasY=270;
//Dessiner les arcs claires
foreach ($element as $libelle=>$quantite){
  $valeur=$quantite/$total*360;
   $fin=$debut+$valeur;
   imagefilledarc($courbe, 200, $i, 350, 220, $debut, $fin, $couleur[$j][0], IMG_ARC_PIE);
   $debut=$fin;
   //Légende
   //Mettons 4 produits par colonne
   if(($j % 5)==4){
        $pasX+=150;
        $pasY=270;
    }
    //Le nom du produit et la quatité vendue avec la couleur qui lui est attribué
    imagestring($courbe, 2, $pasX,$pasY , $libelle.': '.$quantite, $couleur[$j][1]);
    //Le petit rectangle qui designe la couleur
    imagefilledrectangle($courbe,$pasX+160 , $pasY, $pasX+140, $pasY+12, $couleur[$j][0]);
    $pasY+=20;
    $j++;
}
imagepng($courbe);
imagedestroy($courbe);
?>