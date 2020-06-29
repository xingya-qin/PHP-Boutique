<!DOCTYPE html>
<html>
<head>
    <title>Boutique PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="inc/css/style.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>
    <body>

        <div class="container">

        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-5">
            <a class="navbar-brand" href="index.php">Catalogue</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="panier.php">Panier</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Membre</a>
                        <div class="dropdown-menu">

                            <?php if(!internauteEstConnecte()) { ?>
                                <a class="dropdown-item" href="inscription.php">Inscription</a>
                                <a class="dropdown-item" href="connexion.php">Connexion</a>
                            <?php } else{ ?>
                                <a class="dropdown-item" href="profil.php">Mon profil</a>
                            <?php } ?>

                        </div>
                    </li>
                    
                    <?php if(internauteEstConnecteEtEstAdmin()) { ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin/index.php">BackOffice</a>
                        </li>
                    <?php } ?>

                    <!-- Si l'internaute est connecté il pourra se déconnecter -->
                    <?php if(internauteEstConnecte()) { ?>

                        <li title="Me déconnecter">
                            <a href="connexion.php?action=deconnexion">
                                <svg id="button_disconnect" class="bi bi-x-circle-fill" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-4.146-3.146a.5.5 0 0 0-.708-.708L8 7.293 4.854 4.146a.5.5 0 1 0-.708.708L7.293 8l-3.147 3.146a.5.5 0 0 0 .708.708L8 8.707l3.146 3.147a.5.5 0 0 0 .708-.708L8.707 8l3.147-3.146z"/>
                                </svg>
                            </a>
                        </li>
                    
                    <?php } ?>

                </ul>
            </div>
        </nav>


