<?php
    require_once("inc/init.php");

    // Si je vide mon panier
    // alors je supprime la session panier
    if(isset($_GET["action"]) && $_GET["action"] == "viderPanier" ) {
        unset($_SESSION["panier"]);
    }

    ////////////////////////////////////////////
    //////////// PAYER ////////////////
    ////////////////////////////////////////////

    if(isset($_POST["payer"])) {

        // Vérifier la disponibilité de chacun des produits dans le panier
        // et donc en session

        for($i=0; $i < count($_SESSION["panier"]["id_produit"]); $i++) { // j'itère dans chaque produit en session
            $r = $pdo->query('SELECT * FROM produit WHERE id_produit = "' . $_SESSION['panier']['id_produit'][$i]  . '"');
            $produit = $r->fetch(PDO::FETCH_ASSOC);

            if($produit["stock"] < $_SESSION["panier"]["quantite"][$i] ) {
                // Si le stock du produit en base est inférieur à la quantité sélectionnée dans mon panier

                if($produit["stock"] > 0) { // il y a encore un peu de stock

                    $_SESSION["panier"]["quantite"][$i] = $produit["stock"];
                    $content .= "<div class=\"alert alert-warning\" role=\"alert\">
                    La quantité du produit " . $_SESSION["panier"]["titre"][$i] . 
                  " a été réduite car notre stock était insuffisant, veuillez vérifier vos
                  achats!</div>";

                } else { // plus de stock
                    $content .= "<div class=\"alert alert-warning\" role=\"alert\">
                    Le produit " . $_SESSION["panier"]["titre"][$i] . 
                  " a été retiré de votre panier car il est en rupture de stock, veuillez vérifier vos
                  achats!</div>";
                  retirerProduitPanier($_SESSION["panier"]["id_produit"][$i]);
                  $i--;
                }

                $erreur = true;
            }
            
        }

        ////////////////////////////////////////////
        //////////// COMMANDE ////////////////
        ////////////////////////////////////////////

        if(!isset($erreur)) { /// Si je n'ai pas d'erreur de stock au moment du paiement
            $idMembre = $_SESSION["membre"]["id_membre"];
            $montant = montantTotal();
            $pdo->exec("INSERT INTO commande(id_membre, montant, date_enregistrement)
            VALUES( '$idMembre', '$montant', NOW()) ");

            // Récupérer l'id de la dernière commande insérée (lastInsertId)
            $id_commande = $pdo->lastInsertid();
            // Le détail des commandes
            // Pour chaque produit de notre commande il faudra générer un détail de commande en base

            for($i=0; $i < count($_SESSION["panier"]["id_produit"]); $i++){
                // Je vais insérer un détail de commande
                // pour lier une commande a ses détails de commande
                $pdo->exec('INSERT INTO details_commande (id_commande, id_produit, quantite, prix)
                VALUES ("' . $id_commande  . '" ,
                "'. $_SESSION['panier']['id_produit'][$i] .'" ,
                "'.  $_SESSION['panier']['quantite'][$i] .'",
                "'.  $_SESSION['panier']['prix'][$i] .'" ) ');

                // Je vais mettre à jour le stock
                $pdo->exec(' UPDATE produit SET stock = stock - "' .  $_SESSION['panier']['quantite'][$i] . '"');

            }

            // supprimer la session panier
            unset($_SESSION["panier"]);
            // afficher un msg de succès
            $content .= "<div class=\"alert alert-success\" role=\"alert\">
            Merci pour votre commande, votre nº de suivi est le nº " . $id_commande  .
          "</div>";

        }

    }


    // L'idée générale c'est d'afficher dans le panier les produits sélectionnés
    // par l'internaute
    // possibilité de supprimer un produit du panier
    // possibilité de payer (de manière fictive)
    // avant le paiment revérifier le stock des produits séléctionnés


    ////////////////////////////////////////////
    //////////// AFFICHER LES PRODUITS DANS LE PANIER ////////////////
    ////////////////////////////////////////////

    // Je récuère le $_POST["ajout_panier"]
    // je récupère en base le produit qui a été ajouté depuis fiche_produit.php
    if(isset($_POST["ajout_panier"])) {
        
        $r = $pdo->query("SELECT * FROM produit WHERE id_produit =  '$_POST[id_produit]' ");
        $produit = $r->fetch(PDO::FETCH_ASSOC);
        // echo "<pre>";
        // var_dump($produit);
        // echo "</pre>";

        ////////////////////////////////////////////
        //////////// AJOUTER À LA SESSION LE PRODUIT SÉLECTIONNÉ ////////////////
        ////////////////////////////////////////////
        ajouterProduitDansPanier($produit["id_produit"], $_POST["quantite"], $produit["prix"],  $_POST["titre"]);
    
    }

    if(!empty($_SESSION["panier"]["id_produit"])){
        $content .= ' <a style="float:right;margin-bottom:20px" href="?action=viderPanier" class="badge badge-danger"> Vider mon panier </a>';
    }

    // Début de ma table
    $content .= '<table class="table">
        <thead class="thead-light">
            <tr>
            <th scope="col">Titre</th>
            <th scope="col">Quantité</th>
            <th scope="col">Prix</th>
            </tr>
        </thead>
        <tbody>';

        // Si la session panier est vide, alors j'affiche un message panier vide    
        if(empty($_SESSION["panier"]["id_produit"])){

            $content .= '<tr> <td colspan="3"> Votre panier est vide </td> </tr>';
            $content .= '</tbody> </table>';

        } else { // Si la session panier n'est pas vide, alors on affiche les produits dans le panier


            // contenu dynamique de la table
            for($i=0; $i < count($_SESSION["panier"]["id_produit"]); $i++) {
                $content .= "<tr>";
                $content .= "<td>" . $_SESSION["panier"]["titre"][$i] . "</td>";
                $content .= "<td>" . $_SESSION["panier"]["quantite"][$i] . "</td>";
                $content .= "<td>" . $_SESSION["panier"]["prix"][$i] . " €</td>";
                $content .= "</tr>";
            }  

            ////////////////////////////////////////////
            //////////// LE MONTANT TOTAL ////////////////
            ////////////////////////////////////////////

            $content .= '<td colspan="3" style="text-align:right"> <strong> Montant Total : </strong> ' . montantTotal() . ' €</td>'; 
            $content .= '</tbody> </table>';

            ////////////////////////////////////////////
            //////////// AFFICHER LE BOUTTON POUR PAYER ////////////////
            ////////////////////////////////////////////
            if(internauteEstConnecte()){
                $content .= '<form method="post" action="" style="float:right">';
                $content .= '<input class="btn btn-outline-secondary" type="submit" name="payer" value="Payer">';
                $content .= '</form>';
            } else {
                $content .= '<div style="float:right"><p> Veuillez-vous connecter pour payer </p>';
                $content .= '<a href="connexion.php" class="badge badge-dark"> Se connecter </a></div>';
            }

        }

        
    // Fermeture de la table 
    $content .= '<p class="badge badge-info">Règlement par chèque à l\'adresse suivante: 123 rue qqchose, 75000, Paris</p>';

require_once("inc/header.php");

?>

<?= $content; ?>
<!-- Equivalent de < ? = echo  -->
    

<?php
    require_once("inc/footer.php");
?>