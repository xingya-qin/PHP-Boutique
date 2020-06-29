<?php
require_once("inc/init.php");

// Si l'internaute n'est pas connecté je le redirige vers la page de connexion
if(!internauteEstConnecte()){
    header("location:connexion.php");
    exit();
}

require_once("inc/header.php");
?>

<div class="row col-md-10 mx-auto justify-content-center">

    <!-- Condition sur le message de bienvenu -->

    <?php if($_SESSION["membre"]["civilite"] == "m") { ?>
            <h2> Bonjour Mr <?php echo $_SESSION["membre"]["nom"];  ?>, bienvenu sur votre espace personnel ! </h2>
    <?php } else { ?>
        <h2> Bonjour Mme <?php echo $_SESSION["membre"]["nom"];  ?>, bienvenue sur votre espace personnel ! </h2>
    <?php } ?>

    <div class="card" style="width: 18rem;">

    <!-- Condition sur la photo de profil -->

    <?php if($_SESSION["membre"]["civilite"] == "m") { ?>
        <img src="photo/avatar_male.png" class="card-img-top" alt="...">
    <?php } else { ?>
        <img src="photo/avatar_female.png" class="card-img-top" alt="...">
    <?php } ?>

    <div class="card-body">
        <h5 class="card-title"> <?php echo $_SESSION["membre"]["nom"] . " " . $_SESSION["membre"]["prenom"]; ?> </h5>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item"><?php echo $_SESSION["membre"]["email"];  ?></li>
        <li class="list-group-item"><?php echo $_SESSION["membre"]["adresse"];  ?></li>
        <li class="list-group-item"><?php echo $_SESSION["membre"]["code_postal"] . " - " . $_SESSION["membre"]["ville"];  ?></li>
    </ul>
    </div>
</div>

<?php
    require_once("inc/footer.php");
?>