<?php
    require_once("inc/init.php");

    // L'idée générale c'est de s'inscrire sur le site

    if($_POST){

        // Vérification
        $erreur = '';
        // Vérifier si le pseudo a entre 3 et 20 caractères (strlen)
        if(strlen($_POST["pseudo"]) < 3 || strlen($_POST["pseudo"]) > 20){
            $erreur .= "<div class=\"alert alert-danger\" role=\"alert\">
            Le pseudo doit contenir entre 4 et 19 caractères !
          </div>";
        }
        // Vérifier que le pseudo est une valeur alphanumérique (preg_match)
        if (!preg_match('/^[a-zA-Z]+[a-zA-Z0-9._]+$/', $_POST["pseudo"])) {
            $erreur .= "<div class=\"alert alert-danger\" role=\"alert\">
            Le pseudo doit contenir une valeur alpha numérique !
          </div>";
        }
        // Vérifier en base si le pseudo est disponible (select * from membre where pseudo = '$_POST[pseudo]')
        $r = $pdo->query("SELECT * FROM membre WHERE pseudo = '$_POST[pseudo]' ");
        if($r->rowCount() >= 1) { // Si la requête a renvoyé au moins un résultat c'est que le pseudo n'est pas disponible
            $erreur .= "<div class=\"alert alert-danger\" role=\"alert\">
            Veuillez choisir un autre pseudo car celui-ci n'est pas disponible!
          </div>";
        }

        // Si j'ai pas d'erreur je fais l'insert
        // Si j'ai des erreurs je les affiche à l'écran ($content)

        // Pour éviter les erreurs au niveau de l'insert,
        // on va échapper pour chaque donnée du formulaire
        // les caractères succeptibles de provoquer des erreurs SQL
        // comme l'apostrophe grâce à la méthode php addslashes();

        foreach($_POST as $indice=>$valeur){
            $_POST[$indice] = addslashes($valeur);
        }

        // Pour des raisons de sécurité nous allons crypter le mdp
        $_POST["mdp"] = password_hash($_POST["mdp"], PASSWORD_DEFAULT);

        // Insert en BDD

        if(empty($erreur)) {
            $count = $pdo->exec("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite,
            ville, code_postal, adresse, statut)
            VALUES('$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]', '$_POST[email]',
            '$_POST[civilite]', '$_POST[ville]', '$_POST[code_postal]', '$_POST[adresse]', '2' ) ");
    
            if($count > 0) {
                $content .= "<div class=\"alert alert-success\" role=\"alert\">
                Votre inscription a bien été réalisée !
                </div>";
            }
        }

        // J'affiche les erreurs dans ma page en concaténant les msg d'erreurs dans $content
        $content .= $erreur;

    }
require_once("inc/header.php");
?>  

    <div class="row col-md-10 mx-auto justify-content-center">

        <?php echo $content; ?>

        <form action="" method="POST">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" placeholder="Pseudo" name="pseudo">
                </div>
                <div class="form-group col-md-6">
                    <label for="password">Mot de passe</label>
                    <input type="password" class="form-control" id="password" placeholder="Password" name="mdp">
                </div>
                <div class="form-group col-md-3">
                    <label for="name">Nom</label>
                    <input type="text" class="form-control" id="name" placeholder="Name" name="nom">
                </div>
                <div class="form-group col-md-3">
                    <label for="firstName">Prénom</label>
                    <input type="text" class="form-control" id="firstName" placeholder="First Name" name="prenom">
                </div>
                <div class="form-group col-md-3">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" placeholder="Email" name="email">
                </div>

                <div class="form-group col-md-3">
                <label for="email">Email</label>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="civilitem" value="m" name="civilite" checked>
                        <label class="form-check-label" for="civilitem">
                        Masculin
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="civilitef" value="f" name="civilite">
                        <label class="form-check-label" for="civilitef">
                        Féminin
                        </label>
                    </div>
                </div>

            </div>

            <!-- Address -->

            <div class="form-group">
                <label for="address">Adresse</label>
                <input type="text" class="form-control" id="address" name="adresse" placeholder="1234 Main St">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputCity">Ville</label>
                    <input type="text" class="form-control" name="ville" id="inputCity" placeholder="Soissons">
                </div>

                <div class="form-group col-md-2">
                    <label for="inputZip">Code postal</label>
                    <input type="text" class="form-control" id="inputZip" name="code_postal" placeholder="02200">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Créer mon compte</button>

        </form>

    </div>


<?php
    require_once("inc/footer.php");
?>