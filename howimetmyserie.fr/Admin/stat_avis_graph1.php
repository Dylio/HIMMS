<?php
header('Content-type: image/jpg');
require_once 'class_admin_db.php';
$db = new class_admin_db();     // base de données 
$req = $db->questionnaire_user();
$tot= 0;
$q1_Oui=0;
$q1_Non=0;
$q2_Oui=0;
$q2_Non=0;
$q3_Oui=0;
$q3_Non=0;
$q4_Oui=0;
$q4_Non=0;
$q7_Oui=0;
$q7_Non=0;
while($data = $req->fetch()){
    if($data['question_1'] == 1){
        $q1_Oui++;
    }elseif($data['question_1'] == 0){
        $q1_Non++;
    }
    if($data['question_2'] == 1){
        $q2_Oui++;
    }elseif($data['question_2'] == 0){
        $q2_Non++;
    }
    if($data['question_3'] == 1){
        $q3_Oui++;
    }elseif($data['question_3'] == 0){
        $q3_Non++;
    }
    if($data['question_4'] == 1){
        $q4_Oui++;
    }elseif($data['question_4'] == 0){
        $q4_Non++;
    }
    if($data['question_7'] == 1){
        $q7_Oui++;
    }elseif($data['question_7'] == 0){
        $q7_Non++;
    }
    $tot++;
}

$q1_Oui*=100/$tot;
$q1_Non*=100/$tot;
$q2_Oui*=100/$tot;
$q2_Non*=100/$tot;
$q3_Oui*=100/$tot;
$q3_Non*=100/$tot;
$q4_Oui*=100/$tot;
$q4_Non*=100/$tot;
$q7_Oui*=100/$tot;
$q7_Non*=100/$tot;
$element=array();
array_push($element, 'Non');
array_push($element, 'Oui');

$js=array();
$min=0;
$max=100;
$i=6;
array_push($js, 'Q1');
array_push($js, 'Q2');
array_push($js, 'Q3');
array_push($js, 'Q4');
array_push($js, 'Q5');
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
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['0'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q1_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q1_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q1_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Oui', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q1_Oui,1,'.',''));
   $pasX+=50;
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['1'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q2_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q2_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q2_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Oui', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q2_Oui,1,'.',''));
   $pasX+=50;
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['2'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q3_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q3_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q3_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Oui', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q3_Oui,1,'.',''));
   $pasX+=50;
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['3'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q4_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q4_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q4_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Oui', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q4_Oui,1,'.',''));
   $pasX+=50;
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['4'], $noir);
   $pasX+=20;
   $y=($hauteur) -(($q7_Non -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q7_Non,1,'.',''));
   $pasX+=20;
   $y=($hauteur) -(($q7_Oui -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Oui', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($q7_Oui,1,'.',''));
   $pasX+=100;
imagestring($courbe, 2, $pasX+25,$hauteur-$absis+18 , $js['5'], $noir);
   $pasX+=20;
   $res = ($q1_Non+$q2_Non+$q3_Non+$q4_Non+$q7_Non)/5;
   $y=($hauteur) -(($res -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Non', $element)];
   imagefilledrectangle($courbe,$pasX-10 , $hauteur-$absis, $pasX+10, $y, $clr);
   imagefttext($courbe, 10, 270, $pasX-3, $y+5, $blanc, $font_file, number_format($res,1,'.',''));
   $pasX+=20;
   $res = ($q1_Oui+$q2_Oui+$q3_Oui+$q4_Oui+$q7_Oui)/5;
   $y=($hauteur) -(($res -$min) * ($echelleY/$py))-$absis;
   $clr=$couleur[array_search('Oui', $element)];
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
    imagestring($courbe, 2, $pasX,$pasY , $libelle, $couleur[$index]);
    //Un petit rectangle 
    imagefilledrectangle($courbe,$pasX+100 , $pasY, $pasX+120, $pasY+12, $couleur[$index]);
    $pasY+=20;
}

require_once 'lang.php';
$str = lang::getlang();
$lib_quest=array();
array_push($lib_quest, utf8_decode('Q1: '.$str['questionnaire']['1'].'.'));
array_push($lib_quest, utf8_decode('Q2: '.$str['questionnaire']['2'].'.'));
array_push($lib_quest, utf8_decode('Q3: '.$str['questionnaire']['3'].'.'));
array_push($lib_quest, utf8_decode('Q4: '.$str['questionnaire']['4'].'.'));
array_push($lib_quest, utf8_decode('Q5: '.$str['questionnaire']['7'].'.'));
//La legende
$pasX=250;
//Hauteur de la premiere
$pasY=$hauteur-$absis+40;
foreach ($lib_quest as $index=>$libelle){
    //Le nom du poduit avec sa couleur
    imagestring($courbe, 2, $pasX,$pasY , $libelle, $noir);
    $pasY+=20;
}

imagepng($courbe);
imagedestroy($courbe);