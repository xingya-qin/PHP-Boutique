<?php
require_once("../inc/init.php");


////////////////////////////////////////////
//////////// MODIFICATION ////////////////
////////////////////////////////////////////


// $_POST
// tu récupéreras l'id de la commande
// UPDATE EN BDD avec le nouvel état



$r = $pdo->query("SELECT * FROM commande");

if(isset($_GET["action"]) && $_GET["action"] == "suppression") {
    $count = $pdo->exec("DELETE FROM membre WHERE id_membre = $_GET[id_membre]");
    if($count > 0){
        $content .= "<div class=\"alert alert-success\" role=\"alert\">
        Le client Nº" . $_GET["id_membre"] . " a bien été supprimé en base !
        </div>";
    }
}

require_once("inc/header.php");

?>
<div class="col-md-10">
<?php echo $content; ?>
<div class="table-responsive">
<table class="table col-md-12">
   <thead class="thead-dark">
      <tr>
         <?php for($i=0; $i< $r->columnCount(); $i++ ) { ?>
         <th> <?php echo $r->getColumnMeta($i)["name"]; ?> </th>
         <?php } ?>
         <th> Modification </td>
         <th> Suppression </td>
      </tr>
      </tr>
   </thead>
   <tbody>
      <?php
         while($commande = $r->fetch(PDO::FETCH_ASSOC)){
                 echo "<tr>";
                 foreach($commande as $valeur){
                     echo "<td>" . $valeur . "</td>";   
                 }

            echo "<td>
                 <a href=\"?action=modification&id_commande=$commande[id_commande]\" class=\"badge badge-dark\"> Modifier </a>
              <td>        
                 <a href=\"?action=suppression&id_commande= $commande[id_commande]\" class=\"badge badge-danger\"> Supprimer </a>
              </td> </tr>";
         }
         ?>
   </tbody>
</table>

<!-- Formulaire -->
<!-- select options avec les différents états possible pour une commande -->
<!-- input type submit -->


<?php
require_once("inc/footer.php");
?>