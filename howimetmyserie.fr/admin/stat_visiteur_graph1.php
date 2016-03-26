<?php
header('Content-type: image/jpg');
require_once 'class_admin_db.php';
$db = new class_admin_db();     // base de données 

$element=array();
array_push($element, 'Total');
array_push($element, 'Nouveau');

$js=array();
$min=0;
$annee = $_GET['annee'];
$max=$db->nb_user_inf_year($annee);
$min_date = date('m/y',strtotime('01/01/'.$annee));
$annee += 1;
$max_date = date('m/y',strtotime('01/01/'.$annee));
$i=0;
while($min_date != $max_date && $min_date != date('m/y',strtotime('+1 month', strtotime(date('m/y'))))){
    array_push($js, $min_date);
    $min_date = date('d/m/y',strtotime('01/'.$min_date));
    $min_date = date('m/y',strtotime('+1 month', strtotime($min_date)));
    $i++;
}

//Chemin vers le police à utiliser
$font_file = './arial.ttf';
//Adapter la largeur de l'image par rapport au nombre de ligne et nombre de mois
//$largeur=$i*40+(count($js)*20)+125;
$largeur=800;
$hauteur=450;
$absis=75;
$courbe=imagecreatetruecolor($largeur, $hauteur);
//Générer un tableau de couleurs
$couleur=array();
$red=0;
$blue=0;
$green=0;
for($n=0;$n<count($element);$n++){
    $x = $n%3;
    switch ($x){
        case(0):
            $red+=125;
            break;
        case(1):
            $blue+=125;
            break;
        case(2):
            $green+=125;
            break;
    }
    $couleur[$n]=imagecolorallocate($courbe, $red,$green , $blue);
}
//Les autre couleurs utils
$ligne=imagecolorallocate($courbe, 220, 220, 220);
$fond=imagecolorallocate($courbe, 250, 250, 250);
$noir=imagecolorallocate($courbe, 0, 0, 0);
$blanc=imagecolorallocate($courbe, 255, 255, 255);
$rouge=imagecolorallocate($courbe, 255, 0, 0);
//Colorer le fond
imagefilledrectangle($courbe,0 , 0, $largeur, $hauteur, $fond);
//Tracer l'abscisse et l'ordonnée
imageline($courbe, 50, $hauteur-$absis, $largeur-10,$hauteur-$absis, $noir);
imageline($courbe, 50,$hauteur-$absis,50,20, $noir);

$absis+=30;

$nbOrdonne=10;
//Calculer les échelles suivants les abscisses et ordonnées
$echelleX=($largeur-50-((count($js)*10)))/$i;
$echelleY=($hauteur-$absis-20)/$nbOrdonne;
$i=$min;
$py=($max-$min)/$nbOrdonne;
$pasY=$absis;
//Tracer les grides
while($pasY<($hauteur-19)){
    imagestring($courbe, 2,10 , $hauteur-$pasY-6, round($i), $noir);
    imageline($courbe, 50, $hauteur-$pasY, $largeur-20,$hauteur-$pasY, $ligne);
    $pasY+=$echelleY;
    $i+=$py;
}

$pasX=60;
foreach($js as $date){
    $date_pro = date('d/m/y',strtotime('01/'.$date));
    $mois = date('m',strtotime($date_pro));
    $annee = date('Y',strtotime($date_pro));
    $date_pro = "$annee-$mois-31";
    $date_pro2 = "$annee-$mois";
    $nb = $db->nb_user_inf_date($date_pro);
    $nb2 = $db->nb_user_egal_date($date_pro2);
    imagestring($courbe, 1, $pasX+15,$hauteur-$absis+34 , $date, $noir);
       $pasX+=20;
       $y=($hauteur) -(($nb -$min) * ($echelleY/$py))-$absis;
       $clr=$couleur[array_search('Total', $element)];
       imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
       imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, $nb);
       $pasX+=20;
       $y=($hauteur) -(($nb2 -$min) * ($echelleY/$py))-$absis;
       $clr=$couleur[array_search('Nouveau', $element)];
       imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
       imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, $nb2);
       $pasX+=20;
}
//La legende
$pasX=50;
//Hauteur de la premiere
$pasY=$hauteur-$absis+60;
foreach ($element as $index=>$libelle){
    if(($index % 4)==3){
        $pasX+=600;
        $pasY=$hauteur-$absis+47;
    }
    //Le nom du poduit avec sa couleur
    imagestring($courbe, 2, $pasX,$pasY , $libelle, $couleur[$index]);
    //Un petit rectangle 
    imagefilledrectangle($courbe,$pasX+120 , $pasY, $pasX+100, $pasY+12, $couleur[$index]);
    $pasY+=20;
}
imagepng($courbe);
imagedestroy($courbe);