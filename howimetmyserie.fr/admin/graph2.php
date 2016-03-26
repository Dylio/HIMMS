<?php
header('Content-type: image/png');
require_once 'class_admin_db.php';
$db = new class_admin_db();     // base de données 
$data = $db->nb_user();
$date_min = date('m-y',strtotime($data['1']));
$date_max = date('m-y',strtotime($data['2']));
$m=array(02.16, 03.16, 04.16, 05.16, 06.16, 07.16, 08.16, 09.16, 06.17);

$resultat=array(12,17,17,17,7,17,17,17,7);
$min=0;
$nb=0;
foreach ($resultat as $index => $valeur){
    if($nb==0){$max=$valeur;}
    else{if($valeur > $max){$max=$valeur;}};
    $nb++;
}

$largeur=800;
$hauteur=400;
$absis=50;
$courbe=imagecreatetruecolor($largeur, $hauteur);
$bleu=imagecolorallocate($courbe, 0, 0, 255);
$ligne=imagecolorallocate($courbe, 220, 220, 220);
$fond=imagecolorallocate($courbe, 250, 250, 250);
$noir=imagecolorallocate($courbe, 0, 0, 0);
$rouge=imagecolorallocate($courbe, 255, 0, 0);
imagefilledrectangle($courbe,0 , 0, $largeur, $hauteur, $fond);
imageline($courbe, 50, $hauteur-$absis, $largeur-10,$hauteur-$absis, $noir);
imageline($courbe, 50,$hauteur-$absis,50,20, $noir);

if($min==0){
    $min=0;
}else{
    $absis+=10;
}
$nbOrdonne=10;
$pasY=$absis;
$echelleX=($largeur-100)/count($m);
$echelleY=($hauteur-$absis-20)/$nbOrdonne;
$i=$min;
$py=($max-$min)/$nbOrdonne;
while($pasY<($hauteur-19)){
    imagestring($courbe, 2,10 , $hauteur-$pasY-6, $i, $noir);
    imageline($courbe, 50, $hauteur-$pasY, $largeur-20,$hauteur-$pasY, $ligne);
    $pasY+=$echelleY;
    $i+=$py;
}
 $j=-1;
 $pasX=90;
 foreach ($m as $index => $valeur){
   $y=($hauteur) -(($resultat[$index] -$min) * ($echelleY/$py))-$absis;
   imagefilledellipse($courbe, $pasX, $y, 6, 6, $rouge);
   imagestring($courbe, 2, $pasX-20,$hauteur-40 , $valeur, $noir);
   imageline($courbe, $pasX, $hauteur-50, $pasX,$y, $noir);
   if($j<>-1){
      imageline($courbe,($pasX-$echelleX),$yprev,$pasX,$y,$noir);
    }
   imagestring($courbe, 2, $pasX-15,$y-14 , $resultat[$index], $bleu);
   $j=$resultat[$index];
   $yprev=$y;
   $pasX+=$echelleX;
 }
  imagepng($courbe);
  imagedestroy($courbe);
?>