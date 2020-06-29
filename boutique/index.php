<?php
    require_once("inc/init.php");

    // Cette page représente la page d'acceuil 
    // en gros le catalogue des produits

    // j'aimerais afficher les différentes catégories en base
    // et quand je clique sur une catégorie ça m'affiche tous les produits de ma catégories


    ////////////////////////////////////////////
    //////////// AFFICHER LA LISTE DE CATÉGORIES ////////////////
    ////////////////////////////////////////////

    // Récupérer les catégories
    $r = $pdo->query("SELECT DISTINCT(categorie) FROM produit");
    // itérer à l'intérieur et générer une liste
    $content .= '<div class="col-md-2">
    <ul class="list-group">';
    while($categorie = $r->fetch(PDO::FETCH_ASSOC)){
        $content .= "<li class=\"list-group-item\"> <a href=\"?categorie=$categorie[categorie]\">" . $categorie["categorie"] . " </a> </li>";
        // $content .= '<li class="list-group-item"> <a href="?categorie=' . $categorie["categorie"] .'">' . $categorie["categorie"] . '</a> </li>';
    }
    $content .= '</ul></div>';

    ////////////////////////////////////////////
    //////////// AFFICHER LES PRODUITS DE LA CATÉGORIE ////////////////
    ////////////////////////////////////////////

    // quand je clique sur une catégorie c'est à dire un lient
    // j'ai un rechargement de page
    // je rècupère le paramètre $_GET

    if(isset($_GET["categorie"])){
        // je fais un select en base avec la catégorie sélectionnée
        $r = $pdo->query("SELECT * FROM produit WHERE categorie = '$_GET[categorie]' ");
        // j'affiche les produits de la catégorie

        // Ouverture de la partie concernant les produits
        $content .= '<div class="col-md-10"> <div class="row">';

        while($produit = $r->fetch(PDO::FETCH_ASSOC)) { // j'itère dans mon PDOSTATEMENT EN FETCHANT LES DONNÉES EN ITÉRANT DANS CHAQUE ARRAY GÉNÉRÉ PAR LE FETCH

            // Génération de card boostrap à chaque fois qu'un produit est récupéré
            $content .= '<div class="card col-md-3">
                            <img src="' . $produit["photo"]  . '" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title">' . $produit["titre"]  . '</h5>
                                <p class="card-text">' . $produit["description"]  . '</p>
                                <a href="fiche-produit.php?id_produit='. $produit["id_produit"] .'" class="btn btn-primary">Plus d\'info</a>
                            </div> 
                        </div>';
        }

        // Fermeture concernant la partie des produits
        $content .=  '</div> </div>';

    }

require_once("inc/header.php");
?>

<div class="row">

    <?php echo $content; ?>

</div>


<?php
    require_once("inc/footer.php");
?>