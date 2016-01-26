<!DOCTYPE html>
<html>
    <head>
        <title>PTUTAGBD</title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <?php include 'nav.php' ;
        flush();
        ob_flush();
        $req1 = $linkpdo->query("SELECT s.titre, s.num_serie from serie s order by 1;");
        while($data1 = $req1->fetch()){
            echo '<table style="width:50%">
            <tr><td colspan="3">Série : '.$data1['titre'].' </td></tr>
            <tr>
                <td>Saison</td>
                <td>Episode</td>
                <td>Version</td>
            </tr><tr>';
            $req2 = $linkpdo->query("SELECT t.Saison, t.Episode, t.Version from soustitre t where t.num_serie = '".$data1['num_serie']."' order by 1, 2, 3;");
            while($data2 = $req2->fetch()){
                echo'<tr><td style="width:4%">'.$data2['Saison'].'</td><td style="width:4%">'.$data2['Episode'].'</td><td style="width:4%">'.$data2['Version'].'</td></tr>';
            }
            echo '</tr></table></br>';
        }?>
    <br /><br /></body>
</html>