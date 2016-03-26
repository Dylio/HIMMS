<?php
header('Content-type: image/jpg;');
require_once 'class_admin_db.php';
$db = new class_admin_db();     // base de données 
$req = $db->questionnaire_user();
$tot= 0;
$q5_Oui=0;
$q5_Moyen=0;
$q5_Non=0;
$q6_Oui=0;
$q6_Moyen=0;
$q6_Non=0;
while($data = $req->fetch()){
    if($data['question_5'] == 2){
        $q5_Oui++;
    }elseif($data['question_5'] == 1){
        $q5_Moyen++;
    }elseif($data['question_5'] == 0){
        $q5_Non++;
    }
    if($data['question_6'] == 2){
        $q6_Oui++;
    }elseif($data['question_6'] == 1){
        $q6_Moyen++;
    }elseif($data['question_6'] == 0){
        $q6_Non++;
    }
    $tot++;
}
$q5_Oui*=100/$tot;
$q5_Moyen*=100/$tot;
$q5_Non*=100/$tot;
$q6_Oui*=100/$tot;
$q6_Moyen*=100/$tot;
$q6_Non*=100/$tot;
$element=array();
array_push($element, 'Non Satisfait');
array_push($element, 'Moyennement Satisfait');
array_push($element, 'Satisfait');

$js=array();
$min=0;
$max=100;
$i=3;
array_push($js, 'Q1');
array_push($js, 'Q2');
array_push($js, 'Total');

//Chemin vers le police à utiliser
$font_file = './arial.ttf';
$largeur=750;
$hauteur=550;
$absis=130;
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

$absis+=10;

$nbOrdonne=10;
//Calculer les échelles suivants les abscisses et ordonnées
$echelleX=($largeur-50-((count($js)*10)))/$i;
$echelleY=($hauteur-$absis-20)/$nbOrdonne;
$i=$min;
$py=($max-$min)/$nbOrdonne;
$pasY=$absis;
//Tracer les grides
while($pasY<($hauteur-19)){
    imagestring($courbe, 2,10 , $hauteur-$pasY-6, round($i).'%', $noir);
    imageline($courbe, 50, $hauteur-$pasY, $largeur-20,$hauteur-$pasY, $ligne);
    $pasY+=$echelleY;
    $i+=$py;
}

$pasX=60;
imagestring($courbe, 2, $pasX+35,$hauteur-$absis+18 , $js['0'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q5_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q5_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q5_Moyen -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Moyennement Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q5_Moyen,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q5_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q5_Oui,1,'.',''));
   $pasX+=50;
imagestring($courbe, 2, $pasX+35,$hauteur-$absis+18 , $js['1'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q6_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q6_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q6_Moyen -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Moyennement Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q6_Moyen,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q6_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q6_Oui,1,'.',''));
   $pasX+=100;
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['2'], $noir);
   $pasX+=20;
   $res = ($q5_Non+$q6_Non)/2;
   $y=($hauteur) -(($res -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($res,1,'.',''));
   $pasX+=20;
   $res = ($q5_Moyen+$q6_Moyen)/2;
   $y=($hauteur) -(($res -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Moyennement Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($res,1,'.',''));
   $pasX+=20;
   $res = ($q5_Oui+$q6_Oui)/2;
   $y=($hauteur) -(($res -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Satisfait', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($res,1,'.',''));
   $pasX+=50;
   
//La legende
$pasX=50;
//Hauteur de la premiere
$pasY=$hauteur-$absis+40;
foreach ($element as $index=>$libelle){
    if(($index % 4)==3){
        $pasX+=600;
        $pasY=$hauteur-$absis+47;
    }
    //Le nom du poduit avec sa couleur
    imagestring($courbe, 2, $pasX,$pasY, utf8_decode($libelle), $couleur[$index]);
    //Un petit rectangle 
    imagefilledrectangle($courbe,$pasX+170 , $pasY, $pasX+150, $pasY+12, $couleur[$index]);
    $pasY+=20;
}

require_once 'lang.php';
$str = lang::getlang();
$lib_quest=array();
array_push($lib_quest, 'Q1: '.$str['questionnaire']['5'].'.');
array_push($lib_quest, 'Q2: '.$str['questionnaire']['6'].'.');
//La legende
$pasX=250;
//Hauteur de la premiere
$pasY=$hauteur-$absis+40;
foreach ($lib_quest as $index=>$libelle){
    //Le nom du poduit avec sa couleur
    imagestring($courbe, 2, $pasX,$pasY , utf8_decode($libelle), $noir);
    $pasY+=20;
}
imagepng($courbe);
imagedestroy($courbe);