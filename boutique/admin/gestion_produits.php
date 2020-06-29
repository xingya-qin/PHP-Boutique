<?php

require_once("../inc/init.php");

////////////////////////////////////////////
//////////// SUPPRIMER UN PRODUIT ////////////////
////////////////////////////////////////////

if(isset($_GET["action"]) && $_GET["action"] == "suppression") {
    $count = $pdo->exec("DELETE FROM produit WHERE id_produit = $_GET[id_produit]");
    if($count > 0){
        $content .= "<div class=\"alert alert-success\" role=\"alert\">
        Le produit Nº" . $_GET["id_produit"] . " a bien été supprimé en base !
      </div>";
    }
}



////////////////////////////////////////////
//////////// AJOUT/MODIFICATION ////////////////
////////////////////////////////////////////

if($_POST) {

    ////////////////////////////////////////////
    //////////// TRAITEMENT DE LA PHOTO ////////////////
    ////////////////////////////////////////////

    // echo "<pre>";
    // var_dump($_FILES);
    // echo "</pre>";

    // Récupérer le nom de la photo
    $nomPhoto = $_POST["reference"] . "_" . $_FILES["photo"]["name"];
    // Enregistrer en BDD le chemin vers la photo
    $cheminPhotoPourBDD = URL . "photo/" . $nomPhoto;
    // Enregister/copier la photo sur le serveur
    // Fichier de destination à copier
    $dossier_pour_enregistrer_photo = RACINE_SITE . "photo/" . $nomPhoto;

    // Fichier de départ à copier
    // il correspond au fichier temporaire uploadé au niveau de l'input type file
    // il faut récupérer le répertoire de ce fichier temporaire uploadé et le copié vers le répértoire de destination
    // tmp_name correspond au fichier chargé que l'on souhaite copier
    copy($_FILES["photo"]["tmp_name"], $dossier_pour_enregistrer_photo);

    foreach($_POST as $indice=>$valeur){
        $_POST[$indice] = addslashes($valeur);
    }

    ////////////////////////////////////////////
    //////////// AJOUT DE PRODUIT ////////////////
    ////////////////////////////////////////////

    $count = $pdo->exec("INSERT INTO produit (reference, categorie, titre, description, couleur, 
    taille, public, photo, prix, stock)
    VALUES( '$_POST[reference]', '$_POST[categorie]', '$_POST[titre]', '$_POST[description]',
    '$_POST[couleur]', '$_POST[taille]', '$_POST[public]', '$cheminPhotoPourBDD' ,
    '$_POST[prix]', '$_POST[stock]' ) ");

    // Message de confirmation après ajout
    if($count >0 ){
        $content .= "<div class=\"alert alert-success\" role=\"alert\">
        Votre produit " . $_POST["titre"] . " a bien été ajouté en base.
      </div>";
    }

}


////////////////////////////////////////////
//////////// RÉCUPÉRATION DES PRODUITS À AFFICHER ////////////////
////////////////////////////////////////////

$r = $pdo->query("SELECT * FROM produit");

require_once("inc/header.php");

?>

<div class="col-md-10">

    <?php echo $content; ?>

    <div class="table-responsive">

        <table class="table col-md-12">
            <thead class="thead-dark">
                <tr>

                    <!-- JE RÉCUPÈRE LE NOM DE MES COLONNES EN BDD -->
                    <!-- POUR GÉNÉRER LES TH DE MA TABLE HTML DYNAMIQUEMENT -->

                    <?php for($i=0; $i< $r->columnCount(); $i++ ) { ?>
                        <th> <?php echo $r->getColumnMeta($i)["name"]; ?> </th>
                    <?php } ?>

                    <th> modification </th>
                    <th> suppresion </th>

                </tr>
            </thead>
            <tbody>

                <!-- JE FETCH DANS LE JEU DE RÉSULTAT DE MA REQUÊTE SQL (PDOSTATEMENT)-->
                
                <?php while($produit = $r->fetch(PDO::FETCH_ASSOC)) { ?>
                    
                    <tr>

                        <?php foreach($produit as $indice => $valeur) {
                            if($indice == "photo") { ?>
                                <td> <img class="img-fluid" style="width:40px" src="<?php echo $valeur; ?>">  </td>
                           <?php  } else{ ?>
                            <td> <?php echo $valeur;  ?>  </td>
                           <?php } ?>

                        <?php } ?>

                        <!-- LIEN DE MODIFICATION ET SUPPRESSION -->

                        <td>
                            <a href="?action=modification&id_produit= <?php echo $produit['id_produit']; ?>" class="badge badge-dark"> Modifier </a>
                        </td>
                        <td>
                            <a href="?action=suppression&id_produit= <?php echo $produit['id_produit']; ?>" class="badge badge-danger"> Supprimer </a>
                        </td>
                    
                    </tr>
                    
                <?php } ?>

            </tbody>
        </table>

    </div>


    <!-- Formulaire d'ajout/modification de produit -->

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="reference">Reference</label>
                <input type="text" class="form-control" id="reference" name="reference">
            </div>
            <div class="form-group col-md-3">
                <label for="categorie">Categorie</label>
                <input type="text" class="form-control" id="categorie" name="categorie">
            </div>
            <div class="form-group col-md-3">
                <label for="titre">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre">
            </div>
            <div class="form-group col-md-3">
                <label for="couleur">Couleur</label>
                <input type="text" class="form-control" id="couleur" name="couleur">
            </div>
            <div class="form-group col-md-3">
                <label for="taille">Taille</label>
                <input type="text" class="form-control" id="taille" name="taille">
            </div>
            <div class="form-group col-md-3">
                <label for="prix">Prix</label>
                <input type="text" class="form-control" id="prix" name="prix">
            </div>
            <div class="form-group col-md-3">
                <label for="stock">Stock</label>
                <input type="text" class="form-control" id="stock" name="stock">
            </div>
            <div class="w-100"></div>
            <div class="form-group col-md-2">
                <label for="public_m">Public</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="public_m" name="public" class="custom-control-input" value="m">
                    <label class="custom-control-label" for="public_m">Masculin</label>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="public_f" style="color:transparent">Public</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="public_f" name="public" class="custom-control-input" value="f">
                    <label class="custom-control-label" for="public_f">Féminin</label>
                </div>
            </div>
            <div class="custom-file mb-3">
                <input type="file" class="custom-file-input" id="photo" name="photo">
                <label class="custom-file-label" for="photo">Choisir une photo</label>
            </div>
            <div class="form-group col-md-12">
                <label for="description">Description</label>
                <input type="text" class="form-control" id="description" name="description">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" name="ajouterProduit">Ajouter un produit</button>
    </form>
</div>


<?php
require_once("inc/footer.php");
?>