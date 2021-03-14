<?php 
    session_start();
    if(!isset($_SESSION["membre"]) || $_SESSION["role"] == "Guide")
        header("Location: ../login.php");

    require "database.php";
    require "check.php";
    
    $nouvelleData = false;
    $nbrVoyageur = $typeVoyage = $dateVoyage = $reserAvion = $typeLogement = $dormirOu = $solDormir = $totLever = $voyagerDeja = $connaissezVous = $avecGuide = $voitureLoc = $cmbBagage = $budgetVoyage = $etape = $circuit = $comment = $nbrVoyageurError = $typeVoyageError = $dateVoyageError = $reserAvionError = $typeLogementError = $dormirOuError = $solDormirError = $totLeverError = $voyagerDejaError = $connaissezVousError = $avecGuideError = $voitureLocError = $cmbBagageError = $budgetVoyageError = $etapeError = $circuitError = $commentError ="";

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']); // id de voyageurs
    
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM construction WHERE IdVoyageurs = ?");
        $statement->execute(array($id));

        $item = $statement->fetch();  
        
        Database::disconnect();
    };
    
    if(!empty($_POST)){

        if ($statement->rowCount() < 1){ // Permet de savoir si il y a une donnee a ce voyageur, SI oui, on UPDATE, Si non, On INSERT
            $nouvelleData = true;
        };

        $nbrVoyageur = checkInput($_POST['nbrVoyageur']);
        $typeVoyage = checkInput($_POST['typeVoyage']);
        $dateVoyage = checkInput($_POST['dateVoyage']);
        $reserAvion = checkInput($_POST['reserAvion']);
        //$typeLogement = checkInput($_POST['typeLogement']);
        $dormirOu = checkInput($_POST['dormirOu']);
        $solDormir = checkInput($_POST['solDormir']);
        $totLever = checkInput($_POST['totLever']);
        $voyagerDeja = checkInput($_POST['voyagerDeja']);
        $connaissezVous = checkInput($_POST['connaissezVous']);
        $avecGuide = checkInput($_POST['avecGuide']);
        $voitureLoc = checkInput($_POST['voitureLoc']);
        $cmbBagage = checkInput($_POST['cmbBagage']);
        $budgetVoyage = checkInput($_POST['budgetVoyage']);
        $etape = checkInput($_POST['etape']);
        $circuit = checkInput($_POST['circuit']);
        $comment = checkInput($_POST['comment']);


        $isSuccess = true; // NEED TO ADD ALL THE ERROR POSSIBILITIES
        $db = Database::connect();

        if($isSuccess && $nouvelleData == false){

            try{
                $statement = $db->prepare("UPDATE construction SET NbreVoyageurs=?,VoyageAvec=?,DateVoyage=?,AvionReser=?,OuDormir=?,DormirSol=?,LeverTot=?,DejaVoyage=?,VousConnaissez=?,Guide=?,LouerV=?, CBagage=?, Budget=?, IdeeEtape=?, Circuit=?, Commentaire=? WHERE IdVoyageurs=?");
                $statement->execute(array($nbrVoyageur,$typeVoyage,$dateVoyage,$reserAvion,$dormirOu,$solDormir,$totLever,$voyagerDeja,$connaissezVous,$avecGuide,$voitureLoc,$cmbBagage,$budgetVoyage,$etape,$circuit,$comment, $id));
                
            }catch(Exception $e){
                echo($e);
            };           
        };
        if($isSuccess && $nouvelleData){

            try{
                $statement = $db->prepare("INSERT INTO construction (IdVoyageurs,NbreVoyageurs,VoyageAvec,DateVoyage,AvionReser,OuDormir,DormirSol,LeverTot,DejaVoyage,VousConnaissez,Guide,LouerV, CBagage, Budget, IdeeEtape, Circuit, Commentaire) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $statement->execute(array($id,$nbrVoyageur,$typeVoyage,$dateVoyage,$reserAvion,$dormirOu,$solDormir,$totLever,$voyagerDeja,$connaissezVous,$avecGuide,$voitureLoc,$cmbBagage,$budgetVoyage,$etape,$circuit,$comment,));
 
            }catch(Exception $e){
                echo($e);
            };       
        };
        
        $statement = $db->prepare("SELECT * FROM construction WHERE IdVoyageurs = ?");
        $statement->execute(array($id));
        $item = $statement->fetch();

        Database::disconnect();
    };

    // Ceci nous permet de recuperer les infos pour les mettres comme valeurs des inputs

    $nbrVoyageur = $item['NbreVoyageurs'];
    $typeVoyage = $item['VoyageAvec'];
    $dateVoyage = $item['DateVoyage'];
    $reserAvion = $item['AvionReser'];
    $dormirOu = $item['OuDormir'];
    $solDormir = $item['DormirSol'];
    $totLever = $item['LeverTot'];
    $voyagerDeja = $item['DejaVoyage'];
    $connaissezVous =  $item['VousConnaissez'];
    $avecGuide = $item['Guide'];
    $voitureLoc = $item['LouerV'];
    $cmbBagage = $item['CBagage'];
    $budgetVoyage = $item['Budget'];
    $etape = $item['IdeeEtape'];
    $circuit = $item['Circuit'];
    $comment = $item['Commentaire'];

?>

<!doctype html>
<html lang="en">
  <head>
    <title>Construction Voyage</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../images/cap.ico"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="../css/style.css" rel="stylesheet" >
  </head>
  <body>

    <?php
        include "navigation.php";
    ?>

    <form id="formConst" method="POST"> <!-- On ajoute un id au form qui nous servira pour le bouton submit -->
        <div class="container-fluid">
            <div class="row text-center center-block">
                
                <section class="col-12">
                    <br>
                    <h1>Construction De Voyage</h1>
                </section>
                <section class="col-sm-12 col-md-6">
                    <br><br>
                    
                    <label class="font-weight-bold" for="nbrVoyageur">Nombre de participants au voyage (en vous incluant) * : </label><br>
                    <label class="labelA"><?php echo " " . $item['NbreVoyageurs']; ?></label><input type="text" style="display:none" id="nbrVoyageur" name="nbrVoyageur" value="<?php echo $nbrVoyageur?>"><br>
                    <span class="erreur"><?php echo $nbrVoyageurError; ?></span>

                    <label class="font-weight-bold" for="typeVoyage">Vous partez en famille/en couple / entre ami / seul  : </label><br>
                    <label class="labelA"><?php echo " " . $item['VoyageAvec']; ?></label><input type="text" style="display:none"  name="typeVoyage" id="typeVoyage" value="<?php echo $typeVoyage?>"><br>
                    <span class="erreur"><?php echo $typeVoyageError; ?></span>

                    <label class="font-weight-bold" for="dateVoyage">Connaissez-vous déjà vos dates de voyage sur place ?  </label><br>
                    <label class="labelA"><?php echo " " . $item['DateVoyage']; ?></label><input type="text" style="display:none"  name="dateVoyage" id="dateVoyage" value="<?php echo $dateVoyage?>"><br>
                    <span class="erreur"><?php echo $dateVoyageError; ?></span>

                    <label class="font-weight-bold" for="reserAvion">Avez-vous déjà réservé vos billets d'avion ? </label><br>
                    <label class="labelA"><?php echo " " . $item['AvionReser']; ?></label><input type="text" style="display:none"  name="reserAvion" id="reserAvion" value="<?php echo $reserAvion?>"><br>
                    <span ><?php echo $reserAvionError; ?></span>

                    <!-- <label class="font-weight-bold" for="typeLogement">Quel(s) type(s) d'hébergement(s) souhaitez-vous ?  </label><br>
                    <label class="labelA"><?php // echo " " . $item['Type']; ?></label><input type="text" style="display:none"  name="typeLogement" id="typeLogement" value="<?php //echo $item['VoyageAvec'] ?><?php // echo $typeLogement?>"><br>
                    <span ><?php //echo $typeLogementError; ?></span> -->

                    <label class="font-weight-bold" for="dormirOu">Où préférez-vous dormir à Séoul ? </label><br>
                    <label class="labelA"><?php echo " " . $item['OuDormir']; ?></label><input type="text" style="display:none"  name="dormirOu" id="dormirOu" value="<?php echo $dormirOu?>"><br>
                    <span class="erreur"><?php echo $dormirOuError; ?></span>

                    <label class="font-weight-bold" for="solDormir">Pouvez-vous dormir sur le sol ? (couchage traditionnel) * </label><br>
                    <label class="labelA"><?php echo " " . $item['DormirSol']; ?></label><input type="text" style="display:none"  name="solDormir" id="solDormir" value="<?php echo $solDormir?>"><br>
                    <span class="erreur"><?php echo $solDormirError; ?></span>

                    <label class="font-weight-bold" for="totLever">Êtes-vous plutôt lève tôt ou relax en voyage ?  </label><br>
                    <label class="labelA"><?php echo " " . $item['LeverTot']; ?></label><input type="text"  style="display:none"  name="totLever" id="totLever" value="<?php echo $totLever?>"><br>
                    <span class="erreur"><?php echo $totLeverError; ?></span>
                </section>
                <section class="col-sm-12 col-md-6">
                <br><br> 

                    <label class="font-weight-bold" for="voyagerDeja">Avez-vous déjà voyagé en Corée? * </label><br>
                    <label class="labelA"><?php echo " " . $item['DejaVoyage']; ?></label><input type="text" style="display:none"  name="voyagerDeja" id="voyagerDeja" value="<?php echo $voyagerDeja?>"><br>
                    <span class="erreur"><?php echo $voyagerDejaError; ?></span>

                    <label class="font-weight-bold" for="connaissezVous">Que connaissez-vous de la Corée ?  </label><br>
                    <label class="labelA"><?php echo " " . $item['VousConnaissez']; ?></label><input type="text" style="display:none"  name="connaissezVous" id="connaissezVous" value="<?php echo $connaissezVous?>"><br>
                    <span class="erreur"><?php echo $connaissezVousError; ?></span>

                    <label class="font-weight-bold" for="avecGuide">Souhaitez-vous être accompagné durant votre voyage ? </label><br>
                    <label class="labelA"><?php echo " " . $item['Guide']; ?></label><input type="text" style="display:none"  name="avecGuide" id="avecGuide" value="<?php echo $avecGuide?>"><br>
                    <span class="erreur"><?php echo $avecGuideError; ?></span>

                    <label class="font-weight-bold" for="voitureLoc">Souhaitez-vous louer une voiture et conduire ? </label><br>
                    <label class="labelA"><?php echo " " . $item['LouerV']; ?></label><input type="text" style="display:none"  name="voitureLoc" id="voitureLoc" value="<?php echo $voitureLoc?>"><br>
                    <span class="erreur"><?php echo $voitureLocError; ?></span>

                    <label class="font-weight-bold" for="cmbBagage">Avec combien de bagages pensez-vous faire le voyage ? </label><br>
                    <label class="labelA"><?php echo " " . $item['CBagage']; ?></label><input type="text" style="display:none"  name="cmbBagage" id="cmbBagage" value="<?php echo $cmbBagage?>"><br>
                    <span class="erreur"><?php echo $cmbBagageError; ?></span>

                    <label class="font-weight-bold" for="budgetVoyage">Quel est le budget approximatif par personne (hors vols internationaux) ? </label><br>
                    <label class="labelA"><?php echo " " . $item['Budget']; ?></label><input type="text" style="display:none"  name="budgetVoyage" id="budgetVoyage" value="<?php echo $budgetVoyage?>"><br>
                    <span class="erreur"><?php echo $budgetVoyageError; ?></span>

                    <label class="font-weight-bold" for="etape">Avez-vous déjà des idées d'étapes / visites / activités ? </label><br>
                    <label class="labelA"><?php echo " " . $item['IdeeEtape']; ?></label><input type="text" style="display:none"  name="etape" id="etape" value="<?php echo $etape?>"><br>
                    <span class="erreur"><?php echo $etapeError; ?></span>

                    <label class="font-weight-bold" for="circuit">Avez-vous repéré un circuit qui vous plait sur notre site Internet ? </label><br>
                    <label class="labelA"><?php echo " " . $item['Circuit']; ?></label><input type="text" style="display:none"  name="circuit" id="circuit" value="<?php echo $circuit?>"><br>
                    <span class="erreur"><?php echo $circuitError; ?></span>

                    <label class="font-weight-bold" for="comment">Commentaire :</label><br>
                    <label class="labelA"><?php echo " " . $item['Commentaire']; ?></label><input rows="10" cols="30" type="text" style="display:none"  name="comment" id="comment" value="<?php echo $comment?>"><br>
                    <span class="erreur"><?php echo $commentError; ?></span>
                </section>
                
            </div>
        </div>  
    </form>
        
    <div class="row text-center"> <!-- On cree une autre ligue avec les boutons qui vont etre dynamiques -->
        <div class="col-12">
            <button id="btnMod" class="btn btn-primary btn-lg">MODIFIER</button>
            <button type="submit" form="formConst" style="display:none" id="btnValid" class="btn btn-primary btn-lg">VALIDER</button><!-- on rajoute form="" car le bouton submit est en dehors des balises form -->
            <a href="voyageursview.php?id=<?php echo $id ?>" class="btn btn-primary btn-lg">RETOUR</a>
        </div>
    </div>

      <?php
        // On verifie si une row exist dans la base de donnee, et si elle n'existe pas alors on lance un script pour afficher les inputs directement et donc faire un INSERT
            if ($statement->rowCount() < 1){ 
                $nouvelleData = true;
            
        ?>
            <script type="text/javascript"> 

                var button = document.getElementById("btnMod"); // le bouton Modifier
                var val = document.getElementById("btnValid"); // le bouton Valider en position cache au debut du script
                var lesInputs = document.getElementsByTagName('input'); // On selectionne les inputs
                var lesLabels = document.getElementsByClassName('labelA'); // On selectionne les labels
                
                for (var a = 0; a < lesLabels.length; a++) {
                    var etat = lesLabels[a].style.display = "none";
                };

                for (var i = 0; i < lesInputs.length; i++) {
                    etat = lesInputs[i].style.display;
                    if (etat == "none"){ 
                        lesInputs[i].style.display = "inline"; 
                    } else { 
                        lesInputs[i].style.display = "none"; 
                    };
                };
                button.style.display = "none";
                val.style.display = "inline";
            </script>

        <?php
        };
        ?>
        
    <!-- Optional JavaScript -->
    <script src="../javascript/script.js"></script> 
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>