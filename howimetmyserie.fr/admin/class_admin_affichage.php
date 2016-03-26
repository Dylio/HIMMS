<?php
// Cette classe permet l'affichage les données.
class class_admin_affichage{
    private  $_str;             // constantes textuelles du site web
    private  $_db;              // instance vers la base de données
    
    // Constructeur de la classe class_media_object
    // IN : $db instance vers la base de données
    // IN : $str constantes textuelles du site web
    public function __construct($db, $str){
        $this->_db = $db;
        $this->_str = $str;
    }
    
    public function affichage_site($partie){
        echo "<p class='NomPartie3'>$partie</p>";
    }
    
    public function affichage_menu($i){
        echo "<p class='navbar-text' style='margin-top: -12px !important;'>"
            . "<span class='NomPartie'> <a href='./index.php'>HIMMS</a> </span>"
            . "<ul class='nav nav-tabs nav-pills nav-justified'>"
                . "<li role='presentation' ";
                    if($i == 1){ echo "class='active'"; }
                    echo "><a href='serie.php'>Mes Séries TV <span class='badge'>".$this->_db->nb_serie()."</span></a>"
                . "</li>"
                . "<li role='presentation' ";
                    if($i == 2){ echo "class='active'"; }
                    echo "><a href='serie_SRT.php'>Ajouter SRT</a>"
                . "</li>"
                . "<li role='presentation' class='dropdown ";
                    if($i == 3 or $i == 4){ echo "active"; }
                    echo "'>"
                    . "<a class='dropdown-toggle' data-toggle='dropdown' href='#' role='button' aria-haspopup='true' aria-expanded='false'>"
                      . "Mes Statistiques</span></a>"
                    . "<ul class='dropdown-menu'>"
                        ."<li role='presentation' ";
                        if($i == 3){ echo "class='active'"; }
                        echo "><a href='stat_visiteur.php?annee=".date('Y')."'>Les Visites</a></li>"
                        ."<li role='presentation' ";
                        if($i == 4){ echo "class='active'"; }
                        echo "><a href='stat_avis.php'>Les Avis</a></li>"
                    . "</ul>"
                . "</li>"
                . "<li role='presentation' ";
                    if($i == 5){ echo "class='active'"; }
                    echo "><a href='messages.php'>Mes Messages <span class='badge'>".$this->_db->messagerie_nonlu()."</span></a>"
                . "</li>"
                . "<li role='presentation'>"
                        . "<a href='index_1.php'><span class='glyphicon glyphicon-off'></span></a>"
                . "</li>"
            . "</ul>"
        . "</p>";
    }
    
    public function affichage_menu_serie($i, $num_serie){
        echo "<ul class='nav nav-pills nav-stacked' style='float:left; width:25%;'>"
            . "<li role='presentation' ";
                if($i == 1){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=1&num_serie=$num_serie'>Modification des détails</a></li>"
            . "<li role='presentation' ";
                if($i == 2){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=2&num_serie=$num_serie'>Ajouter un nouveau sous-titre</a></li>"
            . "<li role='presentation' ";
                if($i == 3){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=3&num_serie=$num_serie'>Liste des sous-titres insérés</a></li>"
            . "<li role='presentation' ";
                if($i == 4){ echo "class='active'"; }
                echo "><a href='./serie_detail.php?opt=4&num_serie=$num_serie'>Liste des mots-clés</a></li>"
        . "</ul>";
    }
    
    public function affichage_serie($req){
        require_once 'class_admin_media_object.php';
        echo '<div class="SerieContainerIner">';
            $media_object = new class_admin_media_object($this->_db, $this->_str);
            while($data = $req->fetch()){
               $media_object->newSerieDetail($data);
               $media_object->media_object();
            }
        echo '</div>';
    }

    public function affichage_messagerie(){
        if(isset($_POST['opt_lu'])){
            $j=$_POST['opt_lu'];
        }else{
            $j=0;
        }
        echo "<form action='' method='POST' class='formMessage'>"
            .'<button type="submit" name="opt_lu" value=1 ';
                if($j==1){
                    echo "class='btn btn-success buttonTriW' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriW'>";
                }
                echo "Archive"
            ."</button> "
            .'<button type="submit" name="opt_lu" value=0 ';
                if($j == 0){
                    echo "class='btn btn-success buttonTriW' disabled>";
                }else{
                    echo "class='btn btn-info buttonTriW'>";
                }
                echo "Boite de réception"
            ."</button>"   
        . "</form><br/>";  
        $req = $this->_db->messagerie($j);
        echo "<table class='table table-striped table-responsive table-condensed tab_messagerie'>"
            . "<tr>"
                . "<th class='alert-info tab_col_1'></th>"
                . "<th class='alert-info tab_col_2'>De</th>"
                . "<th class='alert-info tab_col_3'>Sujet</th>"
                . "<th class='alert-info tab_col_4'>Date</th>"
                . "<th class='alert-info tab_col_5'></th>"
            . "</tr>";
            $i = 0;
            while($data = $req->fetch()){ 
                $i++;
                echo "<tr class='collapsed' role='button' data-toggle='collapse' data-parent='#accordion' href='#$i' aria-expanded='false' aria-controls='collapseTwo'>"
                    . "<th class='tab_col_1'>";
                        if($data['lu'] == 0){
                            echo '<span class="glyphicon glyphicon-edit" style="color:red;"/>';
                        }else{
                            echo '<span class="glyphicon glyphicon-check" style="color:green;"/>';
                        }
                    echo "</th>"
                    . "<th class='tab_col_2'>".$data['pseudo']."</th>"
                    . "<th class='tab_col_3'>".$data['sujet']."</th>"
                    . "<th class='tab_col_4'>".date('d/m/Y',strtotime($data['dateContact']))."</th>"
                    . "<th class='tab_col_5'><span class='glyphicon glyphicon-zoom-in'></span></th>"
                . "</tr>"
                . "<tr id='$i' class='panel-collapse collapse'>"
                    . "<td colspan=5>"
                        . "<form method='POST'>"
                            . "<input type='hidden' name='num_user' value='".$data['num_user']."'>"
                            . "<input type='hidden' name='dateContact' value='".$data['dateContact']."'>";
                            if(isset($_POST['opt_lu'])){
                                echo '<input type="hidden" name="opt_lu" value='.$_POST['opt_lu'].' >';
                            }
                            if($data['lu'] == 0){
                                echo "<button type='submit' class='btn' name='lu' value='0' style='float:right; color:red;'>"
                                    . 'Marqué comme lu'
                                . '</button>';
                            }else{
                                echo "<button type='submit' class='btn' name='lu' value='1' style='float:right; color:green;'>"
                                    . "Marqué comme non lu"
                                . "</button>";
                            }
                        echo "</form>"
                        . "Adresse Email : <a href='mailto:".$data['email']."?subject=HIMMS - ".$data['sujet']."'>".$data['email']."</a><br/>"
                        . "Sujet : ".$data['sujet']."<br/>"
                        . "Message : ".$data['texte']
                    . "</td>"
                . "</tr>";
            }
        echo "</table>";
    }
    
    public function message_lu(){
        if(isset($_POST['lu'])){
            if($_POST['lu'] == 0){
                $this->_db->update_messagerie_lu(1, $_POST['num_user'], $_POST['dateContact']);
            }else{
                $this->_db->update_messagerie_lu(0, $_POST['num_user'], $_POST['dateContact']);
                
            }
        }
    }
}