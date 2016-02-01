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
            $affichage = new class_affichage();
        ?>
    </head>
    
    <body style='background: url(css/cgu.png) no-repeat center fixed; background-size: 100% 100%; min-width: 1250px;'>
    
    Conditions générales d'utilisation du site <b>HOW I MET MY SERIE</b>

    ARTICLE 1 : Objet

    Les présentes « conditions générales d'utilisation » ont pour objet l'encadrement juridique des modalités de mise à disposition des services du site HowIMetMySerie.fr et leur utilisation par « l'Utilisateur ».

    Les conditions générales d'utilisation doivent être acceptées par tout Utilisateur souhaitant accéder au site. Elles constituent le contrat entre le site et l'Utilisateur. L’accès au site par l’Utilisateur signifie son acceptation des présentes conditions générales d’utilisation.

    Éventuellement :

        En cas de non-acceptation des conditions générales d'utilisation stipulées dans le présent contrat, l'Utilisateur se doit de renoncer à l'accès des services proposés par le site.

        HowIMetMySerie.fr se réserve le droit de modifier unilatéralement et à tout moment le contenu des présentes conditions générales d'utilisation.

    ARTICLE 2 : Mentions légales

    L'édition du site HowIMetMySerie.fr est assurée par la Société Acr'ô Films dont le siège social est situé à Toulouse.

    ARTICLE 3 : Définitions

    La présente clause a pour objet de définir les différents termes essentiels du contrat :

        Utilisateur : ce terme désigne toute personne qui utilise le site ou l'un des services proposés par le site.

        Contenu utilisateur : ce sont les données transmises par l'Utilisateur au sein du site.

    ARTICLE 4 : accès aux services

    Le site permet à l'Utilisateur un accès gratuit aux services suivants :

        Possibilité de création d'une liste de séries favorites ;

        Possibilité de création d'une liste de mots clés intéressés ;


    Le site est accessible gratuitement en tout lieu à tout Utilisateur ayant un accès à Internet. Tous les frais supportés par l'Utilisateur pour accéder au service (matériel informatique, logiciels, connexion Internet, etc.) sont à sa charge.


    ARTICLE 5 : Propriété intellectuelle

    Les marques, logos, signes et tout autre contenu du site font l'objet d'une protection par le Code de la propriété intellectuelle et plus particulièrement par le droit d'auteur.

    L'Utilisateur s'engage à une utilisation des contenus du site dans un cadre strictement privé. Une utilisation des contenus à des fins commerciales est strictement interdite.

    Tout contenu mis en ligne par l'Utilisateur est de sa seule responsabilité. L'Utilisateur s'engage à ne pas mettre en ligne de contenus pouvant porter atteinte aux intérêts de tierces personnes. Tout recours en justice engagé par un tiers lésé contre le site sera pris en charge par l'Utilisateur.

    Le contenu de l'Utilisateur peut être à tout moment et pour n'importe quelle raison supprimé ou modifié par le site. L'Utilisateur ne reçoit aucune justification et notification préalablement à la suppression ou à la modification du contenu Utilisateur.

    ARTICLE 6 : Données personnelles

    Les informations demandées à l’inscription au site sont nécessaires et obligatoires pour l'accés de l'Utilisateur au site.

    Le site assure à l'Utilisateur aucune collecte et un traitement d'informations personnelles dans le respect de la vie privée conformément à la loi n°78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers et aux libertés.

    En vertu des articles 39 et 40 de la loi en date du 6 janvier 1978, l'Utilisateur dispose d'un droit d'accès, de rectification, de suppression et d'opposition de ses données personnelles. L'Utilisateur exerce ce droit via :

        un formulaire de contact.

    ARTICLE 7 : Responsabilité et force majeure

    Les sources des informations diffusées sur le site sont réputées fiables. Toutefois, le site se réserve la faculté d'une non-garantie de la fiabilité des sources. Les informations données sur le site le sont à titre purement informatif. Ainsi, l'Utilisateur assume seul l'entière responsabilité de l'utilisation des informations et contenus du présent site.

    L'Utilisateur s'assure de garder son mot de passe secret. Toute divulgation du mot de passe, quelle que soit sa forme, est interdite.

    L'Utilisateur assume les risques liés à l'utilisation de son identifiant et mot de passe. Le site décline toute responsabilité.

    Tout usage du service par l'Utilisateur ayant directement ou indirectement pour conséquence des dommages doit faire l'objet d'une indemnisation au profit du site.

    Une garantie optimale de la sécurité et de la confidentialité des données transmises n'est pas assurée par le site. Toutefois, le site s'engage à mettre en œuvre tous les moyens nécessaires afin de garantir au mieux la sécurité et la confidentialité des données.

    La responsabilité du site ne peut être engagée en cas de force majeure ou du fait imprévisible et insurmontable d'un tiers.

    ARTICLE 8 : Liens hypertextes

    De nombreux liens hypertextes sortants sont présents sur le site, cependant les pages web où mènent ces liens n'engagent en rien la responsabilité de [Nom du site] qui n'a pas le contrôle de ces liens.

    L'Utilisateur s'interdit donc à engager la responsabilité du site concernant le contenu et les ressources relatives à ces liens hypertextes sortants.

    ARTICLE 9 : Évolution du contrat

    Le site se réserve à tout moment le droit de modifier les clauses stipulées dans le présent contrat.

    ARTICLE 10 : Durée

    La durée du présent contrat est indéterminée. Le contrat produit ses effets à l'égard de l'Utilisateur à compter de l'utilisation du service.

    ARTICLE 11 : Droit applicable et juridiction compétente

    La législation française s'applique au présent contrat. En cas d'absence de résolution amiable d'un litige né entre les parties, seuls les tribunaux du ressort de la Cour d'appel de la ville de Toulouse sont compétents.
</body>    