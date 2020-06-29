<?php
    require_once("inc/init.php");

    // idée générale : afficher les informations produit du produit sélectionné
    // depuis index.php
    if(!isset($_GET["id_produit"])) { // si je n'ai pas de paramètre $_GET["id_produit"]
        header("location:index.php"); // je suis redirigé vers index.php
        exit();
    }
    
    // Je récupère mon paramètre $_GET["id_produit"]
    // je requête en base avec la valeur de id_produit récupérée
    if(isset($_GET["id_produit"])){
        $r = $pdo->query("SELECT * FROM produit WHERE id_produit = '$_GET[id_produit]' ");
        if($r->rowCount() <= 0){ header("location:index.php"); exit(); } // Si le produit n'éxiste pas en base je redirige
        $produit = $r->fetch(PDO::FETCH_ASSOC); // Je récupère le produit
    }

require_once("inc/header.php");
?>

<div class="row col-md-10 mx-auto justify-content-center">
    <div class="card col-md-4">
        <img class="card-img-top" src="<?php echo $produit["photo"]; ?>" alt="Card image cap">
        <div class="card-body">
            <p class="card-text text-center font-weight-bold"> <?php echo $produit["titre"]; ?> </p>
            <p class="card-text text-center"> <?php echo $produit["description"]; ?> </p>
        </div>
    </div>

    <div class="col-md-4">

        <ul class="list-group">
            <li class="list-group-item"><span class="title"> Catégorie : </span> <?php echo $produit["categorie"]; ?> </li>
            <li class="list-group-item"><span class="title"> Couleur : </span> <?php echo $produit["couleur"]; ?> </li>
            <li class="list-group-item"><span class="title"> Taille : </span> <?php echo $produit["taille"]; ?> </li>
            <li class="list-group-item"><span class="title"> Prix: </span> <?php echo $produit["prix"]; ?> € </li>
            <!-- CRÉATION D'UN FORMULAIRE POUR RÉCUPÉRER LE PRODUIT SELECTIONNÉ ET LA QUANTITÉ POUR L'AJOUTER AU PANIER -->
            <form method="POST" action="panier.php">
                <li class="list-group-item">
                    <p><span class="title"> Quantité: </span> </p>
                    <input type="hidden" name="id_produit" value="<?php echo $produit["id_produit"]; ?>">
                    <input type="hidden" name="titre" value="<?php echo $produit["titre"]; ?>">
                    <select class="custom-select" id="selectQuantity" name="quantite">
                    <!-- Je créé dynamiquement la quantité sélectionnable dans la limite du stock -->
                        <option disabled selected>Choisir une quantité</option>
                        <?php for($i=1; $i <= $produit["stock"]; $i++) { ?> 
                            <option value="<?php echo $i;?>"> <?php echo $i; ?> </option>
                        <?php } ?>
                    </select>
                </li>
        </ul>
        
            <input class="btn btn-outline-secondary mt-5" disabled id="ajoutPanier" type="submit" name="ajout_panier" style="width:100%;" value="Ajouter au Panier">
        </form>
        <a href="index.php?categorie=<?php echo $produit["categorie"]; ?>" class="badge badge-dark"> Retour vers la catégorie </a>
    </div>

</div>


<?php
    require_once("inc/footer.php");
?>