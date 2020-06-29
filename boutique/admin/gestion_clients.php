<?php

require_once("../inc/init.php");





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

    $count = $pdo->exec("INSERT INTO  membre(pseudo, mpd, nom, prenom, email, 
    civilite, ville, code_postal, adress, statut)
    VALUES( '$_POST[pseudo]', '$_POST[mpd]', '$_POST[nom]', '$_POST[prenom]',
    '$_POST[email]', '$_POST[civilite]', '$_POST[ville]', '$_POST[code_postal]' ,
    '$_POST[prix]', '$_POST[statut]' ) ");

    // Message de confirmation après ajout
    if($count >0 ){
        $content .= "<div class=\"alert alert-success\" role=\"alert\">
        Votre client " . $_POST["pseudo"] . " a bien été ajouté en base.
      </div>";
    }

}


////////////////////////////////////////////
//////////// RÉCUPÉRATION DES DETAILS COMMANDE À AFFICHER ////////////////
////////////////////////////////////////////

$r = $pdo->query("SELECT * FROM membre");

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
                
                <?php while($client = $r->fetch(PDO::FETCH_ASSOC)) { ?>
                    
                    <tr>

                        <?php foreach($client as $indice => $valeur) {
                            if($indice == "photo") { ?>
                                <td> <img class="img-fluid" style="width:40px" src="<?php echo $valeur; ?>">  </td>
                           <?php  } else{ ?>
                            <td> <?php echo $valeur;  ?>  </td>
                           <?php } ?>

                        <?php } ?>

                        <!-- LIEN DE MODIFICATION ET SUPPRESSION -->

                        <td>
                            <a href="?action=modification&id_membre= <?php echo $client['id_membre']; ?>" class="badge badge-dark"> Modifier </a>
                        </td>
                        <td>
                            <a href="?action=suppression&id_membre= <?php echo $client['id_membre']; ?>" class="badge badge-danger"> Supprimer </a>
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
                <label for="pseudo">Pseudo</label>
                <input type="text" class="form-control" id="pseudo" name="pseudo">
            </div>
            <div class="form-group col-md-3">
                <label for="mdp">Mdp</label>
                <input type="text" class="form-control" id="mdp" name="mdp">
            </div>
            <div class="form-group col-md-3">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom">
            </div>
            <div class="form-group col-md-3">
                <label for="prenom">Prenom</label>
                <input type="text" class="form-control" id="prenom" name="prenom">
            </div>
            <div class="form-group col-md-12">
                <label for="taille">Email</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="w-100"></div>
            <div class="form-group col-md-2">
                <label for="civilite_m">Civilite</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="civilite_m" name="civilite" class="custom-control-input" value="m">
                    <label class="custom-control-label" for="civilite_m">Masculin</label>
                </div>
            </div>
            <div class="form-group col-md-2">
                <label for="civilite_f" style="color:transparent">Civilite</label>
                <div class="custom-control custom-radio">
                    <input type="radio" id="civilite_f" name="civilite" class="custom-control-input" value="f">
                    <label class="custom-control-label" for="civilite_f">Féminin</label>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="stock">Ville</label>
                <input type="text" class="form-control" id="stock" name="stock">
            </div>
            <div class="form-group col-md-3">
                <label for="civilite_m">code_postal</label>
                <input type="text" class="form-control" id="stock" name="stock">
            </div>
            
            <div class="form-group col-md-3">
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