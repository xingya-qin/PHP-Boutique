<?php
    require_once("inc/init.php");

    // L'idée générale est de pouvoir se connecter à son profil

    // si je suis dans le cadre d'une deconnexion
    if(isset($_GET["action"]) && $_GET["action"] == "deconnexion"){
        unset($_SESSION["membre"]); // je supprimer ma session membre dans ma session
    }

    // Si je suis déjà connecté
    // je suis redirigé vers la page profil
    if(internauteEstConnecte()){
        header("location:profil.php");
    }

    if($_POST){

        // Je récupère les infos liées au pseudo renseigné
        $r = $pdo->query("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");
        if($r->rowCount() >= 1) { // si j'ai des données liées au pseudo renseigné

            $membre = $r->fetch(PDO::FETCH_ASSOC);
            // echo $membre["mdp"]; LE mot de passe crypté

            if(password_verify($_POST["mdp"], $membre["mdp"])) { // Si le mot de passe renseigné
                // dans le formulaire est le même que le mot de passe du pseudo en BDD

                // je créé ma session
                $_SESSION["membre"]["id_membre"] = $membre["id_membre"];
                $_SESSION["membre"]["nom"] = $membre["nom"];
                $_SESSION["membre"]["prenom"] = $membre["prenom"];
                $_SESSION["membre"]["email"] = $membre["email"];
                $_SESSION["membre"]["civilite"] = $membre["civilite"];
                $_SESSION["membre"]["code_postal"] = $membre["code_postal"];
                $_SESSION["membre"]["adresse"] = $membre["adresse"];
                $_SESSION["membre"]["ville"] = $membre["ville"];
                $_SESSION["membre"]["statut"] = $membre["statut"];

                // Je redirige vers la page profil.php
                header("location:profil.php");

            } else {
                $content .= "<div class=\"alert alert-danger\" role=\"alert\">
                Veuillez vérifier votre mot de passe!
              </div>";
            }

        } else {
            $content .= "<div class=\"alert alert-danger\" role=\"alert\">
                Veuillez vérifier votre pseudo!
              </div>";
        }

    }

require_once("inc/header.php");

?>


<div class="row col-md-10 mx-auto justify-content-center">

    <?php echo $content; ?>

    <form action="" method="post">

    <div class="form-group">
        <label for="pseudo">Pseudo</label>
        <input type="text" class="form-control" id="pseudo" placeholder="Entrez votre pseudo" name="pseudo">
    </div>

    <div class="form-group">
        <label for="mdp">Mot de passe</label>
        <input type="password" class="form-control" id="mdp" placeholder="Mot de passe" name="mdp">
    </div>
    
    <button type="submit" class="btn btn-primary">Me connecter</button>

    </form>

</div>

<?php
    require_once("inc/footer.php");
?>