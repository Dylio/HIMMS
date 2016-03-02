<?php // constantes textuelles du site web
require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>HIMMS - Administrateur</title>
        
        <!-- Importation des scripts et des stylesheet -->
        <script src="../js/jquery.js"></script>
        <script src="../js/bootstrap.min.js"></script>
        <link href="../css/bootstrap.min.css" rel="stylesheet">
        <link href="../style.css" rel="stylesheet">
        
        <?php // création et gestion des classes permettant l'affichage et le fonctionnement des évènements
            require_once 'class_admin_db.php';
            require_once 'class_admin_affichage.php';     // affichage global
            $db = new class_admin_db();     // base de données 
            $affichage = new class_admin_affichage($db, $str);
        ?>
    </head>
 
    <body>
        <?php 
        $affichage->message_lu();
        $affichage->affichage_menu(4);
        $affichage->affichage_site('MES MESSAGES'); ?>
        <table class="table table-striped table-responsive table-condensed" style="overflow:auto; width:90%; margin: auto;">
            <tr>
                <th class="alert-info" style='font-size: 25px; width:5%'></th>
                <th class="alert-info" style='font-size: 25px; width:25%'>De</th>
                <th class="alert-info" style='font-size: 25px; width:50%'>Sujet</th>
                <th class="alert-info" style='font-size: 25px; width:15%'>Date</th>
                <th class="alert-info" style='font-size: 25px; text-align:right; width:5%'></th>
            </tr>
            <?php $i = 0;
            $req = $db->messagerie();
            while($data = $req->fetch()){ 
                $i++; ?>
                <tr class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#<?php echo $i; ?>" aria-expanded="false" aria-controls="collapseTwo">
                    <th>
                        <?php if($data['lu'] == 0){
                            echo '<button type="submit" class="btn" name="lu" value="0" style="float:right; color:red;">'
                                . '<span id="$titre" class="glyphicon glyphicon-edit"/>'
                            . '</button>';
                        }else{
                            echo '<button type="submit" class="btn" name="lu" value="1" style="float:right; color:green;">'
                                . '<span id="$titre" class="glyphicon glyphicon-check"/>'
                            . '</button>';
                        } ?>
                    </th>
                    <th style='font-size: 25px; width:25%'><?php echo $data['pseudo']; ?></th>
                    <th style='font-size: 25px; width:50%'><?php echo $data['sujet']; ?></th>
                    <th style='font-size: 25px; width:15%'><?php echo date('d/m/Y',strtotime($data['dateContact']));?></th>
                    <th style='font-size: 25px; width:5%; text-align:right; width:5%'><span class="glyphicon glyphicon-zoom-in"></span></th>
                </tr>
                <tr id="<?php echo $i; ?>" class="panel-collapse collapse">
                    <td colspan=5>
                        <form method="POST">
                            <input type="hidden" name="num_user" value="<?php echo $data['num_user']; ?>">
                            <input type="hidden" name="dateContact" value="<?php echo $data['dateContact']; ?>" >
                            <?php if($data['lu'] == 0){
                                echo '<button type="submit" class="btn" name="lu" value="0" style="float:right; color:red;">'
                                    . 'Marqué comme lu <span id="$titre"/>'
                                . '</button>';
                            }else{
                                echo '<button type="submit" class="btn" name="lu" value="1" style="float:right; color:green;">'
                                    . 'Marqué comme non lu <span id="$titre"/>'
                                . '</button>';
                            } ?>
                        </form>
                        Adresse Email : <?php echo "<a href='mailto:".$data['email']."?subject=HIMMS - ".$data['sujet']."'>".$data['email']."</a>"; ?><br/>
                        Sujet : <?php echo $data['sujet']; ?><br/>
                        Message : <?php echo $data['texte']; ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    </body>
</html>
