<?php require_once 'lang.php';
$str = lang::getlang(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $str['site']['name2']; ?></title>	
        <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet">
        <script type="text/javascript"> $(function () {
            $('[data-toggle="tooltip"]').tooltip();
        })</script>
        <?php
            require_once 'class_db.php';
            require_once 'class_affichage.php';
            $bd = new class_db();
            $num_user = $bd->user("false");
            $affichage = new class_affichage($num_user);
        ?>
    </head>
    
    <body style='background: url(css/cgu.png) no-repeat center fixed; background-size: 100% 100%; min-width: 1250px;'>
         <?php $affichage->affichage_titrePartie("<span style='font-size:35px'>Conditions générales d'utilisation<br/>du site howimetmyserie.fr</span>"); ?>
        
        <div class="jumbotron SerieDetailContainer2">
            <span class="cguArticle">ARTICLE 1 : Objet</span><br/>
            Les présentes « conditions générales d'utilisation » ont pour objet l'encadrement juridique des modalités de mise à disposition des services du site HowIMetMySerie.fr et leur utilisation par « l'Utilisateur ».<br/>
            Les conditions générales d'utilisation doivent être acceptées par tout Utilisateur souhaitant accéder au site. Elles constituent le contrat entre le site et l'Utilisateur. L’accès au site par l’Utilisateur signifie son acceptation des présentes conditions générales d’utilisation.<br/>
            En cas de non-acceptation des conditions générales d'utilisation stipulées dans le présent contrat, l'Utilisateur se doit de renoncer à l'accès des services proposés par le site.<br/>
            HowIMetMySerie.fr se réserve le droit de modifier unilatéralement et à tout moment le contenu des présentes conditions générales d'utilisation.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 2 : Mentions légales</span><br/>
            L'édition du site HowIMetMySerie.fr est assurée par la Société LP Prod dont le siège social est situé à Toulouse.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 3 : Définitions</span><br/>
            La présente clause a pour objet de définir les différents termes essentiels du contrat :<br/>
                <ul><li>Utilisateur : ce terme désigne toute personne qui utilise le site ou l'un des services proposés par le site.</li>
                    <li>Contenu utilisateur : ce sont les données transmises par l'Utilisateur au sein du site.</li></ul>
            <br<br/><br/>
            
            <span class="cguArticle">ARTICLE 4 : accès aux services</span><br/>
            Le site permet à l'Utilisateur un accès gratuit aux services suivants :<br/>
                <ul><li>Consultation de toutes les séries proposé par howimetmyserie (dans la limite du légal) ;</li>
                    <li>Possibilité de création d'une liste de séries favorites ;</li>
                    <li>Possibilité de création d'une liste de mots clés intéressés ;</li>
                    <li>Consultation d'une liste de série recommander selon les goût (séries favorites et mots clés intéressés) de l'utilisateur ;</li></ul>
            Le site est accessible gratuitement en tout lieu à tout Utilisateur ayant un accès à Internet.<br/>
            Rappels sur les cookies : Les « cookies » sont des fichiers texte déposés sur le disque dur de l’internaute par le serveur du site web visité. Les cookies permettent de mémoriser des choix faits par l’internaute lors de sa navigation sur un site web. Ils peuvent notamment être utilisés pour faciliter une authentification, mémoriser les préférences de consultation du site. Chaque cookie a sa propre durée de vie d'une durée d'un an. A chaque visite, la durée de vie est réactualisé pour un an de plus. Passé ce délais, les préférences de l'utilisateur seront supprimmer.
            <br/><br/><br/>

            <span class="cguArticle">ARTICLE 5 : Propriété intellectuelle</span><br/>
            Les marques, logos, signes et tout autre contenu du site font l'objet d'une protection par le Code de la propriété intellectuelle et plus particulièrement par le droit d'auteur.<br/>
            Toutes les séries et contenus (textes, images ...) proposés sur howimetmyserie.fr sont des séries appartenant exclusivement à LP Prod.<br/>
            L'Utilisateur s'engage à une utilisation des contenus du site dans un cadre strictement privé. Une utilisation des contenus à des fins commerciales est strictement interdite.<br/>
            Tout contenu mis en ligne par l'Utilisateur est de sa seule responsabilité. L'Utilisateur s'engage à ne pas mettre en ligne de contenus pouvant porter atteinte aux intérêts de tierces personnes. Tout recours en justice engagé par un tiers lésé contre le site sera pris en charge par l'Utilisateur.<br/>
            Le contenu de l'Utilisateur peut être à tout moment et pour n'importe quelle raison supprimé ou modifié par le site. L'Utilisateur ne reçoit aucune justification et notification préalablement à la suppression ou à la modification du contenu Utilisateur.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 6 : Données personnelles</span><br/>
            Les informations demandées à l’inscription au site sont nécessaires et obligatoires pour l'accés de l'Utilisateur au site.<br/>
            Le site assure à l'Utilisateur aucune collecte et un traitement d'informations personnelles dans le respect de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers et aux libertés.<br/>
            En vertu des articles 39 et 40 de la loi en date du 6 janvier 1978, l'Utilisateur dispose d'un droit d'accès, de rectification, de suppression et d'opposition de ses données personnelles. L'Utilisateur exerce ce droit via : un formulaire de contact.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 7 : Responsabilité et force majeure</span><br/>
            Les sources des informations diffusées sur le site sont réputées fiables. Toutefois, le site se réserve la faculté d'une non-garantie de la fiabilité des sources. Les informations données sur le site le sont à titre purement informatif. Ainsi, l'Utilisateur assume seul l'entière responsabilité de l'utilisation des informations et contenus du présent site.<br/>
            L'Utilisateur s'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle que soit sa forme, est interdite.<br/>
            L'Utilisateur assume les risques liés à l'utilisation de son identifiant et mot de passe. Le site décline toute responsabilité.<br/>
            Tout usage du service par l'Utilisateur ayant directement ou indirectement pour conséquence des dommages doit faire l'objet d'une indemnisation au profit du site.<br/>
            Une garantie optimale de la sécurité et de la confidentialité des données transmises n'est pas assurée par le site. Toutefois, le site s'engage à mettre en œuvre tous les moyens nécessaires afin de garantir au mieux la sécurité et la confidentialité des données.<br/>
            La responsabilité du site ne peut être engagée en cas de force majeure ou du fait imprévisible et insurmontable d'un tiers.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 8 : Évolution du contrat</span><br/>
            Le site se réserve à tout moment le droit de modifier les clauses stipulées dans le présent contrat.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 9 : Durée</span><br/>
            La durée du présent contrat est indéterminée. Le contrat produit ses effets à l'égard de l'Utilisateur à compter de l'utilisation du service.
            <br/><br/><br/>
            
            <span class="cguArticle">ARTICLE 10 : Droit applicable et juridiction compétente</span><br/>
            La législation française s'applique au présent contrat. En cas d'absence de résolution amiable d'un litige né entre les parties, seuls les tribunaux du ressort de la Cour d'appel de la ville de Toulouse sont compétents.
            <br/><br/>
        </div> 
    </body>
</html>